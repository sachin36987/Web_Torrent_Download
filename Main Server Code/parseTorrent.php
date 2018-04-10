<?php

require 'vendor/autoload.php';

$encoder = new PHP\BitTorrent\Encoder();
$decoder = new PHP\BitTorrent\Decoder($encoder);

$path = __DIR__."\9DBEE30BA6A2A9B8D0A5C8AA4296792B55209BF5.torrent";
$decodedFile = $decoder->decodeFile($path);

print_r(sha1($encoder->encode($decodedFile['info'])));

$info = $decodedFile['info'];

$files = $info['files'];

$length = 0;

foreach($files as $file){
	$length+=$file['length'];
}

echo round((($length/1024)/1024));
?>