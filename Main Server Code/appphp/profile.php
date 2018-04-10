<?php
session_start();

if(isset($_SESSION['loggedin'])){
	if($_SESSION['loggedin'] == 0){
		$details = json_decode(file_get_contents("php://input"),true);
		
		if(!is_array($details)){
			echo json_encode(array("username"=>$_SESSION['username'],
			"email"=>$_SESSION['email']));
		}
		elseif(isset($details['password'])){
			echo 'success';
		}
	
	}
}



?>