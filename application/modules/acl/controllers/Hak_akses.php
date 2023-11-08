<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Hak_akses extends MY_Controller
{
	private $modul = 'hak_akses';
	private $modul_url = 'acl/hak_akses';

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->model('Hak_akses_m', 'model');
	}

	public function index()
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$data = array(
			'modul' => $this->modul_url,
			'user_role' => $this->model->get_all()->result(),
		);
		if ($this->input->is_ajax_request()) {
		} else {
			$this->layout->render('Hak_akses_v', $data);
		}
	}

	function ajax_list()
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$list = $this->model->custom_get_dtb_modul();
		$key = $this->input->post('key');
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $item) {
			$ha_list = array(
				"BACA" => NULL,
				"TULIS" => NULL,
				"HAPUS" => NULL,
				"UBAH" => NULL
			);

			$item = $this->model->clean($item);

			$where = array(
				'role' => $key,
				'modul' => $item->modul,
			);
			$ha = $this->model->get_ha($where);
			foreach ($ha->result() as $h) {
				$ha_list[$h->hak] = "checked='checked'";
			}

			$no++;
			$row = array();
			$row[] = $key;
			$row[] = $item->modul ? $item->modul : "-";
			$row[] = "
			<input type='checkbox' id='check_ha' name='BACA' value='" . $item->modul . "'  " . $ha_list['BACA'] . " onChange='ha_change(this)'>&nbsp;Baca&nbsp;
			<input type='checkbox' id='check_ha' name='TULIS' value='" . $item->modul . "' " . $ha_list['TULIS'] . "  onChange='ha_change(this)'>&nbsp;Tulis&nbsp; 
			<input type='checkbox' id='check_ha' name='HAPUS' value='" . $item->modul . "' " . $ha_list['HAPUS'] . "  onChange='ha_change(this)'>&nbsp;Hapus&nbsp;
			<input type='checkbox' id='check_ha' name='UBAH' value='" . $item->modul . "' " . $ha_list['UBAH'] . "  onChange='ha_change(this)'>&nbsp;Ubah&nbsp; 
			";
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->model->custom_count_all_modul(),
			"recordsFiltered" => $this->model->custom_count_filtered_modul(),
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
		$this->form_validation->set_rules('role', 'Role', 'required|trim');
		$this->form_validation->set_rules('modul', 'Modul', 'required|trim');
		$this->form_validation->set_rules('hak', 'Hak', 'required|trim');
		if ($this->form_validation->run() == FALSE) {
			$validasi = [
				'status' => 'validasi',
				'role' => form_error('role'),
				'modul' => form_error('modul'),
				'hak' => form_error('hak'),
				'keterangan' => 'Terjadi kesalahan pada data yang dikirim'
			];
		} else {
			$validasi = [
				'status' => "Berhasil",
				'keterangan' => 'Berhasil!'
			];
			$object = array(
				'role' => $this->input->post('role', true),
				'modul' => $this->input->post('modul', true),
				'hak' => $this->input->post('hak', true),
			);

			$insert = $this->model->insert($object);

			if (!$insert['status']) {
				$validasi = [
					'status' => 'validasi',
					'keterangan' => $insert['ket'],
				];
			} else {
				$validasi['keterangan'] = $insert['ket'];
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
