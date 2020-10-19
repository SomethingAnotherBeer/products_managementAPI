<?php 
namespace App\Controllers;
use App\Errors;
use App\Services;
use App\View;
use App\HTTP;

class AuthentificationController extends ServiceController
{	private $login;
	private $password;

	public function __construct(){
		parent::__construct();
		if (!$this->checkRequestParams($this->request_post_data,['login','password'])) throw new Errors\BadRequestException("Для аутентификации ожидались параметры login и password");
		$this->login = $this->request_post_data['login'];
		$this->password = $this->request_post_data['password'];
		unset($this->request_post_data);
	}

	public function auth(){
		if ($this->request_method !== 'POST')return 0;
		$data = Services\Authentification::auth($this->login,$this->password);
		HTTP\HTTPHeaders::setHeader($data['header']);
		HTTP\HTTPHeaders::createHeadersResponse();
		View\View::displayResponse($data['body']);
		
	}
}




 ?>