<?php

class CurlUtil{
	
	static function doGet($url, $return_transfer=true, $timeout=30, $ssl_verify_peer = 0, $ssl_verify_host=2){
		if(empty($url)) {
			throw new Exception("No url for cURL.");
		}
		$server_output = "";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		
		// receive server response ...
		if($return_transfer){
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		}
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ssl_verify_peer);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  $ssl_verify_host);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

		try{
			$server_output = trim(curl_exec($ch));
			if($server_output === false){
				return 'Error connecting to '.$url;
			}
		}catch(Exception $e){
			return "Error occured. ".$e->getMessage();
				
		}
		
		/*$info = curl_getinfo($ch);*/
		
		curl_close ($ch);
		return $server_output;

	}

	static function doPost($url, $post_data, $ssl_verify_peer = 0, $ssl_verify_host = 2){
		if(empty($url) || empty($post_data)) {
			throw new Exception("No url or post data for cURL.");
		}
		
		$ch = curl_init();
                
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ssl_verify_peer);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $ssl_verify_host);
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);	//Post data gets lost while following location	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		try{
			$server_output = curl_exec($ch);
			if($server_output === false){
				return 'Error connecting to '.$url;
			}
		}catch(Exception $e){
			return "Error occured. ".$e->getMessage();
				
		}
		//$info = curl_getinfo($ch);
		curl_close ($ch);
		return $server_output;

	}


}