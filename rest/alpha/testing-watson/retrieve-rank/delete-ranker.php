<?php
	// $rankerId = "c852bax18-rank-3657";
	// $rankerId = "54922ax21-rank-617";
	// $rankerId = "54922ax21-rank-638";
	// $rankerId = "766366x22-rank-396";
	// $rankerId = "54922ax21-rank-642";
	$rankerId = "76643bx23-rank-394";

	include_once('../../../../include/secret.php');

	//http://stackoverflow.com/questions/20064271/how-to-use-basic-authorization-in-php-curl
	$username = $RETRIEVE_AND_RANK_USER_NAME;
	$password = $RETRIEVE_AND_RANK_PASSWORD;
	$URL = 'https://gateway.watsonplatform.net/retrieve-and-rank/api/v1/rankers/' . $rankerId;

	//CURL - GET
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$URL);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
	$response = curl_exec($ch);
	curl_close ($ch);

	$results = json_decode($response);

	$data = array();
	$data['success'] = true;
	$data['message'] = "Deleted ranker.";

	echo json_encode($data);

	/*
	{
		"ranker_id": "c852bax18-rank-3657",
		"name": "exampleRanker",
		"created": "2016-11-11T20:47:02.369Z",
		"url": "https://gateway.watsonplatform.net/retrieve-and-rank/api/v1/rankers/c852bax18-rank-3657",
		"status": "Failed",
		"status_description": "Error encountered during training: Training data quality standards not met: invalid header (duplicate feature names). Row 1 of input data."
	}
	*/
?>