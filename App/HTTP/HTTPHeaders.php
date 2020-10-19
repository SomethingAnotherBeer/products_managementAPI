<?php 
namespace App\HTTP;

class HTTPHeaders
{
	private static $headers = [];

	public static function setHeader(string $header){
		if(!in_array($header, self::$headers)) self::$headers[] = $header;
	}

	public static function createHeadersResponse(){
		foreach(self::$headers as $header){
			header($header);
		}
	}
}




 ?>