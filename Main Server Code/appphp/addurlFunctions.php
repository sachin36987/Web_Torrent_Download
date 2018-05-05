<?php

function hashh($url)
	{
	preg_match('/^m/', $url, $matchh);
	if (count($matchh) > 0)
		{
		$dir = __DIR__ . '/temp';

		// yes magnet url
		// file name to save torrent with

		preg_match('/\w{20,40}/', $url, $match1);
		$file_name = strtolower($match1[0]) . '.torrent';

		// try fast method to get the metadata .. if not possible then get from second method

		if ($torrent_data = file_get_contents('http://itorrents.org/torrent/' . $file_name))
			{
			file_put_contents(__DIR__ . '/temp/' . $file_name, $torrent_data);
			}

		// download metadata -- second method

		  else
			{
			$to_execute = 'aria2c "' . $url . '" --bt-metadata-only=true --bt-save-metadata=true --dir="' . $dir . '"';
			$task_info = shell_exec($to_execute);
			}

		// get the file information

		if (file_exists(__DIR__ . '/temp/' . $file_name))
			{

			// means file downloaded

			$to_return = cal_hash_size(__DIR__ . '/temp/' . $file_name);
			unlink(__DIR__ . '/temp/' . $file_name);
			$to_return['magnet'] = $url;
			preg_match('/dn=[\w\+%\-\.]+/', $url, $match);
			preg_match('/[\w\+%\-\.]+$/', $match[0], $match1);
			$to_return['name'] = $match1[0];
			return $to_return;
			}
		  else
			{

			// fishy throw error

			$rep = json_encode(array(
				'res' => 'error in magnet part'
			));
			echo $rep;
			exit(0);
			}
		}
	  else
		{

		// not magnet
		// download file .. check size before hand... if file size is larger than 1 mb than must be fishy

		$headers = get_headers($url, true);
		if ($headers['Content-Length'] < 10000 && !isset($headers['Location']))
			{

			// get file

			$torrent_data = file_get_contents($url);

			// put_file_on_disk

			$torrent_name_temp = time() . microtime() . "torrent";
			file_put_contents(__DIR__ . '/temp/' . $torrent_name_temp, $torrent_data);

			// calculate hash and return magnet link

			$to_return = cal_hash_size(__DIR__ . '/temp/' . $torrent_name_temp);

			// create magnet link

			preg_match_all('/udp:\/\/[\w\.]+:[\d]+\/announce/', $torrent_data, $matches);
			$trackers = '';
			foreach($matches[0] as $match)
				{
				$trackers.= '&tr=' . urlencode($match);
				}

			$to_return['magnet'] = 'magnet:?xt=urn:btih:' . $to_return['hash'] . $trackers;

			// before returning delete torrent file

			unlink(__DIR__ . '/temp/' . $torrent_name_temp);
			preg_match('/name[\d]+:(.*)12/', $torrent_data, $matches);
			$to_return['name'] = $matches[0];
			return $to_return;
			}
		  else
			{

			// fishy throw error

			$rep = json_encode(array(
				'res' => 'error in addurlfunctions'
			));
			echo $rep;
			exit(0);
			}
		}
	}

function cal_hash_size($path)
	{
	require __DIR__.'/vendor/EncoderInterface.php';
	require __DIR__.'/vendor/DecoderInterface.php';
	require __DIR__.'/vendor/Encoder.php';
	require __DIR__.'/vendor/Decoder.php';
	require __DIR__.'/vendor/Torrent.php';

	$encoder = new Encoder();
	$decoder = new Decoder($encoder);

	$decodedFile = $decoder->decodeFile($path);
	$hash = sha1($encoder->encode($decodedFile['info'])); // hash of file
	$info = $decodedFile['info'];
	$files = $info['files'];
	$length = 0;
	foreach($files as $file)
		{
		$length+= $file['length'];
		}

	$file_size = round((($length / 1024) / 1024)); // total size of torrent
	return array(
		'hash' => $hash,
		'size' => $file_size
	);
	}

?>