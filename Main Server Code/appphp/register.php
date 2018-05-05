<?php
session_start();
require '../conf.php';
// check if registeration is allowed 
if($enable_registration != 1){
	// means not allowed -- redirect
	header("Location: /");
	exit(0);
}
// check logged in session variable is set .. if not then set it to 1 
if(!isset($_SESSION['loggedin'])){
	$_SESSION['loggedin'] = 1; // means not logged in
}
// check if user is already logged in
if($_SESSION['loggedin'] == 0){
	header("Location: /");
}
// now limit number of tries to register
if($_SESSION['loggedin'] == 1){
if(!isset($_SESSION['Tries']) && empty($_SESSION['Tries'])){
	$_SESSION['Tries'] = 0;
	$_SESSION['LastTry'] = time();
}

// after certain time reset the tries and last try
if((time()-$_SESSION['LastTry'])> $time_limit_to_register_login){
	$_SESSION['Tries'] = 0;
	$_SESSION['LastTry'] = time();
}
// if tries goes above below limit than user will be banned for $time_limit_to_register_login value in conf file
if($_SESSION['Tries']>$limit_of_number_registeration_attemps){
		$rep = json_encode(array('res'=>'toomany'));
		echo $rep;
		exit(0);
	}
	$detailsArray = json_decode(file_get_contents("php://input"),true);
	//check if set
if(isset($detailsArray['username']) && 
	isset($detailsArray['password']) && 
	isset($detailsArray['email'])){
		// check if empty
		if(!empty($detailsArray['username']) && !empty($detailsArray['password']) && !empty($detailsArray['email'])){
	
			$_SESSION['Tries']+=1;
	
	
		//Validate Username*****************************************************
		
		$username = $detailsArray['username'];
		preg_match("/\W/",$username,$match);
		
		if(count($match)>0){
			//means invalid character
			$rep = json_encode(array('res'=>'invalidUsername'));
			echo $rep;
			exit(0);
		}
		if(strlen($username)<3){
			//means length is not good
			$rep = json_encode(array('res'=>'userLengthError'));
			echo $rep;
			exit(0);
		}
		// create database connection
		$conn = new mysqli($host,$user,$pass,$db);
		
		
		$username = mysqli_escape_string($conn,$username);
		
		$sql = "SELECT username FROM users WHERE username = '".$username."'";
		
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			//means already in database
			mysqli_close($conn);
			$rep = json_encode(array('res'=>'allreadyUsername'));
			echo $rep;
			exit(0);
		}
		
		
		// Validate Email ******************************************************************************
		
		
		$email = $detailsArray['email'];
		preg_match("/[\w.]+[@][\w]+\.[\w.]+/",$email,$match);
		
		if(count($match)==0){
			//means invalid email
			$rep = json_encode(array('res'=>'invalidEmail'));
			echo $rep;
			exit(0);
		}
		// database connection already connect in username validation
		
		$email = mysqli_escape_string($conn,$email);
		
		$sql = "SELECT email FROM users WHERE email = '".$email."'";
		
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			//means already in database
			mysqli_close($conn);
			$rep = json_encode(array('res'=>'allreadyEmail'));
			echo $rep;
			exit(0);
		}
		
	// Validate Password ****************************************************************************
		
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
		// database connection already connected in username validation
		
		$password = mysqli_escape_string($conn,$password);
		
		// saving passwod as it is not good. so hash it 
		$password = password_hash($password,PASSWORD_DEFAULT);
		
		// params ... e.g. initial size allowed
		$params = mysqli_real_escape_string($conn,base64_encode(json_encode(array("size_allowed"=>$allowed_size))));
		// save all of them in database and reply success. All have been escaped already
		$sql = "INSERT INTO users(username,password,email,params,profile) VALUES('$username','$password','$email','$params','')";
		
		$result = $conn->query($sql);
		$sql = "INSERT INTO userfiles (user,fileIds) VALUES('$username','')";
		
		$newREs = $conn->query($sql);
		if($result && $newREs){
			$rep = json_encode(array('res'=>'registered'));//
			echo $rep;
			exit(0);
		}
		else{
			$rep = json_encode(array('res'=>'problemWithHosting'));
			echo $rep;
			exit(0);
		}
		mysqli_close($conn);
		
	}
	else{ // empty
		$rep = json_encode(array('res'=>'empty'));
			echo $rep;
			exit(0);
	}
	} 
	else{ // not set
		$rep = json_encode(array('res'=>'notset'));
			echo $rep;
			exit(0);
	}
}
?>