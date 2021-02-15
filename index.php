<?php

require __DIR__ . '/vendor/autoload.php';

$strategy = new TaskForce\Task(1, 2, 2);

var_dump($strategy->getStatus(new TaskForce\Cancel()));
