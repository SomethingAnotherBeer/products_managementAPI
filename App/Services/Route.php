<?php 
namespace App\Services;
use App\Errors;
class Route
{
	private $uri;
	private $uri_arr = [
		'/authentification/auth'=>['controller'=>'Authentification','action'=>'auth'],
		'/users/getusers'=>['controller'=>'Users','action'=>'getUsers'],
		'/users/getuserinfo'=>['controller'=>'Users','action'=>'getUserParams'],
		'/users/createuser'=>['controller'=>'Users','action'=>'createUser'],
		'/users/deleteuser'=>['controller'=>'Users','action'=>'deleteUser'],
		'/users/changeuser'=>['controller'=>'Users','action'=>'changeUser'],
		'/categories/getcategories'=>['controller'=>'Categories','action'=>'getCategories'],
		'/categories/addcategory'=>['controller'=>'Categories','action'=>'addCategory'],
		'/categories/editcategory'=>['controller'=>'Categories','action'=>'editCategory'],
		'/categories/deletecategory'=>['controller'=>'Categories','action'=>'deleteCategory'],
		'/categories/getproductscount'=>['controller'=>'Categories','action'=>'getProductsCount'],
		'/categories/getproductsprice'=>['controller'=>'Categories','action'=>'getProductsPrice'],
		'/categories/getcategoryproducts'=>['controller'=>'Categories','action'=>'getCategoryProducts'],
		'/products/addproduct'=>['controller'=>'Products','action'=>'addProduct'],
		'/products/editproduct'=>['controller'=>'Products','action'=>'editProduct'],
		'/products/deleteproduct'=>['controller'=>'Products','action'=>'deleteproduct'],
		'/products/getproductprice'=>['controller'=>'Products','action'=>'getProductPrice'],
		'/products/getproductscount'=>['controller'=>'Products','action'=>'getProductsCount'],
		'/products/gettotalprice'=>['controller'=>'Products','action'=>'getTotalPrice'],
		'/products/getproductcategories'=>['controller'=>'Products','action'=>'getProductCategories'],
		'/products/insertproductincategory'=>['controller'=>'Products','action'=>'insertProductInCategory']

	];

	public function __construct(string $uri){
		$this->uri = $this->prepareGet(strtolower($uri));

	}

	public function getUriParams(){
		$this->checkUri();
		return $this->getPreparedUriParams();

	}



	private function checkUri():bool{
		if(isset($this->uri_arr[$this->uri])){
			return true;
		}
		else{
			throw new Errors\NotFoundException();
			
		}
	}

	private function getPreparedUriParams():array{
		$uri_params = $this->uri_arr[$this->uri];
		$controller = 'App\\Controllers\\'.$uri_params['controller'].'Controller';
		$action = $uri_params['action'];
		return ['controller'=>$controller,'action'=>$action];
		

	}

	


	private function prepareGet(string $uri):string{
		$new_uri = null;
		for($i = 0;$i<strlen($uri);$i++){
			if ($uri[$i]==='?')break;
			$new_uri.=$uri[$i];
		}
		return $new_uri;
	}
}





 ?>