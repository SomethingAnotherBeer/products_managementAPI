<?php 
namespace App\Models;
use App\Includes;
abstract class Model
{
	protected $connection;

	public function __construct(){
		$this->connection = Includes\DB::getConnection();
	}

	protected function fetchQueryRows($query){
		$arr = [];
		while($row = $query->fetch(\PDO::FETCH_ASSOC)){
			$arr[] = $row;
		}
		return $arr;
	}

	protected function fetchQueryRow($query){
		return $row = $query->fetch();
		
	}
}






 ?>