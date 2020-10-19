<?php 
namespace App\Helpers;

class Handler
{
	public static function prepareQueryObjectToArray($query){
		$arr =[];
		while($row = $query->fetch(\PDO::FETCH_ASSOC)){
			$arr[] = $row;
		}
		return $arr;
	}
}



 ?>