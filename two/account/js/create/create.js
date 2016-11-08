function createProject(){
	console.log("createProject()");

	showLoadingMessage('Loading...');

	var name = $(".project-name-textbox").val();

	var naturalService = false;
	if(document.getElementById("natural-checkbox").checked){
		naturalService = true;
	}
	var retrieveService = false;
	if(document.getElementById("retrieve-checkbox").checked){
		retrieveService = true;
	}

	var services = {retrieve : retrieveService, natural : naturalService};

	createProjectOnServer(name, services);

	return false;
}
function createProjectOnServer(name, services){
	var formData = {
		name : name,
		services : JSON.stringify(services),
		invites : JSON.stringify(inviteList)
	};
	$.ajax({
		type        : 'POST',
		url         : './actions/create/new-project',
		data        : formData,
		dataType    : 'json',
		encode      : true
	}).done(function(data) {
		console.log(data);
		if(!data['success']){
			error(data['message']);
		}else{
			window.location.replace("./dashboard?p=" + data.project);
		}
	
	});
}
function error(msg){
	$(".create-btn-wrapper").html('<button class="create-btn" onclick="return createProject();">Create</button>');
	$(".create-btn-wrapper").append('<p class="loading-text">' + msg + '</p>');
}
function showLoadingMessage(msg){
	$(".create-btn-wrapper").html('<p class="loading-text">' + msg + '</p>');
}