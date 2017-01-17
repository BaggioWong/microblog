<?php
include 'db_functions.php';

$author 	= $_POST['author'];
$tweet_ID 	= $_POST['tweet_ID']; 
$reply 		= $_POST['reply'];
$date 		= $_POST['date'];

insert_reply($author, $tweet_ID, $reply, $date);
?>