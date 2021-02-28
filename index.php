<?php
declare(strict_types=1);

ini_set('display_errors', 'On');
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

$loader = new TaskForce\Converter();

$files = $loader->getFilesList();

foreach ($files as $file) {
	try {
		$loader->createSQLFile($file);
	} 
	catch (Exceptions\CustomException $e) {
		echo("Ошибка: " . $e->getMessage());
	}
}
// echo "<pre>";
// var_dump($files);
// echo "</pre>";