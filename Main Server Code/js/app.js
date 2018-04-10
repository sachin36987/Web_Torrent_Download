var FaltuTechApp = angular.module('faltuTech',['ngRoute']);


FaltuTechApp.config(['$routeProvider','$locationProvider',function($routeProvider,$locationProvider){
	$locationProvider.html5Mode(true).hashPrefix('!');
	$routeProvider
	.when('/login',
	{
		templateUrl:'/views/login.html',
		controller:'loginController'
	})
	.when('/register',
	{
		templateUrl:'/views/Register.html',
		controller: 'myControlerRegister'
	})
	.when('/files',
	{
		templateUrl:'/views/files.html',
		controller: 'filesController'
	})
	.when('/profile',
	{
		templateUrl:'/views/profile.html',
		controller:'profileController'
	})
	.otherwise({
		redirectTo: '/'
	})
}]);



//*************************************************************************************************
//**************************************************************************************************
// ************************** controller for files page ********************************************
//**************************************************************************************************

FaltuTechApp.controller('filesController',['$scope','$http','$window',function($scope,$http,$window){
	
	
	
	
}]);

//*************************************************************************************************
//************************** Profile controller ********************************************
FaltuTechApp.controller('profileController',['$scope','$http',function($scope,$http){
	if(!$scope.username){
	let req = {
		method : 'GET',
		url : '/appphp/profile.php'
	}
		$http(req).then(function(resp){
			$scope.username = resp.data.username;
			$scope.email = resp.data.email;
		})
	}
		// change password function
		
		$scope.sendd = function(){
			let req = {
				method : 'POST',
				url : '/appphp/passwordChange.php',
				data : {
					password : $scope.password
				}
			}
				$http(req).then(function(response){
					let resp = response.data.res;
					if(resp == 'PasswordChanged'){
					$scope.headerr = "Success";
					$scope.contentt = "Password Changed :-)";
					$("#myModal").modal();
					}
					else if(resp == "invalidPasswordUpper"){
				$scope.headerr = "Error";
				$scope.contentt = "Password does not have an upper case letter. Please Check again!!!";
				$("#myModal").modal();
			}
			else if(resp == "invalidPasswordLower"){
				$scope.headerr = "Error";
				$scope.contentt = "Password does not have a lower case letter. Please Check again!!!";
				$("#myModal").modal();
			}
			else if(resp == "invalidPasswordSpecial"){
				$scope.headerr = "Error";
				$scope.contentt = "Password does not contain a special character. Please Check again!!!";
				$("#myModal").modal();
			}
			else if(resp == "invalidPasswordDigit"){
				$scope.headerr = "Error";
				$scope.contentt = "Password does not contain a digit. Please Check again!!!";
				$("#myModal").modal();
			}
			else if(resp == "lengthPassword"){
				$scope.headerr = "Error";
				$scope.contentt = "Password length is less than 8. Please Check again!!!";
				$("#myModal").modal();
			}
			else{
				$scope.headerr = "Error";
				$scope.contentt = "Some error on our end have occurred. Please try later.";
				$("#myModal").modal();
			}
					
				}
				);
		}
		
		// check if passwords are equal
		$scope.confirmPass= function(){
			if($scope.password1){
			if($scope.password == $scope.password1){
				$scope.disableBtn = false;
				$scope.passConf = "";
			}
			else{
				$scope.passConf = 'Passwords does not match';
				$scope.disableBtn = true;
			}
		}
		else{
			$scope.disableBtn = true;
		}
		}
		
		// validate password here
		$scope.checkPass = function(){
			var check = 1;
		if($scope.password){
		
		if($scope.password.search(/\W/) == -1){
			check = 0;
			$scope.passReq1 = "Please Include a special character."
		}
		else{
			check = 1;
			$scope.passReq1 = ""
		}
		if($scope.password.search(/\d/) == -1){
			check = 0;
			$scope.passReq2 = "Please include a digit in password"
		}
		else{
			check = 1;
			$scope.passReq2 = ""
		}
		if($scope.password.search(/[a-z]/) == -1){
			check = 0;
			$scope.passReq2 = "Please Insert small case letter"
		}
		else{
			check = 1;
			$scope.passReq3 = ""
		}
		if($scope.password.search(/[A-Z]/) == -1){
			check = 0;
			$scope.passReq2 = "Please Insert upper case letter"
		}
		else{
			check = 1;
			$scope.passReq4 = ""
		}
		if($scope.password.length < 8){
			check = 0;
			$scope.passReq2 = "Length must be 8 or greater"
		}
		else{
			check = 1;
			$scope.passReq5 = ""
		}
		}
		else{
			$scope.passReq1 = "Please Enter Password"
		}
		// finished all the password reqirements
		if(check==0){
			// disable button
			$scope.disableBtn = true;
		}
		else{
			//enable button
			$scope.disableBtn = false;
		}
		}
}])

