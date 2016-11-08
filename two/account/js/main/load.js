$(document).ready(function(){
	loadProjects();
});
function loadProjects(){
	$("#project-menu-item").css("border-bottom-color","#086A87");
	$("#project-menu-item").css("border-bottom-style","solid");
	$("#marketplace-menu-item").css("border-bottom-color","#FFFFFF");
	$("#create-btn-wrapper").show();	

	var formData = {};
	$.ajax({
		type        : 'POST',
		url         : './actions/main/load-projects',
		data        : formData,
		dataType    : 'json',
		encode      : true
	}).done(function(data) {
		console.log(data);
		if(!data['success']){
			error(data['message']);
		}else{
			$("#page-content").html(data.html);
		}
	});

	return false;
}
function loadMarketplace(){
	console.log("loadMarketplace()");
	$("#marketplace-menu-item").css("border-bottom-color","#086A87");
	$("#marketplace-menu-item").css("border-bottom-style","solid");
	$("#project-menu-item").css("border-bottom-color","#FFFFFF");
	$("#create-btn-wrapper").hide();

	var formData = {};
	$.ajax({
		type        : 'POST',
		url         : './actions/main/load-marketplace',
		data        : formData,
		dataType    : 'json',
		encode      : true
	}).done(function(data) {
		console.log(data);
		if(!data['success']){
			error(data['message']);
		}else{
			$("#page-content").html(data.html);
		}
	});

	return false;
}
function error(msg){
	console.log(msg);
}