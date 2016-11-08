var currentSubTab = "";
function loadKnowledgeBase(){
	console.log("loadKnowledgeBase()");

	resetMenuItems();
	$("#knowledge-base-menu-item").css("border-bottom-color","#086A87");

	loadKnowledgeBaseSubMenu();
	loadCurrentDocuments();


	return false;
}
function loadCurrentDocuments(){
	console.log("loadCurrentDocuments()");

	currentSubTab = "currentDocuments";

	resetSubMenuItems();

	$("#current-doc-submenu-item").css("border-bottom-color","#B40431");
	$("#current-doc-submenu-item").css("border-bottom-style","solid");
	
	var formData = {
		publicProjectId : publicProjectId
	};
	$.ajax({
		type        : 'POST',
		url         : './actions/dashboard/knowledge/load-docs',
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
function loadKnowledgeBaseSubMenu(){
	$("#submenu-content").html('<div class="submenu-wrapper"><div class="container-fluid"><div class="row"><div class="col-sm-12"><p class="submenu-item-wrap"><a href="#" class="submenu-item" id="current-doc-submenu-item" onclick="return loadCurrentDocuments();">Current Documents</a><a href="#" class="submenu-item" id="add-html-document-submenu-item" onclick="return addHTMLDocument();">Add HTML Document</a><a href="#" class="submenu-item" id="create-html-document-submenu-item" onclick="return createHTMLDocument();">Create HTML Document</a><a href="#" class="submenu-item" id="upload-pdf-document-submenu-item" onclick="return uploadPDFDocument();">Upload PDF</a><a href="#" class="submenu-item" onclick="return openMarketplace();">From Marketplace</a></p></div></div></div></div>');
}
function resetSubMenuItems(){
	$(".submenu-item").css("border-bottom-color","#ffffff");
}
function error(msg){
	console.log(msg);
}
function addHTMLDocument(){
	console.log("addHTMLDocument()");

	currentSubTab = "addHTMLDocument";

	resetSubMenuItems();

	$("#add-html-document-submenu-item").css("border-bottom-color","#B40431");
	$("#add-html-document-submenu-item").css("border-bottom-style","solid");
	
	$("#page-content").html('<div class="container-fluid"><div class="row"><div class="col-sm-12"><div id="progress" class="progress"><div class="progress-bar progress-bar-success"></div></div></div></div><div class="row htmldocs-title-row"><div class="col-sm-12"><h1 class="html-docs-title">Add HTML Documents</h1></div></div><div class="row add-html-files-row"><div class="col-sm-12 text-center"><input type="file" id="htmlFileUpload" ><button class="btn add-html-files-btn fileinput-button" onclick="return htmlFileUpload();"><i class="glyphicon glyphicon-plus"></i><span> Add HTML Files</span></button></div></div><div class="row htmldocs-files-row"><div class="col-sm-12"><div id="files" class="files"></div></div></div></div>');

	return false;
}
function createHTMLDocument(){
	console.log("createHTMLDocument()");

	currentSubTab = "createHTMLDocument";

	resetSubMenuItems();

	$("#create-html-document-submenu-item").css("border-bottom-color","#B40431");
	$("#create-html-document-submenu-item").css("border-bottom-style","solid");
	
	$("#page-content").html('<div class="container-fluid"><div class="row create-htmldocs-title-row"><div class="col-sm-12"><h1 class="html-docs-title">Create HTML Document</h1></div></div><div class="row create-html-row"><div class="col-sm-12"><input type="text" class="create-html-title-input" id="create-html-title-input" placeholder="Title"><br/><input type="text" class="create-html-title-input" id="create-html-author-input"  placeholder="Author"><br/></div><div class="col-sm-12"><br/><textarea class="create-html-textarea" placeholder="Content"></textarea></div></div><div class="create-html-btn-row row"><div class="col-sm-12"><button class="create-html-btn" onclick="return createHTMLDoc();">Create HTML</button></div></div></div>');

	return false;
}
function uploadPDFDocument(){
	console.log("uploadPDFDocument()");

	currentSubTab = "uploadPDFDocument";

	resetSubMenuItems();

	$("#upload-pdf-document-submenu-item").css("border-bottom-color","#B40431");
	$("#upload-pdf-document-submenu-item").css("border-bottom-style","solid");
	
	//$("#page-content").html('<div class="container-fluid"><div class="row"><div class="col-sm-12"><div id="progress" class="progress"><div class="progress-bar progress-bar-success"></div></div></div></div><div class="row htmldocs-title-row"><div class="col-sm-12"><h1 class="html-docs-title">Add PDF Document</h1></div></div><div class="row add-html-files-row"><div class="col-sm-12 text-center"><span class="btn add-html-files-btn fileinput-button" id="fileupload"><i class="glyphicon glyphicon-plus"></i><span> Add PDF File</span><input id="fileupload" type="file" name="files[]" multiple></span></div></div><div class="row htmldocs-files-row"><div class="col-sm-12"><div id="files" class="files"></div></div></div></div>');
	$("#page-content").html('<div class="container-fluid"><div class="row htmldocs-title-row"><div class="col-sm-12"><h1 class="html-docs-title">Add PDF Documents</h1></div></div><div class="row add-html-files-row"><div class="col-sm-12 text-center"><input type="file" id="pdfFileUpload" ><button class="btn add-html-files-btn fileinput-button" id="pdfFileUploadBtn" onclick="return pdfFileUpload();"><i class="glyphicon glyphicon-plus"></i><span> Add PDF Files</span></button></div></div><div class="row htmldocs-files-row"><div class="col-sm-12"><div id="files" class="files"></div></div></div></div>');

	return false;
}
function createHTMLDoc(){
	$(".create-html-btn").html("Loading...");
	$(".create-html-btn").prop('disabled', true);
	var title = $("#create-html-title-input").val();
	var author = $("#create-html-author-input").val();
	var content = $(".create-html-textarea").val();

	
	//obj with title, author, array of content
	var obj = { title : title, author : author, content : content };
	
	createHTMLDocOnServer(obj);
	
	return false;
}
function createHTMLDocOnServer(obj){
	console.log("createHTMLDocOnServer()");
	var formData = {
		publicProjectId : publicProjectId,
		obj : JSON.stringify(obj)
	};
	$.ajax({
		type        : 'POST',
		url         : './actions/dashboard/knowledge/create-html',
		data        : formData,
		dataType    : 'json',
		encode      : true
	}).done(function(data) {
		console.log(data);
		if(!data['success']){
			error(data['message']);
		}else{
			$("#create-html-title-input").val("");
			$("#create-html-author-input").val("");
			$(".create-html-textarea").val("");
			$(".create-html-btn").html("Done");
			setTimeout(function(){
				$(".create-html-btn").html("Create HTML");
				$(".create-html-btn").prop('disabled', false);
			}, 2000);
		}
	});
}
function openMarketplace(){
	console.log('openMarketplace');
	addBook(4);

	return false;
}
function addBook(bookId){
	console.log("addBook");
	var formData = {
		publicProjectId : publicProjectId,
		bookId : bookId
	};
	$.ajax({
		type        : 'POST',
		url         : './actions/dashboard/knowledge/add-book-to-knowledge',
		data        : formData,
		dataType    : 'json',
		encode      : true
	}).done(function(data) {
		console.log(data);
		if(!data['success']){
			error(data['message']);
		}else{
			alert("DONE");
		}
	});
}