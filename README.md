# products_managementAPI
Данное приложение представляет собой простое API для работы с товарами. 

Приложение поддерживает весь перечень CRUD для работы с товарами и категориями.

В приложении определено два типа пользователей – manager и admin. Менеджер полномочен совершать любое CRUD действие в отношении товаров и категорий. Полномочия admin включают в себя все полномочия менеджера, а так же CRUD действия в отношении зарегистрированных в системе пользователей.

Структура базы данных приложения. 
--------------------------------

### Users:

id – уникальный идентификатор пользователя в таблице;
login – уникальный логин пользователя;
password – пароль пользователя;
user_name – имя пользователя;
user_surname – фамилия пользователя;
type – тип пользователя;
token – уникальный токен пользователя;

Техническая реализация таблицы users:

id INT(11) AUTO_INCREMENT PRIMARY KEY,
login VARCHAR(255) UNIQUE NOT NULL,
password VARCHAR(255) NOT NULL,
user_name VARCHAR(255),
user_surname VARCHAR(255),
type VARCHAR(25) NOT NULL,
token VARCHAR(255) UNIQUE

categories:
id – уникальный идентификатор продукта в таблице;
cat_name – уникальное название категории;

Техническая реализация таблицы categories:

id INT(11) AUTO_INCREMENT PRIMARY KEY,
cat_name VARCHAR(255) UNIQUE NOT NULL

products:
id – Уникальный идентификатор продукта в таблице;
product_name – уникальное название продукта;
description – краткое описание товара;
text- полное описание товара;
price – цена товара;
count – количество товара;



Техническая реализация таблицы products:

id INT(11) AUTO_INCREMENT PRIMARY KEY,
product_name VARCHAR(255) UNIQUE NOT NULL,
description VARCHAR(500),
text TEXT,
price FLOAT(8.2) NOT NULL,
count INT(5) NOT NULL

Так как одна категория может иметь множество товаров, а один товар может принадлежать множеству категорий, то возникает необходимость в реализации дополнительной таблице для реализации связи многие-ко-многим:

products_categories:
id – уникальный идентификатор аттрибута в таблице;
product_id – вторичный ключ, ссылающийся на таблицу products;
cat_id – вторичный ключ, ссылающийся на таблицу categories;

Техническая реализация таблицы products_categories:
id INT(11) AUTO_INCREMENT PRIMARY KEY,
product_id INT(11) NOT NULL,
cat_id INT(11) NOT NULL,
FOREIGN KEY(product_id) REFERENCES products(id),
FOREIGN KEY(cat_id) REFERENCES categories(id)



Работа в приложении.

Аутентификация в приложении

Вся работа в приложении осуществляется только аутентифицированными пользователями, по этой причине, необходимо пройти процесс аутентификации. Так как в системе уже имеются два пользователя:
admin:
	login:’admin’,
	password:’555’

manager1:
	login:’manager1’,
	password:’111’


Данные запроса: {“login”:”<login>”,”password”:”<password>”}

Требования к данным:
login – латиница, длина не менее трех символов, кириллица не допустима;
password – латиница, длина не менее трех символов, кириллица не допустима;

Метод: POST;

url – http://yourdomain/authentification/auth

Реализация:

curl -H “Content-Type: application/json” -d @authentification.json -X POST http://yourdomain/authentification/auth

Ответ: Токен приложения;

При всех последующих запросах, необходимо использовать полученный токен в заголовке запроса Authorization для получения прав доступа (авторизации);

Для работы с товарами и категориями, вам будет достаточно прав доступа manager. Для работы с товарами, категориями и пользователями, аутентифицируйтесь в системе посредством данных пользователя типа admin.

Работа с категориями:

GET запросы:

getCategories – получить все категории товаров в приложении;

Реализация:

curl -H “Authorization:your_token”  http://yourdomain/categories/getcategories

Ответ: Список категорий товаров;

POST запросы:

Требования к данным:

Имя категории – не может быть пустым, должно состоять не менее чем из двух символов, должно быть уникальным среди множества других категорий;

id категории – неотрицательное числовое значение;

addCategory – создать категорию;

Тело запроса: {"cat_name":"<cat_name>"}

Ответ: Сообщение об успешном создании категории или ошибке;

Реализация:

curl -H “Content-Type: application/json” -H “Authorization:your_token” -d @addcategory.json -X POST http:/yourdomain/categories/addcategory




editCategory – редактировать категорию;

Тело запроса: {"id":<id>,"cat_name":"<new_cat_name>"}

Ответ: Сообщение об успешном изменении категории или отсутствии таковой;

Реализация:

curl -H “Content-Type: application/json” -H “Authorization:your_token” -d @editcategory.json -X POST http://yourdomain/categories/editcategory





deleteCategory – удалить категорию

Тело запроса: {"cat_id":<id>}

Ответ: Сообщение об успешном изменении категории или отсутствии таковой;

Реализация:

curl -H “Content-Type: application/json” -H “Authorization:your_token” -d @deletecategory.json -X POST http://yourdomain/categories/deletecategory




getCategoryProducts – получить список продуктов в категории

Тело запроса: {"cat_id":<id>}

Ответ: Список товаров в данной категории или сообщение об отсутствии таковых;

Реализация:

curl -H “Content-Type: application/json” -H “Authorization:your_token” -d @getcategoryproducts.json  -X POST http://yourdomain/categories/getcategoryproducts




getProductsCount – получить количество товаров в категории;

Тело запроса: {"cat_id":<id>}

Ответ: Количество товаров в категории или сообщение об отсутствии таковых;

Реализация:

curl -H “Content-Type: application/json” -H “Authorization:your_token” -d @getproductscount.json -X POST http://yourdomain/categories/getproductscount




