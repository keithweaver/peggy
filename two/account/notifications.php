<?php
	session_start();
	$email = $_SESSION['two_watson_email'];
	if($email == ""){
		die("Error: You already logged in.");
	}
?>
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<link rel="stylesheet" href="./css/main/navbar.css">
<link rel="stylesheet" href="./css/dashboard/main.css">


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




<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>