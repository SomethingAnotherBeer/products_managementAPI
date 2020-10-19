<?php 
namespace App\Controllers;
use App\HTTP;
use App\View;
use App\Services;
use App\Errors;
use App\Models;
use App\Helpers;
class UsersController extends HomeController
{
	
	private $user_model;
	public function __construct(){
		parent::__construct();
		$this->user_model = new Models\UsersModel();
	}


	public function getUsers(){
		if($this->request_method !== 'GET')return 0;
		if(Services\Authorization::getRightsAccess($this->token_key) !== 'admin') throw new Errors\PermissionDeniedException();
		$data = $this->user_model->getUsers();
		$this->createResponse($data);
	}

	public function createUser(){
		if($this->request_method !== 'POST')return 0;
		if(Services\Authorization::getRightsAccess($this->token_key) !== 'admin') throw new Errors\PermissionDeniedException();

		if(!$this->checkRequestParams($this->request_post_data, ['login','password','name','surname','type'])) throw new Erros\BadRequestException('Для создания пользователя ожидаются параметры: login,password,type (обязательные), name,surname (необязательные)');
		$login = $this->request_post_data['login'];
		$password = $this->request_post_data['password'];
		$name = (isset($this->request_post_data['name'])) ? $this->request_post_data['name'] : null;
		$surname = (isset($this->request_post_data['surname'])) ? $this->request_post_data['surname'] : null;
		$type = $this->request_post_data['type'];

		$data = $this->user_model->createUser(['login'=>$login,'password'=>$password,'name'=>$name,'surname'=>$surname,'type'=>$type]);
		$this->createResponse($data);

	}

	public function deleteUser(){
		if($this->request_method !== 'POST')return 0;
		if(Services\Authorization::getRightsAccess($this->token_key) !== 'admin') throw new Errors\PermissionDeniedException();

		if(!$this->checkRequestParams($this->request_post_data,['id']))throw new Errors\BadRequestException('Не удалось удалить пользователя: фактические параметры отличны от ожидаемых');
		$user_id = intval($this->request_post_data['id']);
		$data = $this->user_model->deleteUser($user_id);
		$this->createResponse($data);

	}

	public function changeUser(){
		if($this->request_method !== 'POST')return 0;
		if(Services\Authorization::getRightsAccess($this->token_key) !== 'admin') throw new Errors\PermissionDeniedException();

		/*if(!$this->checkRequestParams($this->request_post_data,['id','user_name','user_surname','type'])) throw new Errors\BadRequestException('Не удалось изменить параметры пользователя: фактические параметры отличны от ожидаемых');
		$user_id = intval($this->request_post_data['id']);*/

		if(!$this->checkRequestParams($this->request_post_data,['id','edited_user_params'])) throw new Errors\BadRequestException('Не удалось изменить параметры пользователя: Фактические параметры отличны от ожидаемых');
		if(!$this->checkRequestParams($this->request_post_data['edited_user_params'],['user_name','user_surname','type'])) throw new Errors\BadRequestException('Не удалось изменить параметры пользователя: Фактические параметры отличны от ожидаемых');
		$data = $this->user_model->changeUser($this->request_post_data['edited_user_params'],$this->request_post_data['id']);
		$this->createResponse($data);


	}

	public function getUserParams(){
		if($this->request_method !== 'GET')return 0;
		$data = $this->user_model->getUserParamsByToken($this->token_key);
		$this->createResponse($data);
	}
	
}







 ?>