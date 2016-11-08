<?php
	session_start();
	$email = $_SESSION['two_watson_email'];
	if($email == ""){
		die("Error: You already logged in.");
	}
	include_once('../../../include/common_rest_functions.php');

	$publicProjectId = grab('c');
	if($publicProjectId == ""){
		die("Error: Unknown project id");
	}
?>

<html>
<head>
</head>
<body>
<p id="statusText"></p>
<br/>


<span id="list-of-clusters"></span>


<br/>
<input type="text" placeholder="Cluster Name" id="create-cluster-name-textbox"><br/>
<button onclick="return createCluster();">Create Cluster</button><br/>
<br/>
<input type="text" placeholder="Collection Name"><br/>
<select id="cluster-options-list">
	<option></option>
</select><br/>
<button onclick="return createCollection();">Create Collection</button><br/>


<br/>
<br/>

<input type="text" placeholder="Ranker Name"><br/>
<select id="cluster-options-list">
	<option></option>
</select><br/>
<select id="collection-options-list">
	<option></option>
</select><br/>
<button>Create Ranker</button><br/>


<script>
<?php
	echo 'var publicProjectId = "' . $publicProjectId . '";';
?>
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="./js/main/ui.js"></script>

</body>
</html>