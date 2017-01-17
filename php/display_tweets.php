<?php
include 'db_functions.php';

global $link;

// 	display tweets
$tweet_ID = $_POST['tweet_ID'];

//	fetch replies
$query = "SELECT * FROM replies WHERE `Tweet ID` = {$tweet_ID}";
$result = mysqli_query($link, $query);

$array = array();

while ($row = mysqli_fetch_array($result)) {
	$current_set = array();
	$current_set['RID'] = $row['RID'];
	$current_set['AuthorID'] = $row['Author ID'];
	$current_set['TweetID'] = $row['Tweet ID'];
	$current_set['Reply'] = $row['Reply'];
	$current_set['Date'] = $row['Date'];
	$current_set['Username'] = get_username($current_set['AuthorID']);
	$current_set['DisplayName'] = get_display_name($current_set['Username']);
	$current_set['Avatar'] = get_user_avatar(get_username($current_set['AuthorID']));
	array_push($array, $current_set);
}

echo json_encode($array);
?>