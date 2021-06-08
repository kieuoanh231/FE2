<?php
require_once './config/database.php';
spl_autoload_register(function ($class_name) {
    require './app/models/' . $class_name . '.php';
});
$input=json_decode(file_get_contents('php://input'),true);

$page = $input['page'];
$productModel = new ProductModel();
$perPage = 3;

$productList = $productModel->getProductsByPage($perPage, $page);

// if (count($productList)<4) {
//     echo json_encode(false); 
// }
echo json_encode($productList);
?>