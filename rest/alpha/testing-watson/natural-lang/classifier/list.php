<?php
	/*
	Returns a list of previously created classifiers.
	*/
	$data = array();

	include_once('../../../../../include/common_rest_functions.php');
	include_once('../../../../../include/secret.php');


	//Make CURL request with files ($localFileRealPath, realpath($trainingMetaDateFile))
	$username = $NATURAL_LANG_USER_NAME;
	$password = $NATURAL_LANG_PASSWORD;
	$URL = "https://gateway.watsonplatform.net/natural-language-classifier/api/v1/classifiers/";

	//Makes a get request
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$URL);
	curl_setopt($ch, CURLOPT_TIMEOUT, 500);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$resp = curl_exec($ch);
	curl_close ($ch);
	$results = json_decode($resp);

	//echo $results;

	$data['classifiers'] = $results->classifiers;
	$data['success'] = true;
	$data['message'] = "Listed the classifiers that have been created.";

	echo json_encode($data);
?>