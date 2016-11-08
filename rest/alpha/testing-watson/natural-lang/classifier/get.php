<?php
	/*
	Returns information about the previously created data.
	*/
	$data = array();

	$classifierId = $_POST['id'];
	$classifierId = addslashes($classifierId);


	include_once('../../../../../include/common_rest_functions.php');
	include_once('../../../../../include/secret.php');

	//Make CURL request with files ($localFileRealPath, realpath($trainingMetaDateFile))
	$username = $NATURAL_LANG_USER_NAME;
	$password = $NATURAL_LANG_PASSWORD;
	$URL = "https://gateway.watsonplatform.net/natural-language-classifier/api/v1/classifiers/" . $classifierId;


	//Parameters for the REST call
	$post = array();
	//add POST parameters here
	$data_strings = json_encode($post);

	//CURL Request
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_URL,$URL);
	curl_setopt($ch, CURLOPT_TIMEOUT, 500);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_strings);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',                                                                                
		'Content-Length: ' . strlen($data_strings))                                                                       
	);
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$results=curl_exec($ch);
	curl_close ($ch);

	$data['classifier'] = $results;
	$data['success'] = true;
	$data['message'] = "Successfully load classifier information.";

	echo json_encode($data);
?>