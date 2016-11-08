/*global window, $ */
// $(function () {
//     'use strict';
//     // Change this to the location of your server-side upload handler:
//     var url = window.location.hostname === 'blueimp.github.io' ?
//                 '//jquery-file-upload.appspot.com/' : 'server/php/',
//         uploadButton = $('<button/>')
//             .addClass('btn btn-primary')
//             .prop('disabled', true)
//             .text('Processing...')
//             .on('click', function () {
//                 var $this = $(this),
//                     data = $this.data();
//                 $this
//                     .off('click')
//                     .text('Abort')
//                     .on('click', function () {
//                         $this.remove();
//                         data.abort();
//                     });
//                 data.submit().always(function () {
//                     $this.remove();
//                 });
//             });
//     var acceptedTypes = "";
//     if(currentSubTab == "uploadPDFDocument"){
//         acceptedTypes = "/(\.|\/)(pdf)$/i";
//     }else{
//         acceptedTypes = "/(\.|\/)(html)$/i";
//         ///(\.|\/)(gif|jpe?g|png)$/i
//     }
//     $('#fileupload').fileupload({
//         url: url,
//         dataType: 'json',
//         autoUpload: false,
//         acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
//         maxFileSize: 999000,
//         // Enable image resizing, except for Android and Opera,
//         // which actually support image resizing, but fail to
//         // send Blob objects via XHR requests:
//         disableImageResize: /Android(?!.*Chrome)|Opera/
//             .test(window.navigator.userAgent),
//         previewMaxWidth: 100,
//         previewMaxHeight: 100,
//         previewCrop: true
//     }).on('fileuploadadd', function (e, data) {
//         data.context = $('<div/>').appendTo('#files');
//         $.each(data.files, function (index, file) {
//             var node = $('<p/>')
//                     .append($('<span/>').text(file.name));
//             if (!index) {
//                 node
//                     .append('<br>')
//                     .append(uploadButton.clone(true).data(data));
//             }
//             node.appendTo(data.context);
//         });
//     }).on('fileuploadprocessalways', function (e, data) {
//         var index = data.index,
//             file = data.files[index],
//             node = $(data.context.children()[index]);
//         if (file.preview) {
//             node
//                 .prepend('<br>')
//                 .prepend(file.preview);
//         }
//         if (file.error) {
//             node
//                 .append('<br>')
//                 .append($('<span class="text-danger"/>').text(file.error));
//         }
//         if (index + 1 === data.files.length) {
//             data.context.find('button')
//                 .text('Upload')
//                 .prop('disabled', !!data.files.error);
//         }
//     }).on('fileuploadprogressall', function (e, data) {
//         var progress = parseInt(data.loaded / data.total * 100, 10);
//         $('#progress .progress-bar').css(
//             'width',
//             progress + '%'
//         );
//     }).on('fileuploaddone', function (e, data) {
//         $.each(data.result.files, function (index, file) {
//             if (file.url) {
//                 console.log(file);

                

//                 var link = $('<a>')
//                         .attr('target', '_blank')
//                         .prop('href', file.url);
//                 $(data.context.children()[index])
//                                 .wrap(link);

