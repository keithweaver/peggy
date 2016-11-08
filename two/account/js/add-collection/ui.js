var chapters = [];
/*
id
name
range
*/
function error(msg){
	$("#errorLabel").html(msg);
}
function displayChapters(){
	var html = "";

	for(var i = 0;i < chapters.length;i++){
		if(html != ""){
			html += "<br/><br/>";
		}
		html += chapters[i].name + "<br/>";
		html += chapters[i].range + "<br/>";
		html += '<a href="#" onclick="return removeChapter(' + chapters[i].id +  ');">Remove</a><br/>';
	}

	$("#chapter-list-content").html(html);
}
function removeChapter(id){
	var index = -1;
	for(var i = 0;i < chapters.length;i++){
		if(chapters[i].id == id){
			index = i;
		}
	}
	if (index > -1) {
	    chapters.splice(index, 1);
	}

	displayChapters();
	return false;
}
function addChapter(){
	var name = $("#new-chapter-name").val();
	var range = $("#new-chapter-range").val();

	if(name == ""){
		error("Error: Name cannot be blank");
	}else if(range == ""){
		error("Error: Range cannot be blank");
	}else{

		var obj = { id : chapters.length+1, name : name, range : range };

		chapters.push(obj);

		$("#new-chapter-name").val("");
		$("#new-chapter-range").val("");

		displayChapters();
	}
	return false;
}