getProductsPrice – получить общую стоимость товаров в категории

Тело запроса: {"cat_id":<id>}

Ответ: Общая стоимость товаров в данной категории или сообщение об отсутствии таковых;

Реализация:

curl -H “Content-Type: application/json” -H “Authorization:your_token” -d getproductsprice.json -X POST http://yourdomain/categories/getproductsprice









Работа с товарами:

GET запросы:

getProductsCount – получить общее количество товаров;

Ответ: Количество товаров или сообщение об их отсутствии

Реализация:

curl -H “Authorization:your_token” http://yourdomain/products/getproductscount




getTotalPrice – получить общую стоимость всех товаров;

Ответ: Общая стоимость всех товаров или сообщение об их отсутствии

Реализация:

curl -H “Authorization:your_token” http://yourdomain/products/gettotalprice 















POST запросы:

Требования к данным:

id – неотрицательное числовое значение;

Имя товара – строковое значение в диапазоне от 3 до 150 символов;

Описание товара – строковое значение до 500 символов;

Текст – строковое значение;

Цена – неотрицательное числовое значение целого или с плавающей точкой типа ;

Количество – неотрицательное числовое значение целого типа;

addProduct – добавить продукт;

Тело запроса: {"cat_id":<id>,"product_params":{"product_name":"<product_name>","description":"<description>","text":"<text>","price":<price>,"count":<count>}}

Ответ: Сообщение об успешно создании продукта или ошибке

Реализация:

curl -H “Content-Type: application/json” -H “Authorization:your_token” -d @addproduct.json -X POST http://yourdomain/products/addproduct




editProduct – редактировать продукт

Тело запроса: {"product_id":<id>,"edited_product_params":{“product_name”:”<product_name>”,”description”:”<description>”,”text”:”text”,”price”:<price>,”count”:<count>}}

Параметр “edited_product_params” представляет собой json строку с переменный количеством параметров. Т.е. Вы можете указать один, несколько, или все параметры из параметра “edited_product_params”.

Ответ: Сообщение об успешном изменении товара или ошибке;

Реализация:

curl -H “Content-Type: application/json” -H “Authorization:your_token” -d @editproduct.json -X POST http://yourdomain/products/editproduct




deleteProduct – удалить товар 

Тело запроса: {"product_id":<id>}

Ответ: Сообщение об успешном удалении категории или ошибке;

Реализация:

curl -H “Content-Type:application/json” -H “Authorization:your_token” -d @deleteproduct.json -X POST http://yourdomain/products.deleteproduct







getProductCategories – Получить список категорий, к которым принадлежит данный товар;

Тело запроса: {"product_id":<id>}

Ответ: Список категорий или сообщение об отсутствии данного товара;

Реализация:

curl -H “Content-Type: application/json” -H “Authorization: your_token” -d @getproductcategories.json -X POST http:/yourdomain/products/getproductcategories’





getProductPrice – Получить общую стоимость данного товара

Тело запроса: {"product_id":<id>}

Ответ: Общая стоимость данного товара или сообщение об ошибке;

Реализация:

curl -H “Content-Type: application/json” -H “Authorization:your_token” -d @getproductprice.json -X POST http://yourdomain/products/getproductprice 






insertProductInCategory – добавить товар в категорию

Тело запроса: {"product_id":<id>,"cat_id":<id>};

Ответ: Сообщение об успешном добавлении товара в категорию или ошибке;

Реализация:

curl -H “Content-Type: application/json” -H “Authorization:your_token” -d @insertproductincategory.json -X POST http://yourdomain/products/insertproductincategory













Работа с пользователями:

Вся работа с пользователями, за исключением запроса getUserInfo, должна осуществляться только с правами доступа admin

GET запросы:

getUsers – получить список пользователей;

Ответ: Список пользователей;

Реализация:

curl -H “Authorization:your_token” http://yourdomain/users/getusers





getUserParams – получить сведения о аутентифицированном пользователе в рамках текущего соединения (о себе)

Ответ: Сведения о пользователе;

Реализация:

curl -H “Authorization:your_token” http://yourdomain/users/getuserinfo








POST запросы:

Требования к данным:

логин – строковое значение, состоящее не менее чем из трех символов, кириллица и пробелы не допустимы;

пароль – строковое значение, состоящее не менее чем из трех символов, кириллица и пробелы не допустимы;

тип – строковое значение, соответствующее любому строковому значению из диапазона [‘admin’,’manager’];

Имя пользователя -строковое значение;

Фамилия пользователя – строковое значение;




createUser – создать пользователя;

Тело запроса: {"login":"<login>","password":"<password>","name":"<name>","surname":"<surname>","type":"<type>"}

Ответ: Сообщение об успешно созданном пользователе или ошибке;

Реализация:

curl -H “Content-Type: application/json” -H “Authorization:your_token” -d @createuser.json -X POST http://yourdomain/users/createuser




deleteUser – удалить пользователя;

Тело запроса : {"id":<id>}

Ответ: Сообщение об успешно удалении пользователя или ошибке;

Реализация:

curl -H “Content-Type: application/json” -H “Authorization:your_token” -d @deleteuser.json -X POST http://yourdomain/users/deleteuser





changeUser – изменить пользователя:

Тело запроса: {"id":<id>,"edited_user_params":{"user_name":"<user_name>","user_surname":"<user_surname>",”type”:”<type>”}}

Параметр “edited_user_params” представляет собой json строку с переменный количеством параметров. Т.е. Вы можете указать один, несколько, или все параметры из параметра “edited_user_params”.

Ответ: Сообщение об успешном изменении параметров пользователя или ошибке;

Реализация:

curl -H “Content-Type: application/json” -H “Authorization:your_token” -d @changeuser.json -X POST http://yourdomain/users/changeuser 