//                 moveFile(file.url,file.thumbnailUrl);
//             } else if (file.error) {
//                 var error = $('<span class="text-danger"/>').text(file.error);
//                 $(data.context.children()[index])
//                     .append('<br>')
//                     .append(error);
//             }
//         });
//     }).on('fileuploadfail', function (e, data) {
//         $.each(data.files, function (index) {
//             var error = $('<span class="text-danger"/>').text('File upload failed.');
//             $(data.context.children()[index])
//                 .append('<br>')
//                 .append(error);
//         });
//     }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');
// });
function htmlFileUpload(){
    console.log("htmlFileUpload()");

    var file_data = $('#htmlFileUpload').prop('files')[0];
    var form_data = new FormData();                  
    form_data.append('htmlFile', file_data);
    form_data.append('publicProjectId',publicProjectId);
    $.ajax({
        url: './actions/dashboard/knowledge/upload-temp-html', // point to server-side PHP script 
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
            processHTMLFileOnServer(data.temp, data.filename);
        }
     });
}
function processHTMLFileOnServer(temp, filename){
    console.log("processHTMLFileOnServer(" + temp + "," + filename + ")");
    var formData = {
        publicProjectId : publicProjectId,
        temp : temp, 
        filename : filename
    };
    $.ajax({
        type        : 'POST',
        url         : './actions/dashboard/knowledge/process-html',
        data        : formData,
        dataType    : 'json',
        encode      : true
    }).done(function(data) {
        console.log(data);
        if(!data['success']){
            error(data['message']);
        }else{
            loadCurrentDocuments();
        }
    });
}
var listOfSingleFiles = [];
function pdfFileUpload(){
    console.log("pdfFileUpload()");

    $("#pdfFileUpload").hide();
    $("#pdfFileUploadBtn").html("Uploading and Processing...");

    var file_data = $('#pdfFileUpload').prop('files')[0];
    var form_data = new FormData();                  
    form_data.append('htmlFile', file_data);
    form_data.append('publicProjectId',publicProjectId);
    $.ajax({
        url: './actions/dashboard/knowledge/upload-temp-html', // point to server-side PHP script 
        dataType: 'text',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function(data){
            console.log(data);
            data = JSON.parse(data);
            processPDFFileOnServer(data.temp, data.filename);

            //check for duplicate content and flag them
            console.log("Upload and Split is complete");
            console.log(listOfSingleFiles);
        }
     });
}
function processPDFFileOnServer(temp, filename){
    console.log("processPDFFileOnServer(" + temp + "," + filename + ")");
    var formData = {
        publicProjectId : publicProjectId,
        temp : temp, 
        filename : filename
    };
    $.ajax({
        type        : 'POST',
        url         : './actions/dashboard/knowledge/process-pdf',
        data        : formData,
        dataType    : 'json',
        encode      : true
    }).done(function(data) {
        console.log(data);
        if(!data['success']){
            error(data['message']);
        }else if(data.isSingleFiles){
            //loadCurrentDocuments();
            console.log("Split Data Files:" + data.splitPDFs.length.toString());
            for(var i = 0;i < data.splitPDFs.length;i++){
                loadSingleFiles(data.splitPDFs[i]);
            }
        }else{
            console.log("Split Data Files:" + data.splitPDFs.length.toString());
            for(var i = 0;i < data.splitPDFs.length;i++){
                findSubPDFFilesOnServer(data.splitPDFs[i]);
            }
        }
    });
}
function findSubPDFFilesOnServer(filePath){
    console.log("findSubPDFFilesOnServer(" + filePath + ")");

    var formData = {
        publicProjectId : publicProjectId,
        filePath : filePath
    };
    console.log(formData);
    $.ajax({
        type        : 'POST',
        url         : './actions/dashboard/knowledge/process-split-pdf',
        data        : formData,
        dataType    : 'json',
        encode      : true
    }).done(function(data) {
        console.log(data);
        if(!data['success']){
            error(data['message']);
        }else if(data.isSingleFiles){
            //loadCurrentDocuments();
            console.log("Split Data Files:" + data.splitPDFs.length.toString());
            for(var i = 0;i < data.splitPDFs.length;i++){
                loadSingleFiles(data.splitPDFs[i]);
            }
        }else{
            console.log("Split Data Files:" + data.splitPDFs.length.toString());
            for(var i = 0;i < data.splitPDFs.length;i++){
                findSubPDFFilesOnServer(data.splitPDFs[i]);
            }
        }
    });
}
function loadSingleFiles(singlePDFFiles){
    console.log("loadSingleFiles()");
    console.log(singlePDFFiles);

    

    listOfSingleFiles.push(singlePDFFiles);
}