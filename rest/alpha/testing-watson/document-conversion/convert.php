<?php
	
	$data = array();


	include_once('../../../../include/common_rest_functions.php');
	include_once('../../../../include/secret.php');

	//Get Type being convert to
	$convertToType = $_POST['type'];
	$convertToType = addslashes($convertToType);

	//Set Target Directory for file upload
	$target_directory = './';

	//Create a Config JSON file from type
	$configContent = '{"conversion_target":"' . $convertToType . '"}';
	$configFileName = 'config.json';
	$configFile = fopen($configFileName,'w+');
	fwrite($configFile, $configContent);
	fclose($configFile);

	//Information about Config JSON file
	$configFilePath = realpath($target_directory . $configFileName);


	//Upload PDF File
	$filename = str_replace(" ","",basename( $_FILES['bookFile']['name']));
	$target_path = $target_directory . $filename;
	if(move_uploaded_file($_FILES['bookFile']['tmp_name'], $target_path)) {
	} else{
	    die("An error has occurred: With file upload");
	}

	$uploadedFilePath = realpath($target_directory . $filename);


	//Get date for version
	$date = date('Y-m-d');

	//Make CURL request with files
	$username = $DOCUMENT_CONVERSION_USER_NAME;
	$password = $DOCUMENT_CONVERSION_PASSWORD;
	$URL = "https://gateway.watsonplatform.net/document-conversion/api/v1/convert_document?version=2015-12-15";

	//Putting together the Post parameters
	$uploadedFile = new CURLFile($filename, 'application/pdf', 'file');
	$configFile = new CURLFile($configFileName, 'application/json', 'config');

	$post = array();
	$post['file'] = $uploadedFile;
	$post['config'] = $configFile;

	
	//Making the POST request
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_URL,$URL);
	curl_setopt($ch, CURLOPT_TIMEOUT, 500);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");//Insert your password and user name
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	$headers = array('Accept: application/json','Content-Type: multipart/form-data');
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$resp = curl_exec($ch);
	$results = json_decode($resp);

	if($results->code == 500){
		die(error("Error 500: " . $results->error));
	}

	$metadata = array();
	foreach ($results->metadata as $obj) {
		$metadata[$obj->name] = $obj->content;
	}
		

	$con = mysqli_connect("localhost","root","root","marketPlaceDB");

	$content = array();
	$data['temp1'] = count($results->answer_units);
	$i = 0;
	foreach ($results->answer_units as $obj) {
		$pieceOfContent = array();
		$pieceOfContent['id'] = $obj->id;
		$pieceOfContent['type'] = $obj->type;
		$pieceOfContent['parent_id'] = $obj->parent_id;
		$pieceOfContent['title'] = $obj->title;
		$pieceOfContent['direction'] = $obj->direction;
		$c = array();
		$contentFromObj = $obj->content;

		//HACK
		$text = "";
		$title = $obj->title;


		foreach ($contentFromObj as $singleContentObj) {
			$c2 = array();

			$c2['media_type'] = $singleContentObj->media_type;
			$c2['text'] = $singleContentObj->text;

			$text = $singleContentObj->text;

			array_push($c, $c2);
		}
		$pieceOfContent['content'] = $c;

		array_push($content, $pieceOfContent);

		$text = addslashes($text);
		$title = addslashes($title);

		if($title != "no-title"){
			if(strlen($title) == 1){
				$text = $title . $text;
				$title = "";
			}
			mysqli_query($con, "INSERT INTO knowledge (title, content) VALUES ('$title','$text')") or die(error("Error: "));
		}

 	}


 	
 	
 	$data['success'] = true;
 	$data['message'] = "Document Converted";
 	$data['content'] = $content;
 	echo json_encode($data);
?>