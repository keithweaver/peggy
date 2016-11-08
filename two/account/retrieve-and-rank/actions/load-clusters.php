<?php
	session_start();

	include_once('../../../../include/common_rest_functions.php');
	include_once('../../../../include/secret.php');

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


	$html = "";
	$watsonIds = array();

	$result = mysqli_query($con, "SELECT * FROM clusters WHERE projectId='$projectId'") or die(error("Error: Loading clusters"));
	while($row = mysqli_fetch_array($result)){
		$watsonId = $row['watsonId'];

		$URL = "http://localhost:8888/peggy/rest/v1/watson/retrieve-rank/cluster/verify";

		$post = [
			'watsonId' => $watsonId
		];

		$data_strings = json_encode($post);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$URL);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_strings);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',                                                                                
			'Content-Length: ' . strlen($data_strings))                                                                       
		);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
		$resp=curl_exec ($ch);
		curl_close ($ch);
		$results = json_decode($resp);

		if($html != ""){
			$html .= "<br/><br/>";
		}
		$html .= "<h2>" . $row['name'] . "</h2>";
		$html .= "<p>Watson Id: <code>" . $watsonId . "</code></p>";
		$html .= "<p>Size: <code>" . $row['size'] . "</code></p>";

		array_push($watsonIds, $watsonId);		
	}
	$data['watsonIds'] = $watsonIds;
	$data['message'] = "Success";
	$data['success'] = true;
	$data['html'] = $html;

	echo json_encode($data);
?>