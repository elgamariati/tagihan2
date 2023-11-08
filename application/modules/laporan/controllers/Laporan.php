<?php

class Laporan extends MY_Controller
{
	private $modul = 'laporan';
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
		$this->load->model('Laporan_m', 'database');
		$this->load->model('periode/Periode_m', 'periode');
		$this->load->model('simari/Simari_m', 'simari');
		$this->load->model('atur_jenjang/Atur_jenjang_m', 'atur_jenjang');
	}

	public function detail_pembayaran($periode = null, $keterangan = null, $fakultas = null, $prodi = null, $tanggal_mulai = null, $tanggal_akhir = null)
	{
		// Jika Periode tidak tersedia
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		if ($this->input->is_ajax_request()) {
			// Filter Keterangan & Periode
			$where['kode_periode'] = $periode;
			if ($keterangan !== 'semua') {
				$where['keterangan'] = urldecode($keterangan);
			}
			// Filter Fakultas & Prodi
			if ($fakultas !== 'semua') {
				$where['kode_fakultas'] = $fakultas;
			}
			if ($prodi !== 'semua') {
				$where['kode_prodi'] = $prodi;
			}
			// Filter Fakultas & Prodi
			if ($tanggal_mulai !== 'semua') {
				$where['tanggal_mulai'] = (new DateTime(urldecode($tanggal_mulai)))->format('Y-m-d H:i:s');
			}
			if ($tanggal_akhir !== 'semua') {
				$where['tanggal_akhir'] = (new DateTime(urldecode($tanggal_akhir)))->format('Y-m-d H:i:s');
			}

			ini_set('memory_limit', '-1');
			//$where['jnsKode'] = 1;
			$get_kode_bank = $this->database->custom_get_datatables($where);
			$data = array();
			$no = $_POST['start'];
			foreach ($get_kode_bank->result() as $gkb) {
				$no++;
				$row = array();
				$row[] = $no;
				$row[] = $gkb->nomor_induk;
				$row[] = $gkb->nama;
				$row[] = rupiah($gkb->total_nilai_pembayaran);
				$row[] = (new datetime($gkb->waktu_transaksi))->format('d-m-Y H:i:s');
				$row[] = $gkb->keterangan;
				$row[] = $gkb->kanal_bayar_bank;
				$row[] = $gkb->kode_bank;

				//$row[] = $tagihan->id_record_tagihan;
				// $row[] = '
				// 		  <a href="#" class="btn" title="Detail" onclick="detail(\'' . $gkb->id_record_tagihan . '\')"><span class="fa fa-search" style="color:#1bb399"></span></a>';
				$data[] = $row;
			}
			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->database->custom_count_all($where),
				"recordsFiltered" => $this->database->count_filtered($where),
				"data" => $data,
			);
			echo json_encode($output);
		} else {
			$periode = $this->session->userdata('user')['periode'];

			$data["ref_fakultas"] = $this->simari->get_fakultas();
			$data['s_periode'] = $periode;
			$data['periode'] = $this->database->get_periode()->result();
			$data['sess'] = $this->session->userdata()['user'];
			$data['kode_periode'] = $this->session->userdata('user')['periode'];
			$data["label_nama_periode"] = null;
			$data["fakultas"] = $this->simari->get_fakultas();
			$model = $this->periode->getOneSelect(array('kode_periode' => $data['kode_periode']), "*", TRUE, TRUE);
			$data["label_nama_periode"] = ($model["nama_periode"] == null ? "" : "<b>Periode " . $model["nama_periode"] . "</b>");
			$data["label_jenjang"] = $this->get_label_jenjang();
			$this->layout->render('Laporan_detail_v', $data);
		}
	}

	public function keringanan_ukt($periode, $keterangan = null)
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		if ($this->input->is_ajax_request()) {
			if ($keterangan) {
				$where['keterangan'] = urldecode($keterangan);
			}

			$where['kode_periode'] = $periode;
			ini_set('memory_limit', '-1');


			//$where['jnsKode'] = 1;
			$get_kode_bank = $this->database->custom_get_datatables_ku($where);
			$data = array();
			$no = $_POST['start'];
			foreach ($get_kode_bank->result() as $gkb) {
				$no++;
				$row = array();
				$row[] = $no;
				$row[] = $gkb->nomor_induk;
				$row[] = $gkb->nama;
				$row[] = rupiah($gkb->total_nilai_tagihan);
				$row[] = $gkb->keterangan;

				//$row[] = $tagihan->id_record_tagihan;
				// $row[] = '
				// 		  <a href="#" class="btn" title="Detail" onclick="detail(\'' . $gkb->id_record_tagihan . '\')"><span class="fa fa-search" style="color:#1bb399"></span></a>';
				$data[] = $row;
			}
			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->database->custom_count_all_ku($where),
				"recordsFiltered" => $this->database->count_filtered_ku($where),
				"data" => $data,
			);
			echo json_encode($output);
		} else {
			$data['s_periode'] = $periode;
			$data['periode'] = $this->database->get_periode()->result();
			$data['sess'] = $this->session->userdata()['user'];
			$data['kode_periode'] = $this->session->userdata('user')['periode'];
			$data["label_nama_periode"] = null;
			$data["fakultas"] = $this->simari->get_fakultas();
			$model = $this->periode->getOneSelect(array('kode_periode' => $data['kode_periode']), "*", TRUE, TRUE);
			$data["label_nama_periode"] = ($model["nama_periode"] == null ? "" : "<b>Periode " . $model["nama_periode"] . "</b>");
			$data["label_jenjang"] = $this->get_label_jenjang();
			$this->layout->render('Laporan_keringanan_ukt_v', $data);
		}
	}

	public function detail_pembayaran_admisi($periode, $like)
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		if ($this->input->is_ajax_request()) {
			// if ($keterangan) {
			// 	$where['keterangan'] = urldecode($keterangan);
			// }
			$id_rec = $like;
			$where['kode_periode'] = $periode;
			ini_set('memory_limit', '-1');


			//$where['jnsKode'] = 1;
			$get_kode_bank = $this->database->custom_get_datatables_admisi($where, $id_rec);

			// echo "<pre>";
			// print_r($this->db->last_query());
			// echo "</pre>";
			// exit();

			$data = array();
			$no = $_POST['start'];
			foreach ($get_kode_bank->result() as $gkb) {
				$no++;
				$np = substr($gkb->nomor_pembayaran, 0, 2);
				$row = array();
				$row[] = $no;
				$row[] = $gkb->nomor_induk;
				$row[] = $gkb->nama;
				$row[] = rupiah($gkb->total_nilai_pembayaran);
				$row[] = (new datetime($gkb->waktu_transaksi))->format('d-m-Y H:i:s');
				$row[] = $np == "91" ? "ADMISI S1 & D3" : ($np == "92" ? "ADMISI PROFESI" : ($np == "93" ? "ADMISI PASCA" : "Lainnya"));
				$row[] = $gkb->kanal_bayar_bank;
				$row[] = $gkb->kode_bank;

				//$row[] = $tagihan->id_record_tagihan;
				// $row[] = '
				// 		  <a href="#" class="btn" title="Detail" onclick="detail(\'' . $gkb->id_record_tagihan . '\')"><span class="fa fa-search" style="color:#1bb399"></span></a>';
				$data[] = $row;
			}
			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->database->custom_count_all_admisi($where, $id_rec),
				"recordsFiltered" => $this->database->count_filtered_admisi($where, $id_rec),
				"data" => $data,
			);
			echo json_encode($output);
		} else {
			$data['s_periode'] = $periode;
			$data['periode'] = $this->database->get_periode()->result();
			$data['sess'] = $this->session->userdata()['user'];
			$data['kode_periode'] = $this->session->userdata('user')['periode'];
			$data["label_nama_periode"] = null;
			$data["fakultas"] = $this->simari->get_fakultas();
			$model = $this->periode->getOneSelect(array('kode_periode' => $data['kode_periode']), "*", TRUE, TRUE);
			$data["label_nama_periode"] = ($model["nama_periode"] == null ? "" : "<b>Periode " . $model["nama_periode"] . "</b>");
			$data["label_jenjang"] = $this->get_label_jenjang();
			$this->layout->render('Laporan_detail_admisi_v', $data);
		}
	}

	public function detail_tidak_bayar($keterangan = null)
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		if ($this->input->is_ajax_request()) {
			if ($keterangan) {
				$where['keterangan'] = urldecode($keterangan);
			}

			$where['kode_periode'] = $this->session->userdata('user')['periode'];
			ini_set('memory_limit', '-1');


			//$where['jnsKode'] = 1;
			$get_kode_bank = $this->database->custom_get_datatables_tidak_bayar($where);
			// echo "<pre>";
			// print_r($this->db->last_query());
			// echo "</pre>";
			// exit();

			$data = array();
			$no = $_POST['start'];
			foreach ($get_kode_bank->result() as $gkb) {
				$no++;
				$row = array();
				$row[] = $no;
				$row[] = $gkb->nomor_induk;
				$row[] = $gkb->nama;
				$row[] = rupiah($gkb->total_nilai_tagihan);
				$row[] = "-";
				$row[] = $gkb->keterangan;
				$row[] = "-";
				$row[] = "-";

				//$row[] = $tagihan->id_record_tagihan;
				// $row[] = '
				// 		  <a href="#" class="btn" title="Detail" onclick="detail(\'' . $gkb->id_record_tagihan . '\')"><span class="fa fa-search" style="color:#1bb399"></span></a>';
				$data[] = $row;
			}
			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->database->custom_count_all_tidak_bayar($where),
				"recordsFiltered" => $this->database->count_filtered_tidak_bayar($where),
				"data" => $data,
			);
			echo json_encode($output);
		} else {
			$data['sess'] = $this->session->userdata()['user'];
			$data['kode_periode'] = $this->session->userdata('user')['periode'];
			$data['periode'] = $this->database->get_periode()->result();
			$data["label_nama_periode"] = null;
			$data["fakultas"] = $this->simari->get_fakultas();
			$model = $this->periode->getOneSelect(array('kode_periode' => $data['kode_periode']), "*", TRUE, TRUE);
			$data["label_nama_periode"] = ($model["nama_periode"] == null ? "" : "<b>Periode " . $model["nama_periode"] . "</b>");
			$data["label_jenjang"] = $this->get_label_jenjang();
			$this->layout->render('Laporan_detail_tidak_bayar_v', $data);
		}
	}

	public function pembayaran($keterangan = null)
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		if ($this->input->is_ajax_request()) {
			$where['kode_periode'] = $this->session->userdata('user')['periode'];
			$where['keterangan'] = urldecode($keterangan);
			$group_by['kode_bank'] = 'kode_bank';
			ini_set('memory_limit', '-1');


			//$where['jnsKode'] = 1;
			$get_kode_bank = $this->database->get_pembayaran($where, $group_by)->result();
			unset($group_by['kode_bank']);
			$data = array();
			$no = 0;
			foreach ($get_kode_bank as $gkb) {
				$no++;
				$row = array();
				$where['kode_bank'] = $gkb->kode_bank;
				$get_list = $this->database->get_pembayaran($where);
				$jumlah = 0;
				foreach ($get_list->result() as $gl) {
					$jumlah = $jumlah + $gl->total_nilai_tagihan;
				}

				$row[] = $no;
				$row[] = $gkb->kode_bank;
				$row[] = $get_list->num_rows();
				$row[] = rupiah($jumlah);
				//$row[] = $tagihan->id_record_tagihan;
				// $row[] = '
				// 		  <a href="#" class="btn" title="Detail" onclick="detail(\'' . $gkb->id_record_tagihan . '\')"><span class="fa fa-search" style="color:#1bb399"></span></a>';
				$data[] = $row;
			}
			$output = array(
				"data" => $data,
			);
			echo json_encode($output);
		} else {
			$data['sess'] = $this->session->userdata()['user'];
			$data['kode_periode'] = $this->session->userdata('user')['periode'];
			$data["label_nama_periode"] = null;
			$data["fakultas"] = $this->simari->get_fakultas();
			$model = $this->periode->getOneSelect(array('kode_periode' => $data['kode_periode']), "*", TRUE, TRUE);
			$data["label_nama_periode"] = ($model["nama_periode"] == null ? "" : "<b>Periode " . $model["nama_periode"] . "</b>");
			$data["label_jenjang"] = $this->get_label_jenjang();
			$this->layout->render('Laporan_detail_v', $data);
		}
	}

	public function mhs_aktif()
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		if ($this->input->is_ajax_request()) {
			$get_list = $this->database->ma_dtb()->result();

			$data = array();
			$no = $_POST['start'];
			foreach ($get_list as $gl) {
				$no++;
				$row = array();
				$row[] = $no;
				$row[] = $gl->mhsNiu;
				$row[] = $gl->mhsNama;
				$row[] = $gl->mhsAngkatan;
				$row[] = $gl->prodiJjarKode;
				$row[] = $gl->prodi;
				$row[] = $gl->fakNamaResmi;

				//$row[] = $tagihan->id_record_tagihan;
				// $row[] = '
				// 		  <a href="#" class="btn" title="Detail" onclick="detail(\'' . $gkb->id_record_tagihan . '\')"><span class="fa fa-search" style="color:#1bb399"></span></a>';
				$data[] = $row;
			}
			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->database->ma_custom_count_all(),
				"recordsFiltered" => $this->database->ma_count_filtered(),
				"data" => $data,
			);
			echo json_encode($output);
		} else {
			$data['sess'] = $this->session->userdata()['user'];
			$data['kode_periode'] = $this->session->userdata('user')['periode'];
			$data["label_nama_periode"] = null;
			$data["fakultas"] = $this->simari->get_fakultas();
			$model = $this->periode->getOneSelect(array('kode_periode' => $data['kode_periode']), "*", TRUE, TRUE);
			$data["label_nama_periode"] = ($model["nama_periode"] == null ? "" : "<b>Periode " . $model["nama_periode"] . "</b>");
			$data["label_jenjang"] = $this->get_label_jenjang();
			$this->layout->render('Laporan_mhs_aktif_v', $data);
		}
	}

	public function data_mahasiswa()
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		if ($this->input->is_ajax_request()) {
			$get_list = $this->database->mhs_dtb()->result();

			$data = array();
			$no = $_POST['start'];
			foreach ($get_list as $gl) {
				$no++;
				$row = array();
				$row[] = $no;
				$row[] = $gl->mhsNomorTes;
				$row[] = $gl->mhsNiu;
				$row[] = $gl->mhsNama;
				$row[] = $gl->mhsAngkatan;
				$row[] = $gl->mhsProdiKode;
				$row[] = $gl->prodiJjarKode;
				$row[] = $gl->prodi;
				$row[] = $gl->fakNamaResmi;
				// $row[] = $gl->mhsJenisKelamin;
				// $row[] = $gl->mhsKotaKodeLahir;
				// $row[] = $gl->mhsTanggalLahir;
				// $row[] = $gl->mhsNoHp;
				// $row[] = $gl->mhsortuNoTelpOrangTua;
				// $row[] = $gl->mhsortuNoTelpWali;
				// $row[] = $gl->mhsEmail;


				//$row[] = $tagihan->id_record_tagihan;
				// $row[] = '
				// 		  <a href="#" class="btn" title="Detail" onclick="detail(\'' . $gkb->id_record_tagihan . '\')"><span class="fa fa-search" style="color:#1bb399"></span></a>';
				$data[] = $row;
			}
			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->database->mhs_custom_count_all(),
				"recordsFiltered" => $this->database->mhs_count_filtered(),
				"data" => $data,
			);
			echo json_encode($output);
		} else {
			$data['sess'] = $this->session->userdata()['user'];
			$data['kode_periode'] = $this->session->userdata('user')['periode'];
			$data["label_nama_periode"] = null;
			$data["fakultas"] = $this->simari->get_fakultas();
			$model = $this->periode->getOneSelect(array('kode_periode' => $data['kode_periode']), "*", TRUE, TRUE);
			$data["label_nama_periode"] = ($model["nama_periode"] == null ? "" : "<b>Periode " . $model["nama_periode"] . "</b>");
			$data["label_jenjang"] = $this->get_label_jenjang();
			$this->layout->render('Laporan_data_mahasiswa', $data);
		}
	}

	function get_prodi()
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$data = $this->database->get_prodi($this->input->post('fakultas'), null, true);

		$html = "<option value=''>Pilih program studi</option>";
		foreach ($data as $row) {
			$html .= "<option value='" . $row->prodiKode . "'>" . $row->prodiJjarKode . " - " . $row->prodiNamaResmi . "</option>";
		}
		echo $html;
	}

	function get_prodi_new()
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$data = $this->database->get_prodi($this->input->post('fakultas'), null, true);
		$dropdown[] = [
			'key' => 'semua',
			'value' => 'Semua Program Studi'
		];
		foreach ($data as $dtKey => $dt) {
			$dropdown[] = [
				'key' => $dt->prodiKode,
				'value' => $dt->prodiJjarKode . ' - ' . $dt->prodiNamaResmi
			];
		}
		echo json_encode($dropdown);
	}

	public function download_xls_tidak_bayar($jenjang, $keterangan, $kode_fakultas = null, $kode_prodi = null, $kode_periode)
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		ini_set('memory_limit', '-1');

		$where = null;
		if ($jenjang !== "0") {
			$where['jenjang'] = $jenjang;
		}
		if ($keterangan !== "0") {
			$where['keterangan'] = $keterangan;
		}
		if ($kode_fakultas !== "0") {
			$where['kode_fakultas'] = $kode_fakultas;
		}
		if ($kode_prodi !== "0") {
			$where['kode_prodi'] = $kode_prodi;
		}
		$where['kode_periode'] = $kode_periode;

		$list = $this->database->get_pembayaran_download_tidak_bayar($where);

		// echo "<pre>";
		// print_r($list->result());
		// echo "</pre>";
		// exit();
		if ($list->num_rows() == 0) {
			show_error('Tidak ada data yang dapat dicetak.');
			exit();
		}
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
		$objTpl = PHPExcel_IOFactory::load("./assets/template_download_pembayaran.xls");
		$objTpl->setActiveSheetIndex(0); //set first sheet as active
		$objTpl->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1); //auto height cell
		$objTpl->getActiveSheet()->getProtection()->setPassword('sepertinyasayapusingsekalimelihattingkahlakukmusudahcukup');
		$objTpl->getActiveSheet()->getStyle("A1:H7")->getProtection()->setLocked(true);

		$no = 1;
		$rowID = 8;
		foreach ($list->result() as $row) {
			// $mhs = $this->database->get_info_mhs($row->nomor_induk)->row();
			$objTpl->getActiveSheet()->setCellValue('A' . $rowID, $no);
			$objTpl->getActiveSheet()->setCellValue('B' . $rowID, $row->nomor_induk);
			$objTpl->getActiveSheet()->setCellValue('C' . $rowID, $row->nama);
			$objTpl->getActiveSheet()->setCellValue('D' . $rowID, $row->total_nilai_tagihan);
			$objTpl->getActiveSheet()->setCellValue('E' . $rowID, "-");
			$objTpl->getActiveSheet()->setCellValue('F' . $rowID, $row->keterangan);
			$objTpl->getActiveSheet()->setCellValue('G' . $rowID, "-");
			$objTpl->getActiveSheet()->setCellValue('H' . $rowID, "-");
			$no++;
			$rowID++;
		}
		$objTpl->getActiveSheet()->getPageSetup()->setPrintArea("A1:" . "H" . ($rowID + 1));
		$objTpl->getActiveSheet()->getPageSetup()->setPrintArea("A1:" . "H" . ($rowID + $v));
		$objTpl->getActiveSheet()->getProtection()->setPassword('sepertinyasayapusingsekalimelihattingkahlakukmusudahcukup');
		$objTpl->getActiveSheet()->getProtection()->setSheet(true);
		$rowID--;
		$objTpl->getActiveSheet()->getStyle("A8:" . "A" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("B8:" . "B" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("C8:" . "C" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("D8:" . "D" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("E8:" . "E" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("F8:" . "F" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("G8:" . "G" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("H8:" . "H" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

		header("Content-Type: application/vnd.ms-excel");
		header('Content-Disposition: attachment;filename="list_belum_bayar_' . (new dateTime())->format('d-m-Y H-i-s') . '.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = new PHPExcel_Writer_Excel5($objTpl);
		ob_end_clean();
		$objWriter->save('php://output');
	}

	public function download_xls_ku($jenjang, $keterangan, $kode_fakultas = null, $kode_prodi = null, $kode_periode)
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		ini_set('memory_limit', '-1');

		$where = null;
		if ($jenjang !== "0") {
			$where['jenjang'] = $jenjang;
		}
		if ($keterangan !== "0") {
			$where['keterangan'] = $keterangan;
		}
		if ($kode_fakultas !== "0") {
			$where['kode_fakultas'] = $kode_fakultas;
		}
		if ($kode_prodi !== "0") {
			$where['kode_prodi'] = $kode_prodi;
		}
		$where['kode_periode'] = $kode_periode;

		$list = $this->database->get_pembayaran_download_ku($where);

		// echo "<pre>";
		// print_r($list->result());
		// echo "</pre>";
		// exit();
		if ($list->num_rows() == 0) {
			show_error('Tidak ada data yang dapat dicetak.');
			exit();
		}
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
		$objTpl = PHPExcel_IOFactory::load("./assets/laporan_keringanan_ukt.xls");
		$objTpl->setActiveSheetIndex(0); //set first sheet as active
		$objTpl->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1); //auto height cell
		$objTpl->getActiveSheet()->getProtection()->setPassword('sepertinyasayapusingsekalimelihattingkahlakukmusudahcukup');
		$objTpl->getActiveSheet()->getStyle("A1:H7")->getProtection()->setLocked(true);

		$no = 1;
		$rowID = 8;
		foreach ($list->result() as $row) {
			// $mhs = $this->database->get_info_mhs($row->nomor_induk)->row();
			$objTpl->getActiveSheet()->setCellValue('A' . $rowID, $no);
			$objTpl->getActiveSheet()->setCellValue('B' . $rowID, $row->nomor_induk);
			$objTpl->getActiveSheet()->setCellValue('C' . $rowID, $row->nama);
			$objTpl->getActiveSheet()->setCellValue('D' . $rowID, $row->total_nilai_tagihan);
			$objTpl->getActiveSheet()->setCellValue('E' . $rowID, $row->keterangan);
			$no++;
			$rowID++;
		}
		$objTpl->getActiveSheet()->getPageSetup()->setPrintArea("A1:" . "E" . ($rowID + 1));
		$objTpl->getActiveSheet()->getPageSetup()->setPrintArea("A1:" . "E" . ($rowID + $v));
		$objTpl->getActiveSheet()->getProtection()->setPassword('sepertinyasayapusingsekalimelihattingkahlakukmusudahcukup');
		$objTpl->getActiveSheet()->getProtection()->setSheet(true);
		$rowID--;
		$objTpl->getActiveSheet()->getStyle("A8:" . "A" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("B8:" . "B" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("C8:" . "C" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("D8:" . "D" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("E8:" . "E" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

		header("Content-Type: application/vnd.ms-excel");
		header('Content-Disposition: attachment;filename="list_tagihan-' . ((new dateTime())->format('d-m-Y h-i-s')) . '.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = new PHPExcel_Writer_Excel5($objTpl);
		ob_end_clean();
		$objWriter->save('php://output');
	}

	public function download_xls($jenjang, $keterangan, $kode_fakultas = null, $kode_prodi = null, $kode_periode, $tanggal_mulai, $tanggal_akhir)
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		ini_set('memory_limit', '-1');

		$where = null;
		$where['kode_periode'] = $kode_periode;
		if ($jenjang !== "semua") {
			$where['jenjang'] = $jenjang;
		}
		if ($keterangan !== "semua") {
			$where['keterangan'] = $keterangan;
		}
		if ($kode_fakultas !== "semua") {
			$where['kode_fakultas'] = $kode_fakultas;
		}
		if ($kode_prodi !== "semua") {
			$where['kode_prodi'] = $kode_prodi;
		}
		if ($tanggal_mulai !== 'semua') {
			$where['tanggal_mulai'] = (new DateTime(urldecode($tanggal_mulai)))->format('Y-m-d H:i:s');
		}
		if ($tanggal_akhir !== 'semua') {
			$where['tanggal_akhir'] = (new DateTime(urldecode($tanggal_akhir)))->format('Y-m-d H:i:s');
		}

		$list = $this->database->get_pembayaran_download($where);

		// echo "<pre>";
		// print_r($this->database->db->last_query());
		// echo "</pre>";
		// die;
		if ($list->num_rows() == 0) {
			show_error('Tidak ada data yang dapat dicetak.');
			exit();
		}
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
		$objTpl = PHPExcel_IOFactory::load("./assets/template_download_pembayaran.xls");
		$objTpl->setActiveSheetIndex(0); //set first sheet as active
		$objTpl->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1); //auto height cell
		$objTpl->getActiveSheet()->getProtection()->setPassword('sepertinyasayapusingsekalimelihattingkahlakukmusudahcukup');
		$objTpl->getActiveSheet()->getStyle("A1:H7")->getProtection()->setLocked(true);

		$no = 1;
		$rowID = 8;
		foreach ($list->result() as $row) {
			// $mhs = $this->database->get_info_mhs($row->nomor_induk)->row();
			$objTpl->getActiveSheet()->setCellValue('A' . $rowID, $no);
			$objTpl->getActiveSheet()->setCellValue('B' . $rowID, $row->nomor_induk);
			$objTpl->getActiveSheet()->setCellValue('C' . $rowID, $row->nama);
			$objTpl->getActiveSheet()->setCellValue('D' . $rowID, $row->total_nilai_pembayaran);
			$objTpl->getActiveSheet()->setCellValue('E' . $rowID, (new datetime($row->waktu_transaksi))->format('d-m-Y H:i:s'));
			$objTpl->getActiveSheet()->setCellValue('F' . $rowID, $row->keterangan);
			$objTpl->getActiveSheet()->setCellValue('G' . $rowID, $row->kanal_bayar_bank);
			$objTpl->getActiveSheet()->setCellValue('H' . $rowID, $row->kode_bank);
			$no++;
			$rowID++;
		}
		$objTpl->getActiveSheet()->getPageSetup()->setPrintArea("A1:" . "H" . ($rowID + 1));
		$objTpl->getActiveSheet()->getPageSetup()->setPrintArea("A1:" . "H" . ($rowID + $v));
		$objTpl->getActiveSheet()->getProtection()->setPassword('sepertinyasayapusingsekalimelihattingkahlakukmusudahcukup');
		$objTpl->getActiveSheet()->getProtection()->setSheet(true);
		$rowID--;
		$objTpl->getActiveSheet()->getStyle("A8:" . "A" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("B8:" . "B" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("C8:" . "C" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("D8:" . "D" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("E8:" . "E" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("F8:" . "F" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("G8:" . "G" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("H8:" . "H" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

		header("Content-Type: application/vnd.ms-excel");
		header('Content-Disposition: attachment;filename="list_-' . ((new dateTime())->format('d-m-Y h-i-s')) . '.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = new PHPExcel_Writer_Excel5($objTpl);
		ob_end_clean();
		$objWriter->save('php://output');
	}

	public function download_xls_admisi($jenjang, $keterangan, $kode_fakultas = null, $kode_prodi = null, $kode_periode)
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		ini_set('memory_limit', '-1');

		$where = null;
		$id_rec = null;
		if ($jenjang !== "0") {
			$where['jenjang'] = $jenjang;
		}
		if ($keterangan !== "0") {
			$id_rec = $keterangan;
		}
		if ($kode_fakultas !== "0") {
			$where['kode_fakultas'] = $kode_fakultas;
		}
		if ($kode_prodi !== "0") {
			$where['kode_prodi'] = $kode_prodi;
		}
		$where['kode_periode'] = $kode_periode;

		$list = $this->database->get_pembayaran_download_admisi($where, $id_rec);

		// echo "<pre>";
		// print_r($list->result());
		// echo "</pre>";
		// exit();
		if ($list->num_rows() == 0) {
			show_error('Tidak ada data yang dapat dicetak.');
			exit();
		}
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
		$objTpl = PHPExcel_IOFactory::load("./assets/template_download_pembayaran.xls");
		$objTpl->setActiveSheetIndex(0); //set first sheet as active
		$objTpl->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1); //auto height cell
		$objTpl->getActiveSheet()->getProtection()->setPassword('sepertinyasayapusingsekalimelihattingkahlakukmusudahcukup');
		$objTpl->getActiveSheet()->getStyle("A1:H7")->getProtection()->setLocked(true);

		$no = 1;
		$rowID = 8;
		foreach ($list->result() as $row) {
			$np = substr($row->nomor_pembayaran, 0, 2);

			// $mhs = $this->database->get_info_mhs($row->nomor_induk)->row();
			$objTpl->getActiveSheet()->setCellValue('A' . $rowID, $no);
			$objTpl->getActiveSheet()->setCellValue('B' . $rowID, $row->nomor_induk);
			$objTpl->getActiveSheet()->setCellValue('C' . $rowID, $row->nama);
			$objTpl->getActiveSheet()->setCellValue('D' . $rowID, $row->total_nilai_pembayaran);
			$objTpl->getActiveSheet()->setCellValue('E' . $rowID, (new datetime($row->waktu_transaksi))->format('d-m-Y H:i:s'));
			$objTpl->getActiveSheet()->setCellValue('F' . $rowID, $np == "91" ? "ADMISI S1 & D3" : ($np == "92" ? "ADMISI PRFOESI" : ($np == "93" ? "ADMISI PASCA" : "Lainnya")));
			$objTpl->getActiveSheet()->setCellValue('G' . $rowID, $row->kanal_bayar_bank);
			$objTpl->getActiveSheet()->setCellValue('H' . $rowID, $row->kode_bank);
			$no++;
			$rowID++;
		}
		$objTpl->getActiveSheet()->getPageSetup()->setPrintArea("A1:" . "H" . ($rowID + 1));
		$objTpl->getActiveSheet()->getPageSetup()->setPrintArea("A1:" . "H" . ($rowID + $v));
		$objTpl->getActiveSheet()->getProtection()->setPassword('sepertinyasayapusingsekalimelihattingkahlakukmusudahcukup');
		$objTpl->getActiveSheet()->getProtection()->setSheet(true);
		$rowID--;
		$objTpl->getActiveSheet()->getStyle("A8:" . "A" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("B8:" . "B" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("C8:" . "C" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("D8:" . "D" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("E8:" . "E" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("F8:" . "F" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("G8:" . "G" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
		$objTpl->getActiveSheet()->getStyle("H8:" . "H" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

		header("Content-Type: application/vnd.ms-excel");
		header('Content-Disposition: attachment;filename="laporan_admisi-' . ((new dateTime())->format('d-m-Y h-i-s')) . '.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = new PHPExcel_Writer_Excel5($objTpl);
		ob_end_clean();
		$objWriter->save('php://output');
	}

	public function mhs_aktif_download($kode_fakultas = null, $kode_prodi = null)
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		ini_set('memory_limit', '-1');

		$where = null;
		if ($kode_fakultas !== "0") {
			$where['kode_fakultas'] = $kode_fakultas;
		}
		if ($kode_prodi !== "0") {
			$where['kode_prodi'] = $kode_prodi;
		}

		$list = $this->database->mhs_aktif_download($where);

		if ($list->num_rows() == 0) {
			show_error('Tidak ada data yang dapat dicetak.');
			exit();
		}
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
		$objTpl = PHPExcel_IOFactory::load("./assets/template_download_mhs_aktif.xls");
		$objTpl->setActiveSheetIndex(0); //set first sheet as active
		$objTpl->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1); //auto height cell
		$objTpl->getActiveSheet()->getProtection()->setPassword('sepertinyasayapusingsekalimelihattingkahlakukmusudahcukup');
		$objTpl->getActiveSheet()->getStyle("A1:H7")->getProtection()->setLocked(true);

		$no = 1;
		$rowID = 8;
		foreach ($list->result() as $row) {
			// $mhs = $this->database->get_info_mhs($row->nomor_induk)->row();
			$objTpl->getActiveSheet()->setCellValue('A' . $rowID, $no);
			$objTpl->getActiveSheet()->setCellValue('B' . $rowID, $row->mhsNiu);
			$objTpl->getActiveSheet()->setCellValue('C' . $rowID, $row->mhsNama);
			$objTpl->getActiveSheet()->setCellValue('D' . $rowID, $row->mhsAngkatan);
			$objTpl->getActiveSheet()->setCellValue('E' . $rowID, $row->prodiJjarKode);
			$objTpl->getActiveSheet()->setCellValue('F' . $rowID, $row->prodi);
			$objTpl->getActiveSheet()->setCellValue('G' . $rowID, $row->fakNamaResmi);
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
		$objTpl->getActiveSheet()->getStyle("G8:" . "G" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

		header("Content-Type: application/vnd.ms-excel");
		header('Content-Disposition: attachment;filename="list_mhs_aktif-' . ((new dateTime())->format('d-m-Y h-i-s')) . '.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = new PHPExcel_Writer_Excel5($objTpl);
		ob_end_clean();
		$objWriter->save('php://output');
	}


	public function data_mahasiswa_download($kode_fakultas = null, $kode_prodi = null)
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		ini_set('memory_limit', '-1');

		$where = null;
		if ($kode_fakultas !== "0") {
			$where['kode_fakultas'] = $kode_fakultas;
		}
		if ($kode_prodi !== "0") {
			$where['kode_prodi'] = $kode_prodi;
		}

		$list = $this->database->data_mhs_download($where);

		if ($list->num_rows() == 0) {
			show_error('Tidak ada data yang dapat dicetak.');
			exit();
		}
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
		$objTpl = PHPExcel_IOFactory::load("./assets/template_download_data_mahasiswa.xls");
		$objTpl->setActiveSheetIndex(0); //set first sheet as active
		$objTpl->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1); //auto height cell
		$objTpl->getActiveSheet()->getProtection()->setPassword('sepertinyasayapusingsekalimelihattingkahlakukmusudahcukup');
		$objTpl->getActiveSheet()->getStyle("A1:H7")->getProtection()->setLocked(true);

		$no = 1;
		$rowID = 8;
		foreach ($list->result() as $row) {
			// $mhs = $this->database->get_info_mhs($row->nomor_induk)->row();
			$objTpl->getActiveSheet()->setCellValue('A' . $rowID, $no);
			$objTpl->getActiveSheet()->setCellValue('B' . $rowID, $row->mhsNomorTes);
			$objTpl->getActiveSheet()->setCellValue('C' . $rowID, $row->mhsNiu);
			$objTpl->getActiveSheet()->setCellValue('D' . $rowID, $row->mhsNama);
			$objTpl->getActiveSheet()->setCellValue('E' . $rowID, $row->mhsAngkatan);
			$objTpl->getActiveSheet()->setCellValue('F' . $rowID, $row->mhsProdiKode);
			$objTpl->getActiveSheet()->setCellValue('G' . $rowID, $row->prodiJjarKode);
			$objTpl->getActiveSheet()->setCellValue('H' . $rowID, $row->prodi);
			$objTpl->getActiveSheet()->setCellValue('I' . $rowID, $row->fakNamaResmi);
			$objTpl->getActiveSheet()->setCellValue('J' . $rowID, $row->mhsJenisKelamin);
			$objTpl->getActiveSheet()->setCellValue('K' . $rowID, $row->mhsKotaKodeLahir);
			$objTpl->getActiveSheet()->setCellValue('L' . $rowID, $row->mhsTanggalLahir);
			$objTpl->getActiveSheet()->setCellValue('M' . $rowID, $row->mhsNoHp);
			$objTpl->getActiveSheet()->setCellValue('N' . $rowID, $row->mhsortuNoTelpOrangTua);
			$objTpl->getActiveSheet()->setCellValue('O' . $rowID, $row->mhsortuNoTelpWali);
			$objTpl->getActiveSheet()->setCellValue('P' . $rowID, $row->mhsEmail);
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
		$objTpl->getActiveSheet()->getStyle("G8:" . "G" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

		header("Content-Type: application/vnd.ms-excel");
		header('Content-Disposition: attachment;filename="daftar_mahasiswa-' . ((new dateTime())->format('d-m-Y h-i-s')) . '.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = new PHPExcel_Writer_Excel5($objTpl);
		ob_end_clean();
		$objWriter->save('php://output');
	}
}
