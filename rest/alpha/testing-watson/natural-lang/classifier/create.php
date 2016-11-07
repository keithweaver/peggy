<?php
	$data = array();

	include_once('../../../../../include/common_rest_functions.php');
	include_once('../../../../../include/secret.php');

	//Handle Training Meta data
	$classifierLang = "en";
	$classifierName = "ExampleTest";
	//Options: English (en), Arabic (ar), French (fr), German, (de), Italian (it), Japanese (ja), Portuguese (pt), and Spanish (es)
	$trainingMetaDataJSONContent = '{"name":"' . $classifierName . '","language":"' . $classifierLang . '"}';
	
	//Create a new JSON object for the meta training data
	$trainingMetaDataVersion = 1;
	$trainingMetaDataFile = fopen('newtrainingdata_' . $trainingMetaDataVersion . '.json','w+');
	fwrite($trainingMetaDataFile, $trainingMetaDataJSONContent);
	fclose($trainingMetaDataFile);
	
	//Get file being passed in with parameter "trainingData"
	$target_directory = './';
	$filename = str_replace(" ","",basename( $_FILES['trainingData']['name']));
	$target_path = $target_directory . $filename;
	if(move_uploaded_file($_FILES['trainingData']['tmp_name'], $target_path)) {
	} else{
	    die("An error has occurred: With file upload");
	}

	$localFileRealPath = realpath($target_directory . $filename);
		
	//Make CURL request with files ($localFileRealPath, realpath($trainingMetaDateFile))
	$username = $NATURAL_LANG_USER_NAME;
	$password = $NATURAL_LANG_PASSWORD;
	$URL = "https://gateway.watsonplatform.net/natural-language-classifier/api/v1/classifiers";


	//Create the CURL File for the .csv file
	/*
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$finfo = finfo_file($finfo, $localFileRealPath);	
	$cFile = new CURLFile($filename, $finfo, basename($filename));
	*/
	//Create the CURL File for the .json file
	$filename2 = 'newtrainingdata_' . $trainingMetaDataVersion . '.json';
	$localFileRealPath2 = realpath($target_directory . $filename2);
	/*
	
	$finfo2 = finfo_open(FILEINFO_MIME_TYPE);
	$finfo2 = finfo_file($finfo2, $localFileRealPath2);	
	$cFile2 = new CURLFile($filename2, $finfo2, basename($filename2));
	*/
	$post = array();
	$post['training_data'] = new CurlFile($localFileRealPath, 'text/csv', $filename);
	$post['training_metadata'] = new CurlFile($localFileRealPath2, 'text/json', $filename2);
	
	// $args['file'] = new CurlFile('filename.png', 'image/png', 'filename.png');
	

	//Create the post parameters
	// $post = [
	// 	$data = array("training_data" => $cFile,"training_metadata" => $cFile2)
	// ];


	$data_strings = json_encode($post);

	echo $data_strings;
	echo '<br/>';
	echo '<br/>';
	// var_dump($post);
	

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

	// curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
	// 	'Content-Type: multipart/form-data',                                                                                
	// 	'Content-Length: ' . strlen($data_strings))                                                                       
	// );
	//curl_setopt($ch, CURLOPT_RETURNTRANSFER => true);
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$result=curl_exec($ch);
	

	if(!curl_errno($ch)){
		$info = curl_getinfo($ch);
		echo json_encode($info);
	}
    curl_close ($ch);


	echo json_encode($result);
	echo '<br/>';
	echo '<br/>';
	//echo '[' . $result['error'] . ']';

	echo '<br/>';
	echo '<br/>';
	echo '<br/>';
	echo '<br/>';
	echo var_dump($post);
	
?>