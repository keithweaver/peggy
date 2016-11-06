<?php
	/*
	Create Cluster
	Verify Cluster
	Create Collection
	Upload Config
	*/
	$data = array();

	include_once('../../../../include/common_rest_functions.php');
	include_once('../../../../include/secret.php');

	//http://stackoverflow.com/questions/20064271/how-to-use-basic-authorization-in-php-curl
	$username = $RETRIEVE_AND_RANK_USER_NAME;
	$password = $RETRIEVE_AND_RANK_PASSWORD;
	$URL='https://gateway.watsonplatform.net/retrieve-and-rank/api/v1/solr_clusters';

	$post = [
		'cluster_size' => '1',
		'cluster_name' => 'ExampleTest2'
	];

	$data_strings = json_encode($post);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$URL);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_strings);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',                                                                                
		'Content-Length: ' . strlen($data_strings))                                                                       
	);
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
	$result=curl_exec ($ch);
	curl_close ($ch);

	//sc1689c542_dc4c_4cb5_bb51_6764d984e920
	//"{\"solr_cluster_id\":\"sc1689c542_dc4c_4cb5_bb51_6764d984e920\",\"cluster_name\":\"ExampleTest2\",\"cluster_size\":\"1\",\"solr_cluster_status\":\"NOT_AVAILABLE\"}"


	//NEED TO SAVE solr_cluster_id
	echo json_encode($result);
?>