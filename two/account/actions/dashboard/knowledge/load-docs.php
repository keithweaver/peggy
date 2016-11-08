<?php
	session_start();

	include_once('../../../../../include/common_rest_functions.php');
	include_once('../../../../../include/secret.php');

	$email = $_SESSION['two_watson_email'];

	if($email == ""){
		die(error("Error: Please log in. Refresh the page."));
	}
	
	$publicProjectId = pickup('publicProjectId');
	if($publicProjectId == ""){
		die(error("Error: Unknown public project id."));
	}

	$data = array();

	//Connect
	$con = mysqli_connect("localhost",$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME);

	//Look up project information
	$result = mysqli_query($con, "SELECT * FROM projects WHERE publicProjectId='$publicProjectId'") or die(error("Error: Unable to load projects"));
	if(mysqli_num_rows($result) == 0){
		die(error("Error: Invalid project"));
	}

	$projectId = -1;
	while($row = mysqli_fetch_array($result)){
		$projectId = $row['id'];

	}
	if($projectId == -1){
		die(error("Error: Unknown project"));
	}

	//Look up users
	$userId = -1;
	$result = mysqli_query($con, "SELECT * FROM userinfo WHERE email='$email'") or die(error("Error: Issues looking up user information"));
	while($row = mysqli_fetch_array($result)){
		$userId = $row['id'];
	}

	if($userId == -1){
		die(error("Error: Issues finding user ifnormation."));
	}

	//Verify project access
	$result = mysqli_query($con, "SELECT * FROM projectAccess WHERE projectId='$projectId' AND userId='$userId'") or die(error("Error: Looking up the user"));
	if(mysqli_num_rows($result) == 0){
		die(error("Error: Missing project access"));
	}


	//Get Knowledge Files
	$html = '';
	$result = mysqli_query($con, "SELECT * FROM knowledgeFiles WHERE projectId='$projectId'") or die(error("Error: unable to load knowledge files"));
	if(mysqli_num_rows($result) > 0){
		$html .= '<div class="container"><div class="row"><div class="col-sm-12">';
		while($row = mysqli_fetch_array($result)){
			$html .= '<a href="#">';
				$html .= '<div class="single-file-wrapper">';
					// $html .= '<div class="single-file-menu">';
					// 	$html .= '<div class="singe-file-menu-img-wrapper text-left single-file-edit-img-wrap">';
					// 		$html .= '<a href="#">';
					// 			$html .= '<img src="../imgs/packs/editcc.png" class="single-file-menu-img single-file-edit-img">';
					// 		$html .= '</a>';
					// 	$html .= '</div>';
					// 	$html .= '<div class="singe-file-menu-img-wrapper text-right single-file-delete-img-wrap">';
					// 		$html .= '<a href="#">';
					// 			$html .= '<img src="../imgs/packs/garbagecc.png" class="single-file-menu-img single-file-delete-img">';
					// 		$html .= '</a>';
					// 	$html .= '</div>';
					// $html .= '</div>';
					$html .= '<img src="../imgs/packs/documentc.png" class="single-file-main-img">';
					$html .= '<p class="file-name">' . $row['displayName'] . '</p>';
					$html .= '<p class="file-collection">' . $row['collection'] . '</p>';
				$html .= '</div>';
			$html .= '</a>';
			
		}
		$html .= '</div></div></div>';
	}else{
		$html .= '<div class="container"><div class="row"><div class="col-sm-12 text-center">';
			$html .= '<p>No Documents</p>';
		$html .= '</div></div></div>';
	}

	$data['html'] = $html;
	$data['success'] = true;

	echo json_encode($data);
?>