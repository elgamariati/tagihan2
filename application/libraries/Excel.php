<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . "/third_party/PHPExcel.php";

//require_once(APPPATH.'/third_party/ZipArchive.php');

class Excel extends PHPExcel {

    public function __construct() {
        parent::__construct();
    }

    public static function saveMacros($obj,$fileName,$macrosPath){
        // Write Excel 2007 document       
        $objWriter = new PHPExcel_Writer_Excel2007($obj);

        // Generate a temp file and save Excel document to temp file
        $tmpfname = tempnam("/tmp", "FOO");
        $objWriter->save($tmpfname);
        $zip = new ZipArchive; // Start Zip archive
        $zip->open($tmpfname); // Open our stored Excel document
        // Add our VBA script to the Excel Zip Document
        $zip->addFile($macrosPath, 'xl/vbaProject.bin');

        // Get the contents of our Content Types xml document from our Excel Zip Document
        $ContentTypes = $zip->getFromName('[Content_Types].xml');

        // Generate an XML object with PHP's DOM functions http://us.php.net/manual/en/book.dom.php
        $ContentTypesXML = new DomDocument();
        $success = (int) @$ContentTypesXML->loadXML($ContentTypes);
        $Types = $ContentTypesXML->getElementsByTagName('Types')->item(0);

        // Add Override node to our Content Types with the file location of our VBA script
        $Override = $ContentTypesXML->createElement("Override");
        $Override = $Types->appendChild($Override);
        $Override->setAttribute('PartName', '/xl/vbaProject.bin');
        $Override->setAttribute('ContentType', 'application/vnd.ms-office.vbaProject');

        // Find out workbook and update the content type to be xlsm instead of xlsx
        foreach ($Types->getElementsByTagName('Override') as $Override) {
            if ($Override->hasAttribute('PartName') && $Override->getAttribute('PartName') == "/xl/workbook.xml") {
                $Override->setAttribute('ContentType', 'application/vnd.ms-excel.sheet.macroEnabled.main+xml');
            }
        }
        // Save content type back to our Excel Zip Document
        $zip->addFromString('[Content_Types].xml', $ContentTypesXML->saveXML());

        // Get our workbook relationship xml document
        $Workbook = $zip->getFromName('xl/_rels/workbook.xml.rels');

        // Generate an XML object with PHP's DOM functions http://us.php.net/manual/en/book.dom.php
        $WorkbookXML = new DomDocument();
        $success = (int) @$WorkbookXML->loadXML($Workbook);
        $Rltns = $WorkbookXML->getElementsByTagName('Relationships')->item(0);

        // Add Relationship that points to our VBA script
        $Rltn = $WorkbookXML->createElement("Relationship");
        $Rltn = $Rltns->appendChild($Rltn);
        $Rltn->setAttribute('Id', 'rId99'); // Arbitraty Relationship ID NOTE may need a higher number based on the number of worksheets and other elements in your Excel document, update would be to calculate the number of children inside the Relationships XML Node
        $Rltn->setAttribute('Type', 'http://schemas.microsoft.com/office/2006/relationships/vbaProject');
        $Rltn->setAttribute('Target', 'vbaProject.bin'); // Our VBA script
        // Save our updated XML to our Workbook relationship xml
        $zip->addFromString('xl/_rels/workbook.xml.rels', $WorkbookXML->saveXML());

        $zip->close(); // Close the zip file.
        // Output xlsm headers
        header('Content-Type: application/vnd.ms-excel.sheet.macroEnabled.main+xml'); // xlsm
        //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); // xlsx
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsm"');
        header('Cache-Control: max-age=0');

        $handle = fopen($tmpfname, "r");
        $BUFF = fread($handle, filesize($tmpfname));
        fclose($handle);
        unset($handle);

        echo $BUFF;

        unlink($tmpfname);
    }

    public function saveWithMacros($fileName, $macrosPath) {
        // Write Excel 2007 document       
        $objWriter = new PHPExcel_Writer_Excel2007($this);

        // Generate a temp file and save Excel document to temp file
        $tmpfname = tempnam("/tmp", "FOO");
        $objWriter->save($tmpfname);
        $zip = new ZipArchive; // Start Zip archive
        $zip->open($tmpfname); // Open our stored Excel document
        // Add our VBA script to the Excel Zip Document
        $zip->addFile($macrosPath, 'xl/vbaProject.bin');

        // Get the contents of our Content Types xml document from our Excel Zip Document
        $ContentTypes = $zip->getFromName('[Content_Types].xml');

        // Generate an XML object with PHP's DOM functions http://us.php.net/manual/en/book.dom.php
        $ContentTypesXML = new DomDocument();
        $success = (int) @$ContentTypesXML->loadXML($ContentTypes);
        $Types = $ContentTypesXML->getElementsByTagName('Types')->item(0);

        // Add Override node to our Content Types with the file location of our VBA script
        $Override = $ContentTypesXML->createElement("Override");
        $Override = $Types->appendChild($Override);
        $Override->setAttribute('PartName', '/xl/vbaProject.bin');
        $Override->setAttribute('ContentType', 'application/vnd.ms-office.vbaProject');

        // Find out workbook and update the content type to be xlsm instead of xlsx
        foreach ($Types->getElementsByTagName('Override') as $Override) {
            if ($Override->hasAttribute('PartName') && $Override->getAttribute('PartName') == "/xl/workbook.xml") {
                $Override->setAttribute('ContentType', 'application/vnd.ms-excel.sheet.macroEnabled.main+xml');
            }
        }
        // Save content type back to our Excel Zip Document
        $zip->addFromString('[Content_Types].xml', $ContentTypesXML->saveXML());

        // Get our workbook relationship xml document
        $Workbook = $zip->getFromName('xl/_rels/workbook.xml.rels');

        // Generate an XML object with PHP's DOM functions http://us.php.net/manual/en/book.dom.php
        $WorkbookXML = new DomDocument();
        $success = (int) @$WorkbookXML->loadXML($Workbook);
        $Rltns = $WorkbookXML->getElementsByTagName('Relationships')->item(0);

        // Add Relationship that points to our VBA script
        $Rltn = $WorkbookXML->createElement("Relationship");
        $Rltn = $Rltns->appendChild($Rltn);
        $Rltn->setAttribute('Id', 'rId99'); // Arbitraty Relationship ID NOTE may need a higher number based on the number of worksheets and other elements in your Excel document, update would be to calculate the number of children inside the Relationships XML Node
        $Rltn->setAttribute('Type', 'http://schemas.microsoft.com/office/2006/relationships/vbaProject');
        $Rltn->setAttribute('Target', 'vbaProject.bin'); // Our VBA script
        // Save our updated XML to our Workbook relationship xml
        $zip->addFromString('xl/_rels/workbook.xml.rels', $WorkbookXML->saveXML());

        $zip->close(); // Close the zip file.
        // Output xlsm headers
        header('Content-Type: application/vnd.ms-excel.sheet.macroEnabled.main+xml'); // xlsm
        //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); // xlsx
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsm"');
        header('Cache-Control: max-age=0');

        $handle = fopen($tmpfname, "r");
        $BUFF = fread($handle, filesize($tmpfname));
        fclose($handle);
        unset($handle);

        echo $BUFF;

        unlink($tmpfname);
    }

}
