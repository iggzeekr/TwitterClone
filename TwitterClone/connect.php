<?php 

try {

	$db=new PDO("mysql:host=localhost;dbname=twitter_clone;charset=utf8",'root','ezgi');
}

catch (PDOExpception $e) {

	echo $e->getMessage();
}

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
 ?>