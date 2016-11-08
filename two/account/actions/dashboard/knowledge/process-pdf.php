<?php
	session_start();

	include_once('../../../../../include/common_rest_functions.php');
	include_once('../../../../../include/secret.php');

	$email = $_SESSION['two_watson_email'];

	if($email == ""){
		die(error("Error: Please log in. Refresh the page."));
	}
	
	$publicProjectId = pickup('publicProjectId');
	if($publicProjectId == ""){
		die(error("Error: Unknown public project id."));
	}

	$temp = pickup('temp');
	if($temp == ""){
		die(error("Error: Unknown temp location. [" . $temp . "]"));
	}

	$filename = pickup('filename');
	if($filename == ""){
		die(error("Error: File name cannot be blank."));
	}

	$data = array();

	//Connect
	$con = mysqli_connect("localhost",$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME);

	//Look up project information
	$result = mysqli_query($con, "SELECT * FROM projects WHERE publicProjectId='$publicProjectId'") or die(error("Error: Unable to load projects"));
	if(mysqli_num_rows($result) == 0){
		die(error("Error: Invalid project"));
	}

	$projectId = -1;
	while($row = mysqli_fetch_array($result)){
		$projectId = $row['id'];

	}
	if($projectId == -1){
		die(error("Error: Unknown project"));
	}

	//Look up users
	$userId = -1;
	$result = mysqli_query($con, "SELECT * FROM userinfo WHERE email='$email'") or die(error("Error: Issues looking up user information"));
	while($row = mysqli_fetch_array($result)){
		$userId = $row['id'];
	}

	if($userId == -1){
		die(error("Error: Issues finding user ifnormation."));
	}

	//Verify project access
	$result = mysqli_query($con, "SELECT * FROM projectAccess WHERE projectId='$projectId' AND userId='$userId'") or die(error("Error: Looking up the user"));
	if(mysqli_num_rows($result) == 0){
		die(error("Error: Missing project access"));
	}

	//---------- PDF Related Things ---------------
	//PDF Library
	require_once('../../../../../vendor/autoload.php');
	require('../../../../../vendor/setasign/fpdf/fpdf.php');
	require('../../../../../vendor/setasign/fpdi/fpdi.php');
	require('./lib/PDFMerger.php');

	use Smalot\PdfParser;

	use Xthiago\PDFVersionConverter\Guesser\RegexGuesser;
	use Symfony\Component\Filesystem\Filesystem,
    Xthiago\PDFVersionConverter\Converter\GhostscriptConverterCommand,
    Xthiago\PDFVersionConverter\Converter\GhostscriptConverter;

    $uploadedFilePath = realpath('../../../server/temp/' . $temp . '/' . $filename);

    $guesser = new RegexGuesser();
	$pdfVersion = $guesser->guess($uploadedFilePath);

	if($pdfVersion > '1.4'){
		die(error("Error: Unsupported PDF Version. You are using PDF version higher than 1.4.\nSolution:\ngs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile=new-pdf.pdf input.pdf"));
	}

	//Get PDF Page Count
	$pdf = new FPDI();
	$pageCount = $pdf->setSourceFile($uploadedFilePath);

	//Split PDF
	$exceptions = array();
	$splitPDFs = array();
	$isSingleFiles = false;
	if($pageCount > 8){
		if(fmod(($pageCount),2) == 0){ //even
			$evenPageCount = $pageCount;
		}else{//odd
			$evenPageCount = $pageCount-1;
		}


		//656/2
		$halfWayPoint = $evenPageCount / 2;
		$quarterOfPages = $halfWayPoint / 2;

		$section1 = "1-" . strval($quarterOfPages);
		$section2 = strval($quarterOfPages+1) . '-' . strval($halfWayPoint);
		$section3 = strval($halfWayPoint+1) . '-' . strval($halfWayPoint + $quarterOfPages);
		$section4 = strval($halfWayPoint + $quarterOfPages) . '-' . strval($pageCount);
		
		try {
			array_push($splitPDFs, ($uploadedFilePath . "_section1.pdf"));

			$pdf = new PDFMerger;
			$pdf->addPDF($uploadedFilePath, $section1);
			$pdf->merge("file", ($uploadedFilePath . "_section1.pdf"));
			
		}catch(Exception $e) {
			array_push($exceptions, $e);
		}

		try {
			array_push($splitPDFs, ($uploadedFilePath . "_section2.pdf"));

			$pdf = new PDFMerger;
			$pdf->addPDF($uploadedFilePath, $section2);
			$pdf->merge("file", ($uploadedFilePath . "_section2.pdf"));
		}catch(Exception $e) {
			array_push($exceptions, $e);
		}

		try {
			array_push($splitPDFs, ($uploadedFilePath . "_section3.pdf"));

			$pdf = new PDFMerger;
			$pdf->addPDF($uploadedFilePath, $section3);
			$pdf->merge("file", ($uploadedFilePath . "_section3.pdf"));	
		}catch(Exception $e) {
			array_push($exceptions, $e);
		}

		try {
			array_push($splitPDFs, ($uploadedFilePath . "_section4.pdf"));

			$pdf = new PDFMerger;
			$pdf->addPDF($uploadedFilePath, $section4);
			$pdf->merge("file", ($uploadedFilePath . "_section4.pdf"));
		}catch(Exception $e) {
			array_push($exceptions, $e);
		}
	}else if($pageCount <= 8 && $pageCount > 1){
		for($i = 0;$i < $pageCount;$i++){
			try {
				array_push($splitPDFs, ($uploadedFilePath . "_" . strval($i) . ".pdf"));

				$pdf = new PDFMerger;
				$pdf->addPDF($uploadedFilePath, (strval($i) . '-' . strval($i+1)));
				$pdf->merge("file", ($uploadedFilePath . "_" . strval($i) . ".pdf"));
			}catch(Exception $e) {
				array_push($exceptions, $e);
			}
		}

		$isSingleFiles = true;
	}else{
		array_push($splitPDFs, $uploadedFilePath);
		$isSingleFiles = true;
	}
	$data['isSingleFiles'] = $isSingleFiles;
	$data['temp'] = $temp;
	$data['pageCount'] = $pageCount;
	$data['exceptions'] = $exceptions;
	$data['splitPDFs'] = $splitPDFs;
	$data['success'] = true;

	echo json_encode($data);
?>