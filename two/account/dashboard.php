<?php
	session_start();
	$email = $_SESSION['two_watson_email'];
	if($email == ""){
		die("Error: You already logged in.");
	}
	include_once('../../include/common_rest_functions.php');

	$publicProjectId = grab('c');
	if($publicProjectId == ""){
		die("Error: Unknown project id");
	}
?>
<!--
Tabs:
Watson Logging Team Settings
Natural Language, Retrieve and Rank, Knowledge Base

If the service is not enabled, first action is to get credentials from User using IBM Bluemix
Can have multiple credentials for a single service


If the service is natural language -> needs to generate training data
If Knowledge Base, list of html documents, can add html document, can add text to create a html document, can upload one pdf document (needs credentials for document conversion)
-->
<html>
<head>
<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<link rel="stylesheet" href="./css/main/navbar.css">
<link rel="stylesheet" href="./css/dashboard/main.css">
<link rel="stylesheet" href="./css/dashboard/menu.css">
<link rel="stylesheet" href="./css/dashboard/submenu.css">
<link rel="stylesheet" href="./css/dashboard/file.css">
<link rel="stylesheet" href="./css/dashboard/htmldocs.css">
<link rel="stylesheet" href="./css/dashboard/jquery/jquery.fileupload.css">
<link rel="stylesheet" href="./css/dashboard/create-html.css">

</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top" id="navbar">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
			</button>
			<a class="navbar-brand" href="#">
				<img src="../imgs/atomic.png" class="navbar-logo">
			</a>
		</div>
		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav navbar-right">
				
				<!-- <li><a href="#" class="navbar-item"><img src="../imgs/packs/settings-1.png" class="settings-navbar-item"></a></li> -->
				<li><a href="#" class="navbar-item"><img src="../imgs/packs/alarm.png" class="settings-navbar-item"></a></li>
				
				<li class="dropdown">
					<!-- <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span id="navbar-add-btn">+</span> <span class="caret"></span></a> -->
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="../imgs/packs/settings-1.png" class="settings-navbar-item"></a>
					<ul class="dropdown-menu">
						<li><a href="https://smartiot.ca/account/plant/setup/">Add a Smart Plant</a></li>
						<!-- <li><a href="#">Add a Smart Home</a></li> -->
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>
<div class="menu-wrapper">
	<div class="container-fluid">
		<div class="row menu-row">
			<div class="col-sm-12">
				<p class="menu-item-wrap">
					<a href="#" class="menu-item" id="dashboard-menu-item" onclick="return loadDashboard();">Dashboard</a>
					<a href="#" class="menu-item" id="knowledge-base-menu-item" onclick="return loadKnowledgeBase();">Knowledge Base</a>
					<a href="#" class="menu-item" id="natural-lang-menu-item">Natural Language</a>
					
					<?php
						//
						echo '<a href="./retrieve-and-rank/?c=' . $publicProjectId . '" class="menu-item" id="retrieve-rank-menu-item">Retrieve and Rank</a>';
					?>
				</p>
			</div>
		</div>
	</div>
</div>
<span id="submenu-content"></span>
<span id="page-content"></span>
<!--
<div class="submenu-wrapper"><div class="container-fluid"><div class="row"><div class="col-sm-12"><p class="submenu-item-wrap"><a href="#" class="submenu-item">Current Documents</a><a href="#" class="submenu-item">Add HTML Document</a><a href="#" class="submenu-item">Create HTML Document</a><a href="#" class="submenu-item">Upload PDF</a><a href="#" class="submenu-item">From Marketplace</a></p></div></div></div></div>
-->
<!--
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			
				<div class="single-file-wrapper">
					<div class="single-file-menu">
						<div class="singe-file-menu-img-wrapper text-left single-file-edit-img-wrap">
							<a href="#">
								<img src="../imgs/packs/editcc.png" class="single-file-menu-img single-file-edit-img">
							</a>
						</div>
						<div class="singe-file-menu-img-wrapper text-right single-file-delete-img-wrap">
							<a href="#">		
								<img src="../imgs/packs/garbagecc.png" class="single-file-menu-img single-file-delete-img">
							</a>
						</div>

					</div>
					<img src="../imgs/packs/documentc.png" class="single-file-main-img">
					<p class="file-name">Depression-And-Craziness</p>
					<p class="file-collection">What to Expect When Your Expecting</p>
				</div>

			
		</div>
	</div>
</div>
-->
<!--
<div class="container-fluid"><div class="row"><div class="col-sm-12"><div id="progress" class="progress"><div class="progress-bar progress-bar-success"></div></div></div></div><div class="row htmldocs-title-row"><div class="col-sm-12"><h1 class="html-docs-title">Add HTML Documents</h1></div></div><div class="row add-html-files-row"><div class="col-sm-12 text-center"><span class="btn add-html-files-btn fileinput-button" id="fileupload"><i class="glyphicon glyphicon-plus"></i><span>Add HTML Files</span><input id="fileupload" type="file" name="files[]" multiple></span></div></div><div class="row htmldocs-files-row"><div class="col-sm-12"><div id="files" class="files"></div></div></div></div>
-->

<!--
Upload PDF:
if(PDF == 1 page):
	Upload and Create HTML Doc from it
else:(PDF > 1 page):
	Show each page
	Allow to create Groups
	Allow to add pages to custom group or "General"

	if(not all pages selected inform user):
	else:
		creae html docs by group

From Marketplace:
- input url or collection/book public code or name
- if more than one option:
	- show multiple and get them to pick one
- else:
	- Charge them or its free and agree to terms
	

-->


<script>
<?php
	echo 'var publicProjectId = "' . $publicProjectId . '";';
?>
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<script src="./js/dashboard/main.js"></script>
<script src="./js/dashboard/current-documents.js"></script>


<script src="./js/dashboard/jquery/vendor/jquery.ui.widget.js"></script>
<script src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<script src="https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<script src="./js/dashboard/jquery/vendor/jquery.ui.widget.js"></script>
<script src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<script src="https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="./js/dashboard/jquery/jquery.iframe-transport.js"></script>
<script src="./js/dashboard/jquery/jquery.fileupload.js"></script>
<script src="./js/dashboard/jquery/jquery.fileupload-process.js"></script>
<script src="./js/dashboard/jquery/jquery.fileupload-image.js"></script>
<script src="./js/dashboard/jquery/jquery.fileupload-audio.js"></script>
<script src="./js/dashboard/jquery/jquery.fileupload-video.js"></script>
<script src="./js/dashboard/jquery/jquery.fileupload-validate.js"></script>
<script src="./js/dashboard/upload.js"></script>

</body>
</html>