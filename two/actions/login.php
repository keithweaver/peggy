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
	if(mysqli_num_rows($result) == 0){
		die(error("Error: Invalid email or password."));
	}

	$hash = "";
	$encrypted = "";
	while($row = mysqli_fetch_array($result)){
		$hash = $row['hash'];
		$encrypted = $row['password'];
	}

	if(decryptAndCheck($password, $encrypted, $hash)){
		//valid login
		$_SESSION['two_watson_email'] = $email;

		$data['success'] = true;
		$data['msg'] = "Log in";
	}else{
		$data['success'] = false;
		$data['msg'] = "Error: Invalid email or password.";
	}

	echo json_encode($data);
?>