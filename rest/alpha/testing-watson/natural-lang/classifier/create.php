<?php
	/*
	Creates a new classifier with training data.
	*/
	$data = array();

	include_once('../../../../../include/common_rest_functions.php');
	include_once('../../../../../include/secret.php');

	//Handle Training Meta data
	$classifierLang = "en";
	$classifierName = "ExampleTest3";
	//Options: English (en), Arabic (ar), French (fr), German, (de), Italian (it), Japanese (ja), Portuguese (pt), and Spanish (es)
	$trainingMetaDataJSONContent = '{"name":"' . $classifierName . '","language":"' . $classifierLang . '"}';
	
	//Create a new JSON object for the meta training data
	$trainingMetaDataVersion = 1;
	$trainingMetaDataFile = fopen('newtrainingdata_' . $trainingMetaDataVersion . '.json','w+');
	fwrite($trainingMetaDataFile, $trainingMetaDataJSONContent);
	fclose($trainingMetaDataFile);

	$target_directory = './';

	//Find the path for the JSON file
	$filename2 = 'newtrainingdata_' . $trainingMetaDataVersion . '.json';
	$localFileRealPath2 = realpath($target_directory . $filename2);
	
	
	//Get file being passed in with parameter "trainingData"
	$filename = str_replace(" ","",basename( $_FILES['trainingData']['name']));
	$target_path = $target_directory . $filename;
	if(move_uploaded_file($_FILES['trainingData']['tmp_name'], $target_path)) {
	} else{
	    die("An error has occurred: With file upload");
	}

	//Find the path for the csv file
	$localFileRealPath = realpath($target_directory . $filename);
		
	//Make CURL request with files ($localFileRealPath, realpath($trainingMetaDateFile))
	$username = $NATURAL_LANG_USER_NAME;//$NATURAL_LANG_USER_NAME <-- this variable is in secret.php
	$password = $NATURAL_LANG_PASSWORD;
	$URL = "https://gateway.watsonplatform.net/natural-language-classifier/api/v1/classifiers";

	//Putting together the Post parameters
	$trainingDataF = new CURLFile($filename, 'text/csv', 'training_data');
	$trainingDataMetaF = new CURLFile($filename2, 'application/json', 'training_metadata');

	$post = array();
	$post['training_data'] = $trainingDataF;
	$post['training_metadata'] = $trainingDataMetaF;

	//Making the POST request
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_URL,$URL);
	curl_setopt($ch, CURLOPT_TIMEOUT, 500);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	$headers = array('Accept: application/json','Content-Type: multipart/form-data');
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$resp = curl_exec($ch);
	$results = json_decode($resp);

	//Handling errors with the CURL so 404, 400, 415, 500
	if(!curl_errno($ch)){
		$info = curl_getinfo($ch);

		// if($info['http_code'] == 400){
		// 	die("JSON file or classifier class name already used or max number of classifiers. 400 error");
		// }
		// if($info['http_code'] == 415){
		// 	die("415 error. invalid type");
		// }
		echo json_encode($info);
	}
    curl_close ($ch);

    //Putting the new created classifier data into the response
	$data['classifierId'] = $results->classifier_id;
	$data['classifierName'] = $results->name;
	$data['classifierStatus'] = $results->status;
	$data['classifierStatusDescription'] = $results->status_description;
	$data['success'] = true;
	$data['message'] = "Success. Created new classifier.";
	
?>