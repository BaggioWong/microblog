<?php
// register.php - add user to database
include 'db_functions.php';

// Variables
$username 		= 	$_POST['full_name'];
$email 			= 	$_POST['email'];
$password 		= 	$_POST['password'];
$display_name 	= 	$_POST['username'];
$date 			= 	date("Y-m-d H:i:s");

// *OPT: sanatize input on server side

// Add to database
$result = 0;
$data = array();

if ( ($result = add_user($username, $display_name, $password, $email, $date)) == -1 )
	$data['result'] = -1;
else
	$data['result'] = 0;

// Echo JSON formatted data back
echo json_encode($data);
?>