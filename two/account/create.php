<?php
	session_start();

	$email = $_SESSION['two_watson_email'];
?>
<html>
<head>
<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<link rel="stylesheet" href="./css/main/navbar.css">
<link rel="stylesheet" href="./css/create/main.css">

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

<!-- Create new project
- Project Name
- Invite Team Members
- Services -->

<div class="container">
	<div class="row create-row">
		<div class="col-sm-8 col-sm-offset-2">
			<div class="create-panel">
				<h1 class="title">Create Project</h1>
				<p class="project-name-label">Project Name:</p>
				<input type="text" class="project-name-textbox">
				<p class="services-label">Services:</p>
				<label class="services-text"><input type="checkbox" id="natural-checkbox"> Natural Language Classifiers</label><br/>
				<label class="services-text"><input type="checkbox" id="retrieve-checkbox"> Retrieve and Rank</label><br/>
				<label class="services-text disabled-services-text"><input type="checkbox" checked disabled=""> Knowledge Base</label>
				<p class="invite-team-label">Invite Team Member:</p>
				<input type="email" class="invite-team-textarea" id="invite-textbox">
				<p class="invite-team-list"></p>
				<!-- <span class="invite-team-list-item">keithmweaver@mgial.com  <a href="#" onclick="return removeInvite()">x</a></span> <span class="invite-team-list-item">keithmweaver@mgial.com</span> -->
				<div class="text-center create-btn-wrapper">
					<button class="create-btn" onclick="return createProject();">Create</button>
					<!-- <p class="loading-text">Loading</p> -->
				</div>
			</div>
		</div>
	</div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="./js/create/invite.js"></script>
<script src="./js/create/create.js"></script>
</body>
</html>