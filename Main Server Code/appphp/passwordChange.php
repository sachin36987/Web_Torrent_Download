<?php
session_start();

// only allow if user is logged in

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 0){
	
	require '../conf.php';
// Validate Password ****************************************************************************
		$detailsArray = json_decode(file_get_contents("php://input"),true);
		$password = $detailsArray['password'];
		// check for upper case
		preg_match("/[A-Z]/",$password,$match); 
		
		if(count($match)==0){
			$rep = json_encode(array('res'=>'invalidPasswordUpper'));
			echo $rep;
			exit(0);
		}
		// check for lower case
		preg_match("/[a-z]/",$password,$match); 
		
		if(count($match)==0){
			$rep = json_encode(array('res'=>'invalidPasswordLower'));
			echo $rep;
			exit(0);
		}
		// check for special case
		preg_match("/[\W]/",$password,$match); 
		
		if(count($match)==0){
			$rep = json_encode(array('res'=>'invalidPasswordSpecial'));
			echo $rep;
			exit(0);
		}
		// check for digit case
		preg_match("/[0-9]/",$password,$match); 
		
		if(count($match)==0){
			$rep = json_encode(array('res'=>'invalidPasswordDigit'));
			echo $rep;
			exit(0);
		}
		//check for length
		if(strlen($password)<8){
			// password length
			$rep = json_encode(array('res'=>'lengthPassword'));
			echo $rep;
			exit(0);
		}
		
		// change password
		
		// create database connection
		$conn = new mysqli($host,$user,$pass,$db);
		$username = $_SESSION['username'];
		$password = password_hash($password,PASSWORD_DEFAULT);
		$sql = "UPDATE users SET password = '$password' WHERE username = '".$username."'";
		
		if($conn->query($sql)){
			mysqli_close($conn);
			$rep = json_encode(array('res'=>'PasswordChanged'));
			echo $rep;
			exit(0);
		}
		else{
			mysqli_close($conn);
			$rep = json_encode(array('res'=>'error'));
			echo $rep;
			exit(0);
		}
		mysqli_close($conn);

}
else{
			$rep = json_encode(array('res'=>'error'));
			echo $rep;
			exit(0);
		}

?>