<?php
error_reporting(E_ALL);
require 'confRemote.php';
if(isset($_POST['key']) && !empty($_POST['key']) && $_POST['key'] == $key){
	
	file_put_contents(__DIR__.'request.txt','request received');
}
else{
	echo 'hi';
}
echo 'hi';
?>