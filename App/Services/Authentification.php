<?php 
namespace App\Services;
use App\Includes;
use App\Errors;
class Authentification
{
	private static $token_key;
	private static $user_id;
	private static $connection;

	public static function auth(string $login,string $password):array{
		self::validateLogin($login);
		self::validatePassword($password);

		$password = md5($password);
		self::$connection = Includes\DB::getConnection();
		$query = self::$connection->query("SELECT id FROM users WHERE login = '$login' AND password = '$password'");
		if($query->rowCount()===0) return ['header'=>'HTTP/1.1 400 Bad Request','body'=>'Неправильный логин или пароль'];

		self::$user_id = intval($query->fetchColumn());
		self::$token_key = self::generateToken(25);
		self::insertToken(self::$user_id, self::$token_key);
		return ['header'=>'HTTP/1.1 200 OK','body'=>self::$token_key];
	}

	public static function checkAuth($token):bool {
		self::$connection = Includes\DB::getConnection();
		$checked_token = md5($token);
		$query = self::$connection->query("SELECT * FROM users WHERE token = '$checked_token'");
		if($query->rowCount()!=0)return true;
		return false;

	}

	private static function validateLogin(string $login){
		if(strlen($login)>mb_strlen($login)) throw new Errors\BadRequestException('Некорректный логин: Кириллица не допустима');
		if(strlen($login)<3) throw new Errors\BadRequestException('Некорректный логин: Длина логина должна составлять не менее трех символов');
		if(count(explode(' ', $login))>1) throw new Errors\BadRequestException('Некорректный логин: Использование пробелов не допустимо');

	}

	private static function validatePassword(string $password){
		if(strlen($password)>mb_strlen($password)) throw new Errors\BadRequestException('Некорректный пароль: Кириллица не допустима');
		if(strlen($password)<3) throw new Errors\BadRequestException('Некорректный пароль: Длина логина должна составлять не менее трех символов');
		if(count(explode(' ', $password))>1) throw new Errors\BadRequestException('Некорректный пароль: Использование пробелов не допустимо');
	}
	
	
	private static function generateToken(int $length):string{
		$token = '';
		$symbol_group = 0;
		for($i = 0;$i<$length;$i++){
			$symbol_group = rand(1,3);
			switch($symbol_group){
				case 1:
					$token.= rand(0,9);
				break;

				case 2:
					$token.= chr(rand(65,90));
				break;

				case 3:
					$token.= chr(rand(97,122));
			}
		}
		return $token;

	}

	private static function insertToken(int $user_id,string $token_key){
		$inserted_token = md5($token_key);
		self::$connection->query("UPDATE users SET token = '$inserted_token' WHERE id = $user_id");
		
		

	}






}




 ?>