<?php
	//Used to Connect to DB
	include_once('../include/secret.php');
	$con = mysqli_connect("localhost",$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME);

	mysqli_query($con, "CREATE TABLE `userinfo` ( `id` int(11) NOT NULL, `email` varchar(250) DEFAULT NULL, `password` varchar(250) DEFAULT NULL, `hash` varchar(250) DEFAULT NULL, `publicUserId` varchar(250) DEFAULT NULL, `verified` int(11) NOT NULL DEFAULT '0', `created` datetime DEFAULT NULL)") or die("Error 001");
?>