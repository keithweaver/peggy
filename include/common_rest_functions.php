<?php
	function grab($box){
		$data = $_GET[$box];
		$data = trim($data);
		$data = addslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	function pickup($box){
		//conLog($box);
		$data = $_POST[$box];
		$data = trim($data);
		$data = addslashes($data);
		$data = htmlspecialchars($data);
		
		return $data;
	}
	function error($msg){
		$data = array();
		$data['succes'] = false;
		$data['msg'] = $msg;
		return json_encode($data);
	}
	/*
	Debug - array of data so $debug['test'] = 'val1';
	*/
	function errorForDebugging($msg,$debug){
		$data = array();
		$data['debug'] = $debug;
		$data['succes'] = false;
		$data['msg'] = $msg;
		return json_encode($data);
	}

	function generateRandomStr($size){
		$l = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
		$s = "";
		for($i = 0;$i < $size;$i++){
			$ran = rand(0, 25);
			$s .= $l[$ran];
		}
		return $s;
	}
	function generateNewAPIKey($DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME){
		$con = mysqli_connect("localhost",$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME) or die("Error: Unable to connect.");
		$s = "";
		$found = false;
		while($found != true){
			$t = generateRandomStr(rand(25,45));
			$result = mysqli_query($con, "SELECT * FROM projects WHERE apiKey='$t'") or die("Error in app 2");
			if(mysqli_num_rows($result) == 0){
				$s = $t;
				return $s;
			}
		}
		return $s;
	}
	function generatePublicId($DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME){
		$con = mysqli_connect("localhost",$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME) or die("Error: Unable to connect.");
		$s = "";
		$found = false;
		while($found != true){
			$t = "prj_" . generateRandomStr(rand(5,13));
			$result = mysqli_query($con, "SELECT * FROM projects WHERE publicProjectId='$t'") or die("Error in app 2");
			if(mysqli_num_rows($result) == 0){
				$s = $t;
				return $s;
			}
		}
		return $s;
	}
	function generateUserPublicId($DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME){
		$con = mysqli_connect("localhost",$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME) or die("Error: Unable to connect.");
		$s = "";
		$found = false;
		while($found != true){
			$t = "usr_" . generateRandomStr(rand(5,13));
			$result = mysqli_query($con, "SELECT * FROM projects WHERE publicProjectId='$t'") or die("Error in app 2");
			if(mysqli_num_rows($result) == 0){
				$s = $t;
				return $s;
			}
		}
		return $s;
	}
	function encrypt($password, $hash){
		$encrypted = "";
		$encrypted = crypt($password, $hash);
	    return $encrypted;
	}
	function decryptAndCheck($password, $encrypted, $hash){//data being encrupted
		$encrypted2 = crypt($password, $hash);
		return ($encrypted2 == $encrypted);
	}
	function generateTempFileCode($DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME){
		$con = mysqli_connect("localhost",$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME) or die("Error: Unable to connect.");
		$s = "";
		$found = false;
		while($found != true){
			$t = "file_" . generateRandomStr(rand(12,140));
			$result = mysqli_query($con, "SELECT * FROM tempFiles WHERE tempCode='$t'") or die("Error in app 2");
			if(mysqli_num_rows($result) == 0){
				$s = $t;
				return $s;
			}
		}
		return $s;
	}

?>