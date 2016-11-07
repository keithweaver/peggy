<?php
	$data = array();

	include_once('../../../../../include/common_rest_functions.php');
	include_once('../../../../../include/secret.php');


	//Create Training JSON File
	$classifierLang = "en";
	$classifierName = "ExampleTest";
	
	$trainingMetaDataJSONContent = '{"name":"' . $classifierName . '","language":"' . $classifierLang . '"}';
		
	$trainingMetaDataVersion = 1;
	$trainingMetaDataFile = fopen('newtrainingdata_' . $trainingMetaDataVersion . '.json','w+');
	fwrite($trainingMetaDataFile, $trainingMetaDataJSONContent);
	fclose($trainingMetaDataFile);

	$trainingDataMetaFileName = 'newtrainingdata_' . $trainingMetaDataVersion . '.json';	
	
	

	//Upload CSV File
	$target_directory = './';
	$filename = str_replace(" ","",basename( $_FILES['trainingData']['name']));
	$target_path = $target_directory . $filename;
	if(move_uploaded_file($_FILES['trainingData']['tmp_name'], $target_path)) {
	} else{
	    die("An error has occurred: With file upload");
	}

	$trainingDataFileName = $filename;

	//Make CURL request with files ($localFileRealPath, realpath($trainingMetaDateFile))
	$username = $NATURAL_LANG_USER_NAME;
	$password = $NATURAL_LANG_PASSWORD;
	$URL = "https://gateway.watsonplatform.net/natural-language-classifier/api/v1/classifiers";

	$path1 = realpath($trainingDataFileName);
	$path2 = realpath($trainingDataMetaFileName);

	echo $path1;
	echo '<br/><br/>';
	echo $path2;
	echo '<br/><br/>';
	

	//CURL Request
	$ch = curl_init($URL);
	curl_setopt($ch, CURLOPT_TIMEOUT, 500);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($ch, CURLOPT_POST, true);

	curl_setopt(
		$ch,
		CURLOPT_POSTFIELDS,
			array(
				'training_data' => '@' . $path1,
				'training_metadata' => '@' . $path2
			)
		);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	curl_close($ch);

	echo json_encode($result);
?>