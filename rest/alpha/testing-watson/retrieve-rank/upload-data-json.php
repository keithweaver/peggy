<?php
	// WILL REQUIRE A CLUSTER <--- NEEDS WORK
	
	$clusterId = "sc1689c542_dc4c_4cb5_bb51_6764d984e920";
	$collectionName = "ExampleCollection";
	//$configName = "example-config";
	//http://www.ibm.com/watson/developercloud/doc/retrieve-rank/tutorial.shtml
	//THIS IS VERIFYING THE CLUSTER IS READY WITH solr_cluster_status
	include_once('../../../../include/secret.php');
	
	$target_directory = "./";

	//Upload the Config File
	$filename = str_replace(" ","",basename( $_FILES['configFileInput']['name']));
	$target_path = $target_directory . $filename;
	if(move_uploaded_file($_FILES['configFileInput']['tmp_name'], $target_path)) {
	} else{
	    die("An error has occurred: With file upload");
	}

	//Get file path of the config file
	$localFileRealPath = realpath($target_directory . $filename);

	echo $localFileRealPath . '<br/><br/>';

	//Create a CURL File
	$bodyFile = new CURLFile($filename, 'application/json', '@');


	//Information for the CURL Request
	$username = $RETRIEVE_AND_RANK_USER_NAME;
	$password = $RETRIEVE_AND_RANK_PASSWORD;
	$URL='https://gateway.watsonplatform.net/retrieve-and-rank/api/v1/solr_clusters/' . $clusterId . '/solr/' . $collectionName . '/update';


	$post = array();
	$post['body'] = $bodyFile;
	$post['solr_cluster_id'] = $clusterId;
	$collection_name['collection_name'] = $collectionName;

	echo var_dump($post) . '<br/><br/>';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_URL,$URL);
	curl_setopt($ch, CURLOPT_TIMEOUT, 500);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	// $headers = array('Accept: application/json','Content-Type: multipart/form-data');
	// $headers = array('Accept: application/json','Content-Type: text/plain');
	// $headers = array('Accept: application/zip','Content-Type: text/plain');
	// $headers = array('Content-Type: multipart/form-data');
	// $headers = array('Accept: application/zip','Content-Type: multipart/form-data');
	$headers = array('Accept: application/json','Content-Type: multipart/form-data');
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$resp = curl_exec($ch);
	$results = json_decode($resp);

	//solr_cluster_status <-- should be ready READY
	if(!curl_errno($ch)){
		$info = curl_getinfo($ch);
		
	}
	echo var_dump($resp);
	echo '<br/>';
	echo '<br/>';
	echo var_dump($results);

	//{"responseHeader":{"status":0,"QTime":1476}}
?>