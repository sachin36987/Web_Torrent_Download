<?php
session_start();

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 0){
	$details = json_decode(file_get_contents("php://input"),true);
	require '../conf.php';
	$conn = new mysqli($host,$user,$pass,$db);
	$sql = "UPDATE files SET completed='true' WHERE hash='".$details['hash']."'";
	$conn->query($sql);
	echo 'success';
}


?>
