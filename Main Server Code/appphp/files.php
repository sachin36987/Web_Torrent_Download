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

echo 'Hi How are You?';

}
?>