MAGENTO_ROOT<?php

require_once "./autoload.php";


$autoload = new autoload( array($path, $libpath, $vendorpath, $path . '../lib/'), new Errors());
$autoload->autoload();

$basepath = dirname(__file__).'/app/api/collections';

$data = new api\data\adapters\pdo\client(
	new \PDO('mysql:host=127.0.0.1', 'root', ''),
	new api\data\adapters\parseini\loader($basepath),
	'api\data\adapters\pdo\get\collection'
);


$magento = $data->selectDB('magento');

$product = $magento->selectCollection('product.ini');

$params['query'] = 'single';
$params[':id'] = 1;

print_r($product->findOne($params));

$params = array();

$params['query'] = 'product_type';
$params[':type_id'] = 'simple';

print_r($product->find($params));

$params['query'] = 'product_type_date';
$params[':date_begin'] = "2013040512251200";
$params[':date_end'] = "2013040512261200";


print_r($product->find($params));