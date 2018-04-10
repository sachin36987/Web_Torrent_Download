<?php
session_start();

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 0){
	
	// means logged in
	$details = json_decode(file_get_contents("php://input"),true);
	if(isset($details['url']) && !empty($details['url'])){
	// validate torrent
	$url = $details['url'];
	preg_match('/^magnet:\?xt=urn:btih:[\w]+|^http:\/\/\w+\.\w+[\w\W]+\.torrent|^https:\/\/\w+\.\w+[\w\W]+\.torrent|^ftp:\/\/\w+\.\w+[\w\W]+\.torrent/',$url,$match);
	
	if(count($match)==1){
		// check if magnet or url
		require 'addurlFunctions.php';
		// get hash and size of torrent files
		
		$hash_size = hashh($match[0]);
		// check the size of file allowed for user
				// add functionality here when needed (while designing package)
		// check if file is already downloaded
		require '../conf.php';
		$conn = new mysqli($host,$user,$pass,$db);
		$sql = "SELECT hash FROM files where hash='".mysqli_real_escape_string($conn,$hash_size['hash'])."'";
		$result = $conn->query($sql);
		if($result && $result['num_rows']>0){
			// means already in database
			$rep = json_encode(array('res'=>'allreadyInDatabase'));
			echo $rep;
			exit(0);
		}
		else{
			// add file to download queue (choose a specific server and send a request to add the file to queue)
			// save file info to files and add it to user files
			$sql = "INSERT INTO files (hash,file_info,date_added,server_name) VALUES()"
		}
		$rep = json_encode($hash_size);
		echo $rep;
		exit(0);
			
	}
	else{ // something fishy. throw error
		$rep = json_encode(array('res'=>'error'));
		echo $rep;
		exit(0);
	}
	
	}
}








?>