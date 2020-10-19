<?php 
namespace App\Controllers;
use App\HTTP;
use App\Services;
use App\Errors;

abstract class HomeController extends MainController
{
	protected $token_key;
	public function __construct(){
		parent::__construct();
		$this->access_users =['admin','manager'];
		$this->token_key = HTTP\HTTPRequest::getAuthorizationKey();
		if(!Services\Authentification::checkAuth($this->token_key)) throw new Errors\UnauthorizedException();
		//if(!in_array(Services\Authentification::checkAuth($this->token_key),$this->access_users)) throw new Errors\PermissionDeniedException();
		if(!in_array(Services\Authorization::getRightsAccess($this->token_key), $this->access_users)) throw new Errors\PermissionDeniedException();
	}
}





 ?>