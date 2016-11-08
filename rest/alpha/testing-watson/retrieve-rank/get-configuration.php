<?php
	// WILL REQUIRE A CLUSTER THAT IS READY
	
	$clusterId = "sc1689c542_dc4c_4cb5_bb51_6764d984e920";
	$configurationName = "cranfield-solr-config.zip";

	include_once('../../../../include/secret.php');
	$username = $RETRIEVE_AND_RANK_USER_NAME;
	$password = $RETRIEVE_AND_RANK_PASSWORD;
	
	$URL = 'https://gateway.watsonplatform.net/retrieve-and-rank/api/v1/solr_clusters/' . $clusterId . '/config/' . $configurationName;
	
	

	//$data_strings = json_encode($post);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$URL);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(   
		'Content-Type: application/zip'                               
		)                                                                       
	);
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$response = curl_exec($ch);
	$results = json_decode($response);
	curl_close ($ch);

	echo json_encode($results);


	//Echo returns zip
	echo "<br/>";
	echo var_dump($response);
	echo "<br/>";

	//{"msg":"WRRCSS005: Configuration [example_config] does not exist.","code":404}
	//cranfield-solr-config.zip
?>