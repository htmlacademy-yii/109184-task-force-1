<?php 

foreach ($users as $key => $user) { 
	echo $this->render('item', ['user' => $user]);
} 