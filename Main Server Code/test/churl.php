<?php

// please update the url

$url = "http://s320.ve.vc/data/320/42284/287407/Snapchat%20Update%20-%20Virasat%20Sandhu%20(DjPunjab.Com).mp3"; // URL you are trying to upload

$curl = curl_init();
curl_setopt_array( $curl, array(
	CURLOPT_HEADER => true,
    CURLOPT_NOBODY => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_URL => $url ) );
curl_exec( $curl );
$head = curl_getinfo( $curl );
curl_close( $curl );



print_r($head);
?>