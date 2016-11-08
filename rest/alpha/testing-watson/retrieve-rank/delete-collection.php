<?php
	// WILL REQUIRE A CLUSTER THAT IS READY
	
	$clusterId = "sc1689c542_dc4c_4cb5_bb51_6764d984e920";
	$collectionName = "ExampleCollection2";
	$configurationName = "cranfield-solr-config.zip";

	include_once('../../../../include/secret.php');
	$username = $RETRIEVE_AND_RANK_USER_NAME;
	$password = $RETRIEVE_AND_RANK_PASSWORD;
	
	$URL = 'https://gateway.watsonplatform.net/retrieve-and-rank/api/v1/solr_clusters/' . $clusterId . '/solr/admin/collections';
	
	$post = [
		'action' => 'DELETE',
		'name' => $collectionName,
		'collection.configName' => $configurationName,
		'wt' => 'json'
	];

	$data_strings = json_encode($post);

	$URL .= '?action=DELETE&name=' . $collectionName . '&collection.configName=' . $configurationName . '&wt=json';
	


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
		"QTime": 3283
		},
			"success": {
				"10.176.45.164:5896_solr": {
					"responseHeader": {
					"status": 0,
					"QTime": 21
					}
				},
				"10.176.142.190:6067_solr": {
					"responseHeader": {
					"status": 0,
					"QTime": 2514
					}
			}
		}
	}
	*/
?>