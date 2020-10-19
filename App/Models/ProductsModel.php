<?php 
namespace App\Models;

class ProductsModel extends Model
{
	public function addProduct(int $cat_id, array $product_params):array{
		if(!$this->checkProductParams($product_params)[1])return ['header'=>'HTTP/1.1 400 Bad Request','body'=>$this->checkProductParams()[0]];

		$product_name = trim($product_params['product_name']);
		$product_description = (isset($product_params['description'])) ? trim($product_params['description']) : null;
		$product_text = (isset($product_params['text'])) ? trim($product_params['text']) : null;
		$product_price = floatval($product_params['price']);
		$product_count = $product_params['count'];

		try{
			$this->connection->beginTransaction();
			$this->connection->exec("INSERT INTO products (product_name,description,text,price,count) VALUES ('$product_name','$product_description','$product_text','$product_price','$product_count')");
			$product_id = intval($this->connection->query("SELECT MAX(id) FROM products")->fetchColumn());
			$this->connection->exec("INSERT INTO products_categories (product_id,cat_id) VALUES ($product_id,$cat_id)");


			$this->connection->commit();
			return ['header'=>'HTTP/1.1 201 Created','body'=>'Товар успешно создан'];
		}
		catch(\PDOException $e){
			$this->connection->rollBack();
			return ['header'=>'HTTP/1.1 400 Bad Request','body'=>'Данной категории не существует'];
		}
	}

	public function editProduct(int $product_id, array $edited_product_params):array{

		if(count($edited_product_params)===0) return ['header'=>'HTTP/1.1 400 Bad Request','body'=>'Не было передано параметров для редактирования товара'];
		if(!$this->checkProductParams($edited_product_params)[1])return ['header'=>'HTTP/1.1 400 Bad Request','body'=>$this->checkProductParams()[0]];

		$product_id = $product_id;
		try{
			$product_key = null;

			$this->connection->beginTransaction();
			foreach($edited_product_params as $edited_product_param){
				$product_key = array_search($edited_product_param, $edited_product_params);
			    $this->connection->exec("UPDATE products SET $product_key = '$edited_product_param' WHERE id = $product_id");
			}


			$this->connection->commit();
			return ['header'=>'HTTP/1.1 201 Created','body'=>'Продукт успешно обновлен'];
		}

		catch(\PDOException $e){
			$this->connection->rollBack();
			return ['header'=>'HTTP/1.1 520 Unknown Error','body'=>$e->getMessage()];
		}
	}

	public function deleteProduct(int $product_id):array{
		try{
			$this->connection->beginTransaction();
			$query = $this->connection->exec("DELETE FROM products_categories WHERE product_id = $product_id");

			if($query==0)return ['header'=>'HTTP/1.1 400 Bad Request','body'=>'Данного товара не существует'];

			$this->connection->exec("DELETE FROM products WHERE id = $product_id");
			$this->connection->commit();
			return ['header'=>'HTTP/1.1 201 Created','body'=>'Товар успешно удален'];
		}

		catch(\PDOException $e){
			$this->connection->rollBack();
			return ['header'=>'HTTP/1.1 520 Unknown Error','body'=>$e->getMessage()];
		}
	}

	public function getProductPrice(int $product_id):array{
		$query = $this->connection->query("SELECT price*count AS price_count FROM products WHERE id = $product_id");
		return ($query->rowCount()!=0) ? ['header'=>'HTTP/1.1 200 OK','body'=>$this->fetchQueryRows($query)] : ['header'=>'HTTP/1.1 400 Bad Request','body'=>'Данного товара не существует'];

	}

	public function getProductsCount(){
		$query = $this->connection->query("SELECT COUNT(id)*SUM(count) AS products_count FROM products");
		return ($query->rowCount()!=0) ? ['header'=>'HTTP/1.1 200 OK','body'=>$this->fetchQueryRows($query)] : ['header'=>'HTTP/1.1 200 OK','body'=>'В системе нет товаров'];
	}

	public function getTotalPrice(){
		$query = $this->connection->query("SELECT SUM(price)*SUM(count) AS total_pice FROM products");
		if($query->rowCount()!==0){
			$total_price = floatval($this->fetchQueryRow($query)[0]);

			return ['header'=>'HTTP/1.1 200 OK','body'=>'total_price: '.sprintf("%8.2f",$total_price)];
		}
		return ['header'=>'HTTP/1.1 200 OK','body'=>'В системе нет товаров'];
		
	}

	public function getProductCategories(int $product_id){
		$query = $this->connection->query("SELECT categories.cat_name FROM categories LEFT JOIN products_categories ON categories.id = products_categories.cat_id RIGHT JOIN products on products_categories.product_id = products.id WHERE products.id = $product_id");
		return ($query->rowCount()!=0) ? ['header'=>'HTTP/1.1 200 OK','body'=>$this->fetchQueryRows($query)] : ['header'=>'HTTP/1.1 200 OK','body'=>'Данный товар отсутствует в системе'];


	}

	public function insertProductInCategory(int $product_id,int $cat_id){
		try{
			$this->connection->beginTransaction();
			$this->connection->exec("INSERT INTO products_categories (product_id,cat_id) VALUES ($product_id,$cat_id)");
			$this->connection->commit();
			return ['header'=>'HTTP/1.1 201 Created','body'=>'Товар успешно добавлен в категорию'];
		}

		catch(\PDOException $e){
			$this->connection->rollBack();
			return ['header'=>'HTTP/1.1 400 Bad Request','body'=>'Ошибка: Товар или категория не существует'];
		}
	}








	private function checkProductParams(array $product_params):array{
		$product_key = null;
		foreach($product_params as $product_param){
			$product_key = array_search($product_param,$product_params);
			switch($product_key){
				case 'product_name':
					if(mb_strlen($product_param) <3 || mb_strlen($product_param)>150)return ['Некорректная длина названия продукта',false];
				break;

				case 'description':
					if(mb_strlen($product_param)>500) return ['Некорректная длина описания продукта',false];
				break;

				case 'price':
					if ($product_param<0)return ['Цена продукта не может быть отрицательным значением',false];
					if(!is_numeric($product_param))return ['Цена продукта может содержать только численные значения',false];
				break;

				case 'count':
					if($product_param<0)return ['Количество продукта не может быть отрицательным значением',false];
					if(!is_int($product_param))return ['Количество продукта может быть выражено только в целочисленных положительных значениях',false];
				break;

			}

		}
		return ['OK',true];
	}


}



?>