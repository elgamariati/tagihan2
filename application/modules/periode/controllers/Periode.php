<?php

class Periode extends MY_Controller
{
	private $modul = 'periode';
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
		$this->load->model('Periode_m', 'database');
		$this->load->model('atur_rentang/Atur_rentang_m', 'atur_rentang');
	}

	public function index()
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$this->layout->render('Periode_v');
	}

	public function ajax_list($periode = null)
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$where = null;
		$list = $this->database->get_datatables($where);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $tagihan) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $tagihan->kode_periode;
			$row[] = $tagihan->nama_periode;
			if ($this->session->userdata('user')['periode'] == $tagihan->kode_periode) {
				$row[] = "Ya";
			} else {
				$row[] = "Tidak";
			}
			// $row[] = $tagihan->is_aktif;
			$row[] = '<a href="#" class="btn" title="Edit" onclick="edit_dialog(\'' . $tagihan->kode_periode . '\')"><span style="color:#1bb399" class="fa fa-pencil"></span></a>
					  <a href="#" class="btn" title="Hapus" onclick="hapus_dialog(\'' . $tagihan->kode_periode . '\')"><span style="color:#e33244" class="fa fa-trash"></span></a>';
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->database->count_all($where),
			"recordsFiltered" => $this->database->count_filtered($where),
			"data" => $data,
		);
		// output to json format
		echo json_encode($output);
	}

	public function periode_add()
	{
		$aksi_modul = 'tulis';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		if ($this->input->post()) {
			$model_input = $this->input->post();
			$this->load->library('form_validation');
			$rules = array(
				array(
					'field' => 'tahun',
					'label' => 'Tahun',
					'rules' => 'required|min_length[4]|max_length[4]'
				)
			);
			$this->form_validation->set_error_delimiters('<span>', '</span>');
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run()) {
				$insert_data['kode_periode'] = $model_input['tahun'] . $model_input['semester'];
				$insert_data['nama_periode'] = $model_input['tahun'] . "/" . $this->semester_ref[$model_input['semester']];
				$insert_data['is_aktif'] = "Tidak";

				//cek apakah kode periode tidak ada di table
				if ($this->database->exist($insert_data['kode_periode']) && $model_input["mode"] == "") {
					if ($this->database->insert($insert_data)) {
						// if ($insert_data['is_aktif'] == "Ya") {
						// $deactive = $this->_aktifasi_periode($insert_data['kode_periode']);
						// if ($deactive) {
						$this->sess_periode($insert_data['kode_periode']);

						// }
						// }

						$hasil["status"] = true;
						$hasil["message"] = "Periode baru berhasil ditambahkan";
					} else {
						$hasil["status"] = false;
						$hasil["message"] = "Periode baru gagal ditambahkan";
					}
				} else if (!$this->database->exist($insert_data['kode_periode']) && $model_input["mode"] == "edit") {
					if ($this->database->update($insert_data['kode_periode'], $insert_data)) {
						// if ($insert_data['is_aktif'] == "Ya") {
						// $deactive = $this->_aktifasi_periode($insert_data['kode_periode']);
						// if ($deactive) {
						$this->sess_periode($insert_data['kode_periode']);
						// }
						// }

						$hasil["status"] = true;
						$hasil["message"] = "Periode baru berhasil ditambahkan";
					} else {
						$hasil["status"] = false;
						$hasil["message"] = "Periode baru gagal ditambahkan";
					}
				} else {
					$hasil["status"] = false;
					$hasil["message"] = "Periode sudah ada, gagal ditambahkan";
				}
			} else {
				$hasil["status"] = false;
				$hasil["message"] = validation_errors();
			}
			echo json_encode($hasil);
		}
	}

	private function sess_periode($kode_periode)
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$get_sess = $this->session->userdata()['user'];
		$get_sess['periode'] = $kode_periode;
		$get_sess['periode_text'] = $this->atur_rentang->periode_convert($kode_periode);
		$this->session->set_userdata('user', $get_sess);
	}

	public function ph_ganti()
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$kode_periode = $this->input->post("kode_periode");
		$get_sess = $this->session->userdata()['user'];
		$get_sess['periode'] = $kode_periode;
		$get_sess['periode_text'] = $this->atur_rentang->periode_convert($kode_periode);
		$this->session->set_userdata('user', $get_sess);
	}

	private function _aktifasi_periode($kode_periode)
	{
		$aksi_modul = 'ubah';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		//set supaya periode lain tidak aktif
		$this->db->where_not_in('kode_periode', $kode_periode);
		$status = $this->db->update('sibaya_ref_periode', array('is_aktif' => 'Tidak'));
		if ($status) {
			return 1;
		} else {
			return 0;
		}
	}

	public function periode_delete()
	{
		$aksi_modul = 'hapus';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		if ($this->input->post()) {
			$model_input = $this->input->post();
			if (!$this->database->exist($model_input['kode_periode'])) {
				if ($this->database->delete($model_input['kode_periode'])) {
					$hasil["status"] = true;
					$hasil["message"] = "Periode berhasil dihapus.";
				} else {
					$hasil["status"] = false;
					$hasil["message"] = "Periode gagal dihapus.";
				}
			} else {
				$hasil["status"] = false;
				$hasil["message"] = "Periode gagal dihapus. Data periode tidak ditemukan";
			}
			echo json_encode($hasil);
		}
	}

	public function periode_edit()
	{
		$aksi_modul = 'ubah';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		if ($this->input->post()) {
			$model_input = $this->input->post();
			if (!$this->database->exist($model_input['id'])) {
				$result = $this->database->getPeriode($model_input['id']);
				$nama_periode = $result["nama_periode"];
				list($tahun, $semester) = explode("/", $nama_periode);

				$hasil["data"]["semester"] = array_search($semester, $this->semester_ref);
				$hasil["data"]["kode_periode"] = $tahun;
				$hasil["data"]["is_aktif"] = $result["is_aktif"];
				$hasil["status"] = true;
			} else {
				$hasil["status"] = false;
				$hasil["message"] = "Maaf, periode yang ingin diperbarui tidak ditemukan";
			}
			echo json_encode($hasil);
		}
	}
}
