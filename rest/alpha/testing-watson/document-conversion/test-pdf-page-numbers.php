<?php
	//https://github.com/delphian/drupal-convert-file/wiki/Installing-ImageMagick-on-Mac-OSX-for-PHP-and-MAMP
	//http://apple.stackexchange.com/questions/115030/install-ghostscript-on-os-x-10-9-using-dmg-file
	require_once('../../../../vendor/autoload.php');
	// require_once('../../../../vendor/zendframework/zendpdf/library/ZendPdf/PdfDocument.php'); 
	// use ZendPdf\PdfDocument;
	require('../../../../vendor/setasign/fpdf/fpdf.php');
	require('../../../../vendor/setasign/fpdi/fpdi.php');
	require('./lib/PDFMerger.php');
	//require('../../../../vendor/skurrier/pdflib/src/PDFLib.php');
	//require('../../../../vendor/smalot/pdfparser/src/Sfpdi.php');
	// require('../../../../vendor/calcinai/php-imagick/src/Imagick.php');

	//use PDFLib\PDFLib;
	use Smalot\PdfParser;

	//use calcinai\Imagick;

	//Converter not working
	use Xthiago\PDFVersionConverter\Guesser\RegexGuesser;
	use Symfony\Component\Filesystem\Filesystem,
    Xthiago\PDFVersionConverter\Converter\GhostscriptConverterCommand,
    Xthiago\PDFVersionConverter\Converter\GhostscriptConverter;


	//http://stackoverflow.com/questions/3790191/php-error-class-imagick-not-found
	$data = array();
	
	//Set Target Directory for file upload
	$target_directory = './';

	//Upload PDF File
	$filename = str_replace(" ","",basename( $_FILES['bookFile']['name']));
	$target_path = $target_directory . $filename;
	if(move_uploaded_file($_FILES['bookFile']['tmp_name'], $target_path)) {
	} else{
	    die("An error has occurred: With file upload");
	}

	$uploadedFilePath = realpath($target_directory . $filename);

	

	

	$guesser = new RegexGuesser();
	$pdfVersion = $guesser->guess($uploadedFilePath);

	// $command = new GhostscriptConverterCommand();
	// $filesystem = new Filesystem();

	if($pdfVersion > '1.4'){
		die("Unsupported version of PDF.");
		//Needs to convert the PDF to 1.4 for the FPDI and FPDF libs to use
		// $converter = new GhostscriptConverter($command, $filesystem);
		// $converter->convert($uploadedFilePath, '1.4');

		//Current solution:
		//http://apple.stackexchange.com/questions/115030/install-ghostscript-on-os-x-10-9-using-dmg-file
		//gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile=new-pdf1.5.pdf WhattoExpectWhenYoureExpecting5thEdition2016.pdf
		//gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile=new-pdf1.5.pdf Input.pdf
	}else{
		// echo '1.4 or lower version.';
	}

	//WORKING
	$pdf = new FPDI();
	$pageCount = $pdf->setSourceFile($uploadedFilePath);

	/*
	Example file:
	Returns 657 for pageCount but 644 plus cover, i, ii, iii, etc.
	*/
	// echo $pageCount;

	
	
	// include_once('./split-pdf.php');

	// // Create and check permissions on end directory!
	// split_pdf($filename, 'split/');
	$parser = new \Smalot\PdfParser\Parser();
	$pdf = $parser->parseFile($filename);

	// Retrieve all pages from the pdf file.
	$pages  = $pdf->getPages();
	
	$pageOfStartOfFirstChapter = 15;
	$pageNumber = 0;
	$currentChapter = 1;
	$i = 0;
	// foreach ($pages as $page) {
		// echo var_dump($page);
		// echo '<br/>';
		// echo '<br/>';
		// if($pageNumber >= $pageOfStartOfFirstChapter){
		// 	$pageContent = $page->getText();
		// 	if(strpos($pageContent, 'Chapter') !== false){
		// 		echo 'On Page ' . $pageNumber . '<br/><br/><br/>';
		// 		echo $pageContent;
		// 		echo '<br/><br/><br/>';
		// 		$currentChapter++;
		// 	}
		// }



		

		// $pageNumber++;

		// die("");
	// }
	// $details  = $pdf->getDetails();
	// foreach ($details as $property => $value) {
	// 	if (is_array($value)) {
	// 		$value = implode(', ', $value);
	// 	}
	// 	echo $property . ' => ' . $value . "<br/>";
	// }
	// echo '<br/>';
	// echo '---';
	// echo '<br/>';
	// $pages = $pdf->getPages();

	
	// //echo var_dump($pages[0]);
	// echo $pages[0]->getText();
	$pdf = new PDFMerger;
	$pdf->addPDF($filename,'10-20');
	$pdf->addPDF($filename,'30-40');
	$pdf->merge("file","newfile.pdf");

	// echo '<br/>';
	// echo '<br/>';
	// echo $i;
?>