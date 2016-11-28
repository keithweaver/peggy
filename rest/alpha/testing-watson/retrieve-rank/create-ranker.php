<?php
	//cranfield-gt.csv is a list of training questions
	//
	//Layout of this csv file:
	//Question   id1 id2 id3
	//Question2  id3
	//
	//id1, id2, id3 are document id files.
	//Dont include ? on the end of the questions
	//
	//Training data has to be prepped: https://www.ibm.com/watson/developercloud/doc/retrieve-rank/training_data.shtml#manual
	//
	//The create ranker API expects a comma-separated-value (CSV) 
	//training-data file with feature vectors. 
	//Each feature vector represents a query-candidate answer pair 
	//and occupies a single row in the file. 
	//
	//The first column ofevery row in the file is the query ID used
	// to group together all of the candidate-answer feature vectors
	// associated with a single query.
	//
	//The last column in the file is the ground-truth label (also 
	//called the relevance label), which indicates how relevant 
	//that candidate answer is to the query. The remaining columns
	// are various features used to score the match between the 
	//query and the candidate answer. 
	//
	//query_id, feature1, feature2, feature3,...,ground_truth
	//question_id_1, 0.0, 3.4, -900,...,0
	//question_id_1, 0.5, -70, 0,...,1
	//
	// Rules for the training data:
	// - The file must contain at least 49 unique questions.
	// - The number of feature vectors (that is, rows in your
	// 		CSV training-data file) must be 10 times the number
	//		of features (that is, feature columns) in each row.
	// - The relevance label must be a non-negative integer
	// - At least two different relevance labels must exist in the data
	// - At least 25% of the questions must have some label variety
	//		 in the answer set. That is, all of the candidate answers 
	//		 for a single question cannot be labeled with a single 
	//		 relevance level.
	// - The relevance value zero (0) has specific implications when 
	// 		training a ranker. Any documents labeled 0 are considered 
	//		totally irrelevant to the question. That being said, the 
	//		training data must contain some zero-labeled documents to 
	//		strengthen the system's ability to search for relevant labels.
	//
	// Create gt_train.csv and gt_test.csv, 70% and 30% split
	//
	//How is proctitis diagnosed?,a605c109–07c5–4670–9b21–3b52fe01a53f,1
	//
	// cranfield-gt.csv is an example training questions


	//Inputs
	// $clusterId = "sc1689c542_dc4c_4cb5_bb51_6764d984e920";
	// $rankerName = "exampleRanker";//sc72db6189_284e_48a7_babe_76b39a8db887
	$clusterId = "sc72db6189_284e_48a7_babe_76b39a8db887";
	$rankerName = "PeggyRanker";

	include_once('../../../../include/secret.php');

	$target_directory = "./";

	//Upload the Training Data File
	$filename = str_replace(" ","",basename( $_FILES['testFileInput']['name']));
	$target_path = $target_directory . $filename;
	if(move_uploaded_file($_FILES['testFileInput']['tmp_name'], $target_path)) {
	} else{
	    die("An error has occurred: With file upload");
	}

	//Get file path of the trainging data file
	$localFileRealPath = realpath($target_directory . $filename);

	//Create JSON for Training Meta Data
	$trainingMetaContent = '{"name":"' . $rankerName . '"}';
	$trainingMetaFileName = 'ranker-info.json';
	$trainingMetaFile = fopen($trainingMetaFileName,'w+');
	fwrite($trainingMetaFile, $trainingMetaContent);
	fclose($trainingMetaFile);

	//Information about Config JSON file
	$trainingMetaFilePath = realpath($target_directory . $trainingMetaFileName);

	$trainingFile = new CURLFile($filename, 'text/csv', 'training_data');
	$trainingMetaFile = new CURLFile($trainingMetaFileName, 'application/json', 'training_metadata');

	//Information for the CURL Request
	$username = $RETRIEVE_AND_RANK_USER_NAME;
	$password = $RETRIEVE_AND_RANK_PASSWORD;
	$URL = 'https://gateway.watsonplatform.net/retrieve-and-rank/api/v1/rankers';	

	$post = array();
	$post['training_data'] = $trainingFile;
	$post['training_metadata'] = $trainingMetaFile;

	//Making the POST request
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_URL,$URL);
	curl_setopt($ch, CURLOPT_TIMEOUT, 500);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	$headers = array('Accept: application/json','Content-Type: multipart/form-data');
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$resp = curl_exec($ch);
	$results = json_decode($resp);

	//Handling errors with the CURL so 404, 400, 415, 500
	if(!curl_errno($ch)){
		$info = curl_getinfo($ch);
		echo json_encode($info);
	}
	curl_close ($ch);

	echo json_encode($results);

	/*
	{"url":"https:\/\/gateway.watsonplatform.net\/retrieve-and-rank\/api\/v1\/rankers","content_type":"application\/json","http_code":200,"header_size":2048,"request_size":575,"filetime":-1,"ssl_verify_result":0,"redirect_count":1,"total_time":4.008653,"namelookup_time":8.2e-5,"connect_time":0.061094,"pretransfer_time":0.439673,"size_upload":30184,"size_download":318,"speed_download":79,"speed_upload":7529,"download_content_length":-1,"upload_content_length":30184,"starttransfer_time":0.573771,"redirect_time":2.219115,"redirect_url":"","primary_ip":"158.85.132.88","certinfo":[],"primary_port":443,"local_ip":"192.168.1.11","local_port":56601}{"ranker_id":"c852bax18-rank-3657","name":"exampleRanker","created":"2016-11-11T20:47:02.369Z","url":"https:\/\/gateway.watsonplatform.net\/retrieve-and-rank\/api\/v1\/rankers\/c852bax18-rank-3657","status":"Training","status_description":"The ranker instance is in its training phase, not yet ready to accept rank requests"}
	
	c852bax18-rank-3657

	{"url":"https:\/\/gateway.watsonplatform.net\/retrieve-and-rank\/api\/v1\/rankers","content_type":"application\/json","http_code":200,"header_size":2050,"request_size":575,"filetime":-1,"ssl_verify_result":0,"redirect_count":1,"total_time":3.352949,"namelookup_time":7.2e-5,"connect_time":0.058743,"pretransfer_time":0.272982,"size_upload":30184,"size_download":318,"speed_download":94,"speed_upload":9002,"download_content_length":-1,"upload_content_length":30184,"starttransfer_time":0.330857,"redirect_time":1.683205,"redirect_url":"","primary_ip":"158.85.132.88","certinfo":[],"primary_port":443,"local_ip":"192.168.1.11","local_port":58327}{"ranker_id":"c852bax18-rank-3660","name":"exampleRanker","created":"2016-11-11T23:43:33.189Z","url":"https:\/\/gateway.watsonplatform.net\/retrieve-and-rank\/api\/v1\/rankers\/c852bax18-rank-3660","status":"Training","status_description":"The ranker instance is in its training phase, not yet ready to accept rank requests"}
	
	c852bax18-rank-3660



	{"url":"https:\/\/gateway.watsonplatform.net\/retrieve-and-rank\/api\/v1\/rankers","content_type":"application\/json","http_code":200,"header_size":2012,"request_size":575,"filetime":-1,"ssl_verify_result":0,"redirect_count":1,"total_time":1.931914,"namelookup_time":8.5e-5,"connect_time":0.056729,"pretransfer_time":0.212437,"size_upload":27997,"size_download":314,"speed_download":162,"speed_upload":14491,"download_content_length":-1,"upload_content_length":27997,"starttransfer_time":0.267412,"redirect_time":0.841591,"redirect_url":"","primary_ip":"158.85.132.88","certinfo":[],"primary_port":443,"local_ip":"192.168.1.5","local_port":62100}{"ranker_id":"54922ax21-rank-617","name":"PeggyRanker","created":"2016-11-28T05:18:08.654Z","url":"https:\/\/gateway.watsonplatform.net\/retrieve-and-rank\/api\/v1\/rankers\/54922ax21-rank-617","status":"Training","status_description":"The ranker instance is in its training phase, not yet ready to accept rank requests"}
	


	{"url":"https:\/\/gateway.watsonplatform.net\/retrieve-and-rank\/api\/v1\/rankers","content_type":"application\/json","http_code":200,"header_size":1980,"request_size":575,"filetime":-1,"ssl_verify_result":0,"redirect_count":1,"total_time":1.118642,"namelookup_time":5.5e-5,"connect_time":0.056269,"pretransfer_time":0.203539,"size_upload":22341,"size_download":314,"speed_download":280,"speed_upload":19971,"download_content_length":-1,"upload_content_length":22341,"starttransfer_time":0.25987,"redirect_time":0.461156,"redirect_url":"","primary_ip":"158.85.132.88","certinfo":[],"primary_port":443,"local_ip":"192.168.1.5","local_port":62227}{"ranker_id":"54922ax21-rank-638","name":"PeggyRanker","created":"2016-11-28T13:27:34.682Z","url":"https:\/\/gateway.watsonplatform.net\/retrieve-and-rank\/api\/v1\/rankers\/54922ax21-rank-638","status":"Training","status_description":"The ranker instance is in its training phase, not yet ready to accept rank requests"}

	Created at 8:27 am -- training --> Ready at 8:44 am

	{"ranker_id":"54922ax21-rank-638","name":"PeggyRanker","created":"2016-11-28T13:27:34.682Z","url":"https:\/\/gateway.watsonplatform.net\/retrieve-and-rank\/api\/v1\/rankers\/54922ax21-rank-638","status":"Failed","status_description":"Error encountered during training: Training data quality standards not met: invalid header (reserved character(s) (\" \") used in the entry \"have your doubts about diets?\"). Row 1 of input data."}
	
	

	766366x22-rank-396

	{"url":"https:\/\/gateway.watsonplatform.net\/retrieve-and-rank\/api\/v1\/rankers","content_type":"application\/json","http_code":200,"header_size":2006,"request_size":575,"filetime":-1,"ssl_verify_result":0,"redirect_count":1,"total_time":1.348312,"namelookup_time":9.4e-5,"connect_time":0.050438,"pretransfer_time":0.198928,"size_upload":23585,"size_download":314,"speed_download":232,"speed_upload":17492,"download_content_length":-1,"upload_content_length":23585,"starttransfer_time":0.254226,"redirect_time":0.485057,"redirect_url":"","primary_ip":"158.85.132.88","certinfo":[],"primary_port":443,"local_ip":"192.168.1.5","local_port":63086}{"ranker_id":"766366x22-rank-396","name":"PeggyRanker","created":"2016-11-28T13:58:30.106Z","url":"https:\/\/gateway.watsonplatform.net\/retrieve-and-rank\/api\/v1\/rankers\/766366x22-rank-396","status":"Training","status_description":"The ranker instance is in its training phase, not yet ready to accept rank requests"}

	Created at 8:58 am -- training --> Ready at: 9:12

	{"ranker_id":"766366x22-rank-396","name":"PeggyRanker","created":"2016-11-28T13:58:30.106Z","url":"https:\/\/gateway.watsonplatform.net\/retrieve-and-rank\/api\/v1\/rankers\/766366x22-rank-396","status":"Failed","status_description":"Error encountered during training: Training data quality standards not met: invalid header (reserved character(s) (\" \") used in the entry \"have your doubts about diets?\"). Row 1 of input data."}

	



	54922ax21-rank-642

	{"url":"https:\/\/gateway.watsonplatform.net\/retrieve-and-rank\/api\/v1\/rankers","content_type":"application\/json","http_code":200,"header_size":2002,"request_size":575,"filetime":-1,"ssl_verify_result":0,"redirect_count":1,"total_time":1.895802,"namelookup_time":7.1e-5,"connect_time":0.053321,"pretransfer_time":0.211778,"size_upload":23082,"size_download":314,"speed_download":165,"speed_upload":12175,"download_content_length":-1,"upload_content_length":23082,"starttransfer_time":0.263156,"redirect_time":1.274657,"redirect_url":"","primary_ip":"158.85.132.88","certinfo":[],"primary_port":443,"local_ip":"192.168.1.5","local_port":63219}{"ranker_id":"54922ax21-rank-642","name":"PeggyRanker","created":"2016-11-28T14:14:11.634Z","url":"https:\/\/gateway.watsonplatform.net\/retrieve-and-rank\/api\/v1\/rankers\/54922ax21-rank-642","status":"Training","status_description":"The ranker instance is in its training phase, not yet ready to accept rank requests"}
	
	Created at 9:14am  --- training --> Ready at: 9:31

	{"ranker_id":"54922ax21-rank-642","name":"PeggyRanker","created":"2016-11-28T14:14:11.634Z","url":"https:\/\/gateway.watsonplatform.net\/retrieve-and-rank\/api\/v1\/rankers\/54922ax21-rank-642","status":"Failed","status_description":"Error encountered during training: Training data quality standards not met: invalid header (reserved character(s) (\" \") used in the entry \"have your doubts about diets\"). Row 1 of input data."}
	


	
	training(4).csv

	76643bx23-rank-394

	{"url":"https:\/\/gateway.watsonplatform.net\/retrieve-and-rank\/api\/v1\/rankers","content_type":"application\/json","http_code":200,"header_size":2006,"request_size":575,"filetime":-1,"ssl_verify_result":0,"redirect_count":1,"total_time":1.233803,"namelookup_time":5.3e-5,"connect_time":0.051257,"pretransfer_time":0.199046,"size_upload":22156,"size_download":314,"speed_download":254,"speed_upload":17957,"download_content_length":-1,"upload_content_length":22156,"starttransfer_time":0.250211,"redirect_time":0.597757,"redirect_url":"","primary_ip":"158.85.132.88","certinfo":[],"primary_port":443,"local_ip":"192.168.1.5","local_port":63575}{"ranker_id":"76643bx23-rank-394","name":"PeggyRanker","created":"2016-11-28T14:33:24.799Z","url":"https:\/\/gateway.watsonplatform.net\/retrieve-and-rank\/api\/v1\/rankers\/76643bx23-rank-394","status":"Training","status_description":"The ranker instance is in its training phase, not yet ready to accept rank requests"}
	
	Created at 9:33 am -- training --> Ready at: 9:44 am ish

	{"ranker_id":"76643bx23-rank-394","name":"PeggyRanker","created":"2016-11-28T14:33:24.799Z","url":"https:\/\/gateway.watsonplatform.net\/retrieve-and-rank\/api\/v1\/rankers\/76643bx23-rank-394","status":"Failed","status_description":"Error encountered during training: Training data quality standards not met: invalid header (reserved character(s) (\" \") used in the entry \"have your doubts about diets\"). Row 1 of input data."}
	
	

	training(5).csv

	54922ax21-rank-645

	{"url":"https:\/\/gateway.watsonplatform.net\/retrieve-and-rank\/api\/v1\/rankers","content_type":"application\/json","http_code":200,"header_size":2002,"request_size":575,"filetime":-1,"ssl_verify_result":0,"redirect_count":1,"total_time":1.555324,"namelookup_time":9.1e-5,"connect_time":0.054216,"pretransfer_time":0.202572,"size_upload":25618,"size_download":314,"speed_download":201,"speed_upload":16471,"download_content_length":-1,"upload_content_length":25618,"starttransfer_time":0.25614,"redirect_time":0.81214,"redirect_url":"","primary_ip":"158.85.132.88","certinfo":[],"primary_port":443,"local_ip":"192.168.1.5","local_port":63824}{"ranker_id":"54922ax21-rank-645","name":"PeggyRanker","created":"2016-11-28T14:49:56.296Z","url":"https:\/\/gateway.watsonplatform.net\/retrieve-and-rank\/api\/v1\/rankers\/54922ax21-rank-645","status":"Training","status_description":"The ranker instance is in its training phase, not yet ready to accept rank requests"}
	
	Created at 9:51 am --> Ready at: 10:14 (probably before that)
	
	{"ranker_id":"54922ax21-rank-645","name":"PeggyRanker","created":"2016-11-28T14:49:56.296Z","url":"https:\/\/gateway.watsonplatform.net\/retrieve-and-rank\/api\/v1\/rankers\/54922ax21-rank-645","status":"Failed","status_description":"Error encountered during training: Training data quality standards not met: invalid header (reserved character(s) (\" \") used in the entry \"have your doubts about diets\"). Row 1 of input data."}


	*/
?>