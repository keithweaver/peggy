function logIn(){
	var email = $("#email-textbox").val();
	var password = $("#password-textbox").val();

	if(email == ""){
		error("Error: Email cannot be blank");
	}else if(password == ""){
		error("Error: Password cannot be blank");
	}else{
		showLoading();
		logInOnServer(email, password);
	}

	return false;
}
function logInOnServer(email, password){
	var formData = {
		email : email,
		password : password
	};
	$.ajax({
		type        : 'POST',
		url         : './actions/login',
		data        : formData,
		dataType    : 'json',
		encode      : true
	}).done(function(data) {
		console.log(data);
		if(!data['success']){
			error(data['msg']);
		}else{
			window.location.replace("./account/");
		}
	});
}
function error(msg){
	$('.login-btn-wrapper').html('<p class="loading-text">' + msg + '</p><button class="login-btn" id="signup-btn" onclick="return logIn();">Log In</button>');
}
function showLoading(){
	$(".login-btn-wrapper").html('<p class="loading-text">Loading...</p>');
}