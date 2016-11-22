<?php
	$data = array();
	include_once('../../../../include/common_rest_functions.php');
	include_once('../../../../include/secret.php');

	$content = json_decode($_POST['content']);


	$contentObj = $content->content;

	$data['temp3'] = $contentObj;

	$tempList = array();
	foreach ($contentObj as $obj) {
		$title = $obj->title;
		$contentObj2 = $obj->content;
		$text = $contentObj2->text;

		$obj = array();
		$obj['title'] = $title;
		$obj['text'] = $text;

		array_push($tempList, $obj);
	}

	$data['temp'] = $content;
	$data['temp2'] = $tempList;
	echo json_encode($data);
?>