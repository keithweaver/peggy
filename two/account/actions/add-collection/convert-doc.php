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

	$author = "";


	$temp = pickup('temp');
	if($temp == ""){
		die(error("Error: Temp is blank"));
	}
		
	$title = pickup('title');
	if($title == ""){
		die(error("Error: Title cannot be blank"));
	}

	$bookId = pickup('bookId');
	if($bookId == ""){
		die(error("Error: Book id cannot be blank."));
	}

	$chapterName = "";//add this to post

	$pdf = $_POST['pdf'];

	
	$pathToFile = "../../server/temp/" . $temp . '/' . $pdf;
	$pathToPDF = "http://localhost:8888/peggy/two/account/server/temp/" . $temp . "/" . $pdf;


	$uploadedFile = new CURLFile($pathToFile, 'application/pdf', 'file');

	$URL = "http://localhost:8888/peggy/rest/v1/watson/document-conversion/convert";

	$post = array();
	$post['inputFile'] = $uploadedFile;
	$post['type'] = "answer_units";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_URL,$URL);
	curl_setopt($ch, CURLOPT_TIMEOUT, 500);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	// curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	$headers = array('Accept: application/json','Content-Type: multipart/form-data');
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$resp = curl_exec($ch);
	$response = json_decode($resp);

	//Create a Chapter
	mysqli_query($con, "INSERT INTO bookChapters (bookId, chapterName, pathToPDF) VALUES ('$bookId','$chapterName','$pathToPDF')") or die(error("Error: Creating book chapter"));


	$chapterId = -1;
	$results = mysqli_query($con, "SELECT * FROM bookChapters WHERE pathToPDF='$pathToPDF'") or die(error("Error: Looking up book chapter"));
	while($row = mysqli_fetch_array($results)){
		$chapterId = $row['id'];
	}

	//Create Chapter Content
	$i = 0;
	foreach ($response->content as $row) {
		$body = "";
		$bodyMediaType = "";
		foreach ($row->content as $obj) {
			$bodyMediaType = $obj->media_type;
			$body = addslashes($obj->text);
		}
		$title = addslashes($row->title);
		$watsonId = $row->id;
		$parentId = $row->parent_id;
		$titleType = $row->type;

		mysqli_query($con, "INSERT INTO bookChapterContent (chapterId, watsonId, parentId, title, titleType, bodyMediaType, body) VALUES ('$chapterId','$watsonId','$parentId','$title','$titleType','$bodyMediaType','$body')") or die(error("Error: Creating chapter content. [" . $title . "]"));
		$i++;
	}

	$data['success'] = true;
	$data['message'] = "";
	$data['results'] = $response;
	$data['newitems'] = $i;
	

	echo json_encode($data);
?>