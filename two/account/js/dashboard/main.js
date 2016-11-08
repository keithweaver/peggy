$(document).ready(function(){
	loadDashboard();
});
function loadDashboard(){
	console.log("loadDashboard()");

	resetMenuItems();
	$("#dashboard-menu-item").css("border-bottom-color","#086A87");

	$("#submenu-content").html('');
	$("#page-content").html('');

	return false;
}
function resetMenuItems(){
	$("#dashboard-menu-item").css("border-bottom-style","solid");
	$("#knowledge-base-menu-item").css("border-bottom-style","solid");
	$("#natural-lang-menu-item").css("border-bottom-style","solid");
	$("#retrieve-rank-menu-item").css("border-bottom-style","solid");
	
	$("#dashboard-menu-item").css("border-bottom-color","#FFFFFF");
	$("#knowledge-base-menu-item").css("border-bottom-color","#FFFFFF");
	$("#natural-lang-menu-item").css("border-bottom-color","#FFFFFF");
	$("#retrieve-rank-menu-item").css("border-bottom-color","#FFFFFF");
	

	// border-bottom-color: #086A87;
}