<?php
include 'db_functions.php';

$tweet_ID = $_POST['tweet_ID'];

delete_tweet($tweet_ID);
?>