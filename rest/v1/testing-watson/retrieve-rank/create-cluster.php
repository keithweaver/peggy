<?php
	$data = array();

	function error($msg){
		$data['succes'] = false;
		$data['msg'] = $msg;
		return json_encode($data);
	}

	//http://stackoverflow.com/questions/20064271/how-to-use-basic-authorization-in-php-curl
	$username='ABC';
	$password='XYZ';
	$URL='<URL>';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$URL);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
	$result=curl_exec ($ch);
	curl_close ($ch);

	echo json_encode($data);
?>