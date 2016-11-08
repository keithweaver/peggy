<html>
<head>

</head>
<body>
<span id="errorLabel"></span>
<br/>
<input type="file" id="fullBook"><br/>
<br/>
<input type="text" id="bookTitle" placeholder="Title" value="What to Expect when you're expecting"><br/>
<br/>
<span id="chapter-list-content"></span>
<br/>
<input type="text" id="new-chapter-name" placeholder="Jack Finds Love"><br/>
<input type="text" id="new-chapter-range" placeholder="1-12"><br/>
<button onclick="return addChapter();">Add Chapter</button><br/>
<br/>
<input type="submit" value="Upload" onclick="return uploadCollection();">


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="./js/add-collection/ui.js"></script>
<script src="./js/add-collection/upload.js"></script>
<script src="./js/add-collection/test.js"></script>
</body>
</html>