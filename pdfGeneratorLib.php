<?php

	// Including pdf generator library
	require_once("." . base_path() . "sites/all/libraries/tcpdf/config/lang/eng.php");
	require_once("." . base_path() . "sites/all/libraries/tcpdf/tcpdf.php");
	
	class MYPDF extends TCPDF {
		
		function __construct() {
       		parent::__construct();
   		}
	
		/*
	    //Page header
	    public function Header() {
	        // Logo
	        $image_file = K_PATH_IMAGES.'logo_example.jpg';
	        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
	        // Set font
	        $this->SetFont('helvetica', 'B', 20);
	        // Title
	        $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
	    }
	    */
	
	    // Page footer
	    public function Footer() {
	        // Position at 15 mm from bottom
	        $this->SetY(-15);
	        // Set font
	        $this->SetFont('helvetica', 'I', 8);
	        // Page number
	        $txt = "RACC, Parkshot, Richmond, Surrey, TW9 2RE. Telephone: 020 8891 5907 - Fax: 020 8332 6560";
	        $this->Cell(0, 10, $txt, 0, false, 'L', 0, '', 0, false, 'T', 'M');
	        //$txt = "Telephone: 020 8891 5907 - Fax: 020 8332 6560";
	        //$this->Cell(0, 10, $txt, 0, false, 'L', 0, '', 0, false, 'T', 'M');
 			//$txt = "Course Enquiry Line: 020 8843 7921 - Email: info@racc.ac.uk";
	        //$this->Cell(0, 10, $txt, 0, false, 'L', 0, '', 0, false, 'T', 'M');	        
	        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
	    }
	    
	/*
            // Page footer
	    public function Footer() {
	        // Position at 15 mm from bottom
	        $this->SetY(-15);
	        // Set font
	        $this->SetFont('helvetica', 'I', 8);
	        // Page number
	        //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	        $txt = "RACC, Parkshot,\nRichmond, Surrey, TW9 2RE\nTelephone: 020 8891 5907\nCourse Enquiry Line: 020 8843 7921\nEmail: info@racc.ac.uk\nFax: 020 8332 6560";
	        $this->MultiCell(0, 10, $txt, 1, 'L', 1, 0, '', '', true);
			$txt = "Parkshot Centre, Richmond\nParkshot,Richmond TW9 2RE";		        
			$this->MultiCell(0, 10, $txt, 1, 'L', 0, 1, '', '', true);
			$txt = "Clifden Centre, Twickenham\nClifden Road, Twickenham, TW1 4LT";		        
			$this->MultiCell(0, 10, $txt, 1, 'L', 0, 1, '', '', true);
			$this->MultiCell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 1, 'R', 0, 1, '', '', true);
	        
	    }
	*/	    
	
		// ---------------------------------------------------------
		//	Functions of the library
		// ---------------------------------------------------------	
	    public function PrintData($title, $data, $addPage=false, $mode=false) {
			
	    	//global $pdf;
	    	
	        if ($addPage){
	        	$this->AddPage();	
	        }
	
	        // print title
	        $this->printTitle($title);
	
	        // print chapter body
	        $this->printText($data, $mode);
	    }
	
	    private function printTitle($title) {
	    	
	    	//global $pdf;
	    	
	        $this->SetFont('helvetica', 'BI', 14);
	        //$this->SetFillColor(200, 220, 255);
	        $this->SetTextColor(50, 50, 50);
	        $this->Cell(180, 0, $title, 0, 1, '', false);
	        $this->Ln();
	    }
	
	    private function printText($data, $mode=false) {
	    	
	    	//global $pdf;
	    	
	    	// set font
	        $this->SetFont('dejavusans', '', 10);
	        $this->SetTextColor(50, 50, 50);
	        // print content
	        if ($mode) {
	            // ------ HTML MODE ------
	            $this->writeHTML($data, true, false, true, false, 'J');
	        } else {
	            // ------ TEXT MODE ------
	            $this->Write(0, $data, '', 0, 'J', true, 0, false, true, 0);
	        }
	        $this->Ln();
	    }
	}
	
?>