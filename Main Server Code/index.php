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


<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
<script src="https://code.angularjs.org/1.6.9/angular-route.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.9/angular-animate.min.js"></script>
<script src="/js/app.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" ></script>


<base href="/"></base>
<style>
#wait_overlay {
    position: fixed;
    display: none;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #fff;
    opacity: 0.4;
    z-index: 2;
    cursor: pointer;
}
</style>
</head>
<body>

<div id="wait_overlay"><span class="fas fa-circle-notch fa-spin fa-4x" style="color:#308ddc;margin: 20% 45% 45% 45%;"></span></div>
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
<div class="container-fluid bg-info">
<div class="row">
<div class="col">
<div class="card bg-secondary">
<div class="card-body">
<button class="btn btn-primary">
&copy;2018 Developer : Sachin Kumar.</button>
</div>
</div>
</div>
</div>
</div>

<script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js"></script>
</body>


</html>