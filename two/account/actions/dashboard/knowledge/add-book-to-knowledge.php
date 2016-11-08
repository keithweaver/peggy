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

	
	$bookId = pickup('bookId');
	$bookTitle = "";

	$result = mysqli_query($con, "SELECT * FROM books WHERE id='$bookId'") or die(error("Error:"));
	if(mysqli_num_rows($result) == 0){
		die(error("Error: unknown book " . $bookId));
	}
	while($row = mysqli_fetch_array($result)){
		$bookTitle = $row['title'];
	}

	$chapterIds = array();
	$result = mysqli_query($con, "SELECT * FROM bookChapters WHERE bookId='$bookId'") or die(error("Error: 1"));
	while($row = mysqli_fetch_array($result)){
		$chapterId = $row['id'];
		if(!in_array($chapterId, $chapterIds)){
			array_push($chapterIds, $chapterId);
		}
	}

	foreach ($chapterIds as $chapterId) {
		$result = mysqli_query($con, "SELECT * FROM bookChapterContent WHERE chapterId='$chapterId'") or die(error("Error: 23"));
		while($row = mysqli_fetch_array($result)){
			$title = $row['title'];
			$body = $row['body'];

			mysqli_query($con, "INSERT INTO knowledgeFiles (projectId, displayName, collection, author, title, body) VALUES ('$projectId','$title','$bookTitle','','$title','$content')") or die(error("Error: Adding new html"));
		}
	}
	
	
	$data['success'] = true;
	$data['message'] = "";

	echo json_encode($data);
?>