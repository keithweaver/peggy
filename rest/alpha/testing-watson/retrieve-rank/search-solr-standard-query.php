<?php
	//Input data
	$clusterId = "sc1689c542_dc4c_4cb5_bb51_6764d984e920";
	$collectionName = "ExampleCollection";
	$query = "What is the basic mechanism of the transonic aileron buzz";
	$query = rawurlencode($query);
	$query = strtolower($query);

	//Information to Connect to Auth
	include_once('../../../../include/secret.php');
	$username = $RETRIEVE_AND_RANK_USER_NAME;
	$password = $RETRIEVE_AND_RANK_PASSWORD;
		
	//URL with information
	$URL = 'https://gateway.watsonplatform.net/retrieve-and-rank/api/v1/solr_clusters/' . $clusterId . '/solr/' . $collectionName . '/select';
	
	//Post parameters
	$post = [
		'solr_cluster_id' => $clusterId,
		'name' => $collectionName,
		'q' => $query,
		'wt' => 'json'
	];
	
	$data_strings = json_encode($post);

	//POST Parrmeters in URL
	$URL .= '?solr_cluster_id=' . $post['solr_cluster_id'] . '&name=' . $post['name'] . '&q=' . $post['q'] . '&wt=' . $post['wt'];
	
	//CURL Request
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$URL);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 500);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_strings);
	// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_strings)));//this line throws a 400
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$response = curl_exec($ch);
	curl_close ($ch);
	$results = json_decode($response);

	// echo var_dump($response);

	// echo '<br/>';
	// echo '<br/>';

	echo json_encode($results);

	//solr_cluster_status <-- should be ready READY

	/*
	
	*/
?>