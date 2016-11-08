<?php
	session_start();

	include_once('../../include/common_rest_functions.php');
	include_once('../../include/secret.php');

	$email = $_SESSION['two_watson_email'];

	if($email != ""){
		die(error("Error: You already logged in."));
	}

	$email = pickup('email');
	if($email == ""){
		die(error("Error: Email cannot be blank."));
	}
	$password = pickup('password');
	if($password == null || $password != null && strlen($password) == 0){
		die(error("Error: Password cannot be blank."));
	}
	if(strlen($password) < 6){
		die(error("Error: Password cannot be less than 6 characters."));
	}
	if(1 !== preg_match('~[0-9]~', $password)){
		die(error("Error: Password must contain a number."));
	}

	//Connect
	$con = mysqli_connect("localhost",$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME);

	$result = mysqli_query($con, "SELECT * FROM userinfo WHERE email='$email'") or die(error("Error: Looking up the user"));
	if(mysqli_num_rows($result) > 0){
		die(error("Error: User alrady exists."));
	}


	date_default_timezone_set("America/Toronto");
	$date = date('Y-m-d H:i:s');

	//encrypt password
	$hash = generateRandomStr(rand(25,150));
	$encrypted = encrypt($password, $hash);

	//add user
	$publicUserId = generateUserPublicId($DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

	$data = array();

	mysqli_query($con, "INSERT INTO userinfo (email, password, hash, publicUserId, created) VALUES ('$email','$encrypted','$hash','$publicUserId','$date')") or die(error("Error: Unable to add user"));

	$_SESSION['two_watson_email'] = $email;

	$data['success'] = true;
	$data['msg'] = "Sign up";

	echo json_encode($data);
?>