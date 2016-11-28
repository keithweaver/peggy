<?php
	// WILL REQUIRE A CLUSTER THAT IS READY
	
	$clusterId = "sc72db6189_284e_48a7_babe_76b39a8db887";
	$collectionName = "WhatToExpectCollection";
	$configurationName = "cranfield-solr-config.zip";

	include_once('../../../../include/secret.php');
	$username = $RETRIEVE_AND_RANK_USER_NAME;
	$password = $RETRIEVE_AND_RANK_PASSWORD;
	
	$URL = 'https://gateway.watsonplatform.net/retrieve-and-rank/api/v1/solr_clusters/' . $clusterId . '/solr/admin/collections';
	
	$post = [
		'action' => 'CREATE',
		'name' => $collectionName,
		'collection.configName' => $configurationName,
		'wt' => 'json'
	];

	$data_strings = json_encode($post);

	$URL .= '?action=CREATE&name=' . $collectionName . '&collection.configName=' . $configurationName . '&wt=json';
	// $URL .= '?action=CREATE&name=' . $collectionsName . '&collection.configName=' . $configurationName . '&wt=json"';


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
	$response = curl_exec($ch);
	curl_close ($ch);
	$results = json_decode($response);

	// echo var_dump($response);

	// echo '<br/>';
	// echo '<br/>';

	echo json_encode($results);

	//solr_cluster_status <-- should be ready READY

	/*
	{
		"responseHeader": {
		"status": 0,
		"QTime": 11352
		},
		"success": {
		"10.176.45.164:5896_solr": {
		"responseHeader": {
		"status": 0,
		"QTime": 1840
		},
		"core": "ExampleCollection2_shard1_replica1"
		},
		"10.176.142.190:6067_solr": {
		"responseHeader": {
		"status": 0,
		"QTime": 2168
		},
		"core": "ExampleCollection2_shard1_replica2"
		}
		}
	}
	{
		"responseHeader": {
		"status": 400,
		"QTime": 9
		},
		"Operation create caused exception:": "org.apache.solr.common.SolrException:org.apache.solr.common.SolrException: collection already exists: ExampleCollection",
		"exception": {
		"msg": "collection already exists: ExampleCollection",
		"rspCode": 400
		},
		"error": {
		"metadata": [
		  "error-class",
		  "org.apache.solr.common.SolrException",
		  "root-error-class",
		  "org.apache.solr.common.SolrException"
		],
		"msg": "collection already exists: ExampleCollection",
		"code": 400
		}
	}
	*/

	//{"responseHeader":{"status":0,"QTime":12166},"success":{"10.176.142.240:5879_solr":{"responseHeader":{"status":0,"QTime":2476},"core":"WhatToExpectCollection_shard1_replica1"},"10.176.42.47:5773_solr":{"responseHeader":{"status":0,"QTime":2884},"core":"WhatToExpectCollection_shard1_replica2"}}}
?>