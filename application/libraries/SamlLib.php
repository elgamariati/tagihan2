<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


//require_once(APPPATH.'/third_party/ZipArchive.php');

class SamlLib {
	public function __construct(){
		require_once APPPATH . "/third_party/SamlConsumer/loader.php";
	}
}