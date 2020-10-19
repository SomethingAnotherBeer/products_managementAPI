<?php 
namespace App\View;

class View
{
	public static function displayResponse($message){
		echo json_encode($message,JSON_UNESCAPED_UNICODE);
	}
	public static function renderPage($page,$format){
		$render_page = require_once "App/Views/$page.$format";
	}

}




 ?>