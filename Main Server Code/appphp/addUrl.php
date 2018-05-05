<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 0)
	{

	// means logged in

	$details = json_decode(file_get_contents("php://input") , true);
	if (isset($details['url']) && !empty($details['url']))
		{

		// validate torrent

		$url = $details['url'];
		preg_match('/^magnet:\?xt=urn:btih:[\w]+|^http:\/\/\w+\.\w+[\w\W]+\.torrent|^https:\/\/\w+\.\w+[\w\W]+\.torrent|^ftp:\/\/\w+\.\w+[\w\W]+\.torrent/', $url, $match);
		if (count($match) == 1)
			{

			// check if magnet or url

			require 'addurlFunctions.php';

			// get hash and size of torrent files

			$hash_size = hashh($details['url']); // returns $hash_size['size','hash','magnet','name']

			// check the size of file allowed for user
			// add this field into session size_allowed .. when user log in

			if ($_SESSION['size_allowed'] < $hash_size['size'])
				{
				echo json_encode(array(
					'res' => 'oversize'
				));
				exit(0);
				}

			// check if file is already downloaded

			require '../conf.php';

			$conn = new mysqli($host, $user, $pass, $db);
			$sql = "SELECT id,hash,owned_by FROM files where hash='" . mysqli_real_escape_string($conn, $hash_size['hash']) . "'";
			$result = $conn->query($sql);
			if ($result && $result->num_rows > 0)
				{

				// means already in database.. add it to owned by in files and add that id to userfiles

				$row_up = mysqli_fetch_array($result);

				// get user files and check if user already have the specific id file

				$sql = "SELECT fileIds FROM userfiles WHERE user='" . $_SESSION['username'] . "'";
				$user_res = $conn->query($sql);
				$fileIDs = mysqli_fetch_array($user_res);
				$fileIDs_exp = explode(',', $fileIDs['fileIds']);
				
				$prebreak = 0;
				foreach($fileIDs_exp as $fileID_exp)
					{
					if ($fileID_exp == $row_up['id'])
						{
						$prebreak = 1;
						break;
						}
					}

				// add to user fileIDs if prebreak == 0

				if ($prebreak == 0)
					{

					// means file is not in the fileIds of user and also in owned_by in files ..  so add them
					// now check if there were files before ... it may create a anamoly

					if (strlen($fileIDs) > 0)
						{
						$fileID_exp[count($fileID_exp) ] = $row_up['id'];
						$fileID_exp = implode(',', $fileID_exp);
						}
					  else
						{
						$fileID_exp = $row_up['id'];
						}

					$sql = "UPDATE userfiles SET fileIds='" . $fileID_exp . "' WHERE user='".$_SESSION['username']."'";
					
					$conn->query($sql);

					// also add to files owned_by column 

					$owned_by_add = array_merge(explode(',',$row_up['owned_by']), array(
						$_SESSION['username']
					));
					
					$sql = "UPDATE files SET owned_by='" . implode(',', $owned_by_add) . "' WHERE hash='" . mysqli_real_escape_string($conn, $hash_size['hash']) . "'"; // added to files
					$conn->query($sql);
					}

				mysqli_close($conn);
				$rep = json_encode(array(
					'res' => 'allreadyInDatabase',
					'hash' => $hash_size['hash']
				));
				echo $rep;
				exit(0);
				}
			  else
				{

				// add file to download queue (choose a specific server and send a request to add the file to queue)
				// find server with least load --  there will be another php script that will run every minute to find the load and save to db

				$server_name = 'server1.faltutech.club'; // choose it
				$server = 'https://' . $server_name . '/addTorrent.php'; // add logic before it to find the best server.. for now we are just using it
				$data = array(
					'hash' => $hash_size['hash'],
					'key' => 'mykey',
					'magnet' => $hash_size['magnet']
				); // data to send to choosen server .. key will be choosen above

				// make curl call to specified server

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_URL, $server);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
				curl_setopt($ch, 'CURLOPT_TCP_FASTOPEN', TRUE);
				curl_setopt($ch, CURLOPT_SSLVERSION, 6);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				$result_from_server = curl_exec($ch); // it will return pid or unsuccessfull or error as json... key will be pid
				curl_close($ch);

				// analyze $result_from_server

				$rfs = json_decode($result_from_server);
				if ($rfs->pid == 'error')
					{
					echo json_encode(array(
						'res' => 'error'
					));
					exit(0);
					}
				  else
				if ($rfs->pid == 'alreadyDownloaded')
					{
					echo json_encode(array(
						'res' => 'downloading',
						'hash' => $hash_size['hash']
					));
					exit(0);
					}
				  else
					{

					// save pid, name, hash, file id to userfiles and username to owned_by in files

					$pid = $rfs->pid;
					$name = $hash_size['name'];
					$hash = $hash_size['hash'];
					$owned_byy = $_SESSION['username'];
					$date = time();

					// first of all create a file entry in files

					$sql = 'INSERT INTO files (hash,name,pid,owned_by,date_added,server_name) VALUES("' . $hash . '","' . $name . '","' . $pid . '","' . $owned_byy . '","' . $date . '","' . $server_name . '")';
					$conn->query($sql);

					// now get that id and save to userfiles

					$sql = "SELECT id FROM files WHERE hash='" . $hash . "'";
					$res_id = $conn->query($sql);
					$row_id = mysqli_fetch_array($res_id);
					$save_id = $row_id['id'];

					// now save it user files but first get all the file user have

					$sql = "SELECT fileIds FROM userfiles WHERE user = '" . $_SESSION['username'] . "'";
					$res_id = $conn->query($sql);
					$row_id = mysqli_fetch_array($res_id);
					$all_id = '';

					// now count the returned result and check if explode implode is needed

					if (strlen($row_id['fileIds'] < 1))
						{

						// no need to implode explode

						$all_id = $save_id;
						}
					  else
						{
						$all_id = explode(',', $row_id['fileIds']);
						$all_id[count($all_id) ] = $save_id;
						$all_id = implode(',', $all_id);
						}

					// now save the ids

					$sql = "UPDATE userfiles SET fileIds='" . $all_id . "' WHERE user = '" . $_SESSION['username'] . "'";
					$conn->query($sql);
					echo json_encode(array(
						'res' => 'downloading',
						'hash' => $hash_size['hash']
					));
					exit(0); // same as before because i just need to return hash and server

					// after it call the files page again to get the file info

					exit(0);
					}
				}
			}
		  else
			{ // something fishy. throw error
			$rep = json_encode(array(
				'res' => 'error'
			));
			echo $rep;
			exit(0);
			}
		}
	}

?>