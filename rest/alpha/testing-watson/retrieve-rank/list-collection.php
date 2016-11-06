<?php
	// WILL REQUIRE A CLUSTER THAT IS READY
	
	$clusterId = "sc1689c542_dc4c_4cb5_bb51_6764d984e920";

	include_once('../../../../include/secret.php');
	$username = $RETRIEVE_AND_RANK_USER_NAME;
	$password = $RETRIEVE_AND_RANK_PASSWORD;
	
	$URL = 'https://gateway.watsonplatform.net/retrieve-and-rank/api/v1/solr_clusters/' . $clusterId . '/solr/admin/collections?action=LIST&wt=json';

	$post = [
		'action' => 'LIST',
		'wt' => 'json'
	];

	$data_strings = json_encode($post);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$URL);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_strings);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data_strings))
	);
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$result=curl_exec ($ch);
	curl_close ($ch);

	echo json_encode($result);

	//solr_cluster_status <-- should be ready READY

	//"{\"responseHeader\":{\"status\":0,\"QTime\":3},\"collections\":[]}\n"
?>