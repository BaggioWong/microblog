<?php
include 'db_functions.php';
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

$data = array();

//	Check password and return status
if (password_valid($username, $password)) {
	$_SESSION['username'] = $username;
	$data['invalid_status'] = 'valid';
	//	Also set logged_in session var (prevent unlogged in users to see home page)
	$_SESSION['logged_in'] = true;
} else {
	$data['invalid_status'] = 'invalid';
	$data['invalid_string'] = 'Incorrect user information. Please try again.';
}

echo json_encode($data);

?>