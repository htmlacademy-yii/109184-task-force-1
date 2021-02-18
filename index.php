<?php
declare(strict_types=1);

ini_set('display_errors', 'On');
error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

try {
	$strategy = new TaskForce\Task(1, 1, 2);
	$strategy->getStatus(new TaskForce\Refuse());
	$strategy->getStatus(new TaskForce\MarkDone());
}
catch (Exceptions\CustomException $e) {
	echo("Ошибка: " . $e->getMessage());
}

