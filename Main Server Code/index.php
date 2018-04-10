<?php
session_start();
// check if user logged in -- then redirect him to files page
if(isset($_SESSION['loggedin'])){
	if($_SESSION['loggedin'] == 0){
		if($_SERVER['REQUEST_URI'] != '/files' && $_SERVER['REQUEST_URI'] != '/profile'){
			header("Location: /files");  
			exit(0);
		}
		
	}
}



?>
<html>

<head>

<title> Web Torrent Download Service </title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
<script src="https://code.angularjs.org/1.6.9/angular-route.js"></script>
<script src="/js/app.js"></script>

<base href="/"></base>
</head>
<body>


<div ng-app="faltuTech">
<!--- Navigation Bar  -->
	<?php
	require 'conf.php';
	if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == 1){
		include 'header.php';
	}
	else{
		include 'appphp/headerLogged.php';
	}
	?>
<!--- Content -->

<main ng-view></main>

</div> <!-- ng-app div closed -->
<!--- Footer-->



</body>


</html>