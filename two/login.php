<html>
<head>
<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<link rel="stylesheet" href="./css/signup/main.css">
<link rel="stylesheet" href="./css/signup/top.css">

</head>
<body>

<div class="container">
	<div class="row top-row">
		<div class="col-sm-10 col-sm-offset-1 text-center">
			<img src="./imgs/atomic.png" class="top-img">
		</div>
	</div>
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 text-center">
			<div class="signup-panel">
				<form onclick="return false;">
					<p class="email-label">Email:</p>
					<input type="email" class="signup-textbox" id="email-textbox">
					<p class="password-label">Password:</p>
					<input type="password" class="signup-textbox password-textbox" id="password-textbox">
					<div class="login-btn-wrapper">
						<!-- <p class="loading-text">Loading</p> -->
						<button class="login-btn" onclick="return logIn();">Log In</button>
					</div>
				</form>
				<p class="signup-bottom-text">
					<a href="#" id="resetPassword">Reset your password</a>
					<a href="./signup" id="signupText">Sign Up</a>
				</p>
			</div>
		</div>
	</div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="./js/login/submit.js"></script>
</body>
</html>