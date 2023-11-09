<?php
class Spp extends MY_Controller
{
	private $modul = 'spp';
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
		$this->load->model('Spp_m', 'database');
		$this->load->model('periode/Periode_m', 'periode');
		$this->load->model('simari/Simari_m', 'simari');
		$this->load->helper('datetime_helper');
		$this->load->helper('rupiah_helper');
		$this->load->helper('nim_helper');
		$this->load->helper('msg_helper');
	}

	function get_strata()
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$data = $this->database->get_prodi($this->input->post('prodi'))->row()->prodiJjarKode;
		$res = [
			'strata' => $data,
		];
		echo json_encode($res);
	}

	function get_prodi()
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$data = $this->database->get_prodi_new($this->input->post('fakultas'), null);
		$html = "<option value=''>Pilih program studi</option>";
		foreach ($data as $val) $html .= "<option value='" . $val->prodiKode . "'>" . $val->prodiJjarKode . "-" . $val->prodiNamaResmi . "</option>";
		echo $html;
	}

	public function datagrid($request, $where)
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$select = "tagihan.*";
		$data = $this->database->getDataGrid($request, $select, $where);
		return json_encode($data);
	}

	public function index()
	{

		//redirect(base_url('spp/salin_tagihan')); exit();
		$this->load->library('form_validation');
		$data['listPeriode'] = $this->periode->getPeriodeTagihan(1, "PEMBAYARAN", null);
		$data['listPeriode_min'] = $this->periode->getPeriode();
		$data['label_jenjang'] = $this->get_label_jenjang();
		$this->layout->render('list_periode', $data);
	}

	public function salin_tagihan()
	{
		$aksi_modul = 'tulis';
		//var_dump($this->role);var_dump($this->modul);die();
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$this->load->library('form_validation');
		$data['listPeriode'] = $this->periode->getPeriodeTagihan(1, "PEMBAYARAN", null);
		$data['listPeriode_min'] = $this->periode->getPeriode();
		$data['label_jenjang'] = $this->get_label_jenjang();
		$this->layout->render('salin_tagihan_v', $data);
	}

	public function getCopyData()
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$sumber = $this->input->post("periode_sumber");
		$target = $this->input->post("periode_target");
		//cek apakah semester sumber dan target ada di database
		if ($this->periode->cekPeriode($sumber) && $this->periode->cekPeriode($target)) {
			$data = $this->periode->getPeriodeTagihan(1, "PEMBAYARAN", $sumber);
			// echo $this->db->last_query();
			$nm_sumber = $this->periode->getPeriode($sumber);
			$nm_target = $this->periode->getPeriode($target);
			echo '<hr />';
			echo "Salin data tagihan dari <b>" . $nm_sumber['nama_periode'] . "</b> ke <b>" . $nm_target['nama_periode'] . "</b>";
			echo "<br />";
			echo '<div class="row">';
			echo '<div class="col-sm-6">';
			echo '<label class="form-label">Mulai Pembayaran</label>';
			echo '</div>';
			echo '<div class="col-sm-6">';
			echo "<div class='input-group date'>" . form_input('copy_waktu_berlaku', date('Y-m-d 00:00:00'), array('class' => 'form-control copy_waktu_berlaku', 'id' => 'copy_waktu_berlaku', 'placeholder' => 'Isikan tanggal mulai pembayaran')) . "<span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span></div>";
			echo '</div>';
			echo '</div>';
			echo '<div class="row">';
			echo '<div class="col-sm-6">';
			echo '<label class="form-label">Akhir Pembayaran</label>';
			echo '</div>';
			echo '<div class="col-sm-6">';
			echo "<div class='input-group date'>" . form_input('copy_waktu_berakhir', date('Y-m-d 23:59:00'), array('class' => 'form-control copy_waktu_berakhir', 'id' => 'copy_waktu_berakhir', 'placeholder' => 'Isikan tanggal akhir pembayaran')) . "<span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span></div>";
			echo '</div>';
			echo '</div>';
			echo "<a href='#' class='btn btn-success btn-lanjutkan-copy' onclick='do_copy(" . $sumber . "," . $target . ")'>Proses</a>";
		} else
			echo '<div class="alert alert-danger">Data gagal disalin. Terdapat kesalahan input Periode</div>';
	}

	public function doCopyData()
	{
		$aksi_modul = 'tulis';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$this->load->library('form_validation');
		$this->form_validation->set_rules('sumber', 'Periode Sumber', 'required|trim');
		$this->form_validation->set_rules('data', 'Periode Target', 'required|trim');
		$this->form_validation->set_rules('waktu_berlaku', 'Mulai Pembayaran', 'required|trim');
		$this->form_validation->set_rules('waktu_berakhir', 'Akhir Pembayaran', 'required|trim');

		if ($this->form_validation->run() == FALSE) {
			$validasi = [
				'status' => 0,
				'sumber' => form_error('sumber'),
				'data' => form_error('data'),
				'waktu_berlaku' => form_error('waktu_berlaku'),
				'waktu_berakhir' => form_error('waktu_berakhir'),
				'keterangan' => 'Data belum lengkap.',
			];
		} else {
			$validasi = [
				'status' => 0,
			];

			$sumber = $this->input->post("sumber");
			$target = $this->input->post("data");
			$waktu_berlaku = $this->input->post("waktu_berlaku");
			$waktu_berakhir = $this->input->post("waktu_berakhir");
			$cb['mhs_cuti'] = 0;
			$cb['mhs_keringanan'] = 0;
			if ($this->input->post("mhs_cuti")) {
				$cb['mhs_cuti'] = 1;
			}
			if ($this->input->post("mhs_keringanan")) {
				$cb['mhs_keringanan'] = 1;
			}
			//cek apakah format tanggalnya benar dan waktu berakhir lebih lambat dibanding waktu berlaku
			if (cekFormatTanggal($waktu_berlaku) && cekFormatTanggal($waktu_berakhir) && (new DateTime($waktu_berakhir) > new DateTime($waktu_berlaku))) {
				if ($this->periode->cekPeriode($sumber) && $this->periode->cekPeriode($target)) {
					$data = $this->periode->getPeriode($target);

					if ($this->database->salinTagihanMhsAktif($sumber, $data, (new DateTime($waktu_berlaku))->format('Y-m-d h:i:s'), (new DateTime($waktu_berakhir))->format('Y-m-d h:i:s'), $cb)) {
						$validasi = [
							'status' => 1,
							'keterangan' => 'Tagihan berhasil disalin.',
						];
					} else {
						$validasi['keterangan'] = 'Gagal menyalin tagihan.';
					}
				} else {
					$validasi['keterangan'] =  'Gagal menyalin tagihan.';
				}
			} else {
				$validasi['keterangan'] = 'Tanggal Akhir Pembayaran harus setelah Tanggal Mulai Pembayaran.';
			}
		}
		echo json_encode($validasi);
	}

	public function tagihan()
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		if (!$this->periode->cekPeriode($this->session->userdata('user')['periode'])) {
			echo "Periode yang anda cari tidak ada dalam database kami";
			die;
		}

		$data["kode_periode"] = $this->session->userdata('user')['periode'];
		$data["label_nama_periode"] = null;
		$data["fakultas"] = $this->simari->get_fakultas();
		$model = $this->periode->getOneSelect(array('kode_periode' => $data["kode_periode"]), "*", TRUE, TRUE);
		$data["label_nama_periode"] = ($model["nama_periode"] == null ? "" : "<b>Periode " . $model["nama_periode"] . "</b>");
		$data["label_jenjang"] = $this->get_label_jenjang();
		$this->layout->render('Tagihan_v', $data);
	}

	public function tagihan_non_ukt($id_record_tagihan = null)
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$periode = $this->session->userdata('user')['periode'];
		if (!$this->periode->cekPeriode($periode)) {
			echo "Periode yang anda cari tidak ada dalam database kami";
			die;
		}

		$data["kode_periode"] = $this->session->userdata('user')['periode'];
		$data["label_nama_periode"] = null;
		$data["fakultas"] = $this->simari->get_fakultas();
		$model = $this->periode->getOneSelect(array('kode_periode' => $periode), "*", TRUE, TRUE);
		$data["label_nama_periode"] = ($model["nama_periode"] == null ? "" : "<b>Periode " . $model["nama_periode"] . "</b>");
		$data["label_jenjang"] = $this->get_label_jenjang();
		$this->layout->render('Tagihan_non_ukt_v', $data);
	}

	public function setting_rule($jenis_form)
	{
		if ($jenis_form != "maba")
			$this->form_validation->set_rules('nomor_pembayaran', 'Nomor Pembayaran', 'required');

		$this->form_validation->set_rules('nomor_induk', 'Nomor Induk', 'required|trim');
		$this->form_validation->set_rules('nama', 'Nama', 'required');
		$this->form_validation->set_rules('angkatan', 'Angkatan', 'required');
		$this->form_validation->set_rules('kode_fakultas', 'Fakultas', 'required');
		$this->form_validation->set_rules('kode_prodi', 'Prodi', 'required');
		$this->form_validation->set_rules('strata', 'Jenjang', 'required');
		$this->form_validation->set_rules('total_nilai_tagihan', 'Nilai Tagihan', 'required');
		$this->form_validation->set_rules('is_tagihan_aktif', 'Status Tagihan', 'required');
		$this->form_validation->set_rules('jnsKode', 'Status Mahasiswa', 'required');
		//$this->form_validation->set_rules('urutan_antrian', 'Prioritas', 'required');
		$this->form_validation->set_rules('waktu_berlaku', 'Masa Berlaku', 'required');
		$this->form_validation->set_rules('waktu_berakhir', 'Masa Berakhir', 'required');
		$this->form_validation->set_rules('pembayaran_atau_voucher', 'Masa Berakhir', 'required');
	}

	//tidak jadi digunakan
	/**/
	public function formulir($id_record_tagihan = null)
	{
		$aksi_modul = 'tulis';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		// print_r($this->input->post());
		$periode = $this->session->userdata('user')['periode'];

		$data["kode_periode"] = $this->session->userdata('user')['periode'];
		$data["label_nama_periode"] = null;
		$model = $this->periode->getOneSelect(array('kode_periode' => $this->session->userdata('user')['periode']), "*", TRUE, TRUE);
		$data["label_nama_periode"] = ($model["nama_periode"] == null ? "" : "<b>Periode " . $model["nama_periode"] . "</b>");
		$data["label_jenjang"] = $this->get_label_jenjang();
		$model_tagihan = $this->database->getOneSelect(array('id_record_tagihan' => $id_record_tagihan), "*", TRUE, TRUE);

		// echo "<pre>";
		// print_r($id_record_tagihan);
		// echo "</pre>";
		// exit();

		if ($model["kode_periode"] == null)
			redirect("/spp/");

		$data["tagihan"] = $model_tagihan;
		if ($data["tagihan"] != null)
			$data["mode_input"] = "edit";
		else
			$data["mode_input"] = "add";

		$data["ref_fakultas"] = $this->simari->get_fakultas();
		$data["msg"] = "";

		if ($this->input->post()) {
			$this->load->library('form_validation');
			$this->setting_rule("mahasiswa lama");

			// echo "<pre>";
			// print_r($this->input->post());
			// echo "</pre>";
			// exit();

			if ($this->form_validation->run()) {
				$nomor_induk = $this->input->post("nomor_induk");
				$insert["id_record_tagihan"] = substr($periode, 2, 3) . "01" . nim($nomor_induk);
				$insert["nomor_pembayaran"] = nim($nomor_induk);
				$insert["nama"] = $this->input->post("nama");
				$insert["kode_fakultas"] = $this->input->post("kode_fakultas");
				$fakultas = $this->simari->get_fakultas($insert["kode_fakultas"]);
				$insert["nama_fakultas"] = $fakultas["fakNamaSingkat"];
				$insert["kode_prodi"] = $this->input->post("kode_prodi");
				$prodi = $this->simari->get_prodi(null, $insert["kode_prodi"]);
				$insert["nama_prodi"] = $prodi["prodiNamaResmi"];
				$insert["nama_periode"] = $model["nama_periode"];
				$insert["kode_periode"] = $this->session->userdata('user')['periode'];
				$insert["nomor_induk"] = $nomor_induk;
				$insert["strata"] = $this->input->post("strata");
				$insert["angkatan"] = $this->input->post("angkatan");
				$insert["is_tagihan_aktif"] = $this->input->post("is_tagihan_aktif");
				$insert["jnsKode"] = $this->input->post("jnsKode");
				$insert["urutan_antrian"] = 1;
				$insert["waktu_berlaku"] =  (new datetime($this->input->post("waktu_berlaku")))->format('Y-m-d H:i:s');
				$insert["waktu_berakhir"] = (new datetime($this->input->post("waktu_berakhir")))->format('Y-m-d H:i:s');
				$insert["total_nilai_tagihan"] = intval(str_replace(".", "", $this->input->post("total_nilai_tagihan")));
				$insert["pembayaran_atau_voucher"] = $this->input->post("pembayaran_atau_voucher");
				$insert["keterangan"] = "UKT";
				$insert["keringanan_ukt"] = $this->input->post("keringanan_ukt");


				if ($insert["pembayaran_atau_voucher"] == "VOUCHER") {
					$insert["voucher_nama"] = $insert["nama"];
					$insert["voucher_nama_fakultas"] = $insert["nama_fakultas"];
					$insert["voucher_nama_prodi"] = $insert["nama_prodi"];
					$insert["voucher_nama_periode"] = $insert["nomor_pembayaran"];
				}

				$data["msg_status"] = false;
				if ($model_tagihan["id_record_tagihan"] != null) {
					// echo "edit";
					if ($id_record_tagihan == $model_tagihan["id_record_tagihan"]) {
						if ($this->database->update($id_record_tagihan, $insert)) {
							$data["msg"] = "Tagihan berhasil diupdate";
							$data["msg_status"] = true;
						} else
							$data["msg"] = "Tagihan gagal diupdate";
					} else
						$data["msg"] = "Id Tagihan tidak ditemukan";
				} else {
					// echo "add";
					$where["nomor_pembayaran"] = nim($nomor_induk);
					$where["kode_periode"] = $this->session->userdata('user')['periode'];
					$hasil = $this->database->cek_tagihan($where);
					if ($hasil > 0) {
						$data["msg"] = "Tagihan periode ini gagal disimpan karena sudah ada di database";
					} else {
						if ($this->database->insert($insert)) {
							$data["msg"] = "Tagihan berhasil disimpan";
							$data["msg_status"] = true;
						} else
							$data["msg"] = "Tagihan gagal disimpan";
					}
				}
			} else {
				$data["msg"] = "Tagihan gagal disimpan";
				$data["msg_status"] = false;
			}
		}
		$this->layout->render('formulir_manual', $data);
	}

	public function formulir_plagiasi($id_record_tagihan = null)
	{
		$aksi_modul = 'tulis';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		// print_r($this->input->post());
		$data["kode_periode"] = $this->session->userdata('user')['periode'];
		$data["label_nama_periode"] = null;
		$model = $this->periode->getOneSelect(array('kode_periode' => $this->session->userdata('user')['periode']), "*", TRUE, TRUE);
		$data["label_nama_periode"] = ($model["nama_periode"] == null ? "" : "<b>Periode " . $model["nama_periode"] . "</b>");
		$data["label_jenjang"] = $this->get_label_jenjang();
		$model_tagihan = $this->database->getOneSelect(array('id_record_tagihan' => $id_record_tagihan), "*", TRUE, TRUE);
		if ($model["kode_periode"] == null)
			redirect("/spp/");

		$data["tagihan"] = $model_tagihan;
		if ($data["tagihan"] != null)
			$data["mode_input"] = "edit";
		else
			$data["mode_input"] = "add";

		$data["ref_fakultas"] = $this->simari->get_fakultas();
		$data["msg"] = "";

		if ($this->input->post()) {
			$this->load->library('form_validation');
			$this->setting_rule("mahasiswa lama");

			if ($this->form_validation->run()) {
				$nomor_induk = $this->input->post("nomor_induk");
				$insert["id_record_tagihan"] = "9404" . substr($data["kode_periode"], 2, 3) . nim($nomor_induk);
				$insert["nomor_pembayaran"] = nim($nomor_induk);
				$insert["nama"] = $this->input->post("nama");
				$insert["kode_fakultas"] = $this->input->post("kode_fakultas");
				$fakultas = $this->simari->get_fakultas($insert["kode_fakultas"]);
				$insert["nama_fakultas"] = $fakultas["fakNamaSingkat"];
				$insert["kode_prodi"] = $this->input->post("kode_prodi");
				$prodi = $this->simari->get_prodi(null, $insert["kode_prodi"]);
				$insert["nama_prodi"] = $prodi["prodiNamaResmi"];
				$insert["nama_periode"] = $model["nama_periode"];
				$insert["kode_periode"] = $this->session->userdata('user')['periode'];
				$insert["nomor_induk"] = $nomor_induk;
				$insert["strata"] = $this->input->post("strata");
				$insert["angkatan"] = $this->input->post("angkatan");
				$insert["is_tagihan_aktif"] = $this->input->post("is_tagihan_aktif");
				$insert["jnsKode"] = $this->input->post("jnsKode");
				$insert["urutan_antrian"] = 1;
				$insert["waktu_berlaku"] =  (new datetime($this->input->post("waktu_berlaku")))->format('Y-m-d H:i:s');
				$insert["waktu_berakhir"] = (new datetime($this->input->post("waktu_berakhir")))->format('Y-m-d H:i:s');
				$insert["total_nilai_tagihan"] = intval(str_replace(".", "", $this->input->post("total_nilai_tagihan")));
				$insert["pembayaran_atau_voucher"] = $this->input->post("pembayaran_atau_voucher");
				$insert["keterangan"] = "Cek Plagiasi";

				if ($insert["pembayaran_atau_voucher"] == "VOUCHER") {
					$insert["voucher_nama"] = $insert["nama"];
					$insert["voucher_nama_fakultas"] = $insert["nama_fakultas"];
					$insert["voucher_nama_prodi"] = $insert["nama_prodi"];
					$insert["voucher_nama_periode"] = $insert["nomor_pembayaran"];
				}

				$data["msg_status"] = false;
				if ($model_tagihan["id_record_tagihan"] != null) {
					// echo "edit";
					if ($id_record_tagihan == $model_tagihan["id_record_tagihan"]) {
						if ($this->database->update($id_record_tagihan, $insert)) {
							$data["msg"] = "Tagihan berhasil diupdate";
							$data["msg_status"] = true;
						} else
							$data["msg"] = "Tagihan gagal diupdate";
					} else
						$data["msg"] = "Id Tagihan tidak ditemukan";
				} else {
					// echo "add";
					$where["nomor_pembayaran"] = nim($nomor_induk);
					$where["kode_periode"] = $this->session->userdata('user')['periode'];
					$hasil = $this->database->cek_tagihan($where);
					if ($hasil > 0) {
						$data["msg"] = "Tagihan periode ini gagal disimpan karena sudah ada di database";
					} else {
						if ($this->database->insert($insert)) {
							$data["msg"] = "Tagihan berhasil disimpan";
							$data["msg_status"] = true;
						} else
							$data["msg"] = "Tagihan gagal disimpan";
					}
				}
			} else {
				$data["msg"] = "Tagihan gagal disimpan";
				$data["msg_status"] = false;
			}
		}
		$this->layout->render('formulir_plagiasi_v', $data);
	}

	public function formulir_maba()
	{
		$aksi_modul = 'tulis';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		// print_r($this->input->post());
		$periode = $this->session->userdata('user')['periode'];
		$data["kode_periode"] = $this->session->userdata('user')['periode'];
		$data["label_nama_periode"] = null;
		$model = $this->periode->getOneSelect(array('kode_periode' => $this->session->userdata('user')['periode']), "*", TRUE, TRUE);
		$data["label_nama_periode"] = ($model["nama_periode"] == null ? "" : "<b>Periode " . $model["nama_periode"] . "</b>");
		$data["label_jenjang"] = $this->get_label_jenjang();
		$model_tagihan = $this->database->getOneSelect(array('id_record_tagihan' => null), "*", TRUE, TRUE);

		if ($model["kode_periode"] == null)
			redirect("/spp/");

		$data["tagihan"] = $model_tagihan;
		if ($data["tagihan"] != null)
			$data["mode_input"] = "edit";
		else
			$data["mode_input"] = "add";

		$data["ref_fakultas"] = $this->simari->get_fakultas();
		$data["msg"] = "";

		if ($this->input->post()) {
			$this->load->library('form_validation');
			$this->setting_rule("maba");

			if ($this->form_validation->run()) {
				$nomor_induk = $this->input->post("nomor_induk");
				$insert["id_record_tagihan"] = substr($periode, 2, 3) . "01" . nim($nomor_induk);
				$insert["nomor_pembayaran"] = nim($nomor_induk);
				$insert["nama"] = $this->input->post("nama");
				$insert["kode_fakultas"] = $this->input->post("kode_fakultas");
				$fakultas = $this->simari->get_fakultas($insert["kode_fakultas"]);
				$insert["nama_fakultas"] = $fakultas["fakNamaSingkat"];
				$insert["kode_prodi"] = $this->input->post("kode_prodi");
				$prodi = $this->simari->get_prodi(null, $insert["kode_prodi"]);
				$insert["nama_prodi"] = $prodi["prodiNamaResmi"];
				$insert["nama_periode"] = $model["nama_periode"];
				$insert["kode_periode"] = $this->session->userdata('user')['periode'];
				$insert["nomor_induk"] = $nomor_induk;
				$insert["strata"] = $this->input->post("strata");
				$insert["angkatan"] = $this->input->post("angkatan");
				$insert["is_tagihan_aktif"] = $this->input->post("is_tagihan_aktif");
				$insert["jnsKode"] = $this->input->post("jnsKode");
				$insert["urutan_antrian"] = 1;
				$insert["waktu_berlaku"] = (new datetime($this->input->post("waktu_berlaku")))->format('Y-m-d H:i:s');
				$insert["waktu_berakhir"] =  (new datetime($this->input->post("waktu_berakhir")))->format('Y-m-d H:i:s');
				$insert["total_nilai_tagihan"] = intval(str_replace(".", "", $this->input->post("total_nilai_tagihan")));
				$insert["pembayaran_atau_voucher"] = $this->input->post("pembayaran_atau_voucher");
				$insert['keterangan'] = 'UKT';

				if ($insert["pembayaran_atau_voucher"] == "VOUCHER") {
					$insert["voucher_nama"] = $insert["nama"];
					$insert["voucher_nama_fakultas"] = $insert["nama_fakultas"];
					$insert["voucher_nama_prodi"] = $insert["nama_prodi"];
					$insert["voucher_nama_periode"] = $insert["nomor_pembayaran"];
				}

				$data["msg_status"] = false;

				$mhs_aktif = $maba = false;
				if ($this->simari->cek_mhs_aktif($nomor_induk))
					$mhs_aktif = true;
				if (count($this->database->cek_maba($nomor_induk, $periode)) == 1)
					$maba = true;

				if ($maba && !$mhs_aktif) {
					$where["nomor_pembayaran"] = nim($nomor_induk);
					//$where["kode_periode"] = $periode;
					$hasil = $this->database->cek_tagihan($where);
					if ($hasil > 0) {
						$data["msg"] = "Tagihan gagal disimpan. No ujian ini sudah pernah dipakai";
					} else {
						if ($this->database->insert($insert)) {
							$data["msg"] = "Tagihan berhasil disimpan";
							$data["msg_status"] = true;
						} else
							$data["msg"] = "Tagihan gagal disimpan";
					}
				} else {
					$data["msg"] = "No Ujian yang anda masukan tidak terdaftar sebagai mahasiswa baru";
					$data["msg_status"] = false;
				}
			} else {
				$data["msg"] = "Input data salah atau tidak lengkap";
				$data["msg_status"] = false;
			}
		}
		$this->layout->render('formulir_maba', $data);
	}

	public function input_non_ukt($edit = NULL)
	{
		$aksi_modul = 'tulis';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$periode = $this->session->userdata('user')['periode'];
		$model = $this->periode->getOneSelect(array('kode_periode' => $this->session->userdata('user')['periode']), "*", TRUE, TRUE);

		// if ($this->session->userdata('user')['role'] == "keuangan_pasca") {
		// 	show_404();
		// 	exit();
		// }
		// echo "<pre>";
		// print_r($this->session->userdata('user')['jenjang']);
		// echo "</pre>";
		// exit();
		if ($this->input->is_ajax_request()) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('nomor_induk', 'No.Ujian', 'required|trim');
			$this->form_validation->set_rules('nama', 'Nama', 'required|trim');
			$this->form_validation->set_rules('kode_fakultas', 'Fakultas', 'required|trim');
			$this->form_validation->set_rules('kode_prodi', 'Prodi', 'required|trim');
			$this->form_validation->set_rules('angkatan', 'Angkatan', 'required|trim');
			$this->form_validation->set_rules('total_nilai_tagihan', 'Jumlah', 'required|trim');
			$this->form_validation->set_rules('is_tagihan_aktif', 'Status Tagihan', 'required|trim');
			$this->form_validation->set_rules('keterangan_db', 'Keterangan Tagihan', 'required|trim');
			$this->form_validation->set_rules('waktu_berlaku', 'Awal', 'required|trim');
			$this->form_validation->set_rules('waktu_berakhir', 'Akhir', 'required|trim');

			if ($this->form_validation->run() == FALSE) {
				$validasi = [
					'status' => 0,
					'nomor_induk' => form_error('nomor_induk'),
					'nama' => form_error('nama'),
					'kode_fakultas' => form_error('kode_fakultas'),
					'kode_prodi' => form_error('kode_prodi'),
					'angkatan' => form_error('angkatan'),
					'total_nilai_tagihan' => form_error('total_nilai_tagihan'),
					'is_tagihan_aktif' => form_error('is_tagihan_aktif'),
					'keterangan_db' => form_error('keterangan_db'),
					'waktu_berlaku' => form_error('waktu_berlaku'),
					'waktu_berakhir' => form_error('waktu_berakhir'),
					'keterangan' => 'Data belum lengkap.',
				];
			} else {
				$post = $this->input->post();
				$validasi['status'] = 0;

				$prodi = $this->database->get_prodi($post['kode_prodi'])->row();
				if (!$this->database->validasi_strata($prodi->prodiJjarKode)) {
					$validasi["keterangan"] = "Nomor Induk " . $post['nomor_induk'] . " adalah mahasiswa " . $prodi->prodiJjarKode . ". Anda hanya bisa mengakses " . $this->database->list_strata_allowed(1) . ". <br>Anda bisa mengatur akses di menu \"Pengaturan > Atur Jenjang\".";
				} else {
					// 9401 : cek plagiasi
					// psikologi
					// tes bakat
					//
					$data = array(
						'nama' => $post['nama'],
						'kode_fakultas' => $post['kode_fakultas'],
						'nama_fakultas' => $prodi->fakNamaResmi,
						'kode_prodi' => $post['kode_prodi'],
						'nama_prodi' =>  $prodi->prodiNamaResmi,
						'nama_periode' => $model["nama_periode"],
						'kode_periode' => $this->session->userdata('user')['periode'],
						'is_tagihan_aktif' => $post['is_tagihan_aktif'],
						'waktu_berlaku' => (new datetime($post['waktu_berlaku']))->format('Y-m-d H:i:s'),
						'waktu_berakhir' => (new datetime($post['waktu_berakhir']))->format('Y-m-d H:i:s'),
						'strata' => $prodi->prodiJjarKode,
						'angkatan' => $post['angkatan'],
						'total_nilai_tagihan' => $post['total_nilai_tagihan'],
						'nomor_induk' => $post['nomor_induk'],
						'pembayaran_atau_voucher' => 'VOUCHER',
						'voucher_nama' => $post['nama'],
						'voucher_nama_fakultas' => $prodi->fakNamaResmi,
						'voucher_nama_prodi' => $prodi->prodiNamaResmi,
						'voucher_nama_periode' => $this->session->userdata('user')['periode_text'],
						'jnsKode' => 0,
						'keterangan' => $post['keterangan_db'],
						'urutan_antrian' => 1,
					);
					if ($data['keterangan'] == 'Tes Psikologi') {
						$data['id_record_tagihan'] = substr($this->session->userdata('user')['periode'], 2, 3) . "9401" . $post['nomor_induk'];
						$data['nomor_pembayaran'] =  "9401" . $post['nomor_induk'];
					}
					if ($data['keterangan'] == 'Tes Kesehatan') {
						$data['id_record_tagihan'] = substr($this->session->userdata('user')['periode'], 2, 3) . "9402" .  $post['nomor_induk'];
						$data['nomor_pembayaran'] =  "9402" . $post['nomor_induk'];
					}
					if ($data['keterangan'] == 'Tes Bakat') {
						$data['id_record_tagihan'] = substr($this->session->userdata('user')['periode'], 2, 3) . "9403" . $post['nomor_induk'];
						$data['nomor_pembayaran'] =  "9403" . $post['nomor_induk'];
					}
					if ($this->input->post('id_record_tagihan')) {
						$data['id_record_tagihan'] = $this->input->post('id_record_tagihan');
						$data['nomor_pembayaran'] =  $this->input->post('id_record_tagihan') ?? $data['nomor_pembayaran'];

						if ($this->database->update_tagihan($data)) {
							$validasi = [
								'status' => 1,
								'keterangan' => 'Tagihan berhasil disimpan',
							];
						} else {

							$validasi["keterangan"] = "Tagihan gagal disimpan";
						}
					} else {
						if ($this->database->insert_tagihan($data)) {
							$validasi = [
								'status' => 1,
								'keterangan' => 'Tagihan berhasil disimpan',
							];
						} else {
							$validasi["keterangan"] = "Tagihan gagal disimpan";
						}
					}
				}
			}
			echo json_encode($validasi);
		} else {
			$data["kode_periode"] = $this->session->userdata('user')['periode'];
			$data["label_nama_periode"] = null;
			$model = $this->periode->getOneSelect(array('kode_periode' => $this->session->userdata('user')['periode']), "*", TRUE, TRUE);
			$data["label_nama_periode"] = ($model["nama_periode"] == null ? "" : "<b>Periode " . $model["nama_periode"] . "</b>");
			$data["label_jenjang"] = $this->get_label_jenjang();
			$model_tagihan = $this->database->getOneSelect(array('id_record_tagihan' => null), "*", TRUE, TRUE);

			if ($model["kode_periode"] == null)
				redirect("/spp/");

			$data["tagihan"] = $model_tagihan;
			if ($data["tagihan"] != null)
				$data["mode_input"] = "edit";
			else
				$data["mode_input"] = "add";

			$reg_pendaftaran = $this->database->get_reg_pendaftaran();
			$data['reg_pendaftaran'] = $reg_pendaftaran->result();

			$data["ref_fakultas"] = $this->simari->get_fakultas();
			$data['ref_prodi'] = [];
			$data["edit"] = $edit;
			if ($edit) {
				$edit_data = $this->database->get_tagihan_where($edit);
				if ($edit_data->num_rows() > 0) {
					$data['edit'] = $edit_data->row();

					// echo "<pre>";
					// print_r($edit_data->row());
					// echo "</pre>";
					// exit();
				} else {
					show_error('Data tidak ada!');
				}
			}
			$data["msg"] = "";
			$this->layout->render('formulir_non_ukt', $data);
		}
	}

	public function angsuran($periode)
	{
		$aksi_modul = 'tulis';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$data["kode_periode"] = $this->session->userdata('user')['periode'];
		$data["label_nama_periode"] = null;
		$model = $this->periode->getOneSelect(array('kode_periode' => $this->session->userdata('user')['periode']), "*", TRUE, TRUE);
		$data["label_nama_periode"] = ($model["nama_periode"] == null ? "" : "<b>Periode " . $model["nama_periode"] . "</b>");
		$data["label_jenjang"] = $this->get_label_jenjang();
		$model_tagihan = $this->database->getOneSelect(array('id_record_tagihan' => null), "*", TRUE, TRUE);

		if ($model["kode_periode"] == null)
			redirect("/spp/");

		$data["tagihan"] = $model_tagihan;
		if ($data["tagihan"] != null)
			$data["mode_input"] = "edit";
		else
			$data["mode_input"] = "add";

		$data["ref_fakultas"] = $this->simari->get_fakultas();
		$data["msg"] = "";

		if ($this->input->post()) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('nomor_induk', 'Nomor Induk', 'required|trim');
			$this->form_validation->set_rules('total_nilai_tagihan', 'Nilai Tagihan', 'required');
			$this->form_validation->set_rules('waktu_berlaku', 'Masa Berlaku', 'required');
			$this->form_validation->set_rules('waktu_berakhir', 'Masa Berakhir', 'required');


			if ($this->form_validation->run()) {
				$nomor_induk = $this->input->post("nomor_induk");
				$tagihan = $this->database->get_tagihan_by_nim($nomor_induk)->row();
				$insert = (array) $tagihan;
				$angsuran_ke = $this->database->get_tagihan_pembayaran_by_nim(nim($nomor_induk))->num_rows() + 1;
				$insert['parent_id_record_tagihan'] = $insert['id_record_tagihan'];
				$insert['id_record_tagihan'] = $insert['id_record_tagihan'] . $angsuran_ke;
				$insert['nomor_pembayaran'] = str_replace("#", "", $insert['nomor_pembayaran']);
				$insert['total_nilai_tagihan'] =  str_replace(".", "", $this->input->post("total_nilai_tagihan"));
				$insert['waktu_berlaku'] = (new datetime($this->input->post("waktu_berlaku")))->format('Y-m-d H:i:s');
				$insert['waktu_berakhir'] =  (new datetime($this->input->post("waktu_berakhir")))->format('Y-m-d H:i:s');
				$insert['kode_periode'] = $this->session->userdata('user')['periode'];
				$insert['is_angsuran'] = 1;

				if ($insert["pembayaran_atau_voucher"] == "VOUCHER") {
					$insert["voucher_nama"] = $insert["nama"];
					$insert["voucher_nama_fakultas"] = $insert["nama_fakultas"];
					$insert["voucher_nama_prodi"] = $insert["nama_prodi"];
					$insert["voucher_nama_periode"] = $insert["nomor_pembayaran"];
				}

				$data["msg_status"] = false;
				if ($model_tagihan["id_record_tagihan"] != null) {
					// echo "edit";
					if ($insert['id_record_tagihan'] == $model_tagihan["id_record_tagihan"]) {
						if ($this->database->update($insert['id_record_tagihan'], $insert)) {
							$data["msg"] = "Tagihan berhasil diupdate";
							$data["msg_status"] = true;
						} else
							$data["msg"] = "Tagihan gagal diupdate";
					} else
						$data["msg"] = "Id Tagihan tidak ditemukan";
				} else {
					// echo "add";
					$where["nomor_pembayaran"] = nim($nomor_induk);
					$where["kode_periode"] = $this->session->userdata('user')['periode'];
					$data_parent = (array) $tagihan;
					$data_parent['nomor_pembayaran'] = str_replace("#", "", $data_parent['nomor_pembayaran']) . "#";

					if ($this->database->insert($insert)) {
						$update_parent = $this->database->update_angsuran($data_parent);
						if ($update_parent) {
							$data["msg"] = "Tagihan angsuran berhasil disimpan";
							$data["msg_status"] = true;
						} else {
							$data["msg"] = "Tagihan gagal disimpan, gagal mengupdate data parent.";
						}
					} else
						$data["msg"] = "Tagihan gagal disimpan";
				}
			} else {
				$data["msg"] = "Tagihan angsuran gagal disimpan";
				$data["msg_status"] = false;
			}
		}
		$this->layout->render('angsuran_v', $data);
	}

	public function cek_tagihan_maba()
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$nomor = trim($this->input->post("nomor_induk"));
		$kode_periode = $this->session->userdata('user')['periode'];
		$jenjang = null;
		foreach ($this->database->cek_maba($nomor, $kode_periode) as $row) {
			$jenjang = $row->jenjang;
		}

		//cek apakah benar pemilik no ujian tersebut adl maba
		if (count($this->database->cek_maba($nomor, $kode_periode)) == 1) {
			if ($this->role == 'admin_s1' && ($jenjang == 'S2' || $jenjang == 'S3')) {
				$response["status"] = false;
				$response["msg"] = "Pemilik No Ujian ini adalah mahasiswa " . $jenjang;
			} else if ($this->role == 'admin_s2' && $jenjang != 'S2' && $jenjang != 'S3') {
				$response["status"] = false;
				$response["msg"] = "Pemilik No Ujian ini adalah mahasiswa " . $jenjang;
			} else {
				$where["nomor_pembayaran"] = nim($nomor);
				$where["kode_periode"] = $kode_periode;
				$hasil = $this->database->cek_tagihan($where);

				if ($hasil > 0) {
					$response["status"] = false;
					$response["msg"] = "Tagihan Uang Kuliah untuk No Ujian yang anda masukan tidak bisa ditambahkan, karena sudah tersimpan pada periode ini";
				} else {
					$response["status"] = true;
					$data = array();
					$data = $this->database->cek_maba($nomor, $kode_periode);
					//print_r($data);die;
					//cek apakah ini tagihan mhs lama atau bukan
					if (count($data) > 0)
						$data += ["nomor_pembayaran" => nim($nomor)];
					//else //jika tagihan mhs baru atau mhs pindah, maka no_pembayaran = AFK
					//$data = ["nomor_pembayaran" => 'AFK' ];

					$response["data"] = $data;
				}
			}
		} else {
			$response["status"] = false;
			$response["msg"] = "Pemilik No Ujian ini tidak terdaftar sebagai mahasiswa baru";
		}
		echo json_encode($response);
	}

	/**/

	public function set_waktuberlaku($periode)
	{
		$aksi_modul = 'ubah';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$data["kode_periode"] = $this->session->userdata('user')['periode'];
		$data["msg"] = msg(false, "Masa tagihan periode " . $this->session->userdata('user')['periode'] . " gagal diupdate");
		$data["msg_status"] = false;
		$model = $this->periode->getOneSelect(array('kode_periode' => $this->session->userdata('user')['periode']), "*", TRUE, TRUE);

		if ($model["kode_periode"] != null) {
			$waktu_berlaku = $this->input->post("waktu_berlaku");
			$waktu_berakhir = $this->input->post("waktu_berakhir");

			if (cekFormatTanggal($waktu_berlaku) && cekFormatTanggal($waktu_berakhir) && (new DateTime($waktu_berakhir) > new DateTime($waktu_berlaku))) {
				$where["kode_periode"] = $this->session->userdata('user')['periode'];
				$insert["waktu_berlaku"] = $waktu_berlaku;
				$insert["waktu_berakhir"] = $waktu_berakhir;
				if ($this->database->updateByField($where, $insert, true)) {
					$data["msg"] = msg(true, "Tagihan berhasil diupdate");
					$data["msg_status"] = true;
				}
			}
		}
		echo json_encode($data);
	}

	public function detail($id_record_tagihan = null)
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$model_tagihan = $this->database->getOneSelect(array('id_record_tagihan' => $id_record_tagihan), "*", TRUE, TRUE);
		$dberlaku = new DateTime($model_tagihan['waktu_berlaku']);
		$dberakhir = new DateTime($model_tagihan['waktu_berakhir']);

		echo "<table class='table table-hover table-condensed' style='font-size:8px' width='100%'>";
		echo "<thead>";
		echo "<tr>";
		echo "<th>NO.</th>";
		echo "<th>NOMOR PEMBAYARAN</th>";
		echo "<th>NIM</th>";
		echo "<th>NAMA</th>";
		echo "<th>FAKULTAS</th>";
		echo "<th>PRODI</th>";
		echo "<th>ANGKATAN / JENJANG</th>";
		echo "<th>NILAI TAGIHAN</th>";
		echo "<th>MASA BERLAKU</th>";
		echo "<th>STATUS TAGIHAN</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
		echo "<tr>";
		echo "<td>1.</td>";
		echo "<td>" . $model_tagihan['nomor_pembayaran'] . "</td>";
		echo "<td>" . $model_tagihan['nomor_induk'] . "</td>";
		echo "<td>" . $model_tagihan['nama'] . "</td>";
		echo "<td>" . $model_tagihan['nama_fakultas'] . "</td>";
		echo "<td>" . $model_tagihan['nama_prodi'] . "</td>";
		echo "<td>" . $model_tagihan['angkatan'] . " / " . $model_tagihan["strata"] . "</td>";
		echo "<td>" . rupiah($model_tagihan['total_nilai_tagihan']) . "</td>";
		echo "<td>" . tgl_indo($dberlaku->format("Y-m-d")) . " s.d " . tgl_indo($dberakhir->format("Y-m-d")) . "</td>";
		echo "<td>" . ($model_tagihan['is_tagihan_aktif'] == 1 ? "Aktif" : "Non Aktif")  . "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>2.</td>";
		echo "<td>" . $model_tagihan['nomor_pembayaran'] . "</td>";
		echo "<td>" . $model_tagihan['nomor_induk'] . "</td>";
		echo "<td>" . $model_tagihan['nama'] . "</td>";
		echo "<td>" . $model_tagihan['nama_fakultas'] . "</td>";
		echo "<td>" . $model_tagihan['nama_prodi'] . "</td>";
		echo "<td>" . $model_tagihan['angkatan'] . " / " . $model_tagihan["strata"] . "</td>";
		echo "<td>" . rupiah($model_tagihan['total_nilai_tagihan']) . "</td>";
		echo "<td>" . tgl_indo($dberlaku->format("Y-m-d")) . " s.d " . tgl_indo($dberakhir->format("Y-m-d")) . "</td>";
		echo "<td>" . ($model_tagihan['is_tagihan_aktif'] == 1 ? "Aktif" : "Non Aktif")  . "</td>";
		echo "</tr>";
		echo "</tbody>";
		echo "</table>";

		// echo "<table class='table' width='100%'>";
		// echo "<tr>";
		// echo "<td width='30%'>Nomor Pembayaran</td>";
		// echo "<td width='70%'>" . $model_tagihan['nomor_pembayaran'] . "</td>";
		// echo "</tr>";
		// echo "<tr>";
		// echo "<td width='30%'>NIM</td>";
		// echo "<td width='70%'>" . $model_tagihan['nomor_induk'] . "</td>";
		// echo "</tr>";
		// echo "<tr>";
		// echo "<td width='30%'>Nama</td>";
		// echo "<td width='70%'>" . $model_tagihan['nama'] . "</td>";
		// echo "</tr>";
		// echo "<tr>";
		// echo "<td width='30%'>Fakultas</td>";
		// echo "<td width='70%'>" . $model_tagihan['nama_fakultas'] . "</td>";
		// echo "</tr>";
		// echo "<tr>";
		// echo "<td width='30%'>Prodi</td>";
		// echo "<td width='70%'>" . $model_tagihan['nama_prodi'] . "</td>";
		// echo "</tr>";
		// echo "<tr>";
		// echo "<td width='30%'>Angkatan / Jenjang</td>";
		// echo "<td width='70%'>" . $model_tagihan['angkatan'] . " / " . $model_tagihan["strata"] . "</td>";
		// echo "</tr>";
		// echo "<tr>";
		// echo "<td width='30%'>Nilai Tagihan</td>";
		// echo "<td width='70%'>" . rupiah($model_tagihan['total_nilai_tagihan']) . "</td>";
		// echo "</tr>";
		// echo "<tr>";
		// echo "<td width='30%'>Masa Berlaku</td>";
		// echo "<td width='70%'>" . tgl_indo($dberlaku->format("Y-m-d")) . " s.d " . tgl_indo($dberakhir->format("Y-m-d")) . "</td>";
		// echo "</tr>";
		// echo "<tr>";
		// echo "<td width='30%'>Status Tagihan</td>";
		// echo "<td width='70%'>" . ($model_tagihan['is_tagihan_aktif'] == 1 ? "Aktif" : "Non Aktif") . "</td>";
		// echo "</tr>";
		// echo "</table>";
	}

	public function tagihan_delete()
	{
		$aksi_modul = 'hapus';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$nomor = $this->input->post("id");
		$data["status"] = false;
		$data["msg"] = "Tagihan gagal dihapus";
		$data["msg_status"] = false;
		$where['id_record_tagihan'] = $nomor;

		if ($this->database->get_tagihan_with_strata($where)->num_rows() > 0) {
			if ($this->database->delete_tagihan_single($nomor)) {
				$data["status"] = true;
				$data["msg"] = "Tagihan berhasil dihapus";
				$data["msg_status"] = true;
			} else {
				$data["keterangan"] = "Tagihan gagal dihapus";
			}
		} else {
			$data["keterangan"] = "Tagihan gagal dihapus";
		}
		// echo $this->db->last_query();
		echo json_encode($data);
	}

	public function cek_tagihan_angsuran($mode = null)
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$nomor = trim($this->input->post("nomor_induk"));
		$kode_periode = $this->input->post("kode_periode");

		if ($this->simari->cek_mhs_aktif($nomor)) {
			$where["nomor_induk"] = nim($nomor);
			$where["kode_periode"] = $kode_periode;

			$hasil = $this->database->cek_tagihan_angsuran($where);

			if ($hasil == 0) {
				$response["status"] = false;
				$response["msg"] = "Tagihan Uang Kuliah untuk NIM yang anda masukan belum diinput untuk periode ini, silahkan input melalui halaman input tagihan";
			} else {
				$data = array();
				$data = $this->simari->get_mhs($nomor);
				$jenjang = $data['prodiJjarKode'];


				if (!$this->database->validasi_strata($jenjang)) {
					$response["status"] = false;
					$response["msg"] = "Nomor Induk " . $nomor . " adalah mahasiswa " . $jenjang . ". Anda hanya bisa mengakses " . $this->database->list_strata_allowed(1) . ". <br>Anda bisa mengatur akses di menu \"Pengaturan > Atur Jenjang\".";
				} else {
					$response["status"] = true;
					//cek apakah ini tagihan mhs lama atau bukan
					if (count($data) > 0)
						$data += ["nomor_pembayaran" => str_replace("#", "", nim($nomor))];
					//else //jika tagihan mhs baru atau mhs pindah, maka no_pembayaran = AFK
					//$data = ["nomor_pembayaran" => 'AFK' ];

					$tagihan = $this->database->get_tagihan_by_nim(nim($nomor))->row();

					$data['total_tagihan'] = $tagihan->total_nilai_tagihan;
					$data['sudah_dibayar'] = 0;
					$data['angsuran_ke'] = 0;

					$tagihan_pembayaran = $this->database->get_tagihan_pembayaran_by_nim(nim($nomor))->result();
					$angsuran_ke = 0;
					foreach ($tagihan_pembayaran as $tp) {
						$data['sudah_dibayar'] = $data['sudah_dibayar'] + $tp->total_nilai_tagihan;
						$angsuran_ke++;
					}
					$data['angsuran_ke'] = $angsuran_ke + 1;

					$response["data"] = $data;
				}
			}
		} else {
			$response["status"] = false;
			$response["msg"] = "NIM yang anda cari tidak ditemukan pada database mahasiswa atau mahasiswa tersebut tidak aktif";
		}
		echo json_encode($response);
	}

	public function cek_tagihan_plagiasi()
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$nomor = trim($this->input->post("nomor_induk"));
		$kode_periode = $this->input->post("kode_periode");

		if ($this->simari->cek_mhs_aktif($nomor)) {
			$where["id_record_tagihan"] = "94" . substr($kode_periode, 2, 3) . nim($nomor);
			$where["kode_periode"] = $kode_periode;
			$hasil = $this->database->cek_tagihan($where);


			if ($hasil > 0) {
				$response["status"] = false;
				$response["msg"] = "Tagihan Uang Kuliah untuk NIM yang anda masukan tidak bisa ditambahkan, karena sudah tersimpan pada periode ini";
			} else {
				$data = array();
				$data = $this->simari->get_mhs($nomor);
				$jenjang = $data['prodiJjarKode'];

				if (!$this->database->validasi_strata($jenjang)) {
					$response["status"] = false;
					$response["msg"] = "Nomor Induk " . $nomor . " adalah mahasiswa " . $jenjang . ". Anda hanya bisa mengakses " . $this->database->list_strata_allowed(1) . ". <br>Anda bisa mengatur akses di menu \"Pengaturan > Atur Jenjang\".";
				} else {
					$response["status"] = true;
					//cek apakah ini tagihan mhs lama atau bukan
					if (count($data) > 0)
						$data += ["nomor_pembayaran" => nim($nomor)];
					//else //jika tagihan mhs baru atau mhs pindah, maka no_pembayaran = AFK
					//$data = ["nomor_pembayaran" => 'AFK' ];

					$response["data"] = $data;
				}
			}
		} else {
			$response["status"] = false;
			$response["msg"] = "NIM yang anda cari tidak ditemukan pada database mahasiswa atau mahasiswa tersebut tidak aktif";
		}
		echo json_encode($response);
	}

	public function cek_tagihan()
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$nomor = trim($this->input->post("nomor_induk"));
		$kode_periode = $this->input->post("kode_periode");

		if ($this->simari->cek_mhs_aktif($nomor)) {
			$where["nomor_pembayaran"] = nim($nomor);
			$where["kode_periode"] = $kode_periode;
			$hasil = $this->database->cek_tagihan($where);


			if ($hasil > 0) {
				$response["status"] = false;
				$response["msg"] = "Tagihan Uang Kuliah untuk NIM yang anda masukan tidak bisa ditambahkan, karena sudah tersimpan pada periode ini";
			} else {
				$data = array();
				$data = $this->simari->get_mhs($nomor);
				$jenjang = $data['prodiJjarKode'];


				if (!$this->database->validasi_strata($jenjang)) {
					$response["status"] = false;
					$response["msg"] = "Nomor Induk " . $nomor . " adalah mahasiswa " . $jenjang . ". Anda hanya bisa mengakses " . $this->database->list_strata_allowed(1) . ". <br>Anda bisa mengatur akses di menu \"Pengaturan > Atur Jenjang\".";
				} else {
					$response["status"] = true;
					//cek apakah ini tagihan mhs lama atau bukan
					if (count($data) > 0)
						$data += ["nomor_pembayaran" => nim($nomor)];
					//else //jika tagihan mhs baru atau mhs pindah, maka no_pembayaran = AFK
					//$data = ["nomor_pembayaran" => 'AFK' ];

					$response["data"] = $data;
				}
			}
		} else {
			$response["status"] = false;
			$response["msg"] = "NIM yang anda cari tidak ditemukan pada database mahasiswa atau mahasiswa tersebut tidak aktif";
		}
		echo json_encode($response);
	}

	public function delete_banyak()
	{
		$aksi_modul = 'hapus';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		if (!$this->input->post('checked')) {
			echo json_encode([
				'status' => false,
				'keterangan' => 'Tidak ada data yang dipilih'
			]);
		} else {
			$checked = explode(",", $this->input->post('checked'));
			$delete = $this->database->delete_tagihan($checked);

			if ($delete) {
				echo json_encode([
					'status' => true,
					'keterangan' => 'Berhasil menghapus'
				]);
			} else {
				echo json_encode([
					'status' => false,
					'keterangan' => 'Gagal menghapus'
				]);
			}
		}
	}

	public function get_all_tagihan_id($keterangan = null)
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$where['kode_periode'] = $this->session->userdata('user')['periode'];
		$where['keterangan'] = urldecode($keterangan);
		$list = array();
		$list = $this->database->tagihan_get_all_id($where)->result();
		foreach ($list as $val) {
			$array[] = $val;
		}
		$output = array(
			"all" => $array
		);
		echo json_encode($output);
	}

	public function ajax_list($periode = null, $keterangan = null)
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		ini_set('memory_limit', '-1');
		$where['kode_periode'] = $this->session->userdata('user')['periode'];
		$where['keterangan'] = urldecode($keterangan);
		if (in_array($where['keterangan'], array("91", "92", "93"))) {
			$where['nomor_pembayaran'] = $where['keterangan'];
			$where['keterangan'] = "nonukt";
		}

		//$where['jnsKode'] = 1;
		$list = $this->database->get_dtb_tagihan($where)->result();

		// echo "<pre>";
		// print_r($this->db->last_query() . "<br>");
		// print_r($list);
		// echo "</pre>";
		// exit();

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $tagihan) {
			$no++;
			$row = array();
			$row[] = '<input type="checkbox" onclick="check_nim(this)"id="' . $tagihan->id_record_tagihan . '" name="check[]" value="' . $tagihan->id_record_tagihan . '">';
			// $row[] = nim(str_replace("#", "", $tagihan->nomor_pembayaran));
			$row[] = $tagihan->nama_periode;
			$row[] = $tagihan->nomor_induk;
			$row[] = $tagihan->nama;
			$row[] = $tagihan->nama_prodi;
			$total = rupiah($tagihan->total_nilai_tagihan);
			$angsuran = 0;
			if (strpos($tagihan->nomor_pembayaran, "#")) {
				$tagihan_pembayaran = $this->database->get_tagihan_pembayaran_by_nim(nim(str_replace("#", "", $tagihan->nomor_pembayaran)))->result();
				foreach ($tagihan_pembayaran as $tp) {
					$angsuran = $angsuran + $tp->total_nilai_tagihan;
				}
				$total .= '<br>Total Angsuran : ' . rupiah($angsuran);
			}
			$row[] = $total;

			$np = substr($tagihan->nomor_pembayaran, 0, 2);
			if (strtolower($where['keterangan']) == "nonukt" && ($np == "91" || $np == "92" || $np == "93")) {
				$row[] = $np == "91" ? "ADMISI S1 & D3" : ($np == "92" ? "ADMISI PROFESI" : ($np == "93" ? "ADMISI PASCA" : "Lainnya"));
			} else {
				$row[] = $tagihan->keterangan;
			}

			$row[] = 'CICIL / UKT';

			//$row[] = $tagihan->id_record_tagihan;
			if ($tagihan->keterangan == "Cek Plagiasi") {
				$aksi = '<a class="btn" title="Edit" href="' . base_url() . 'spp/formulir_plagiasi/' . $tagihan->id_record_tagihan . '"><span style="color:#1bb399" class="fa fa-pencil"></span></a>';
			} else if ($tagihan->keterangan !== "UKT" && $tagihan->keterangan !== "ukt") {
				$aksi = '<a class="btn" title="Edit" href="' . base_url() . 'spp/input_non_ukt/' . $tagihan->id_record_tagihan . '"><span style="color:#1bb399" class="fa fa-pencil"></span></a>';
			} else {
				$aksi = '<a class="btn" title="Edit" href="' . base_url() . 'spp/formulir/' . $tagihan->id_record_tagihan . '"><span style="color:#1bb399" class="fa fa-pencil"></span></a>';
			}
			$aksi .= '
			<a href="#" class="btn" title="Hapus" onclick="hapuskan(\'' . $tagihan->id_record_tagihan . '\')"><span style="color:#e33244" class="fa fa-trash"></span></a>
			<a href="#" class="btn" title="Detail" onclick="detail(\'' . $tagihan->id_record_tagihan . '\')"><span class="fa fa-search" style="color:#1bb399"></span></a>';
			// if (strpos($tagihan->nomor_pembayaran, "#")) {
			// 	$aksi .= '&nbsp<a href="#" class="btn" title="Detail" onclick="detail(\'' . $tagihan->id_record_tagihan . '\')"><span class="fa fa-user-o" style="color:#1bb399"></span></a>';
			// }

			$row[] = $aksi;
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->database->custom_count_all_tagihan($where),
			"recordsFiltered" => $this->database->count_filtered_tagihan($where),
			"data" => $data,
		);


		echo json_encode($output);
	}

	function download_tagihan_non_ukt($keterangan = null)
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		ini_set('memory_limit', '-1');
		$where['kode_periode'] = $this->session->userdata('user')['periode'];
		if ($keterangan != null && $keterangan != "") {
			$where['keterangan'] = urldecode($keterangan);
		} else {
			$where['keterangan'] = 'nonukt';
		}
		$list = $this->database->download_tagihan($where);

		$this->load->library('Excel');
		$center = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$allborder = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			)
		);
		$style = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			)
		);
		$outlineborder = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$objTpl = PHPExcel_IOFactory::load("./assets/update_tagihan_massal.xls");
		$objTpl->setActiveSheetIndex(0); //set first sheet as active
		$objTpl->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1); //auto height cell
		$objTpl->getActiveSheet()->getProtection()->setPassword('sepertinyasayapusingsekalimelihattingkahlakukmusudahcukup');
		$objTpl->getActiveSheet()->getStyle("A1:L7")->getProtection()->setLocked(true);

		$no = 1;
		$rowID = 8;
		$objTpl->getActiveSheet()->setCellValue('I6', "Program Studi")
			->mergeCells("I6:I7")
			->getStyle('I1:I7')->applyFromArray(
				array(
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => '000000')
					),
					'font' => [
						'color' => ['rgb' => 'FFFFFF'],
						'bold' => true,
					],
					'alignment' => [
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						'vertical'	=> PHPExcel_Style_Alignment::VERTICAL_CENTER
					]
				)
			);

		foreach ($list->result() as $row) {
			// $mhs = $this->database->get_info_mhs($row->nomor_induk)->row();
			$objTpl->getActiveSheet()->setCellValue('A' . $rowID, strval($no));
			// $objTpl->getActiveSheet()->setCellValueExplicit('B' . $rowID, nim($row->mhsNiu), PHPExcel_Cell_DataType::TYPE_STRING);
			// $objTpl->getActiveSheet()->setCellValueExplicit('C' . $rowID, $row->mhsNiu, PHPExcel_Cell_DataType::TYPE_STRING);
			// $objTpl->getActiveSheet()->setCellValue('B' . $rowID, html_entity_decode($row->mhsNama));
			$objTpl->getActiveSheet()->setCellValue('B' . $rowID, $row->nomor_induk);
			$objTpl->getActiveSheet()->setCellValue('C' . $rowID, $row->nama);
			$objTpl->getActiveSheet()->setCellValue('D' . $rowID, $row->is_tagihan_aktif);

			// if ($row->is_tagihan_aktif == 1) {
			// 	$objTpl->getActiveSheet()->setCellValue('D' . $rowID, 'AKTIF');
			// } else {
			// 	$objTpl->getActiveSheet()->setCellValue('D' . $rowID, 'TIDAK AKTIF');
			// }
			$objTpl->getActiveSheet()->setCellValue('E' . $rowID, $row->total_nilai_tagihan);
			$objTpl->getActiveSheet()->setCellValue('F' . $rowID, $row->jnsKode);
			$objTpl->getActiveSheet()->setCellValue('H' . $rowID, $row->keterangan);
			$objTpl->getActiveSheet()->setCellValue('I' . $rowID,	$row->strata . " - " . $row->nama_prodi);

			$no++;
			$rowID++;
		}
		$objTpl->getActiveSheet()->getPageSetup()->setPrintArea("A1:" . "G" . ($rowID + 1));
		$objTpl->getActiveSheet()->getPageSetup()->setPrintArea("A1:" . "G" . ($rowID + 1));
		$objTpl->getActiveSheet()->getProtection()->setPassword('sepertinyasayapusingsekalimelihattingkahlakukmusudahcukup');
		$objTpl->getActiveSheet()->getProtection()->setSheet(true);
		$rowID--;
		$objTpl->getActiveSheet()->getStyle("A8:" . "A" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("B8:" . "B" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("C8:" . "C" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("D8:" . "D" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("E8:" . "E" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("F8:" . "F" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

		header("Content-Type: application/vnd.ms-excel");
		header('Content-Disposition: attachment;filename="list_tagihan_' . (new dateTime())->format('d-m-Y H-i-s') . '.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = new PHPExcel_Writer_Excel5($objTpl);
		ob_end_clean();
		$objWriter->save('php://output');
	}

	function download_tagihan()
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		ini_set('memory_limit', '-1');

		$list = $this->database->get_tagihan_ukt();

		// echo "<pre>";
		// print_r($list->result());
		// echo "</pre>";
		// exit();

		$this->load->library('Excel');
		$center = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$allborder = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			)
		);
		$style = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			)
		);
		$outlineborder = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$objTpl = PHPExcel_IOFactory::load("./assets/update_tagihan_massal.xls");
		$objTpl->setActiveSheetIndex(0); //set first sheet as active
		$objTpl->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1); //auto height cell
		$objTpl->getActiveSheet()->getProtection()->setPassword('sepertinyasayapusingsekalimelihattingkahlakukmusudahcukup');
		$objTpl->getActiveSheet()->getStyle("A1:L7")->getProtection()->setLocked(true);
		$objTpl->getActiveSheet()->setCellValue('H6', 'Prodi');

		$no = 1;
		$rowID = 8;
		foreach ($list->result() as $row) {
			$keringanan = 0;
			// $mhs = $this->database->get_info_mhs($row->nomor_induk)->row();
			$objTpl->getActiveSheet()->setCellValue('A' . $rowID, $no);
			// $objTpl->getActiveSheet()->setCellValueExplicit('B' . $rowID, nim($row->mhsNiu), PHPExcel_Cell_DataType::TYPE_STRING);
			// $objTpl->getActiveSheet()->setCellValueExplicit('C' . $rowID, $row->mhsNiu, PHPExcel_Cell_DataType::TYPE_STRING);
			// $objTpl->getActiveSheet()->setCellValue('B' . $rowID, html_entity_decode($row->mhsNama));
			$objTpl->getActiveSheet()->setCellValue('B' . $rowID, $row->nomor_induk);
			$objTpl->getActiveSheet()->setCellValue('C' . $rowID, $row->nama);
			$objTpl->getActiveSheet()->setCellValue('D' . $rowID, $row->is_tagihan_aktif);

			// if ($row->is_tagihan_aktif == 1) {
			// 	$objTpl->getActiveSheet()->setCellValue('D' . $rowID, 'AKTIF');
			// } else {
			// 	$objTpl->getActiveSheet()->setCellValue('D' . $rowID, 'TIDAK AKTIF');
			// }
			$objTpl->getActiveSheet()->setCellValue('E' . $rowID, $row->total_nilai_tagihan);
			$objTpl->getActiveSheet()->setCellValue('F' . $rowID, $row->jnsKode);
			if ($row->keringanan_ukt) {
				$keringanan = 1;
			} else {
				$keringanan = 0;
			}
			$objTpl->getActiveSheet()->setCellValue('G' . $rowID, $keringanan);
			$objTpl->getActiveSheet()->setCellValue('H' . $rowID, $row->strata . " - " . $row->nama_prodi);

			$no++;
			$rowID++;
		}
		$objTpl->getActiveSheet()->getPageSetup()->setPrintArea("A1:" . "G" . ($rowID + 1));
		$objTpl->getActiveSheet()->getPageSetup()->setPrintArea("A1:" . "G" . ($rowID + $v));
		$objTpl->getActiveSheet()->getProtection()->setPassword('sepertinyasayapusingsekalimelihattingkahlakukmusudahcukup');
		$objTpl->getActiveSheet()->getProtection()->setSheet(true);
		$rowID--;
		$objTpl->getActiveSheet()->getStyle("A8:" . "A" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("B8:" . "B" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("C8:" . "C" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("D8:" . "D" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("E8:" . "E" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("F8:" . "F" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

		header("Content-Type: application/vnd.ms-excel");
		header('Content-Disposition: attachment;filename="list_tagihan_' . (new dateTime())->format('d-m-Y H-i-s') . '.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = new PHPExcel_Writer_Excel5($objTpl);
		ob_end_clean();
		$objWriter->save('php://output');
	}

	function download_template_non_ukt()
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$list = $this->database->get_prodifakultas_list();

		// echo "<pre>";
		// print_r($list->result());
		// echo "</pre>";
		// exit();

		$this->load->library('Excel');
		$center = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$allborder = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			)
		);
		$style = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			)
		);
		$outlineborder = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$objTpl = PHPExcel_IOFactory::load("./assets/list_tagihan_2020.xls");
		$objTpl->setActiveSheetIndex(0); //set first sheet as active
		$objTpl->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1); //auto height cell
		$objTpl->getActiveSheet()->getProtection()->setPassword('sepertinyasayapusingsekalimelihattingkahlakukmusudahcukup');
		$objTpl->getActiveSheet()->getStyle("A1:L7")->getProtection()->setLocked(true);

		$no = 1;
		$rowID = 8;
		foreach ($list->result() as $row) {
			$objTpl->getActiveSheet()->setCellValue('A' . $rowID, $row->prodiKode);
			// $objTpl->getActiveSheet()->setCellValueExplicit('B' . $rowID, nim($row->mhsNiu), PHPExcel_Cell_DataType::TYPE_STRING);
			// $objTpl->getActiveSheet()->setCellValueExplicit('C' . $rowID, $row->mhsNiu, PHPExcel_Cell_DataType::TYPE_STRING);
			// $objTpl->getActiveSheet()->setCellValue('B' . $rowID, html_entity_decode($row->mhsNama));
			$objTpl->getActiveSheet()->setCellValue('B' . $rowID, $row->prodiNamaResmi);
			$objTpl->getActiveSheet()->setCellValue('C' . $rowID, $row->fakNamaSingkat);
			$objTpl->getActiveSheet()->setCellValue('D' . $rowID, $row->prodiJjarKode);
			$no++;
			$rowID++;
		}
		$objTpl->getActiveSheet()->getPageSetup()->setPrintArea("A1:" . "G" . ($rowID + 1));
		$objTpl->getActiveSheet()->getPageSetup()->setPrintArea("A1:" . "G" . ($rowID + $v));
		$objTpl->getActiveSheet()->getProtection()->setPassword('sepertinyasayapusingsekalimelihattingkahlakukmusudahcukup');
		$objTpl->getActiveSheet()->getProtection()->setSheet(true);
		$rowID--;
		$objTpl->getActiveSheet()->getStyle("E8:" . "E" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("F8:" . "F" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("G8:" . "G" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

		header("Content-Type: application/vnd.ms-excel");
		header('Content-Disposition: attachment;filename="Template_Mahasiswa_aktif_' . (new dateTime())->format('d-m-Y H-i-s') . '.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = new PHPExcel_Writer_Excel5($objTpl);
		ob_end_clean();
		$objWriter->save('php://output');
	}

	function download_template_mhs_aktif($angkatan, $fakultas)
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$this->load->library('Excel');
		$center = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			)
		);
		$allborder = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			)
		);
		$style = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			)
		);
		$outlineborder = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);
		$objTpl = PHPExcel_IOFactory::load("./assets/list_pembayaran.xls");
		$objTpl->setActiveSheetIndex(0); //set first sheet as active
		$objTpl->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1); //auto height cell
		$objTpl->getActiveSheet()->getProtection()->setPassword('sepertinyasayapusingsekalimelihattingkahlakukmusudahcukup');
		$objTpl->getActiveSheet()->getStyle("A1:L7")->getProtection()->setLocked(true);
		if ($angkatan != "all") $where["sia_m_mahasiswa.mhsAngkatan"] = $angkatan;
		if ($fakultas != "all") $where["sia_m_fakultas.fakKode"] = $fakultas;
		$peserta = $this->simari->getMahasiswaAktif($where);
		$no = 1;
		$rowID = 8;
		foreach ($peserta as $row) {
			$objTpl->getActiveSheet()->setCellValue('A' . $rowID, $no);
			$objTpl->getActiveSheet()->setCellValueExplicit('B' . $rowID, nim($row->mhsNiu), PHPExcel_Cell_DataType::TYPE_STRING);
			$objTpl->getActiveSheet()->setCellValueExplicit('C' . $rowID, $row->mhsNiu, PHPExcel_Cell_DataType::TYPE_STRING);
			$objTpl->getActiveSheet()->setCellValue('D' . $rowID, html_entity_decode($row->mhsNama));
			$objTpl->getActiveSheet()->setCellValue('E' . $rowID, $row->fakKode);
			$objTpl->getActiveSheet()->setCellValue('F' . $rowID, $row->fakNamaResmi);
			$objTpl->getActiveSheet()->setCellValue('G' . $rowID, $row->prodiKode);
			$objTpl->getActiveSheet()->setCellValue('H' . $rowID, $row->prodiNamaResmi);
			$objTpl->getActiveSheet()->setCellValue('I' . $rowID, $row->prodiJjarKode);
			$objTpl->getActiveSheet()->setCellValue('J' . $rowID, $row->mhsAngkatan);
			$no++;
			$rowID++;
		}
		$objTpl->getActiveSheet()->getPageSetup()->setPrintArea("A1:" . "G" . ($rowID + 1));
		$objTpl->getActiveSheet()->getPageSetup()->setPrintArea("A1:" . "G" . ($rowID + $v));
		$objTpl->getActiveSheet()->getProtection()->setPassword('sepertinyasayapusingsekalimelihattingkahlakukmusudahcukup');
		$objTpl->getActiveSheet()->getProtection()->setSheet(true);
		$rowID--;
		$objTpl->getActiveSheet()->getStyle("K8:" . "K" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		header("Content-Type: application/vnd.ms-excel");
		header('Content-Disposition: attachment;filename="Template_Mahasiswa_aktif_' . (new dateTime())->format('d-m-Y H-i-s') . '.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = new PHPExcel_Writer_Excel5($objTpl);
		ob_end_clean();
		$objWriter->save('php://output');
	}

	function test()
	{

		$mhs_list = $this->database->get_list_pendaftar('62401');

		echo "<pre>";
		print_r($mhs_list);
		echo "</pre>";
		exit();
	}

	function do_upload_non_ukt()
	{
		$aksi_modul = 'ubah';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		if ($this->session->userdata('user')['jenjang'] == "'S2', 'S3'") {
			show_404();
			exit();
		}
		$kesahalan = 0;
		$this->load->library('form_validation');
		$this->form_validation->set_rules('m_daftarId', 'Jenis Jalur', 'required|trim');
		$this->form_validation->set_rules('m_awal', 'Waktu Tagihan Berlaku', 'required|trim');
		$this->form_validation->set_rules('m_akhir', 'Waktu Tagihan Berakhir', 'required|trim');
		$this->form_validation->set_rules('m_kode_periode', 'Kode Periode', 'required|trim');

		if (empty($_FILES)) {
			$this->form_validation->set_rules('m_files', 'File Excel', 'required');
		}

		if ($this->form_validation->run() == FALSE) {
			$validasi = [
				'status' => 0,
				'm_daftarId' => form_error('m_daftarId'),
				'm_awal' => form_error('m_awal'),
				'm_akhir' => form_error('m_akhir'),
				'm_file' => form_error('m_files'),
				'm_kode_periode' => form_error('m_kode_periode'),
				'keterangan' => 'Data belum lengkap.',
			];
		} else {
			$validasi = [
				'status' => 0,
			];

			$m_daftarId = $this->input->post("m_daftarId");
			$m_awal = $this->input->post("m_awal");
			$m_akhir = $this->input->post("m_akhir");
			$m_file = $this->input->post("m_file");
			$m_kode_periode = $this->input->post("m_kode_periode");

			//cek apakah format tanggalnya benar dan waktu berakhir lebih lambat dibanding waktu berlaku
			if (cekFormatTanggal($m_awal) && cekFormatTanggal($m_akhir) && (new DateTime($m_awal) < new DateTime($m_akhir))) {
				$upload = $this->database->upload_berkas('m_file', 'xls|xlsx|XLS|XLSX');



				if ($upload !== 'error') {

					// Akses file excel yang telah diupload
					$this->load->library('excel_reader');
					$this->excel_reader->setOutputEncoding('CP1251');
					$file = $upload['file']['full_path'];
					$this->excel_reader->read($file);
					$data = $this->excel_reader->sheets[0];
					$data_xls_error = "";
					$update = array();
					$insert = array();
					$no_urut = 1;
					$valid = true;
					$hal = "<ul>";

					// akses row pertama yaitu ke-8
					$batch = array();
					$kip = array();

					$row_bermasalah = [];
					for ($i = 8; $i <= $data['numRows']; $i++) {
						if (isset($data['cells'][$i][1])) {
							$prodata = $this->database->get_prodi_data($data['cells'][$i][1]);
							if ($prodata->num_rows() > 0) {
								$prodata = $prodata->row();
								$mhs_list = $this->database->get_list_pendaftar($data['cells'][$i][1], $this->input->post('m_daftarId'))->result();

								foreach ($mhs_list as $ml) {
									if ($ml->regBidikmisi == "YA") {
										$kip[] = $ml->regNopes;
									}
									$new = [
										'id_record_tagihan' => '',
										'nomor_pembayaran' => '',
										'nama' => $ml->regNama,
										'kode_fakultas' => $prodata->fakKode,
										'nama_fakultas' => $prodata->fakNamaSingkat,
										'kode_prodi' => $prodata->prodiKode,
										'nama_prodi' => $prodata->prodiNamaResmi,
										'kode_periode' => $ml->daftarSemAwal,
										'is_tagihan_aktif' => '1',
										'waktu_berlaku' => (new DateTime($this->input->post('m_awal')))->format('Y-m-d H:i:s'),
										'waktu_berakhir' => (new DateTime($this->input->post('m_akhir')))->format('Y-m-d H:i:s'),
										'strata' => $prodata->prodiJjarKode,
										'angkatan' => $ml->daftarTahun,
										'nomor_induk' => $ml->regNopes,
										'pembayaran_atau_voucher' => 'VOUCHER',
										'voucher_nama' => $ml->regNama,
										'voucher_nama_fakultas' => $prodata->fakNamaResmi,
										'voucher_nama_prodi' => $prodata->prodiNamaResmi,
										'voucher_nama_periode' => $this->session->userdata('user')['periode_text'],
										'jnsKode' => 0,
										'keterangan' => '',
										'urutan_antrian' => 1,
									];
									$periode = str_split($new['kode_periode'], 4);
									$nama_periode = $periode[0] . "/" . $this->semester_ref[$periode[1]];
									$new['nama_periode'] = $nama_periode;
									$new['total_nilai_tagihan'] = 0;

									if (isset($data['cells'][$i][5]) && $data['cells'][$i][5] !== "0") {
										$new['id_record_tagihan'] =  substr($this->session->userdata('user')['periode'], 2, 3) . "9401" . $ml->regNopes;
										$new['nomor_pembayaran'] = "9401" . $ml->regNopes;
										// $new['voucher_nama_periode'] = $new['id_record_tagihan'];
										$new['keterangan'] = 'Tes Psikologi';
										if ($ml->regBidikmisi !== "YA") {
											$new['total_nilai_tagihan'] = $data['cells'][$i][5];
										}
										$batch[] = $new;
									}
									if (isset($data['cells'][$i][6]) && $data['cells'][$i][6] !== "0") {
										$new['id_record_tagihan'] =  substr($this->session->userdata('user')['periode'], 2, 3) . "9402" . $ml->regNopes;
										$new['nomor_pembayaran'] = "9402" . $ml->regNopes;
										// $new['voucher_nama_periode'] = $new['id_record_tagihan'];
										$new['keterangan'] = 'Tes Kesehatan';
										if ($ml->regBidikmisi !== "YA") {
											$new['total_nilai_tagihan'] = $data['cells'][$i][6];
										}
										$batch[] = $new;
									}
									if (isset($data['cells'][$i][7]) && $data['cells'][$i][7] !== "0") {
										$new['id_record_tagihan'] = substr($this->session->userdata('user')['periode'], 2, 3) . "9403" .  $ml->regNopes;
										$new['nomor_pembayaran'] = "9403" . $ml->regNopes;
										// $new['voucher_nama_periode'] = $new['id_record_tagihan'];
										$new['keterangan'] = 'Tes Bakat';
										if ($ml->regBidikmisi !== "YA") {
											$new['total_nilai_tagihan'] = $data['cells'][$i][7];
										}
										$batch[] = $new;
									}
								}
							} else {
								$row_bermasalah[] = $i;
							}
						} else {
							$validasi = [
								'status' => 0,
								'keterangan' => 'Ada data yang belum dilengkapi didalam file template.',
							];
							$kesahalan = 1;
						}
					}

					if ($kesahalan !== 1 && count($batch) > 0) {
						// $batch_insert = array(
						// 	'status' => 1,
						// 	'fail' => ''
						// );
						// batch inserting tagihan
						$batch_insert = $this->database->batch_insert_tagihan_non_ukt($batch);
						if ($batch_insert['status'] !== 0) {
							// inserting pembayaran untuk yang KIP
							if (count($kip) > 0) {
								foreach ($kip as $kk) {
									$list_tagihan = $this->database->get_tagihan_where_nopem($kk);
									foreach ($list_tagihan->result() as $lt) {
										$insert = array();
										$insert["id_record_pembayaran"] = nim($lt->id_record_tagihan) . "-keu-" . $lt->kode_periode;
										$insert["id_record_tagihan"] = $lt->id_record_tagihan;
										$insert["waktu_transaksi"] = date('Y-m-d H:i:s');
										$insert["nomor_pembayaran"] = nim($lt->nomor_pembayaran);
										$insert["kode_bank"] = "KIP";
										$insert["kanal_bayar_bank"] = "SI TAGIHAN";
										$insert["kode_terminal_bank"] = $this->session->user["username"];
										$insert["total_nilai_pembayaran"] = $lt->total_nilai_tagihan;
										$insert["status_pembayaran"] = 1;
										$insert["metode_pembayaran"] = "keuangan";
										$insert["catatan"] = "";
										$insert["key_val_1"] = nim($lt->nomor_induk);
										$insert["key_val_2"] = $lt->nomor_pembayaran;
										$insert["key_val_4"] = $lt->id_record_tagihan;
										$insert["key_val_5"] = $lt->kode_periode;
										$status = $this->db->replace('pembayaran', $insert);
									}
								}
							}
							$validasi = [
								'status' => 1,
								'keterangan' => 'Berhasil mengupload template.',
								'fail' => $batch_insert['fail'],
							];
							if ($row_bermasalah) {
								$list_b = '';
								foreach ($row_bermasalah as $rb) {
									$list_b .= $rb . ",";
								}
								$validasi['keterangan'] = 'Berhasil dengan catatan';
								$validasi['fail'] = 'Berhasil namun anda tidak berhak untuk mengupload template untuk row ' . $list_b;
							}
						} else {
							$validasi = [
								'status' => 0,
								'keterangan' => 'Gagal mengupload template.',
							];
						}
					} else {
						$validasi = [
							'status' => 0,
							'keterangan' => 'Gagal mengupload template.',
						];
						if (count($batch) == 0) {
							$validasi['keterangan'] = 'Data calon mahasiswa tidak ditemukan dengan template yang diunggah.';
						}
					}
				} else {
					$kesahalan = 1;
					$validasi['keterangan'] = 'Gagal mengupload file.';
				}
			} else {
				$kesahalan = 1;
				$validasi['keterangan'] = 'Tanggal Akhir Pembayaran harus setelah Tanggal Mulai Pembayaran.';
			}
		}
		echo json_encode($validasi);
	}

	function do_upload()
	{
		$aksi_modul = 'ubah';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$config['overwrite'] = FALSE;
		$config['upload_path'] = $this->config->item("upload_path"); //'./uploads/';
		$config['allowed_types'] = 'xls';
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('file')) {
			echo '<div id="status_error">error</div>';
			echo '<div id="message">' . $this->upload->display_errors() . '</div>';
		} else {
			$kode_periode = $this->input->post('kode_periode');
			$upload = $this->upload->data();
			$this->load->library('excel_reader');
			$this->excel_reader->setOutputEncoding('CP1251');
			$file = $upload['full_path'];
			$this->excel_reader->read($file);
			$data = $this->excel_reader->sheets[0];
			$data_xls_error = "";
			$update = array();
			$insert = array();
			$no_urut = 0;
			$sukses = 0;
			$valid = true;
			$hal = "<ul style='list-style: circle'>";

			$ref_jnsKode = array("1", "2", "3");
			$ref_is_tagihan_aktif = array("1", "0");

			$waktu_berlaku = $this->input->post("awal");
			$waktu_berakhir = $this->input->post("akhir");

			//echo $data['cells'][6][2]; die;

			//cek apakah format waktunya sudah benar
			if (cekFormatTanggal($waktu_berlaku) && cekFormatTanggal($waktu_berakhir) && (new DateTime($waktu_berakhir) > new DateTime($waktu_berlaku))) {
				for ($i = 8; $i <= $data['numRows']; $i++) {
					$no_urut++;
					//echo $kode_periode." ".$data['cells'][$i][2]." ".$data['cells'][$i][4]." ".$data['cells'][$i][5]." ".$data['cells'][$i][6];
					if ($this->periode->cekPeriode($kode_periode) && isset($data['cells'][$i][2]) && isset($data['cells'][$i][4]) && isset($data['cells'][$i][5]) && isset($data['cells'][$i][6])) {
						//ambil nama periode berdasarkan kode_periode
						$periode = $this->periode->getPeriode($kode_periode);
						$nomor_induk = trim($data['cells'][$i][2]);
						$is_tagihan_aktif = $data['cells'][$i][4];
						$total_nilai_tagihan = $data['cells'][$i][5];
						$jnsKode = $data['cells'][$i][6];
						$keringanan_ukt = (isset($data['cells'][$i][7]) ? $data['cells'][$i][7] : "0");

						$tagihan = false;
						//cek jenis template apakah tagihan maba atau mhs lama
						if ($data['cells'][6][2] == "NIM") {
							//cek apakah no induk ini ada di simari
							if ($this->simari->cek_mhs_aktif($nomor_induk)) {
								$detail = $this->simari->cek_detail_mhs($nomor_induk);
								$tagihan = true;
							}
						} else if ($data['cells'][6][2] == "No Ujian") {
							//cek apakah no ujian ini terdaftar di sirema
							if (count($this->database->cek_maba($nomor_induk, $kode_periode)) == 1) {
								$detail = $this->database->detail_maba($nomor_induk, $kode_periode);
								$tagihan = true;
							}
						}

						if (in_array($is_tagihan_aktif, $ref_is_tagihan_aktif) && in_array($jnsKode, $ref_jnsKode)) {
							//jika ada tagihan
							if ($tagihan) {
								$jenjang = $detail->strata;
								if ($this->role == 'admin_s1' && ($jenjang == 'S2' || $jenjang == 'S3')) {
									$valid = false;
									$hal .= "<li>- Record baris no. " . $no_urut . " : Anda hanya dapat menambah/mengupdate data D3/S1/Profesi/Spesialis</li>";
									continue;
								} else if ($this->role == 'admin_s2' && $jenjang != 'S2' && $jenjang != 'S3') {
									$valid = false;
									$hal .= "<li>- Record baris no. " . $no_urut . " : Anda hanya dapat menambah/mengupdate data S1/S2</li>";
									continue;
								} else {
									$insert = array(
										"id_record_tagihan" => substr($kode_periode, 2, 3) . "01" . nim($detail->nim),
										"nomor_pembayaran" => nim($detail->nim),
										"nama" => $detail->nama,
										"kode_fakultas" => $detail->kode_fakultas,
										"nama_fakultas" => $detail->nama_fakultas,
										"kode_prodi" => $detail->kode_prodi,
										"nama_prodi" => $detail->nama_prodi,
										"nama_periode" => $periode['nama_periode'],
										"kode_periode" => $periode['kode_periode'],
										"nomor_induk" => $detail->nim,
										"strata" => $detail->strata,
										"angkatan" => $detail->angkatan,
										"is_tagihan_aktif" => $is_tagihan_aktif,
										"urutan_antrian" => 1,
										"waktu_berlaku" => (new datetime($this->input->post("awal")))->format('Y-m-d H:i:s'),
										"waktu_berakhir" => (new datetime($this->input->post("akhir")))->format('Y-m-d H:i:s'),
										"total_nilai_tagihan" => intval(str_replace(".", "", $total_nilai_tagihan)),
										"pembayaran_atau_voucher" => "PEMBAYARAN",
										"jnsKode" => $jnsKode,
										"keterangan" => ($this->input->post("keterangan") ? $this->input->post("keterangan") : "UKT"),
										"keringanan_ukt" => $keringanan_ukt,
									);
									//print_r($insert); die;

									$this->database->replace($insert);
									$sukses++;
								}
							} else {
								$valid = false;
								$hal .= "<li>- Record baris no. " . $no_urut . " : NIM yang anda cari tidak ditemukan pada database mahasiswa, atau Mahasiswa tersebut tidak aktif</li>";
								continue;
							}
						} else {
							$valid = false;
							$hal .= "<li>- Record baris no. " . $no_urut . " : Isian status tagihan tidak valid, atau Isian status mahasiswa tidak valid</li>";
							continue;
						}
					} else {
						$valid = false;
						$hal .= "<li>- Record baris no. " . $no_urut . " : Periode salah input, atau Data pada berkas unggahan belum lengkap</li>";
						continue;
					}
				}
			} else {
				$valid = false;
				$hal .= "<li>Format tanggal salah, atau</li>";
				$hal .= "<li>Isian tanggal belum tepat. Isian Waktu Berlaku harus lebih awal dibanding Waktu Berakhir</li>";
			}
			$hal .= "</ul>";

			if ($hal == "<ul style='list-style: circle'></ul>") {
				$jumlah = $no_urut;
				echo '<br><div class="alert alert-success">Berhasil memperbarui ' . $jumlah . ' baris data tagihan</div>';
				//unlink($upload['full_path']);				
			} else {
				$jumlah = $no_urut;
				unlink($upload['full_path']);
				echo '<br><div class="alert alert-success">Berhasil memperbarui ' . $sukses . ' baris data tagihan</div>';
				echo '<div id="box_gagal">';
				echo '<div id="message" class="alert alert-danger">' . ($jumlah - $sukses) . ' data gagal tersimpan. Harap periksa kembali data anda. <br/>' . $hal . '</div>';
				echo '</div>';
			}
		}
	}

	function do_upload_plagiasi()
	{
		$aksi_modul = 'ubah';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$config['overwrite'] = FALSE;
		$config['upload_path'] = $this->config->item("upload_path"); //'./uploads/';
		$config['allowed_types'] = 'xls';
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('file')) {
			echo '<div id="status_error">error</div>';
			echo '<div id="message">' . $this->upload->display_errors() . '</div>';
		} else {
			$kode_periode = $this->session->userdata('user')['periode'];
			$upload = $this->upload->data();
			$this->load->library('excel_reader');
			$this->excel_reader->setOutputEncoding('CP1251');
			$file = $upload['full_path'];
			$this->excel_reader->read($file);
			$data = $this->excel_reader->sheets[0];
			$data_xls_error = "";
			$update = array();
			$insert = array();
			$no_urut = 1;
			$valid = true;
			$hal = "<ul>";

			$ref_jnsKode = array("1", "2", "3");
			$ref_is_tagihan_aktif = array("1", "0");

			$waktu_berlaku = $this->input->post("awal");
			$waktu_berakhir = $this->input->post("akhir");

			//echo $data['cells'][6][2]; die;

			//cek apakah format waktunya sudah benar
			if (cekFormatTanggal($waktu_berlaku) && cekFormatTanggal($waktu_berakhir) && (new DateTime($waktu_berakhir) > new DateTime($waktu_berlaku))) {
				for ($i = 8; $i <= $data['numRows']; $i++) {
					//echo $kode_periode." ".$data['cells'][$i][2]." ".$data['cells'][$i][4]." ".$data['cells'][$i][5]." ".$data['cells'][$i][6];
					if ($this->periode->cekPeriode($kode_periode) && isset($data['cells'][$i][2]) && isset($data['cells'][$i][4]) && isset($data['cells'][$i][5]) && isset($data['cells'][$i][6])) {
						//ambil nama periode berdasarkan kode_periode
						$periode = $this->periode->getPeriode($kode_periode);
						$nomor_induk = trim($data['cells'][$i][2]);
						$is_tagihan_aktif = $data['cells'][$i][4];
						$total_nilai_tagihan = $data['cells'][$i][5];
						$jnsKode = $data['cells'][$i][6];

						$tagihan = false;
						//cek jenis template apakah tagihan maba atau mhs lama
						if ($data['cells'][6][2] == "NIM") {
							//cek apakah no induk ini ada di simari
							if ($this->simari->cek_mhs_aktif($nomor_induk)) {
								$detail = $this->simari->cek_detail_mhs($nomor_induk);
								$tagihan = true;
							}
						} else if ($data['cells'][6][2] == "No Ujian") {
							//cek apakah no ujian ini terdaftar di sirema
							if (count($this->database->cek_maba($nomor_induk, $kode_periode)) == 1) {
								$detail = $this->database->detail_maba($nomor_induk, $kode_periode);
								$tagihan = true;
							}
						}

						if (in_array($is_tagihan_aktif, $ref_is_tagihan_aktif) && in_array($jnsKode, $ref_jnsKode)) {
							//jika ada tagihan
							if ($tagihan) {
								$jenjang = $detail->strata;
								if ($this->role == 'admin_s1' && ($jenjang == 'S2' || $jenjang == 'S3')) {
									$valid = false;
									$hal .= "<li>Anda hanya dapat menambah/mengupdate data D3/S1/Profesi/Spesialis, atau";
									$hal .= "<li>Template yang anda upload salah</li>";
									break;
								} else if ($this->role == 'admin_s2' && $jenjang != 'S2' && $jenjang != 'S3') {
									$valid = false;
									$hal .= "<li>Anda hanya dapat menambah/mengupdate data S1/S2, atau";
									$hal .= "<li>Template yang anda upload salah</li>";
									break;
								} else {
									$insert = array(
										"id_record_tagihan" => "9404" . substr($kode_periode, 2, 3) . nim($detail->nim),
										"nomor_pembayaran" => nim($detail->nim),
										"nama" => $detail->nama,
										"kode_fakultas" => $detail->kode_fakultas,
										"nama_fakultas" => $detail->nama_fakultas,
										"kode_prodi" => $detail->kode_prodi,
										"nama_prodi" => $detail->nama_prodi,
										"nama_periode" => $periode['nama_periode'],
										"kode_periode" => $periode['kode_periode'],
										"nomor_induk" => $detail->nim,
										"strata" => $detail->strata,
										"angkatan" => $detail->angkatan,
										"is_tagihan_aktif" => $is_tagihan_aktif,
										"urutan_antrian" => 1,
										"waktu_berlaku" => (new datetime($this->input->post("awal")))->format('Y-m-d H:i:s'),
										"waktu_berakhir" => (new datetime($this->input->post("akhir")))->format('Y-m-d H:i:s'),
										"total_nilai_tagihan" => intval(str_replace(".", "", $total_nilai_tagihan)),
										"pembayaran_atau_voucher" => "PEMBAYARAN",
										"jnsKode" => $jnsKode,
										"keterangan" => $this->input->post("keterangan"),
									);
									//print_r($insert); die;

									$this->database->replace($insert);
								}
							} else {
								$valid = false;
								$hal .= "<li>NIM yang anda cari tidak ditemukan pada database mahasiswa, atau</li>";
								$hal .= "<li>Mahasiswa tersebut tidak aktif, atau</li>";
								$hal .= "<li>Template yang anda upload salah</li>";
								break;
							}
						} else {
							$valid = false;
							$hal .= "<li>Isian status tagihan tidak valid, atau</li>";
							$hal .= "<li>Isian status mahasiswa tidak valid</li>";
							break;
						}
					} else {
						$valid = false;
						$hal .= "<li>Periode salah input, atau</li>";
						$hal .= "<li>Data pada berkas unggahan belum lengkap</li>";
						break;
					}
					$no_urut++;
				}
			} else {
				$valid = false;
				$hal .= "<li>Format tanggal salah, atau</li>";
				$hal .= "<li>Isian tanggal belum tepat. Isian Waktu Berlaku harus lebih awal dibanding Waktu Berakhir</li>";
			}
			$hal .= "</ul>";

			if ($valid) {
				$jumlah = $no_urut - 1;
				echo '<br><div class="alert alert-success">Berhasil memperbarui ' . $jumlah . ' baris data tagihan</div>';
				//unlink($upload['full_path']);				
			} else {
				unlink($upload['full_path']);
				echo '<div id="box_gagal">';
				echo '<div id="message">Data gagal tersimpan. Record pada baris no. ' . $no_urut . ' belum valid karena : ' . $hal . ' Harap periksa kembali data anda.</div>';
				echo '</div>';
			}
		}
	}
}
