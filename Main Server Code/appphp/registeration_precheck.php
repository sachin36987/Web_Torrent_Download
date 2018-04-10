<?php
session_start();
require '../conf.php';
// check logged in session variable is set .. if not then set it to 1 
if(!isset($_SESSION['loggedin'])){
	$_SESSION['loggedin'] = 1;
}

// check if user is already logged in
if($_SESSION['loggedin'] == 0){
	header("Location: /");
}
//****************** No security measure of prechecks
/*/ now limit number of tries to register
if($_SESSION['loggedin'] == 1){
if(!isset($_SESSION['Tries']) && empty($_SESSION['Tries'])){
	$_SESSION['Tries'] = 0;
	$_SESSION['LastTry'] = time();
}
//after certain time reset the tries and last try
if((time()-$_SESSION['LastTry'])> 3){
	$_SESSION['Tries'] = 0;
	$_SESSION['LastTry'] = time();
}
// if tries goes above below limit than user will be banned for $time_limit_to_register_login value in conf file

if($_SESSION['Tries']>10){
		$rep = json_encode(array('res'=>'toomany'));
		echo $rep;
		exit(0);
	}
	
	*/
	$usernameArray = json_decode(file_get_contents("php://input"),true);
if(isset($usernameArray['username']) && !empty($usernameArray['username'])){
	
	//Validate Username again
		$username = $usernameArray['username'];
		$conn = new mysqli($host,$user,$pass,$db);
		$username = mysqli_escape_string($conn,$username);
		$sql = "SELECT username FROM users WHERE username = '".$username."'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			mysqli_close($conn);
			$rep = json_encode(array('res'=>'allready'));
			echo $rep;
			exit(0);
		}
		else{
			mysqli_close($conn);
			$rep = json_encode(array('res'=>'valid'));
			echo $rep;
			exit(0);
		}
	}
	
	if(isset($usernameArray['email']) && !empty($usernameArray['email'])){
	
	$_SESSION['Tries']+=1;
	//Validate Username again
		$username = $usernameArray['email'];
		$conn = new mysqli($host,$user,$pass,$db);
		$username = mysqli_escape_string($conn,$username);
		$sql = "SELECT email FROM users WHERE email = '".$username."'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			mysqli_close($conn);
			$rep = json_encode(array('res'=>'allready'));
			echo $rep;
			exit(0);
		}
		else{
			mysqli_close($conn);
			$rep = json_encode(array('res'=>'valid'));
			echo $rep;
			exit(0);
		}
	}

?>