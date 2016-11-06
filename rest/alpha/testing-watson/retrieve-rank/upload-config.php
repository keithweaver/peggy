<?php
	// WILL REQUIRE A CLUSTER
	
	$clusterId = "sc1689c542_dc4c_4cb5_bb51_6764d984e920";
	$filePath = "example-config.zip";

	//http://www.ibm.com/watson/developercloud/doc/retrieve-rank/tutorial.shtml
	//THIS IS VERIFYING THE CLUSTER IS READY WITH solr_cluster_status
	include_once('../../../../include/secret.php');
	$username = $RETRIEVE_AND_RANK_USER_NAME;
	$password = $RETRIEVE_AND_RANK_PASSWORD;
	$URL='https://gateway.watsonplatform.net/retrieve-and-rank/api/v1/solr_clusters/' . $clusterId . '/config/example-config';

	$POST_DATA = array(
        'file' => '@'.  realpath($filePath)
    );

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $URL);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $POST_DATA);
    $response = curl_exec($curl);
    curl_close ($curl);

	echo json_encode($result);

	//solr_cluster_status <-- should be ready READY
?>