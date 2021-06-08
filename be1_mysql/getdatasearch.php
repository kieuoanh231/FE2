<?php
require_once './config/database.php';
require_once './config/config.php';
spl_autoload_register(function ($class_name) {
    require './app/models/' . $class_name . '.php';
});
$input=json_decode(file_get_contents('php://input'),true);

$key = $input['key'];
$listName = [];
$productModel = new ProductModel();
$productList = $productModel->searchProducts($key);

if (count($productList)==0) {
    $listName=null;
}
else{
    foreach ($productList as $value) {
        array_push($listName,$value['product_name']);
    }
}
echo json_encode($listName);
?>