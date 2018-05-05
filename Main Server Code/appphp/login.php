<?php
session_start();
require '../conf.php';
// check if logged in variable is set if not then set it to 1
if(!isset($_SESSION['loggedin'])){
	$_SESSION['loggedin'] = 1; // means not logged in
}
// check if user already logged in
if($_SESSION['loggedin'] == 0){
	header("Location: /");
}
// set tries and last try variables 
if($_SESSION['loggedin'] == 1){
if(!isset($_SESSION['Tries']) && empty($_SESSION['Tries'])){
	$_SESSION['Tries'] = 0;
	$_SESSION['LastTry'] = time();
}
// check last time user tried to login
if((time()-$_SESSION['LastTry']) > $time_limit_to_register_login){
	$_SESSION['Tries'] = 0;
	$_SESSION['LastTry'] = time();
}

$details = json_decode(file_get_contents("php://input"),true);

	if(isset($details['username']) && isset($details['username'])){
		if(!empty($details['username']) && !empty($details['username'])){
			
			// validate
			$conn = new mysqli($host,$user,$pass,$db);
			$username = mysqli_escape_string($conn,$details['username']);
			$sql = "SELECT username,password,email,params FROM users where username = '".$username."'";
			if($result = $conn->query($sql)){
				if($result->num_rows>0){
				$rows = mysqli_fetch_array($result);
				if(password_verify($details['password'],$rows['password'])){
					$rep = json_encode(array('res'=>'loggedin'));
					$_SESSION['loggedin'] = 0;
					$_SESSION['username'] = $username;
					$_SESSION['email'] = $rows['email'];
                             $paramss = json_decode(base64_decode($rows['params']));
          $_SESSION['size_allowed'] = $paramss->size_allowed;
					echo $rep;
					exit(0);
				}
				else{ // password is not correct
					$rep = json_encode(array('res'=>'incorrectPassword'));
					echo $rep;
					exit(0);
				}
				}
				else{// username not found
					$rep = json_encode(array('res'=>'usernameNotFound'));
					echo $rep;
					exit(0);
				}
			}
			else{ // sql error
				$rep = json_encode(array('res'=>'SQLerror'));
				echo $rep;
				exit(0);
			}
			
		}
		else{// empty
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