//*************************************************************************************************
//************************** Navigation bar controller ********************************************

FaltuTechApp.controller('navController',['$scope','$http','$window',function($scope,$http,$window){
	$scope.logoutt = function(){
		let req = {}
		$http.get('appphp/logout.php',req).then($window.location.href="/login")
	}
	
	
	$("#success-alert").hide();
	$scope.addUrl = function(){
		
		if($scope.urlinput){
			let matc = /^magnet:\?xt=urn:btih:[\w]+|^http:\/\/\w+\.\w+[\w\W]+\.torrent|^https:\/\/\w+\.\w+[\w\W]+\.torrent|^ftp:\/\/\w+\.\w+[\w\W]+\.torrent/.exec($scope.urlinput);
			if(matc){
				// means valid
				let req = {
					method : 'POST',
					url : '/appphp/addUrl.php',
					data : {
						url : $scope.urlinput
					}
				}
				
				
				$http(req).then(function(response){
					
					
					console.log(response);
					
					
				})
				
				// on addition run following
				$scope.headAlert = "Success :-)"
				$scope.ContentAlert = "Title of the Torrent"
				
                $("#success-alert").fadeTo(2000, 100).slideUp(500, function(){
               $("#success-alert").slideUp(100);
                });
				
			}
			else{
				//means invalid
				$scope.title = 'Not Valid'
				$scope.information = 'Magnet link or HTTP/HTTPS/FTP link you provided does seems to be valid.'
				$('#urlInfo').modal('show')
			}
		}
	}
	
}])








//**************************************************************************************************
// ************************** controller for login page ********************************************
//**************************************************************************************************
FaltuTechApp.controller('loginController',['$scope','$http','$window',function($scope,$http,$window){
	
$scope.sendd = function(){
	var req = {
		method : 'POST',
		url : 'appphp/login.php',
		data : {
			username : $scope.username,
			password : $scope.password
		}
	}
	
	$http(req).then(function(resp){
		let respp = resp.data.res;
		if(respp == 'loggedin'){
			$window.location.href='/files'
		}
		else if(respp == 'incorrectPassword'){
				$scope.headerr = "Error";
				$scope.contentt = "Incorrect Password. Please Check!!!";
				$("#myModal").modal();
		}
		else if(respp == 'usernameNotFound'){
				$scope.headerr = "Error";
				$scope.contentt = "Username not found. Please register!!!";
				$("#myModal").modal();
		}
		else if(respp == 'SQLerror'){
				$scope.headerr = "Error";
				$scope.contentt = "Error on our end. Please try again later!!!";
				$("#myModal").modal();
		}
		else if(respp == 'empty'){
				$scope.headerr = "Error";
				$scope.contentt = "Username or password not filled. Please Check!!!";
				$("#myModal").modal();
		}
		else if(respp == 'notset'){
				$scope.headerr = "Error";
				$scope.contentt = "Username or password not filled. Please Check!!!";
				$("#myModal").modal();
		}
		else{
				$scope.headerr = "Error";
				$scope.contentt = "Error on our end. Please try again later!!!";
				$("#myModal").modal();
		}
		
	})
}	
	
}]);

