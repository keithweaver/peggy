<?php
	/*
	Are You Pregnant?
	15-17
	
	Your Pregnancy Profile
	18-74
	
	Your Pregnancy Lifestyle
	75-96
	
	Nine Months of Eating Well
	97-119
	
	*/
	session_start();

	include_once('../../../../include/common_rest_functions.php');
	include_once('../../../../include/secret.php');

	$email = $_SESSION['two_watson_email'];

	if($email == ""){
		die(error("Error: Please log in. Refresh the page."));
	}
	
	

	$data = array();

	//Connect
	$con = mysqli_connect("localhost",$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME);

	
	//Look up users
	$userId = -1;
	$result = mysqli_query($con, "SELECT * FROM userinfo WHERE email='$email'") or die(error("Error: Issues looking up user information"));
	while($row = mysqli_fetch_array($result)){
		$userId = $row['id'];
	}

	if($userId == -1){
		die(error("Error: Issues finding user ifnormation."));
	}

	
	$title = pickup('title');
	if($title == ""){
		die(error("Error: Title cannot be blank"));
	}
	$temp = pickup('temp');
	if($temp == ""){
		die(error("Error: Temp cannot be blank"));
	}
	$filename = pickup('filename');
	if($filename == ""){
		die(error("Error: Filename cannot be blank"));
	}

	$pathToPDF = "http://localhost:8888/peggy/two/account/server/temp/" . $temp . '/' . $filename;

	mysqli_query($con, "INSERT INTO books (ownerId, title, pathToPDF) VALUES ('$userId','$title','$pathToPDF')") or die(error("Error: Creating book"));
	

	$bookId = -1;
	$result = mysqli_query($con, "SELECT * FROM books WHERE ownerId='$userId' AND title='$title' AND pathToPDF='$pathToPDF'") or die(error("Error: Looking up book info"));
	while($row = mysqli_fetch_array($result)){
		$bookId = $row['id'];
	}
	
	$data['success'] = true;
	$data['message'] = "";
	$data['bookId'] = $bookId;

	echo json_encode($data);
?>