var clusters = [];

refreshClusters();
function refreshClusters(){
	showStatus("Loading...");
	var formData = {
		publicProjectId : publicProjectId
	};
	$.ajax({
		type        : 'POST',
		url         : './actions/load-clusters',
		data        : formData,
		dataType    : 'json',
		encode      : true
	}).done(function(data) {
		console.log(data);
		if(!data['success']){
			error(data['message']);
		}else{
			clusters = data.clusters;
			displayClusters(data.html, data.watsonIds);
		}
	});
	return false;
}
function displayClusters(html, watsonIds){
	showStatus("");
	$("#list-of-clusters").html(html);
	var options = "";
	for(var i = 0;i < watsonIds.length;i++){
		options += "<option>" + watsonIds[i] + "</option>";
	}
	$("#cluster-options-list").html(options);
}
function createCluster(){
	console.log("createCluster()");
	var name = $("#create-cluster-name-textbox").val();
	var formData = {
		publicProjectId : publicProjectId,
		name : name
	};
	console.log(formData);
	$.ajax({
		type        : 'POST',
		url         : './actions/create-cluster',
		data        : formData,
		dataType    : 'json',
		encode      : true
	}).done(function(data) {
		console.log(data);
		if(!data['success']){
			error(data['message']);
		}else{
			$("#create-cluster-name-textbox").val("");
			showStatus("DONE");

			displayClusters();
		}
	});

	return false;
}
function createCollection(){
	
	return false;
}
function createRanker(){

}
function showStatus(msg){
	$("#statusText").html(msg);	
}
function error(msg){
	showStatus(msg);
}