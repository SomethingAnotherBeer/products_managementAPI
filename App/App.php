<?php 
namespace App;

class App
{
	public static function main(){
		try{
			HTTP\HTTPHeaders::setHeader('Content-Type:application/json');
			Includes\DB::setConnection('localhost','product_manage','root','your_password');

			$route = new Services\Route($_SERVER['REQUEST_URI']);
			$uri_params = $route->getUriParams();
			$controller = new $uri_params['controller'];
			$action = $uri_params['action'];
			
			$controller->$action();

		}
		catch(Errors\NotFoundException $e){
			HTTP\HTTPHeaders::setHeader('HTTP/1.1 404 Not Found');
			HTTP\HTTPHeaders::createHeadersResponse();
			View\View::displayResponse('Ресурс не найден');
		}

		catch(Errors\PermissionDeniedException $e){
			HTTP\HTTPHeaders::setHeader('HTTP/1.1 403 Forbidden');
			HTTP\HTTPHeaders::createHeadersResponse();
			View\View::displayResponse('Доступ запрещен');
		}

		catch(Errors\MethodNotAllowedException $e){
			HTTP\HTTPHeaders::setHeader('HTTP/1.1 405 Method Not Allowed');
			HTTP\HTTPHeaders::createHeadersResponse();
			View\View::displayResponse('Метод не поддерживается');
		}
		catch(Errors\BadRequestException $e){
			HTTP\HTTPHeaders::setHeader('HTTP/1.1 400 Bad Request');
			HTTP\HTTPHeaders::createHeadersResponse();
			View\View::displayResponse($e->getMessage());
		}
		catch(Errors\UnauthorizedException $e){
			HTTP\HTTPHeaders::setHeader('HTTP/1.1 401 Unauthorized');
			HTTP\HTTPHeaders::createHeadersResponse();
			View\View::displayResponse('Вы не аутентифицированы в системе');
		}
		catch(Errors\UnsupportedMediaTypeException $e){
			HTTP\HTTPHeaders::setHeader('HTTP/1.1 415 Unsupported Media Type');
			HTTP\HTTPHeaders::createHeadersResponse();
			View\View::displayResponse($e->getMessage());
			
		
	}
		}
}





 ?>