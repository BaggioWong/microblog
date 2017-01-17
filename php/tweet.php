<?php
include 'db_functions.php';

$author = $_POST['author'];
$tweet 	= $_POST['tweet'];
$date 	= $_POST['date'];

insert_tweet($author, $tweet, $date);
?>