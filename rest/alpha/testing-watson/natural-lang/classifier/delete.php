<?php
	$data = array();

	include_once('../../../../../include/common_rest_functions.php');
	include_once('../../../../../include/secret.php');
	
	$classifierId = $_POST['classifierId'];
	$classifierId = addslashes($classifierId);

	
	//Make CURL request with files ($localFileRealPath, realpath($trainingMetaDateFile))
	$username = $NATURAL_LANG_USER_NAME;
	$password = $NATURAL_LANG_PASSWORD;
	$URL = "https://gateway.watsonplatform.net/natural-language-classifier/api/v1/classifiers/" . $classifierId;


	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$URL);
	curl_setopt($ch, CURLOPT_TIMEOUT, 500);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	$resp = curl_exec($ch);
	$results = json_decode($resp);

	$data['success'] = true;
	$data['message'] = "Removed " . $classifierId;

	echo json_encode($data); 
?>