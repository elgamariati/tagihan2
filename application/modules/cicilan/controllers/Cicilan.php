<?php

class Cicilan extends MY_Controller
{
	private $modul = 'cicilan';
	private $semester_ref = array(
		1 => "Ganjil",
		2 => "Genap",
		3 => "Antara"
	);
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('datetime_helper');
		$this->load->helper('rupiah_helper');
		$this->load->model('periode/Periode_m', 'periode');
		$this->load->model('simari/Simari_m', 'simari');
		$this->load->model('atur_jenjang/Atur_jenjang_m', 'atur_jenjang');
	}

	public function index()
	{
        $aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$this->layout->render('index');
	}
}
