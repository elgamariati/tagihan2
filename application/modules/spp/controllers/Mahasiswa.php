<?php
class Mahasiswa extends MY_Controller
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

	public function datagrid($request, $where)
	{
		$select = "tagihan.*";
		$data = $this->database->getDataGrid($request, $select, $where);
		return json_encode($data);
	}

	public function index()
	{
		//die("test");
		$data['label_jenjang'] = $this->get_label_jenjang();
		$data["list_periode"] = $this->periode->getPeriode();
		$data["ref_aktif"] = array("1" => "Aktif", "0" => "Tidak Aktif");
		$data["ref_jnsKode"] = array("1" => "Aktif Kuliah", "2" => "Cuti", "3" => "Daftar Admisi");
		//print_r($data); die;
		$this->layout->render('mahasiswa/index', $data);
	}

	public function get_mahasiswa()
	{
		$response["status"] = false;
		$response["msg"] = "NIM yang anda cari tidak ada di database mahasiswa atau statusnya bukan mahasiswa aktif";
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nomor_induk', 'NIM', 'required');

		if ($this->form_validation->run()) {
			$nomor = $this->input->post("nomor_induk");
			$data = $this->simari->get_mhs($nomor);
			$jj = $data['prodiJjarKode'];

			// echo "<pre>";
			// print_r(strtolower($this->session->userdata('user')['jenjang_text']));
			// echo "<br>";
			// print_r(strtolower($jj));
			// echo "<br>";

			// print_r(strstr(strtolower($this->session->userdata('user')['jenjang_text']), strtolower($jj)));
			// echo "</pre>";
			// exit();

			if ($this->simari->cek_mhs_aktif($nomor) && count($data) > 0) {
				if (!strstr(strtolower($this->session->userdata('user')['jenjang_text']), strtolower($jj))) {
					$response["msg"] = $nomor . " merupakan mahasiswa pada jenjang " . $jj;
				}
				// else if ($this->role == 'keuangan_rektorat' && $jj != 'S3' && $jj != 'S2') {
				// 	if ($jj == 'PR') $jj = 'Profesi';
				// 	$response["msg"] = $nomor . " merupakan mahasiswa pada jenjang " . $jj;
				// } 
				else {

					$response["status"] = true;
					$response["data"] = $data;
				}
			}
		}
		echo json_encode($response);
	}

	public function simpan_tagihan()
	{
		if ($this->input->post()) {
			$id_record_tagihan = $this->input->post("id_record_tagihan");
			$this->load->library('form_validation');
			$this->form_validation->set_rules('nomor_induk', 'Nomor Induk', 'required');
			$this->form_validation->set_rules('is_tagihan_aktif', 'Status Tagihan', 'required');
			$this->form_validation->set_rules('jnsKode', 'Status Mahasiswa', 'required');
			$this->form_validation->set_rules('periode', 'Periode', 'required');
			$this->form_validation->set_rules('waktu_berlaku', 'Masa Berlaku', 'required');
			$this->form_validation->set_rules('waktu_berakhir', 'Masa Berakhir', 'required');
			if ($this->form_validation->run()) {
				$waktu_berlaku = $this->input->post("waktu_berlaku");
				$waktu_berakhir = $this->input->post("waktu_berakhir");
				$nomor_induk = $this->input->post("nomor_induk");
				//echo $this->simari->cek_mhs_aktif($nomor_induk); die;

				if ($this->simari->cek_mhs_aktif($nomor_induk) && cekFormatTanggal($waktu_berlaku) && cekFormatTanggal($waktu_berakhir) && (new DateTime($waktu_berakhir) > new DateTime($waktu_berlaku))) {
					$periode = $this->input->post("periode");
					$mhs = $this->simari->get_mhs($nomor_induk);
					$insert["id_record_tagihan"] = substr($periode, 2, 3) . "01" . nim($nomor_induk);
					$insert["nomor_pembayaran"] = nim($nomor_induk);
					$insert["nama"] = $mhs["mhsNama"];
					$insert["kode_fakultas"] = $mhs["fakKode"];
					$insert["nama_fakultas"] = $mhs["fakNamaSingkat"];
					$insert["kode_prodi"] = $mhs["prodiKode"];
					$insert["nama_prodi"] = $mhs["prodiNamaResmi"];

					$periode_db = $this->periode->getPeriode($periode);
					$insert["nama_periode"] = $periode_db["nama_periode"];
					$insert["kode_periode"] = $periode_db["kode_periode"];
					$insert["nomor_induk"] = $nomor_induk;
					$insert["strata"] = $mhs["prodiJjarKode"];
					$insert["angkatan"] = $mhs["mhsAngkatan"];
					$insert["is_tagihan_aktif"] = $this->input->post("is_tagihan_aktif");;
					$insert["jnsKode"] = $this->input->post("jnsKode");;
					$insert["urutan_antrian"] = $this->input->post("urutan_antrian");
					$insert["waktu_berlaku"] = (new dateTime($waktu_berlaku))->format('Y-m-d H:i:s');
					$insert["waktu_berakhir"] = (new dateTime($waktu_berakhir))->format('Y-m-d H:i:s');
					$insert["total_nilai_tagihan"] = intval(str_replace(".", "", $this->input->post("total_nilai_tagihan")));
					$insert["pembayaran_atau_voucher"] = "PEMBAYARAN";
					$insert["keterangan"] = "UKT";
					if ($insert["pembayaran_atau_voucher"] == "VOUCHER") {
						$insert["voucher_nama"] = $insert["nama"];
						$insert["voucher_nama_fakultas"] = $insert["nama_fakultas"];
						$insert["voucher_nama_prodi"] = $insert["nama_prodi"];
						$insert["voucher_nama_periode"] = $insert["nomor_pembayaran"];
					}
					$data["status"] = false;
					//echo $id_record_tagihan;
					if ($id_record_tagihan != null || $id_record_tagihan != "") {
						$where["id_record_tagihan"] = $id_record_tagihan;
						$hasil = $this->database->cek_tagihan($where);
						if ($hasil > 0) {
							if ($this->database->update($id_record_tagihan, $insert)) {
								$data["msg"] = msg(true, "Tagihan berhasil diupdate");
								$data["status"] = true;
							} else $data["msg"] = msg(false, "Tagihan gagal diupdate");
						} else {
							$data["msg"] = msg(false, "Tagihan tidak ditemukan");
						}
					} else {
						// echo "add";
						$where["nomor_pembayaran"] = nim($nomor_induk);
						$where["kode_periode"] = $periode;
						$hasil = $this->database->cek_tagihan($where);
						if ($hasil > 0) {
							$data["msg"] = msg(false, "Tagihan periode ini gagal disimpan karena sudah ada di database");
						} else {
							if ($this->database->insert($insert)) {
								$data["msg"] = msg(true, "Tagihan berhasil disimpan");
								$data["status"] = true;
							} else $data["msg"] = msg(false, "Tagihan gagal disimpan");
						}
					}
				} else {
					$data["status"] = false;
					$data["msg"] = msg(false, validation_errors('<div class="error">', '</div>') . "Data gagal disimpan. Terdapat kesalahan input");
				}
			}
		}
		echo json_encode($data);
	}

	public function ajax_list()
	{
		$data = array();
		$jumlah_data = 0;
		$jumlah_data_terfilter = 0;

		if ($this->input->post("nomor_induk") != null) {
			$where['nomor_pembayaran'] = nim(trim($this->input->post("nomor_induk")));
			//$where['jnsKode'] = 1;
			$list = $this->database->get_datatables($where);
			$no = $_POST['start'];
			foreach ($list as $tagihan) {
				$no++;
				$row = array();
				$row[] = $no;
				$row[] = $tagihan->nomor_pembayaran;
				$row[] = $tagihan->nama_periode;
				$row[] = $tagihan->nama;
				$row[] = $tagihan->nama_prodi;
				$row[] = rupiah($tagihan->total_nilai_tagihan);
				$row[] = $tagihan->id_record_tagihan;
				$row[] = '<div class="dropdown">
							<button class="btn btn-primary" type="button" onclick="edit_modal(\'' . $tagihan->id_record_tagihan . '\')"><i class="fa fa-pencil"></i></button>				
							<button class="btn btn-primary" type="button" onclick="hapus_dialog(\'' . $tagihan->id_record_tagihan . '\')"><i class="fa fa-trash"></i></button>				
						</div>';
				$row[] = $tagihan->is_tagihan_aktif;
				$data[] = $row;
			}
			//$jumlah_data = $this->database->count_all($where);
			//$jumlah_data_terfilter = $this->database->count_filtered($where);
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" =>  $jumlah_data,
			"recordsFiltered" =>  $jumlah_data_terfilter,
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function getTagihan()
	{
		// print_r($this->input->post());
		$id_record_tagihan = $this->input->post("id_record_tagihan");
		$model_tagihan = $this->database->getOneSelect(array('id_record_tagihan' => $id_record_tagihan), "*", TRUE, TRUE);
		$model_tagihan['waktu_berlaku'] = (new dateTime($model_tagihan['waktu_berlaku']))->format('d-m-Y H:i:s');
		$model_tagihan['waktu_berakhir'] = (new dateTime($model_tagihan['waktu_berakhir']))->format('d-m-Y H:i:s');

		echo json_encode($model_tagihan);
	}

	function download_template_mhs_aktif($angkatan, $fakultas)
	{
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
		header('Content-Disposition: attachment;filename="Template_Mahasiswa_aktif.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = new PHPExcel_Writer_Excel5($objTpl);
		ob_end_clean();
		$objWriter->save('php://output');
	}

	function do_upload()
	{
		$periode = $this->input->post('kode_periode');
		$config['overwrite'] = TRUE;
		$config['upload_path'] = $this->config->item("upload_path"); //'./uploads/';
		$config['allowed_types'] = '*';
		//$config['max_size'] = '36000';
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('file')) {
			echo '<div id="status_error">error</div>';
			echo '<div id="message">' . $this->upload->display_errors() . '</div>';
		} else {
			$upload = $this->upload->data();
			$this->load->library('excel_reader');
			$this->excel_reader->setOutputEncoding('CP1251');
			$file = $upload['full_path'];
			$this->excel_reader->read($file);
			$data = $this->excel_reader->sheets[0];
			// $id_template_teks=str_replace(' ', '', $data['cells'][3][2]);
			// $id_template_id=explode('|', $id_template_teks);
			$data_xls_error = "";
			$update = array();
			$insert = array();
			$no_urut = 1;
			$valid = true;
			//echo $data['numRows']."<br />";exit;
			$hal = "";
			for ($i = 8; $i <= $data['numRows']; $i++) {
				if (isset($data['cells'][$i][1])) {
					// $no=$data['cells'][$i][2];
					// echo "1";
					if (isset($data['cells'][$i][2]))
						$no_bayar = $data['cells'][$i][2];
					else {
						$valid = false;
						$hal = "No. Ujian";
						break;
					}
					$parameter = array(
						"nomor_pembayaran" => $no_bayar,
						"kode_periode" => $periode
					);

					if (isset($data['cells'][$i][3]))
						$nomor_induk = trim($data['cells'][$i][3]);
					else {
						$valid = false;
						$hal = "NIM";
						break;
					}

					if (isset($data['cells'][$i][4]))
						$nama = $data['cells'][$i][4];
					else
						$pil1 = null;

					if (isset($data['cells'][$i][5]))
						$kode_fakultas = $data['cells'][$i][5];
					else
						$pil2 = null;

					if (isset($data['cells'][$i][6]))
						$nama_fakultas = $data['cells'][$i][6];
					else
						$pil3 = null;

					if (isset($data['cells'][$i][7]))
						$kode_prodi = $data['cells'][$i][7];
					else
						$pil3 = null;

					if (isset($data['cells'][$i][8]))
						$nama_prodi = $data['cells'][$i][8];
					else
						$pil3 = null;

					if (isset($data['cells'][$i][9]))
						$strata = $data['cells'][$i][9];
					else
						$pil3 = null;

					if (isset($data['cells'][$i][10]))
						$angkatan = $data['cells'][$i][10];
					else
						$pil3 = null;

					if (isset($data['cells'][$i][11]))
						$total_nilai_tagihan = $data['cells'][$i][11];
					else
						$total_nilai_tagihan = 0;

					$insert = array(
						"id_record_tagihan" => substr($periode, 2, 3) . "01" . $no_bayar,
						"nomor_pembayaran" => $no_bayar,
						"nama" => $nama,
						"kode_fakultas" => $kode_fakultas,
						"nama_fakultas" => $nama_fakultas,
						"kode_prodi" => $kode_prodi,
						"nama_prodi" => $nama_prodi,
						"nama_periode" => $periode,
						"kode_periode" => $periode,
						"nomor_induk" => $nomor_induk,
						"strata" => $strata,
						"angkatan" => $angkatan,
						"is_tagihan_aktif" => 1,
						"urutan_antrian" => 1,
						"waktu_berlaku" => $this->input->post("awal"),
						"waktu_berakhir" => $this->input->post("akhir"),
						"total_nilai_tagihan" => str_replace(".", "", $total_nilai_tagihan),
						"pembayaran_atau_voucher" => "PEMBAYARAN",
						"jnsKode" => 1
					);

					if (count($insert) > 0) {
						if ($this->database->replace($insert)) {
						}
					}
					$no_urut++;
				} else {
					// echo "2";
					// $valid=false;
					// $hal="Nomor Baris";
					break;
				}
			}

			if ($valid) {
				//echo "<pre>";
				//print_r($insert);exit;
				echo '<br><div class="alert alert-success"><b>Berhasil</b> Data sebanyak ' . $no_urut . ' berhasil disimpan.</div>';
				unlink($upload['full_path']);
			} else {
				// print_r($data_xls);
				unlink($upload['full_path']);
				echo '<div id="box_gagal">';
				echo '<div id="status">Gagal.</div>';
				echo '<div id="message">Gagal menyimpan. Data template masih belum valid pada baris ' . $no_urut . ' karena <b>' . $hal . '</b>. Harap periksa kembali data anda.</div>';
				echo '</div>';
			}
		}
	}

	public function mahasiswa()
	{
		$this->layout->render('formulir_mahasiswa', $data);
	}
}
