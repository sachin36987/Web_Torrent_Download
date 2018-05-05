<?php
error_reporting(E_ALL);
/*
$magnet = 'magnet:?xt=urn:btih:32FA70FF92D7077D916B12CE52202219324847F5&dn=Deep+Blue+Sea+2.2018.DVDRip.XviD.AC3-EVO%5BEtMovies%5D&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A6969%2Fannounce&tr=udp%3A%2F%2Ftracker.internetwarriors.net%3A1337%2Fannounce&tr=udp%3A%2F%2Finferno.demonoid.pw%3A3391%2Fannounce&tr=udp%3A%2F%2Fopen.stealth.si%3A80%2Fannounce&tr=udp%3A%2F%2Ftracker.pirateparty.gr%3A6969%2Fannounce&tr=udp%3A%2F%2Fpeerfect.org%3A6969%2Fannounce&tr=udp%3A%2F%2Fp4p.arenabg.com%3A1337%2Fannounce&tr=udp%3A%2F%2Ftracker.open-internet.nl%3A6969%2Fannounce&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969%2Fannounce&tr=udp%3A%2F%2Fipv4.tracker.harry.lu%3A80%2Fannounce&tr=udp%3A%2F%2Fshadowshq.yi.org%3A6969%2Fannounce&tr=udp%3A%2F%2Ftracker.vanitycore.co%3A6969%2Fannounce&tr=udp%3A%2F%2Ftracker.opentrackr.org%3A1337%2Fannounce&tr=udp%3A%2F%2Fexplodie.org%3A6969%2Fannounce&tr=udp%3A%2F%2Ftracker.zer0day.to%3A1337%2Fannounce&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969%2Fannounce&tr=udp%3A%2F%2Fcoppersurfer.tk%3A6969%2Fannounce';
$dir = __DIR__.'/appphp/temp';
$to_execute = 'aria2c "'.$magnet.'" --bt-metadata-only=true --bt-save-metadata=true --dir="'.$dir.'"';
$task_info = shell_exec($to_execute);
print_r($task_info);
*/
$magnet = 'magnet:?xt=urn:btih:E8A3623409497C53A274409AE92E0EACC53D4FB2&dn=Cardi+B+-+Invasion+of+Privacy+%282018%29+Mp3+%28320kbps%29+%5BHunter%5D&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A6969%2Fannounce&tr=udp%3A%2F%2Ftracker.pirateparty.gr%3A6969%2Fannounce&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969%2Fannounce&tr=udp%3A%2F%2Fpublic.popcorn-tracker.org%3A6969%2Fannounce&tr=udp%3A%2F%2Ftracker.zer0day.to%3A1337%2Fannounce&tr=udp%3A%2F%2Ftracker.opentrackr.org%3A1337%2Fannounce&tr=udp%3A%2F%2Feddie4.nl%3A6969%2Fannounce&tr=udp%3A%2F%2Feddie4.nl%3A6969%2Fannounce&tr=udp%3A%2F%2Ftracker.cypherpunks.ru%3A6969%2Fannounce&tr=udp%3A%2F%2Finferno.demonoid.pw%3A3418%2Fannounce&tr=udp%3A%2F%2Fthetracker.org%3A80%2Fannounce&tr=udp%3A%2F%2Fthetracker.org%3A80%2Fannounce&tr=udp%3A%2F%2F9.rarbg.com%3A2710%2Fannounce&tr=udp%3A%2F%2Fpubt.in%3A2710%2Fannounce&tr=udp%3A%2F%2Ftracker.zer0day.to%3A1337%2Fannounce&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969%2Fannounce&tr=udp%3A%2F%2Fcoppersurfer.tk%3A6969%2Fannounce';
//$data = array('hash'=>'1C20BE0B5CDBA45C2DDDB60DB09A67FC174AA31E','key'=>'mykey','magnet'=>$magnet);
$data = array('hash'=>'1C20BE0B5CDBA45C2DDDB60DB09A67FC174AA31E');
//$server = 'https://server1.faltutech.club/addTorrent.php';
//$server = 'https://server1.faltutech.club/currentProgress.php';
$server = 'https://server1.faltutech.club/files.php';
 $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_URL, $server);
    curl_setopt($ch, CURLOPT_SSLVERSION,3); 
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($ch, CURLOPT_TCP_FASTOPEN, true);
	curl_setopt($ch, CURLOPT_SSLVERSION,6);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
    $result = curl_exec($ch);
    curl_close($ch);
	
print_r($result);

?>