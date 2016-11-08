<?php
	/*
	Analyzes a phrase and identifies it.
	*/
	$data = array();

	$classifierId = $_POST['classifierId'];
	$classifierId = addslashes($classifierId);

	$phrase = $_POST['phrase'];
	$phrase = addslashes($phrase);

	include_once('../../../../../include/common_rest_functions.php');
	include_once('../../../../../include/secret.php');

	//Make CURL request with files ($localFileRealPath, realpath($trainingMetaDateFile))
	$username = $NATURAL_LANG_USER_NAME;
	$password = $NATURAL_LANG_PASSWORD;
	$URL = "https://gateway.watsonplatform.net/natural-language-classifier/api/v1/classifiers/" . $classifierId . "/classify";


	$post = array();
	$post["text"] = $phrase;
	//$post["classifier_id"] = $classifierId;
	$data_strings = json_encode($post);

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
	$resp=curl_exec($ch);
	curl_close ($ch);
	$results = json_decode($resp);

	if($results->code == 409){//needs testing
		die($results->error);
	}

	$cs = array();
	foreach ($results->classes as $class) {
		$c = array();
		$c["name"] = $class->class_name;
		$c["confidence"] = $class->confidence;

		array_push($cs, $c);
	}

	//Prep results
	$data['top_class'] = $results->top_class;
	$data['classes'] = $cs;
	$data['success'] = true;
	$data['message'] = "Successfully Classified.";

	echo json_encode($data);
?>