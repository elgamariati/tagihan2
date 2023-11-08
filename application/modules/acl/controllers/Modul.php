<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Modul extends MY_Controller
{

	private $modul = 'modul';
	private $modul_url = 'acl/modul';
	private $column = 'modul';
	private $nama_modul = 'Modul';

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->model('Modul_m', 'model');
	}

	public function index()
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$data = array(
			'modul' => $this->modul_url,
			'column' => $this->column,
			'nama_modul' => $this->nama_modul,
		);
		if ($this->input->is_ajax_request()) {
		} else {
			$this->layout->render('Modul_v', $data);
		}
	}

	function ajax_list()
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$list = $this->model->custom_get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $item) {
			$item = $this->model->clean($item);
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $item->modul ? $item->modul : "-";
			$row[] = '
			<a href="#" class="btn" title="Ubah" onclick="tambah(\'' . $item->modul . '\')"><span class="fa fa-pencil" style="color:#1bb399"></span></a>
			<a href="#" class="btn" title="Hapus" onclick="hapus(\'' . $item->modul . '\')"><span style="color:#e33244" class="fa fa-trash"></span></a>
			';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->model->custom_count_all(),
			"recordsFiltered" => $this->model->custom_count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	function action()
	{
		$aksi_modul = 'ubah';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->column, 'Nama', 'required|trim');

		if ($this->form_validation->run() == FALSE) {
			$validasi = [
				'status' => 'validasi',
				$this->column => form_error($this->column),
				'keterangan' => 'Cek isian'
			];
		} else {
			$validasi = [
				'status' => "Berhasil",
				'keterangan' => 'Aksi berhasil'
			];
			$object = array(
				$this->column => $this->input->post($this->column),
			);

			$object = $this->model->clean($object);

			if ($this->input->post('key') !== null) {
				$insert = $this->model->update($object, $this->input->post('key'));
			} else {
				$insert = $this->model->insert($object);
			}

			if (!$insert['status']) {
				$validasi = [
					'status' => 'validasi',
					'keterangan' => $insert['ket'],
				];
			}
		}
		echo json_encode($validasi);
	}

	function hapus()
	{
		$aksi_modul = 'hapus';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$validasi = [
			'status' => 'validasi',
		];
		if ($this->input->post('key') !== null) {
			$delete = $this->model->delete($this->input->post('key'));
			if ($delete) {
				$validasi = [
					'status' => "Berhasil",
					'keterangan' => $delete['ket']
				];
			} else {
				$validasi = [
					'status' => 'validasi',
					'keterangan' => $delete['ket'],
				];
			}
			echo json_encode($validasi);
		}
	}
}
