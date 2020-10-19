<?php 
namespace App\Controllers;
use App\HTTP;
use App\View;
use App\Models;
use App\Services;
use App\Errors;


class ProductsController extends HomeController 
{
	private $products_model;

	public function __construct(){
		parent::__construct();
		$this->products_model = new Models\ProductsModel();
	}

	public function addProduct(){
		if($this->request_method !== 'POST')return 0;

		if(!$this->checkRequestParams($this->request_post_data,['cat_id','product_params'])) throw new Errors\BadRequestException("Ошибка: фактические параметры отличны от ожидаемых");
		if(!$this->checkRequestParams($this->request_post_data['product_params'],['product_name','description','text','price','count'])) throw new Errors\BadRequestException('Ошибка: ожидаемые параметры отличны от фактических');

		$data = $this->products_model->addProduct($this->request_post_data['cat_id'],$this->request_post_data['product_params']);
		$this->createResponse($data);
	}

	public function editProduct(){
		if ($this->request_method !== 'POST')return 0;
		if(!$this->checkRequestParams($this->request_post_data,['product_id','edited_product_params'])) throw new Errors\BadRequestException("Ошибка: фактические параметры отличны от ожидаемых");
		if(!$this->checkRequestParams($this->request_post_data['edited_product_params'],['product_name','description','text','price','count'])) throw new Errors\BadRequestException('Ошибка: фактические параметры массива product_params отличны от ожидаемых');


		$data = $this->products_model->editProduct($this->request_post_data['product_id'],$this->request_post_data['edited_product_params']);
		$this->createResponse($data);
	}

	public function deleteProduct(){
		if($this->request_method !== 'POST')return 0;
		if(!$this->checkRequestParams($this->request_post_data,['product_id'])) throw new Errors\BadRequestException('Ошибка: Ожидаемые параметры отличны от фактических');
		$data = $this->products_model->deleteProduct($this->request_post_data['product_id']);
		$this->createResponse($data);
	}

	public function getProductPrice(){
		if($this->request_method !== 'POST') return 0;
		if(!$this->checkRequestParams($this->request_post_data,['product_id'])) throw new Errros\BadRequestException('Ошибка: Ожидаемые параметры отличны от фактических');
		$data = $this->products_model->getProductPrice($this->request_post_data['product_id']);
		$this->createResponse($data);
	}

	public function getProductsCount(){
		if($this->request_method !== 'GET')return 0;
		$data = $this->products_model->getProductsCount();
		$this->createResponse($data);
	}

	public function getTotalPrice(){
		if($this->request_method !== 'GET')return 0;
		$data= $this->products_model->getTotalPrice();
		$this->createResponse($data);
	}

	public function getProductCategories(){
		if($this->request_method !=='POST')return 0;
		if(!$this->checkRequestParams($this->request_post_data,['product_id'])) throw new Errors\BadRequestException('Ошибка: Ожидался только параметр product_id');
		$data = $this->products_model->getProductCategories($this->request_post_data['product_id']);
		$this->createResponse($data);
	}

	public function insertProductInCategory(){
		if($this->request_method !== 'POST')return 0;
		if(!$this->checkRequestParams($this->request_post_data,['product_id','cat_id'])) throw new Errors\BadRequestException("Ошибка: Ожидались только параметры product_id и cat_id");
		$data = $this->products_model->insertProductInCategory($this->request_post_data['product_id'],$this->request_post_data['cat_id']);
		$this->createResponse($data);
	}

}



 ?>