<?php 
namespace App\Includes;

class DB 
{
	private static $connection;
	public static function setConnection($host,$dbname,$user,$password){
		self::$connection = new \PDO("mysql:host=$host;dbname=$dbname;charset=utf8",$user,$password);
		self::$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

	public static function getConnection(){
		return self::$connection;
	}
}



 ?>