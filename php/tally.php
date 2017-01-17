<!-- tally.php -->
<?php
// Functions to access database
include 'db_connect.php';

global $link;
$query = "SELECT * FROM users";
$result = mysqli_query($link, $query);
$users = mysqli_num_rows($result);

// Tally following and fill in users table
for ($i = 1; $i <= $users; $i++) {
	$query = "SELECT * FROM relationships WHERE FollowerID = {$i}";
	$result = mysqli_query($link, $query);

	$rows = mysqli_num_rows($result);
	
	$query = "UPDATE users SET following = {$rows} WHERE UID = {$i}";
	mysqli_query($link, $query);
}


// Tally followers and fill in users table
for ($i = 1; $i <= $users; $i++) {
	$query = "SELECT * FROM relationships WHERE FollowedID = {$i}";
	$result = mysqli_query($link, $query);

	$rows = mysqli_num_rows($result);

	$query = "UPDATE users SET followers = {$rows} WHERE UID = {$i}";
	mysqli_query($link, $query);
}

?>