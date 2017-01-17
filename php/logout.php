<?php
include 'db_functions.php';
session_start();

//	If session var (logged_in) is set, delete it
if (isset($_SESSION['logged_in'])) {
	unset($_SESSION['logged_in']);
	//	Send status back to JS - can't seem to redirect from here
	$data = array();
	$data['logout'] = true;
	echo json_encode($data);
}
?>