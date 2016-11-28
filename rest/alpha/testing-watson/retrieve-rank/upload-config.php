<?php
	// WILL REQUIRE A CLUSTER <--- NEEDS WORK
	
	$clusterId = "sc72db6189_284e_48a7_babe_76b39a8db887";
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

	//Create a CURL File
	$configFile = new CURLFile($filename, 'application/zip', '@');


	//Information for the CURL Request
	$username = $RETRIEVE_AND_RANK_USER_NAME;
	$password = $RETRIEVE_AND_RANK_PASSWORD;
	$URL='https://gateway.watsonplatform.net/retrieve-and-rank/api/v1/solr_clusters/' . $clusterId . '/config/' . $filename;


	$post = array();
	$post['@'] = $configFile;

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
	$headers = array('Accept: application/json','Content-Type: application/zip');
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$resp = curl_exec($ch);
	$results = json_decode($resp);

	//solr_cluster_status <-- should be ready READY
	if(!curl_errno($ch)){
		$info = curl_getinfo($ch);
		echo var_dump($info);
		echo '<br/><br/>';
	}
	echo var_dump($results);

	//object(stdClass)#2 (2) { ["message"]=> string(132) "WRRCSR026: Successfully uploaded named config [cranfield-solr-config.zip] for Solr cluster [sc1689c542_dc4c_4cb5_bb51_6764d984e920]." ["statusCode"]=> int(200) }
?>