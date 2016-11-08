<?php
	include_once('../../../../include/secret.php');

	//http://stackoverflow.com/questions/20064271/how-to-use-basic-authorization-in-php-curl
	$username = $RETRIEVE_AND_RANK_USER_NAME;
	$password = $RETRIEVE_AND_RANK_PASSWORD;
	$URL = 'https://gateway.watsonplatform.net/retrieve-and-rank/api/v1/rankers';

	//CURL - GET
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$URL);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
	$response = curl_exec($ch);
	curl_close ($ch);

	$results = json_decode($response);

	echo json_encode($results);

	/*
	{
	"rankers": [
			{
				"ranker_id": "c852bax18-rank-3657",
				"url": "https://gateway.watsonplatform.net/retrieve-and-rank/api/v1/rankers/c852bax18-rank-3657",
				"name": "exampleRanker",
				"created": "2016-11-11T20:47:02.369Z"
			}
		]
	}
	*/
?>