<?php

$nav = '
  
<style>
a{
color: '.$website_navigation_text_color.';
text-decoration: none;
background-color: transparent;
-webkit-text-decoration-skip: objects;
}
*, ::after, ::before {
    box-sizing: border-box;
}
a:hover {
    color: '.$website_navigation_link_color_hover.';
    text-decoration: underline;
}

.searchCus{
	margin-left:40%;
	margin-top:10px;
}
@media (max-width:608px){
	.searchCus{
		margin-left:0;
		margin-left:0;
	}
}
.btn-light:hover{
	cursor: pointer;
}

#overlay {
    position: fixed;
    display: none;
    width: 90%;
    height: 20%;
    top: 5%;
    left: 5%;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5);
    z-index: 2;
    cursor: pointer;
}
</style>
<nav class="navbar navbar-expand-lg" style="background:'.$website_navigation_back_color.'" ng-controller="navController">

<div class="modal fade" tabindex="-1" role="dialog" id="urlInfo">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{title}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>{{information}}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

 <a class="navbar-brand" href="/">
    <img src="'.$website_logo.'" width="'.$logo_width.'" height="'.$logo_height.'" class="d-inline-block align-top" alt="">
    '.$website_moto.'
  </a>
  <div class="searchCus form-inline">
  <form style="margin-right:10px;margin-top:10px" ng-submit="addUrl()">
    <div class="form-group mb-2 " ><input class="form-control is-valid" type="text" ng-model="urlinput" required>
	<button type="submit" class="btn-light mb-2" style="border:0;padding: 0;color:red;line-height:0.5;align:center;margin-top:10px"><img width="33px" height="33px" src="/media/plus.svg" alt="+"></button>
  </div></form>
  
  
<button class="navbar-toggler navbar-light" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon" ></span>
  </button>
<div class="collapse navbar-collapse mb-2" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto ">
      <li class="nav-item">
        <a class="nav-link" href="/files"><button class="btn btn-light">Files </button></a>
      </li>
	  <li class="nav-item">
      <span class="nav-link"> <a href="/profile">  <button class="btn btn-light"> Profile </button></a></span>
      </li>
      <li class="nav-item">
      <span class="nav-link"> <button class="btn btn-light" ng-click="logoutt()"> Logout</button></span>
      </li>
	  
	  
	  </ul>
      <!-- alert auto fade -->
<div id="success-alert" style="float:right;position:fixed">
<div class="alert {{color_class}} overlay" >
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>{{headAlert}} </strong>
    {{ContentAlert}}
</div>
</div>
  </div>
  </div>
</nav>
';

// show navigation only if user is logged in

	echo $nav;




?>