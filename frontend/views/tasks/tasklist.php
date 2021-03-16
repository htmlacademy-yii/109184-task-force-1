<?php 

foreach ($tasks as $key => $task) { 
	echo $this->render('item', ['task' => $task]);
} 