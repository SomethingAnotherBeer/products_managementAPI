<?php 
namespace App\Controllers;
use App\HTTP;
use App\View;
abstract class MainController
{
	protected $request_method;
	protected $request_post_data;
	protected $access_users = [];
	
	public function __construct(){
		$this->request_method = HTTP\HTTPRequest::getRequestMethod();
		$this->request_post_data = HTTP\HTTPRequest::getRequestBody();
	}


		protected function checkRequestParams(array $request_params, array $expected_params, bool $check_length = false):bool{
		$request_param_key = null;
		foreach($request_params as $request_param){
			$request_param_key = array_search($request_param, $request_params);
			if(!in_array($request_param_key, $expected_params))return false;
		}

		if($check_length === true){
			if(count($request_params) !== count($expected_params))return false;
		}
		
		return true;
	}




	protected function createResponse(array $response_params){
		HTTP\HTTPHeaders::setHeader($response_params['header']);
		HTTP\HTTPHeaders::createHeadersResponse();
		View\View::displayResponse($response_params['body']);
	}

	


}




 ?>