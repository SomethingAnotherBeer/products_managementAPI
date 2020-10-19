<?php 
namespace App\Services;
use App\Includes;

class Authorization 
{
	private static $connection; 

	public static function getRightsAccess($token):string{
		self::$connection  = Includes\DB::getConnection(); 
		$auth_token = md5($token);
		$rights = self::$connection->query("SELECT type FROM users WHERE token = '$auth_token'")->fetchColumn();
		return $rights;
	}
}




 ?>