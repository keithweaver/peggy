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

	$name = pickup('name');
	if($name == ""){
		die(error("Error: Name cannot be blank [" . $name . "]"));
	}
		
	$URL = "http://localhost:8888/peggy/rest/v1/watson/retrieve-rank/cluster/create";

	// $post = [
	// 	'name' => $name
	// ];
	$post = array();
	$post['name'] = $name;

	$data_strings = json_encode($post);

	$data['postfields'] = $data_strings;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$URL);
	curl_setopt($ch, CURLOPT_POST, 1);
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

	$data['resp'] = $resp; 

	$watsonId = $results->solr_cluster_id;
	$clusterSize = $results->cluster_size;

	mysqli_query($con, "INSERT INTO clusters (projectId, watsonId, name, size) VALUES ('$projectId','$watsonId','$name','$clusterSize')") or die(error("Error: Saving new cluster"));

	$data['message'] = "Success";
	$data['success'] = true;

	echo json_encode($data);
?>