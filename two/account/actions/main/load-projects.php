<?php
	session_start();

	include_once('../../../../include/common_rest_functions.php');
	include_once('../../../../include/secret.php');

	$email = $_SESSION['two_watson_email'];

	if($email == ""){
		die(error("Error: Please log in. Refresh the page."));
	}

	$data = array();

	//Connect
	$con = mysqli_connect("localhost",$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME);


	$userId = 0;
	$result = mysqli_query($con, "SELECT * FROM userinfo WHERE email='$email'") or die(error("Error: Looking up the user"));
	while($row = mysqli_fetch_array($result)){
		$userId = $row['id'];
	}
	if($userId == 0){
		die(error("Error: Issue looking the user information."));
	}

	$projects = array();

	$result = mysqli_query($con, "SELECT * FROM projectAccess WHERE userId='$userId'") or die(error("Error: Loading user id"));
	while($row = mysqli_fetch_array($result)){
		$projectId = $row['projectId'];
		if(!in_array($projectId, $projects)){
			array_push($projects, $projectId);
		}
	}

	$html = '';

	if(count($projects) > 0){
		foreach ($projects as $projectId) {
			$html .= '<div class="container"><div class="row projects-row">';

			$result = mysqli_query($con, "SELECT * FROM projects WHERE id='$projectId'") or die(error("Error: Loading project information"));
			while($row = mysqli_fetch_array($result)){
				$html .= '<div class="col-sm-4"><a href="./dashboard?c=' . $row['publicProjectId'] . '" class="project-panel-link"><div class="project-panel"><div class="project-cover-wrap" style="background-color:' . $row['color'] . ';"><div class="project-img-wrap"><img src="../imgs/atomic.png" class="project-img"></div></div><div class="project-text-wrap"><p class="project-name-text">' . $row['name'] . '</p></div></div></a></div>';
			}

			$html .= '</div></div>';
		}
	}else{

		$html = '<div class="container"><div class="row empty-row"><div class="col-sm-10 col-sm-offset-1 text-center"><p class="empty-text">You have no projects.</p><p class="empty-new-project-text"><a href="./create">Create your first project</a></p></div></div></div>';

	}

	$data['success'] = true;
	$data['message'] = "Success";
	$data['html'] = $html;

	echo json_encode($data);
?>