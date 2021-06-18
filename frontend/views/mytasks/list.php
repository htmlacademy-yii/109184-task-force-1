<?php 
if (!empty($tasks)) {
	foreach ($tasks as $key => $task) { 
		echo $this->render('item', ['task' => $task]);
	} 
} else {
	echo "<p>Ничего не найдено.</p>";
}