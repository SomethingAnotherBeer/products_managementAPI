<?php 
namespace App\Models;
use App\Includes;

class UsersModel extends Model
{
	public function __construct(){
		parent::__construct();
	}

	public function getUsers(){
		$query = $this->connection->query("SELECT id,login,user_name,user_surname,type FROM users");
		$users_params = $this->fetchQueryRows($query);
		for($i = 0;$i<count($users_params);$i++){
			if($users_params[$i]['user_name']== null || $users_params[$i]['user_surname']==null){
				$users_params[$i]['fullname'] = 'Анонимный пользователь';	
			}
			else{
				$users_params[$i]['fullname'] = $users_params[$i]['user_name'] . " ".$users_params[$i]['user_surname'];
			}
			unset($users_params[$i]['user_name']);
			unset($users_params[$i]['user_surname']);
		}


		return ['header'=>'HTTP/1.1 200 OK','body'=>$users_params];

	}

	public function createUser(array $user_params):array{
		$validate_login = $this->validateLogin($user_params['login']);
		if(!$validate_login[1])return ['header'=>'HTTP/1.1 400 Bad Request','body'=>$validate_login[0]];

		$validate_password = $this->validatePassword($user_params['password']);
		if(!$validate_password[1])return ['header'=>'HTTP/1.1 400 Bad Request','body'=>$validate_password[1]];
		
		if(!$this->validateType($user_params['type']))return ['HTTP/1.1 400 Bad Request','body'=>'Недопустимый тип пользователя'];

		$login = $user_params['login'];
		$password = md5($user_params['password']);
		$type = $user_params['type'];
		$name = $user_params['name'];
		$surname = ($name !== null) ? $user_params['surname'] : null;

		try{
			$this->connection->exec("INSERT INTO users (login,password,user_name,user_surname,type) VALUES ('$login','$password','$name','$surname','$type')");
			return ['header'=>'HTTP/1.1 201 Created','body'=>'Пользователь успешно создан'];
		}
		catch(\PDOException $e){
			$header = 'HTTP/1.1 400 Bad Request';
			$body = $e->getMessage();
			if($e->getCode()==23000)$body ='Данный пользователь уже существует в системе';
			return ['header'=>$header,'body'=>$body];
		}

	}

	public function deleteUser(int $user_id ): array{
		$query =  $this->connection->exec("DELETE FROM users WHERE id = $user_id");
		if($query == 0)return ['header'=>'HTTP/1.1 400 Bad Request','body'=>'Пользователя с данным id не существует'];
		return ['header'=>'HTTP/1.1 201 Created','body'=>'Пользователь успешно удален'];
	}

	public function changeUser(array $user_params,int $user_id){
		try{
			$this->connection->beginTransaction();

			$user_param_key = null;
			foreach($user_params as $user_param){
				$user_param_key = array_search($user_param, $user_params);
				$this->connection->exec("UPDATE users SET $user_param_key = '$user_param' WHERE id = $user_id");
			}

			$this->connection->commit();
			return ['header'=>'HTTP/1.1 201 Created','body'=>'Параметры пользователя успешно изменены'];
		}
		catch(\PDOException $e){
			$this->connection->rollBack();
			return ['header'=>'HTTP/1.1 400 Bad Request','body'=>$e->getMessage()];
		}

	}

	public function getUserParamsByToken(string $token):array{
		$token = md5($token);
		$query =  $this->connection->query("SELECT user_name,user_surname,type FROM users WHERE token = '$token'");
		if($query->rowCount()==0)return ['header'=>'HTTP/1.1 400 Bad Request','body'=>'Пользователя не существует или токен не действителен'];

		$prepared_query = $this->fetchQueryRow($query);
		

		$result['fullname'] = (($prepared_query['user_name'] == null || $prepared_query['user_surname'] == null)) ? 'Анонимный пользователь' : $prepared_query['name'] . " " . $prepared_query['surname']; 
		$result['type'] = $prepared_query['type'];

		return ['header'=>'HTTP/1.1 200 OK','body'=>$result];
	}

	private function validateLogin(string $login):array{
		if(strlen($login)>mb_strlen($login)) return ['Некорректный логин: Кириллица не допустима',false];
		if(strlen($login)<3) return ['Некорректный логин: длина логина должна составлять не менее трех символов',false];
		if(count(explode(' ', $login))>1) return ['Некорректный логин: использование пробелов не допустимо',false];
		return ['Валидация прошла успешно',true];
	}
	private function validatePassword(string $password):array{
		if(strlen($password)>mb_strlen($password)) return ['Некорректный пароль: Кириллица не допустима',false];
		if(strlen($password)<3) return ['Некорректный пароль: длина пароля должна составлять не менее трех символов',false];
		if(count(explode(' ', $password))>1) return ['Некорректный пароль: использование пробелов не допустимо',false];
		return ['Валидация прошла успешно',true];
	}

	private function validateType(string $type): bool{
		$validate_types = ['admin','manager'];
		if(!in_array($type, $validate_types))return false;
		return true;
	}

	

}




 ?>