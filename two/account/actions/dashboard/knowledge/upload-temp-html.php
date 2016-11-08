<?php
	session_start();

	include_once('../../../../../include/common_rest_functions.php');
	include_once('../../../../../include/secret.php');

	$email = $_SESSION['two_watson_email'];

	if($email == ""){
		die(error("Error: Please log in. Refresh the page."));
	}
	
	$publicProjectId = pickup('publicProjectId');
	if($publicProjectId == ""){
		die(error("Error: Unknown public project id."));
	}

	$data = array();

	//Connect
	$con = mysqli_connect("localhost",$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME);

	//Look up project information
	$result = mysqli_query($con, "SELECT * FROM projects WHERE publicProjectId='$publicProjectId'") or die(error("Error: Unable to load projects"));
	if(mysqli_num_rows($result) == 0){
		die(error("Error: Invalid project"));
	}

	$projectId = -1;
	while($row = mysqli_fetch_array($result)){
		$projectId = $row['id'];

	}
	if($projectId == -1){
		die(error("Error: Unknown project"));
	}

	//Look up users
	$userId = -1;
	$result = mysqli_query($con, "SELECT * FROM userinfo WHERE email='$email'") or die(error("Error: Issues looking up user information"));
	while($row = mysqli_fetch_array($result)){
		$userId = $row['id'];
	}

	if($userId == -1){
		die(error("Error: Issues finding user ifnormation."));
	}

	//Verify project access
	$result = mysqli_query($con, "SELECT * FROM projectAccess WHERE projectId='$projectId' AND userId='$userId'") or die(error("Error: Looking up the user"));
	if(mysqli_num_rows($result) == 0){
		die(error("Error: Missing project access"));
	}


	//DIRECTORY SETUP
	$tempFileCode = generateTempFileCode($DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

	$target_directory = "../../../server/temp/" . $tempFileCode;
	mkdir($target_directory);
	$target_directory .= "/";

	//FILE UPLOAD
	$filename = str_replace(" ","",basename( $_FILES['htmlFile']['name']));
	$target_path = $target_directory . $filename;
	if(move_uploaded_file($_FILES['htmlFile']['tmp_name'], $target_path)) {
	} else{
	    die("An error has occurred: With file upload");
	}
	
	mysqli_query($con, "INSERT INTO tempFiles (projectId, email, tempCode, filename) VALUES ('$projectId','$email','$tempFileCode', '$filename')") or die(error("Error: "));

	$data['filename'] = $filename;
	$data['temp'] = $tempFileCode;
	$data['message'] = "Success";
	$data['success'] = true;

	echo json_encode($data);
?>