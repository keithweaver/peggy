function uploadCollection(){
	console.log("uploadCollection()");
	tempFileUpload();
	return false;
}
function tempFileUpload(){
    console.log("tempFileUpload()");

    var file_data = $('#fullBook').prop('files')[0];
    var form_data = new FormData();                  
    form_data.append('htmlFile', file_data);
    $.ajax({
        url: './actions/add-collection/upload-temp-file', // point to server-side PHP script 
        dataType: 'text',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function(data){
            console.log(data);
            console.log(data.temp);
            data = JSON.parse(data);
            proccessNewCollection(data.temp, data.filename);
        }
     });
}
function proccessNewCollection(temp, filename){
	console.log("proccessNewCollection(" + temp + "," + filename + ")");
   	var title = $("#bookTitle").val();

    var formData = {
        temp : temp, 
        filename : filename,
        chapters : JSON.stringify(chapters),
        title : title
    };
    $.ajax({
        type        : 'POST',
        url         : './actions/add-collection/process-new-collection',
        data        : formData,
        dataType    : 'json',
        encode      : true
    }).done(function(data) {
        console.log(data);
        if(!data['success']){
            error(data['message']);
        }else{
            // convertNewDocs(data.temp, data.title, data.splitPDFs);
            createBook(data.temp, filename, data.title, data.splitPDFs);
        }
    });
}
function createBook(temp, filename, title, splitPDFs){
    console.log("createBook");
    var formData = {
        title : title,
        temp : temp,
        filename : filename
    };
    $.ajax({
        type        : 'POST',
        url         : './actions/add-collection/create-book',
        data        : formData,
        dataType    : 'json',
        encode      : true
    }).done(function(data) {
        console.log(data);
        if(!data['success']){
            error(data['message']);
        }else{
            convertNewDocs(temp, title, splitPDFs, data.bookId);
            
        }
    });
}
function convertNewDocs(temp, title, newFiles, bookId){
    console.log("convertNewDocs()");
    console.log(newFiles);
    if(newFiles.length > 0){
        var pdf = newFiles[0];

        newFiles.splice(0, 1);

        var formData = {
            temp : temp, 
            title : title,
            pdf : pdf,
            bookId : bookId
        };
        $.ajax({
            type        : 'POST',
            url         : './actions/add-collection/convert-doc',
            data        : formData,
            dataType    : 'json',
            encode      : true
        }).done(function(data) {
            console.log(data);
            if(!data['success']){
                error(data['message']);
            }else{
                convertNewDocs(temp, title, newFiles, bookId);
            }
        });
    }else{
        doneConvertingPDFDocs();
    }
}
function doneConvertingPDFDocs(){
    console.log("doneConvertingPDFDocs");
}