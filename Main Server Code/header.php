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
</style>
<nav class="navbar navbar-expand-lg" style="background:'.$website_navigation_back_color.'">
 <a class="navbar-brand" href="/">
    <img src="'.$website_logo.'" width="'.$logo_width.'" height="'.$logo_height.'" class="d-inline-block align-top" alt="">
    '.$website_moto.'
  </a>
<button class="navbar-toggler navbar-light" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon" ></span>
  </button>
<div class="collapse navbar-collapse " id="navbarSupportedContent" style="margin-left:50%">
    <ul class="navbar-nav mr-auto ">
      <li class="nav-item active">
        <a class="nav-link" href="/"><button class="btn btn-info btn-lg">Home </button><span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/login"><button class="btn btn-info btn-lg"> Login</button></a>
      </li>
	  <li class="nav-item">
        <a class="nav-link" href="/register"><button class="btn btn-info btn-lg"> Register</button></a>
      </li>
	  </ul>
      
  </div>
</nav>
';

// show navigation
	echo $nav;




?>