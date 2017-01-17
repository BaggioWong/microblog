<?php
include 'db_functions.php';

global $link;

$action 		= 	$_POST['action'];
$follower_name 	= 	$_POST['follower_name'];
$followed_name 	= 	$_POST['followed_name'];
$follower_ID 	= 	get_user_ID($follower_name);
$followed_ID 	= 	get_user_ID($followed_name);
$result			= 	"";

//	if action is delete
if ($action == 'delete') {
	$result = delete_relationship($follower_ID, $followed_ID);
}
//	if action is add
else if ($action == 'add') {
	//	if $follower_name != $followed_name, add
	if ($follower_name != $followed_name) {
		$result = add_relationship($follower_ID, $followed_ID);
	}
}

$data = array();

if ($result == true) {
	$data['status'] = "success";
}
else {
	$data['status'] = "failure";
}

$query = "SELECT followers FROM users WHERE UID = {$followed_ID}";
$result = mysqli_query($link, $query);
$followers = mysqli_fetch_array($result);

$data['followers'] = $followers['followers'];

echo json_encode($data);

?>