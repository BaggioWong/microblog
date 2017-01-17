<?php
header('Cache-Control: no-cache');

// Connection info
$db_url 		= 	'localhost';
$db_username 	= 	'root';		//'blazoni2';
$db_password 	= 	'root';		//'fudan1234';
$db_name 		= 	'microblog'; 	//'blazoni2_bim';
$db_port 		= 	8889;			//3306;

// Connect to database
$link = mysqli_connect($db_url, $db_username, $db_password, $db_name, $db_port); //, , $db_port);
// Error connecting to database
if (!$link)
	die("Could not connect: " . mysql_error());

mysqli_set_charset($link, 'utf8');

// // Create database if doesn't exist
// $db_name = "microblog";
// $query = "CREATE DATABASE IF NOT EXISTS {$db_name}";
// if (mysqli_query($link, $query) == false)
// 	echo "Error in creating database";

// // Select database
// mysqli_select_db($link, $db_name);

// // Create tables if don't exist

?>