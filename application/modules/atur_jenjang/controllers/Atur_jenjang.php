<?php

class Atur_jenjang extends MY_Controller
{
	private $modul = 'atur_jenjang';
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
		$this->load->model('Atur_jenjang_m', 'db');
	}

	public function index()
	{
		$aksi_modul = 'tulis';
                //var_dump($this->modul);var_dump($this->role);die();
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		if ($this->input->post()) {
			$jenjang = $this->input->post('jenjang');

			$status = $this->db->set_jenjang($jenjang);

			if ($status == 1) {
				$validasi = [
					'status' => 1,
					'jenjang' => $jenjang,
				];
			} else {
				$validasi = [
					'status' => 0,
					'jenjang' => 'terjadi kesalahan saat memodifikasi data'
				];
			}

			echo json_encode($validasi);
		} else {
			$pkg['sess'] = $this->session->userdata()['user'];
			$this->layout->render('Atur_jenjang_v', $pkg);
		}
	}
}
