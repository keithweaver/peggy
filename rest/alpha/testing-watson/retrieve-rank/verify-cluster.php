<?php
	// WILL REQUIRE A CLUSTER
	
	$clusterId = "sc72db6189_284e_48a7_babe_76b39a8db887";

	//THIS IS VERIFYING THE CLUSTER IS READY WITH solr_cluster_status
	include_once('../../../../include/secret.php');
	$username = $RETRIEVE_AND_RANK_USER_NAME;
	$password = $RETRIEVE_AND_RANK_PASSWORD;
	$URL='https://gateway.watsonplatform.net/retrieve-and-rank/api/v1/solr_clusters/' . $clusterId;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$URL);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
	$result=curl_exec ($ch);
	curl_close ($ch);

	echo json_encode($result);

	//solr_cluster_status <-- should be ready READY

	//"{\"solr_cluster_id\":\"sc1689c542_dc4c_4cb5_bb51_6764d984e920\",\"cluster_name\":\"ExampleTest2\",\"cluster_size\":\"1\",\"solr_cluster_status\":\"READY\"}"
	//"{\"solr_cluster_id\":\"sc72db6189_284e_48a7_babe_76b39a8db887\",\"cluster_name\":\"PeggyCluster\",\"cluster_size\":\"1\",\"solr_cluster_status\":\"READY\"}"
?>