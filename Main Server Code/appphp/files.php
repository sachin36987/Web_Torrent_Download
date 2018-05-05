<?php
session_start();
// if user not logged in redirect

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']==1){
		
				$rep = json_encode(array('res'=>'ntRegisLogin'));
				echo $rep;
				exit(0);
			
	
}
else{ // user is logged in
// header of logged in users

require '../conf.php';
$conn = new mysqli($host,$user,$pass,$db);
$sql = "SELECT * FROM userfiles WHERE user = '".$_SESSION['username']."'";
$result = $conn->query($sql);

if($result->num_rows>0){
	$rows = mysqli_fetch_array($result);
	// check if there are any ids to return
	if(strlen($rows['fileIds']) == 0) {echo json_encode(array('res'=>'nofiles')); exit(0);}
	$files_id_array  = explode(',',$rows['fileIds']);
	
	$to_return = array();
	foreach($files_id_array as $fileid){
		$sql = 'SELECT hash,name,server_name,completed FROM files WHERE id='.$fileid;
		$field = $conn->query($sql);$file_info = mysqli_fetch_array($field);
		$server = $remote_server_protocol.$file_info['server_name'];
		$to_return[count($to_return)]= array('name'=>substr(urldecode($file_info['name']),0,50),'hash'=>$file_info['hash'],'server'=>$server,'completed'=>$file_info['completed']);
	}
	mysqli_close($conn);
	echo json_encode($to_return);
}
else{
	mysqli_close($conn);
}
} // else closed
?>