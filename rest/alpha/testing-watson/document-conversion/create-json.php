<?php
	$con = mysqli_connect("localhost","root","root","marketPlaceDB");

	echo '{';

	$i = 0;
	
	$result = mysqli_query($con, "SELECT * FROM knowledge") or die("ERJNOADNAOISDNOIASNDOIANDINAOISDNOA");
	while($row = mysqli_fetch_array($result)){
		if($i > 0){
			echo ',';
		}

		// echo '"add' . strval($i) . '" : {';
		echo '"add" : {';
			echo '"doc" : {';
				echo '"id" : ' . ($i+1) . ',';
				echo '"author" : "heidi murkoff",';
				echo '"bibliography" : "",';
				echo '"body" : "' . htmlspecialchars(addslashes(strtolower($row['content']))) . '",';
				echo '"title" : "' . htmlspecialchars(addslashes(strtolower($row['title']))) . '"';
				
			echo '}';
		echo '}';

		$i++;
		
		// if($i == 1){
		// 	break;
		// }
	}

	echo ',';
	echo '"commit" : { }';
	echo '}';
?>