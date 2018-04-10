<?php

function hashh($url){
	preg_match('/^m/',$url,$matchh);
	if(count($matchh)> 0){
			// yes magnet url
			//download metadata
			$dir = __DIR__.'/appphp/temp';
			//$to_execute = 'aria2c "'.$url.'" --bt-metadata-only=true --bt-save-metadata=true --dir="'.$dir.'"';
			//$task_info = shell_exec($to_execute);
			//preg_match('/download\scompleted\.$/',$task_info,$match);
			// extract file name (to delete and get torrent info) and hash and size
				preg_match('/\w{20,40}/',$url,$match1);
				$file_name = $match1[0].'.torrent';
			//if(is_array($match) && count($match)>0){
				if(file_exists(__DIR__.'/temp/'.$file_name)){
				// means file downloaded
				
				$to_return = cal_hash_size(__DIR__.'/temp/'.$file_name);
				//unlink('temp/'.$file_name);
				
				return $to_return;
				
			}
			else{
				// fishy throw error
				$rep = json_encode(array('res'=>'error'));
				echo $rep;
				exit(0);
			}
			
		}
		else{
			// not magnet
			
			// download file .. check size before hand... if file size is larger than 1 mb than must be fishy
			$headers = get_headers($url,true);
			if($headers['Content-Length'] < 10000 && !isset($headers['Location'])){
				
				// get file
				$torrent_data = file_get_contents($url);
				
				//put_file_on_disk
				$torrent_name_temp = time().microtime()."torrent";
				file_put_contents(__DIR__.'/temp/'.$torrent_name_temp,$torrent_data);
				
				// calculate hash and return magnet link
				
				$to_return = cal_hash_size(__DIR__.'/temp/'.$torrent_name_temp);
					
				// before returning delete torrent file
				//unlink(__DIR__.'/temp/'.$torrent_name_temp);
				
				return $to_return;
				
			}
			else{
				// fishy throw error
				$rep = json_encode(array('res'=>'error'));
				echo $rep;
				exit(0);
			}
		}
}

function cal_hash_size($path){
				require 'vendor/autoload.php';

				$encoder = new PHP\BitTorrent\Encoder();
				$decoder = new PHP\BitTorrent\Decoder($encoder);
				
				$decodedFile = $decoder->decodeFile($path);

				$hash = sha1($encoder->encode($decodedFile['info'])); // hash of file
				
				$info = $decodedFile['info'];

				$files = $info['files'];

				$length = 0;

				foreach($files as $file){
					$length+=$file['length'];
				}

				$file_size = round((($length/1024)/1024));  // total size of torrent
				
				return array('hash'=>$hash,'size'=>$file_size);
}



?>