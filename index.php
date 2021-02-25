<?php
declare(strict_types=1);

ini_set('display_errors', 'On');
error_reporting(E_ALL);
//dt_add,rate,description
require __DIR__ . '/vendor/autoload.php';

$loader = new TaskForce\Converter('data\cities.csv', 'cities');

try {
	$loader->import();
    $loader->getSQL();
}
catch (Exceptions\CustomException $e) {
	echo("Ошибка: " . $e->getMessage());
}
