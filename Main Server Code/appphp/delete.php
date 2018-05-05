<?php
session_start();

/*******************************************
used to delete the files from server on user request
**********************************************/
/*****************************************
condition to check:
*only delete from server when no other user owns the file...
*if any body owns the file then delete the file from owners database
***********************************************/
$details = json_decode(file_get_contents("php://input"),true);
// check if user is logged in
if(isset($details['hash']) && !empty($details['hash']) && $_SESSION['loggedin'] == 0){
	// means perform the required actions
	require '../conf.php';
	// check if any other user owns same file 
	$conn = new mysqli($host,$user,$pass,$db);
	$sql = 'SELECT id,owned_by,server_name,completed,pid FROM files WHERE hash="'.$details['hash'].'"';
	$res = $conn->query($sql);
	$row = mysqli_fetch_array($res);
	$users = explode(',',$row['owned_by']);
	
	if(count($users)>1){
		//means other user also own the file
		// remove this user from the list 
		$to_delete = array_search($_SESSION['username'],$users);
		array_splice($users,$to_delete,1);
   
		// and save to db again
		$sql = "UPDATE files SET owned_by='".implode(",",$users)."' WHERE hash='".$details['hash']."'";
		$conn->query($sql);
		
	}
	else if(isset($users[0]) && !empty($users[0])){
		//means no user now owns the file so delete from db and from other 
		$sql = "DELETE FROM files WHERE id=".$row['id'];
		$conn->query($sql);
		// also delete from remote server  - to be added
		$server = 'https://'.$row['server_name'].'/delete.php';
		// if not completed then send the pid also 
			$data = array();
		if($row['completed'] == 'false'){
			$data = array('hash'=>$details['hash'], 'key'=>'mykey', 'pid'=>$row['pid']);
		}
		else{
			$data = array('hash'=>$details['hash'], 'key'=>'mykey');
		}
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_URL, $server);
		curl_setopt($ch, CURLOPT_SSLVERSION,3); 
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, 'CURLOPT_TCP_FASTOPEN', 1);
		curl_setopt($ch, CURLOPT_SSLVERSION,6);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
		$result = curl_exec($ch);
		curl_close($ch);
	}
	// delete from userfiles db also 
	$sql = 'SELECT fileIds FROM userfiles WHERE user="'.$_SESSION['username'].'"';
	$res = $conn->query($sql);
	$row_del = mysqli_fetch_array($res);
	$files = explode(',',$row_del['fileIds']);

	$to_delete = array_search($row['id'],$files);
	array_splice($files,$to_delete,1);
	// save again to db
	$sql = "UPDATE userfiles SET fileIds='".implode(',',$files)."' WHERE user='".$_SESSION['username']."'";
	$conn->query($sql);
	mysqli_close($conn);
	echo json_encode(array('resp'=>'success'));
}

?>