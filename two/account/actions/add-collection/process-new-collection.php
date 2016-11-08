<?php
	/*
	Are You Pregnant?
	15-17
	
	Your Pregnancy Profile
	18-74
	
	Your Pregnancy Lifestyle
	75-96
	
	Nine Months of Eating Well
	97-119
	
	*/
	session_start();

	include_once('../../../../include/common_rest_functions.php');
	include_once('../../../../include/secret.php');

	$email = $_SESSION['two_watson_email'];

	if($email == ""){
		die(error("Error: Please log in. Refresh the page."));
	}
	
	

	$data = array();

	//Connect
	$con = mysqli_connect("localhost",$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME);

	
	//Look up users
	$userId = -1;
	$result = mysqli_query($con, "SELECT * FROM userinfo WHERE email='$email'") or die(error("Error: Issues looking up user information"));
	while($row = mysqli_fetch_array($result)){
		$userId = $row['id'];
	}

	if($userId == -1){
		die(error("Error: Issues finding user ifnormation."));
	}

	
	$temp = pickup('temp');
	if($temp == ""){
		die(error("Error: Temp is blank"));
	}
	$filename = pickup('filename');
	if($filename == ""){
		die(error("Error: File name is blank"));
	}
	
	//---------- PDF Related Things ---------------
	//PDF Library
	require_once('../../../../vendor/autoload.php');
	require('../../../../vendor/setasign/fpdf/fpdf.php');
	require('../../../../vendor/setasign/fpdi/fpdi.php');
	require('../dashboard/knowledge/lib/PDFMerger.php');

	use Smalot\PdfParser;

	use Xthiago\PDFVersionConverter\Guesser\RegexGuesser;
	use Symfony\Component\Filesystem\Filesystem,
    Xthiago\PDFVersionConverter\Converter\GhostscriptConverterCommand,
    Xthiago\PDFVersionConverter\Converter\GhostscriptConverter;

	$uploadedFilePath = realpath('../../server/temp/' . $temp . '/' . $filename);    

    $guesser = new RegexGuesser();
	$pdfVersion = $guesser->guess($uploadedFilePath);

	if($pdfVersion > '1.4'){
		die(error("Error: Unsupported PDF Version. You are using PDF version higher than 1.4.\nSolution:\ngs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile=new-pdf.pdf input.pdf"));
	}

	//Get PDF Page Count
	$pdf = new FPDI();
	$pageCount = $pdf->setSourceFile($uploadedFilePath);


	$chaptersStr = $_POST['chapters'];
	$chapters = json_decode($chaptersStr);

	$title = pickup('title');

	$exceptions = array();
	$splitPDFs = array();
	
	foreach ($chapters as $chapter) {

		$filename = $chapter->name;
		$filename = str_replace(" ", "", $filename);
		$filename = strtolower($filename);
		$filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
		$data['chaptName'] = $chapter->name;
		$data['filename'] = $filename;
		$data['chaptRange'] = $chapter->range;
		
		try {
			array_push($splitPDFs, ($filename . ".pdf"));

			$pdf = new PDFMerger;
			$pdf->addPDF($uploadedFilePath, $chapter->range);
			$pdf->merge("file", ('../../server/temp/' . $temp . '/' . $filename . ".pdf"));
			
		}catch(Exception $e) {
			array_push($exceptions, $e);
		}
	}

	$data['title'] = $title;
	$data['exceptions'] = $exceptions;
	$data['splitPDFs'] = $splitPDFs;
	$data['chapters'] = $chapters;
	$data['success'] = true;
	$data['message'] = "";
	$data['temp'] = $temp;

	echo json_encode($data);
?>