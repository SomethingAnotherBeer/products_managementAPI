<?php 
namespace App\HTTP;
use App\Errors;
class HTTPRequest
{
	private static $request_method;
	private static $allowed_methods = ['GET','POST'];
	private static $allowed_post_types = ['array'];

	public static function getRequestMethod(){
		self::setRequestMethod();
		return self::$request_method;
	}

	public static function setRequestMethod(){
		$request_method = $_SERVER['REQUEST_METHOD'];
		if(!in_array($request_method, self::$allowed_methods)) throw new Errors\MethodNotAllowedException();
		self::$request_method = $request_method;
	}

	public static function getRequestBody(){
		$input = json_decode(file_get_contents("php://input"),true);
		self::checkPostTypes($input);
		return $input;
	}

	public static function getAuthorizationKey(){
		return (isset($_SERVER['HTTP_AUTHORIZATION'])) ? $_SERVER['HTTP_AUTHORIZATION'] : 'none';
	}

	private static function checkPostTypes($post_request){
		if($_SERVER['REQUEST_METHOD'] === 'GET')return 0;
		$type = gettype($post_request);
		if(!in_array($type, self::$allowed_post_types)) throw new Errors\UnsupportedMediaTypeException("Тип переданного параметра $type не поддерживается приложением");
	}





}




 ?>