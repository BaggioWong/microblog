<?php
// Check if user exists
include 'db_functions.php';

// Variables
$full_name = $_POST['full_name'];

$data = array();

// Format return error
$exists = "";
if ( ($exists = user_exists($full_name)) == true)
	$data['exists'] = true;
else if ($exists == false)
	$data['exists'] = false;
else if ($exists == -1)
	$data['exists'] = "error";

// Encode with JSON and echo back
echo json_encode($data);

?>