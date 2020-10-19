<?php 
namespace App\Models;

class CategoriesModel extends Model
{

	public function getCategories(){
		$query = $this->connection->query("SELECT * FROM categories");
		$header = 'HTTP/1.1 200 OK';
		$body = ($query->rowCount()!= 0) ? $this->fetchQueryRows($query) : "Приложение не содержит категорий";
		return ['header'=>$header,'body'=>$body];

	}

	public function addCategory(string $cat_name): array{
		$cat_name = trim($cat_name);
		if(!$this->validateCategoryName($cat_name))return ['header'=>'HTTP/1.1 400 Bad Request','body'=>'Некорректное имя категории'];
		$this->connection->exec("INSERT INTO categories (cat_name) VALUES ('$cat_name')");
		return ['header'=>'HTTP/1.1 201 Created','body'=>'Категория успешно создана'];


	}

	public function editCategory(int $cat_id, string $new_cat_name): array{
		if(!$this->validateCategoryName($new_cat_name))return ['header'=>'HTTP/1.1 400 Bad Request','body'=>'Некорректное имя категории'];
		$query =  $this->connection->exec("UPDATE categories SET cat_name = '$new_cat_name' WHERE id = $cat_id");
		return ($query !== 0) ? ['header'=>'HTTP/1.1 201 Created','body'=>'Категория успешно изменена'] : ['header'=>'HTTP/1.1 400 Bad Request','body'=>'Данной категории не существует'];

	}

	public function deleteCategory(int $cat_id): array{
		try{
			$this->connection->beginTransaction();
			$this->connection->exec("DELETE FROM products_categories WHERE cat_id = $cat_id");
			$query = $this->connection->exec("DELETE FROM categories WHERE id = $cat_id");
			$this->connection->commit();
			return ($query !== 0) ? ['header'=>'HTTP/1.1 201 Created','body'=>'Категория успешно удалена'] : ['header'=>'HTTP/1.1 400 Bad Request','body'=>'Данной категории не существует'];
		}

		catch(\PDOException $e){
			$this->connection->rollBack();
			return ['header'=>'HTTP/1.1 520 Unknown Error','body'=>'Неизвестная ошибка'];

		}
	}

	public function getProductsCount(int $cat_id): array{
		$query = $this->connection->query("SELECT SUM(products.count) AS product_sum FROM products INNER JOIN products_categories ON products.id = products_categories.product_id WHERE products_categories.cat_id = $cat_id");
		$prepared_query = $this->fetchQueryRow($query);
		return ($prepared_query['product_sum'] != '') ? ['header'=>'HTTP/1.1 200 OK','body'=>"products_summ: ".$prepared_query['product_sum']] : ['header'=>'HTTP/1.1 200 OK','body'=>'Данная категория не содержит товаров'];

	}

	public function getProductsPrice(int $cat_id){
		$query = $this->connection->query("SELECT SUM(products.count)*SUM(products.price) AS products_price FROM products INNER JOIN products_categories ON products.id = products_categories.product_id WHERE products_categories.cat_id = $cat_id");	
		$prepared_query = $this->fetchQueryRow($query);
		return ($prepared_query['products_price']!='') ? ['header'=>'HTTP/1.1 200 OK','body'=>'products_price: '.$prepared_query['products_price']] : ['header'=>'HTTP/1.1 200 OK','body'=>'У данной категории нет товаров'];

		
	}

	public function getCategoryProducts(int $cat_id):array{
		$query = $this->connection->query("SELECT products.id,products.product_name,products.description,products.text,products.price,products.count FROM products INNER JOIN products_categories ON products.id = products_categories.product_id WHERE products_categories.cat_id = $cat_id");
		return ($query->rowCount()!=0) ? ['header'=>'HTTP/1.1 200 OK','body'=>$this->fetchQueryRows($query)] : ['header'=>'HTTP/1.1 200 OK','body'=>'Данная категория не содержит товаров'];
		
	}




	private function validateCategoryName(string $cat_name):bool{
		if($cat_name == null)return false;
		if(mb_strlen($cat_name)<2)return false;
		return true;

	}
}





 ?>