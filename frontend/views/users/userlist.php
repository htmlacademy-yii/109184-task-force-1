<?php 

if (!empty($users)) {
	foreach ($users as $key => $user) { 
		echo $this->render('item', ['user' => $user]);
	} 
} else {
	echo "<p>Ничего не найдено.</p>";
}