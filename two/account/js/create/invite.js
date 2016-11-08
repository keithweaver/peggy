var inviteList = [];

$(document).ready(function(){
	$("#invite-textbox").change(function(){
		var inviteEmail = $("#invite-textbox").val();
		if(notInArray(inviteEmail)){
			inviteList.push(inviteEmail);
			$("#invite-textbox").val('');
		}

		printInvites();
	});
});
function printInvites(){
	var label = "";

	for(var i = 0;i < inviteList.length;i++){
		if(label.length > 0){
			label += " ";
		}
		label += '<span class="invite-team-list-item">' + inviteList[i] + '  <a href="#" onclick="return removeInvite(\'' + inviteList[i] + '\')">x</a></span>';
	}

	$(".invite-team-list").html(label);
}
function notInArray(email){
	for(var i = 0;i < inviteList.length;i++){
		if(email == inviteList[i]){
			return false;
		}
	}
	return true;
}
function removeInvite(email){
	var index = inviteList.indexOf(email);
	if (index > -1) {
	    inviteList.splice(index, 1);
	}
	printInvites();
	return false;
}