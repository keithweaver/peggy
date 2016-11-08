<form enctype="multipart/form-data" method="POST" action="./convert.php">
	<label>Convert PDF To:</label>
	<select name="type">
		<option>answer_units</option>
		<option>normalized_html</option>
		<option>normalized_text</option>
	</select><br/>
	<input type="file" name="bookFile"/><br/>
	<button type="submit">Upload</button>
</form>

<br/>
<br/>
<br/>
<form enctype="multipart/form-data" method="POST" action="./test-pdf-page-numbers">
	<input type="file" name="bookFile"/><br/>
	<button type="submit">Upload</button>
</form>