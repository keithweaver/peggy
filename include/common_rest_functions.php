<?php
	function error($msg){
		$data = array();
		$data['succes'] = false;
		$data['msg'] = $msg;
		return json_encode($data);
	}
	/*
	Debug - array of data so $debug['test'] = 'val1';
	*/
	function errorForDebugging($msg,$debug){
		$data = array();
		$data['debug'] = $debug;
		$data['succes'] = false;
		$data['msg'] = $msg;
		return json_encode($data);
	}
?>