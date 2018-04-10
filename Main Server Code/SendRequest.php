<?php

function get_url($request_url) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $request_url);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $response = curl_exec($ch);
  curl_close($ch);

  return $response;
}

$params = '["server1Sachinkhokhar","https://i.imgur.com/EjNdrgp.jpg"]';

$request_url = "http://159.89.164.208:6800/jsonrpc?method=aria2.addUri&id=hello&params=".base64_encode($params);
print_r(get_url($request_url));
?>