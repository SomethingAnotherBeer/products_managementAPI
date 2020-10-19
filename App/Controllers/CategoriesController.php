<?php 
namespace App\Controllers;
use App\HTTP;
use App\View;
use App\Models;
use App\Services;
use App\Errors;
class CategoriesController extends HomeController
{	
	private $categories_model;
	public function __construct(){
		parent::__construct();
	    $this->categories_model = new Models\CategoriesModel();
	}

	public function getCategories(){
		if($this->request_method !== 'GET')return 0;
		$data = $this->categories_model->getCategories();
		$this->createResponse($data);

	}

	public function addCategory(){
		if($this->request_method !== 'POST')return 0;
		if(!$this->checkRequestParams($this->request_post_data,['cat_name'])) throw new Errors\BadRequestException('Ошибка: фактические параметры отличны от ожидаемых');
		$data = $this->categories_model->addCategory($this->request_post_data['cat_name']);
		$this->createResponse($data);

	}

	public function editCategory(){
		if($this->request_method !== 'POST')return 0;
		if(!$this->checkRequestParams($this->request_post_data,['id','cat_name'])) throw new Errors\BadRequestException('Ошибка: фактические параметры отличны от ожидаемых');
		$data = $this->categories_model->editCategory($this->request_post_data['id'],$this->request_post_data['cat_name']);
		$this->createResponse($data);
	}

	public function deleteCategory(){
		if($this->request_method !== 'POST')return 0;
		if(!$this->checkRequestParams($this->request_post_data,['cat_id'])) throw new Errors\BadRequestException('Ошибка: ожидался только параметр cat_id');
		$data = $this->categories_model->deleteCategory($this->request_post_data['cat_id']);
		$this->createResponse($data);
	}

	public function getProductsCount(){
		if($this->request_method !== 'POST')return 0;
		if(!$this->checkRequestParams($this->request_post_data,['cat_id'])) throw new Errors\BadRequestException('Ошибка: ожидался только параметр cat_id');
		$data =$this->categories_model->getProductsCount($this->request_post_data['cat_id']);
		$this->createResponse($data);
	}

	public function getProductsPrice(){
		if($this->request_method!=='POST')return 0;
		if(!$this->checkRequestParams($this->request_post_data,['cat_id']))throw new Errors\BadRequestException('Ошибка: Ожидался только параметр cat_id');
		$data = $this->categories_model->getProductsPrice($this->request_post_data['cat_id']);
		$this->createResponse($data);
		
	}

	public function getCategoryProducts(){
		if($this->request_method !== 'POST')return 0;
		if(!$this->checkRequestParams($this->request_post_data,['cat_id'])) throw new Errors\BadRequestException('Ошибка: ожидался только параметр cat_id');
		$data = $this->categories_model->getCategoryProducts($this->request_post_data['cat_id']);
		$this->createResponse($data);

	}




}





 ?>