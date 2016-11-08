<?php
	session_start();

	include_once('../../../../include/common_rest_functions.php');
	include_once('../../../../include/secret.php');

	$email = $_SESSION['two_watson_email'];

	if($email == ""){
		die(error("Error: Please log in. Refresh the page."));
	}

	$name = pickup('name');
	if($name == ""){
		die(error("Error: Name cannot be empty"));
	}

	$servicesStr = $_POST['services'];
	$services = json_decode($servicesStr);

	$natural = 0;
	if($services->retrieve == true){
		$retrieve = 1;
	}
	$natural = 0;
	if($services->natural == true){
		$natural = 1;
	}

	$invitesStr = $_POST['invites'];
	$invites = json_decode($invitesStr);


	$data = array();

	//Connect
	$con = mysqli_connect("localhost",$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME);

	//Get owner id
	$ownerId = 0;
	$result = mysqli_query($con, "SELECT * FROM userinfo WHERE email='$email'") or die(error("Error: Looking up the user"));
	while($row = mysqli_fetch_array($result)){
		$ownerId = $row['id'];
	}
	if($ownerId == 0){
		die(error("Error: Issue looking the user information."));
	}

	//Additional Info
	$colors = array("#086A87","#045FB4","#5F04B4","#00FFFF","#A9A9F5","#B40431","#31B404");
	$color = $colors[rand(0,count($colors)-1)];

	//Date information
	date_default_timezone_set('America/Toronto');
	$date = date('Y-m-d H:i:s');

	//Create Project
	$apiKey = generateNewAPIKey($DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
	$publicId = generatePublicId($DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
	mysqli_query($con, "INSERT INTO projects (publicProjectId, apiKey, name, color, ownerId, naturalLang, retrieve, created) VALUES ('$publicId','$apiKey','$name','$color','$ownerId','$natural', '$retrieve','$date')") or die(error("Error: Unable to add projects"));

	$projectId = 0;
	$result = mysqli_query($con, "SELECT * FROM projects WHERE publicProjectId='$publicId'") or die(error("Error: Looking up project"));
	while($row = mysqli_fetch_array($result)){
		$projectId = $row['id'];
	}

	//Invites Users
	foreach ($invites as $newUser) {
		$result = mysqli_query($con, "SELECT * FROM userinfo WHERE email='$email'") or die(error("Error: Issue looking up existing user"));
		if(mysqli_num_rows($result) > 0){
			$userId = 0;
			while($row = mysqli_fetch_array($result)){
				$userId = $row['id'];
			}

			mysqli_query($con, "INSERT INTO projectAccess (projectId, ownerId, userId) VALUES ('$projectId','$ownerId','$userId')") or die(error("Error: Adding more users for access"));
		}else{
			mysqli_query($con, "INSERT INTO pendingProjectInvites (projectId, email,ownerId) VALUES ('$projectId','$newUser','$ownerId')") or die(error("Error: Adding more users for access.2"));
		
			//send invite
		}
	}

	$data['success'] = true;
	$data['message'] = "";
	$data['project'] = $publicId;


	echo json_encode($data);
?>