<?php
include 'db_functions.php';
$allowedExts 	= 	array("gif", "jpeg", "jpg", "png");
$temp 			= 	explode(".", $_FILES["file"]["name"]);
$extension 		= 	end($temp);


// if (( 	($_FILES["file"]["type"] == "image/gif") 	|| ($_FILES["file"]["type"] == "image/jpeg")
// 	|| ($_FILES["file"]["type"] == "image/jpg") 	|| ($_FILES["file"]["type"] == "image/png"))
// 	&& ($_FILES["file"]["size"] < 10000000)		//	max file size is 10 MB
// 	&& in_array($extension, $allowedExts)) {
// 	if ($_FILES["file"]["error"] > 0) {
// 		// echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
// 	} else {

	// echo 'execution';
	$username = $_POST['username'];
	$extension = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
	$filename = $username . "." . $extension;

		// if (file_exists("../avatars/" . $_FILES["file"]["name"])) {
		// 	echo $_FILES["file"]["name"] . " already exists. ";
		// } else {
			move_uploaded_file($_FILES["file"]["tmp_name"], "../avatars/" . $filename);

			insert_avatar_filename($username, $filename);

			redirect("../home.php");
			// echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
		// }

	// }
// } else {
//   echo "Invalid file";
// }

?>