//**************************************************************************************************
// ************************** controller for register page *****************************************
//**************************************************************************************************
FaltuTechApp.controller('myControlerRegister',['$scope','$http','$window','$timeout',function($scope,$http,$window,$timeout){
	
	// handle the user submitted form
	$scope.sendd = function(){
		
		var req =  {
			method : 'POST',
			url : 'appphp/register.php',
			data : {
				username : $scope.username,
				password : $scope.password,
				email : $scope.email
			}
		}
		
		$http(req).then(function(response){
			let resp = response.data.res;
			$scope.dis = "none";
			if(resp == "invalidUsername"){
				$scope.headerr = "Error";
				$scope.contentt = "Invalid Username. Please Check.";
				$("#myModal").modal();
			}
			else if(resp == "userLengthError"){
				$scope.headerr = "Error";
				$scope.contentt = "Username length must be 3 or more. Please Check.";
				$("#myModal").modal();
			}
			else if(resp == "allreadyUsername"){
				$scope.headerr = "Error";
				$scope.contentt = "Username already exists . Please Change!!!";
				$("#myModal").modal();
			}
			else if(resp == "invalidEmail"){
				$scope.headerr = "Error";
				$scope.contentt = " Email is not valid. Please Check again!!!";
				$("#myModal").modal();
			}
			else if(resp == "allreadyEmail"){
				$scope.headerr = "Error";
				$scope.contentt = "Email already exists. Please Change!!!";
				$("#myModal").modal();
			}
			else if(resp == "invalidPasswordUpper"){
				$scope.headerr = "Error";
				$scope.contentt = "Password does not have an upper case letter. Please Check again!!!";
				$("#myModal").modal();
			}
			else if(resp == "invalidPasswordLower"){
				$scope.headerr = "Error";
				$scope.contentt = "Password does not have a lower case letter. Please Check again!!!";
				$("#myModal").modal();
			}
			else if(resp == "invalidPasswordSpecial"){
				$scope.headerr = "Error";
				$scope.contentt = "Password does not contain a special character. Please Check again!!!";
				$("#myModal").modal();
			}
			else if(resp == "invalidPasswordDigit"){
				$scope.headerr = "Error";
				$scope.contentt = "Password does not contain a digit. Please Check again!!!";
				$("#myModal").modal();
			}
			else if(resp == "lengthPassword"){
				$scope.headerr = "Error";
				$scope.contentt = "Password length is less than 8. Please Check again!!!";
				$("#myModal").modal();
			}
			else if(resp == "problemWithHosting"){
				$scope.headerr = "Error";
				$scope.contentt = "Some error on our end have occurred. Please try later.";
				$("#myModal").modal();
			}
			else if(resp == "empty"){
				$scope.headerr = "Error";
				$scope.contentt = "Username or Password is not filled";
				$("#myModal").modal();
			}
			else if(resp == "notset"){
				$scope.headerr = "Error";
				$scope.contentt = "Some error on your end. Please try later.";
				$("#myModal").modal();
			}
			else{
				$scope.headerr = "Registered";
				$scope.dis = "inline";
				$scope.contentt = 'You will redirected to login page in ';
				$("#myModal").modal();
				$timeout(function(){$window.location.href = '/login'},3000);
				$scope.time = 3;
				let prog = 0;
				var timer = function(){
					if(prog<3000){
						prog+=1000;
						$scope.time = 3 - (prog/1000)
						$timeout(timer,1000);
					}
				}
				$timeout(timer,1000);
			}
			
		});
	}
	// validation is below
	// validate username
	var error_color = "#f73128";
	var default_color = "#000000";
	var success_color = "#28f738";
	$scope.userClass = default_color;
	$scope.checkUser = function(){
		if($scope.username){
			if($scope.username.length<3){
				$scope.userClass = error_color;
				$scope.userWarn = 'Username must be more than 2 character long';
			}
			else {
				// username validation
				if($scope.username.search(/\W/) != -1){
					$scope.userClass = error_color;
					$scope.userWarn = 'Only alphabets and digits are allowed';
				}
					
				else{
				// check if already in database
				
					var req =  {
						method : 'POST',
						url : 'appphp/registeration_precheck.php',
						data : {
							username : $scope.username
						}
					}
		
					$http(req).then(function(response){
					// now i have the response from the server
						
						if(response.data.res == 'valid'){
							$scope.userClass = success_color;
							$scope.userWarn = "Great";
							$scope.disableBtn = false;
						}
						else if(response.data.res == 'allready'){
							$scope.userClass = error_color;
							$scope.userWarn = "Username already exists";
							$scope.disableBtn = true;
						}
						
					})
					
				}
			}
		}
		else{
			$scope.userClass = error_color;
			$scope.userWarn = 'Enter the username';
		}
	}
	
	// validate password
	
	$scope.checkPass = function(){
		var check = 1;
		if($scope.password){
		
		if($scope.password.search(/\W/) == -1){
			check = 0;
			$scope.passReq1 = "Please Include a special character."
		}
		else{
			check = 1;
			$scope.passReq1 = ""
		}
		if($scope.password.search(/\d/) == -1){
			check = 0;
			$scope.passReq2 = "Please include a digit in password"
		}
		else{
			check = 1;
			$scope.passReq2 = ""
		}
		if($scope.password.search(/[a-z]/) == -1){
			check = 0;
			$scope.passReq2 = "Please Insert small case letter"
		}
		else{
			check = 1;
			$scope.passReq3 = ""
		}
		if($scope.password.search(/[A-Z]/) == -1){
			check = 0;
			$scope.passReq2 = "Please Insert upper case letter"
		}
		else{
			check = 1;
			$scope.passReq4 = ""
		}
		if($scope.password.length < 8){
			check = 0;
			$scope.passReq2 = "Length must be 8 or greater"
		}
		else{
			check = 1;
			$scope.passReq5 = ""
		}
		}
		else{
			$scope.passReq1 = "Please Enter Password"
		}
		// finished all the password reqirements
		if(check==0){
			// disable button
			$scope.disableBtn = true;
		}
		else{
			//enable button
			$scope.disableBtn = false;
		}
	}
	
	// check if both passwords are equal
	
	$scope.confirmPass = function(){
		if($scope.password1){
			if($scope.password == $scope.password1){
				$scope.disableBtn = false;
				$scope.passConf = "";
			}
			else{
				$scope.passConf = 'Passwords does not match';
				$scope.disableBtn = true;
			}
		}
		else{
			$scope.disableBtn = true;
		}
	}
	
	//validate email address
	
		$scope.checkEmail = function(){
			if($scope.email){
				if($scope.email.search(/[\w.]+[@][\w]+\.[\w.]+/) == -1){
					//means invalid
					$scope.userEmail = "Invalid Email"
					$scope.emailClass = error_color
				}
				else{
					// check if already exists
					
					var req =  {
						method : 'POST',
						url : 'appphp/registeration_precheck.php',
						data : {
							email : $scope.email
						}
					}
					$http(req).then(function(response){
					// now i have the response from the server
						if(response.data.res == 'valid'){
							$scope.userEmail = "Valid"
							$scope.emailClass = success_color
							$scope.disableBtn = false;
						}
						else if(response.data.res == 'allready'){
							$scope.emailClass = error_color;
							$scope.userEmail = "Email already exists";
							$scope.disableBtn = true;
						}
						
					})
					
					
				}
			}
			else{
				$scope.userEmail = "Please Enter Email"
				$scope.emailClass = error_color
			}
		}
	
}]);