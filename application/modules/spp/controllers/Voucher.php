<?php
class Voucher extends MY_Controller

{
	private $modul = 'spp';
	private $semester_ref = array(
		1 => "Ganjil",
		2 => "Genap",
		3 => "Antara"
	);
	public function __construct()
	{
		die("Halaman yang anda cari tidak ditemukan");
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
		$this->load->library('form_validation');
		$data['listPeriode'] = $this->periode->getPeriodeTagihan(3,"VOUCHER", null);
                //echo $this->db->last_query();
		$data['listPeriode_min'] = $this->periode->getPeriode();
		$this->layout->render('voucher/list_periode', $data);
	}

	public function getCopyData()
	{
		$sumber = $this->input->post("periode_sumber");
		$target = $this->input->post("periode_target");
		$data = $this->periode->getPeriodeTagihan(1, $sumber);
		// echo $this->db->last_query();
		$nm_sumber = $this->periode->getPeriode($sumber);
		$nm_target = $this->periode->getPeriode($target);
		echo "Copy data tagihan dari <b>" . $nm_sumber['nama_periode'] . "</b> ke <b>" . $nm_target['nama_periode'] . "</b>";
		echo "<br />Data yang dicopy<br /><table class='table' width='100%'>";
		echo "<tr>";
		echo "<td width='30%'>Jumlah Record Tagihan</td>";
		echo "<td width='70%'>" . $data->jumlah . "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td width='30%'>Total Tagihan</td>";
		echo "<td width='70%'>" . rupiah($data->total) . "</td>";
		echo "</tr>";
		echo "</table>";
		echo "<a href='#' class='btn btn-success btn-lanjutkan-copy' onclick='do_copy(" . $sumber . "," . $target . ")'>Lanjutkan</a>";
	}

	public function doCopyData()
	{
		$sumber = $this->input->post("periode_sumber");
		$target = $this->input->post("periode_target");
		$data = $this->periode->getPeriode($target);
		if ($this->database->copy($sumber, $data)) {
			echo msg(true, "Tagihan berhasil dicopy");
		}
	}

	public function tagihan($periode = null)
	{
		$data["kode_periode"] = $periode;
		$data["key_val_5"] = $periode;
		$data["label_nama_periode"] = null;
		$data["fakultas"] = $this->simari->get_fakultas();
		$model = $this->periode->getOneSelect(array(
			'kode_periode' => $periode
		) , "*", TRUE, TRUE);
		$data["label_nama_periode"] = ($model["nama_periode"] == null ? "" : "<b>Periode " . $model["nama_periode"] . "</b>");
		$this->layout->render('index', $data);
	}

	function get_prodi()
	{
		$data = $this->simari->get_prodi($this->input->post('fakultas') , null);
		$html = "<option value=''>Pilih program studi</option>";
		foreach($data as $row => $val) $html.= "<option value='" . $row . "'>" . $val . "</option>";
		echo $html;
	}

	public function formulir($periode, $id_record_tagihan = null)
	{
		// print_r($this->input->post());
		$data["kode_periode"] = $periode;
		$data["label_nama_periode"] = null;
		$model = $this->periode->getOneSelect(array(
			'kode_periode' => $periode
		) , "*", TRUE, TRUE);
		$data["label_nama_periode"] = ($model["nama_periode"] == null ? "" : "<b>Periode " . $model["nama_periode"] . "</b>");
		$model_tagihan = $this->database->getOneSelect(array(
			'id_record_tagihan' => $id_record_tagihan
		) , "*", TRUE, TRUE);
		if ($model["kode_periode"] == null) redirect("/spp/");
		$data["tagihan"] = $model_tagihan;
		// print_r($data["tagihan"]);
		if ($data["tagihan"] != null) $data["mode_input"] = "edit";
		else $data["mode_input"] = "add";
		$data["ref_fakultas"] = $this->simari->get_fakultas();
		$data["msg"] = "";
		if ($this->input->post()) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('nomor_induk', 'Nomor Induk', 'required');
			$this->form_validation->set_rules('nomor_pembayaran', 'Nomor Pembayaran', 'required');
			$this->form_validation->set_rules('nama', 'Nama', 'required');
			$this->form_validation->set_rules('angkatan', 'Angkatan', 'required');
			$this->form_validation->set_rules('kode_fakultas', 'Fakultas', 'required');
			$this->form_validation->set_rules('kode_prodi', 'Prodi', 'required');
			$this->form_validation->set_rules('strata', 'Jenjang', 'required');
			$this->form_validation->set_rules('total_nilai_tagihan', 'Nilai Tagihan', 'required');
			$this->form_validation->set_rules('urutan_antrian', 'Prioritas', 'required');
			$this->form_validation->set_rules('waktu_berlaku', 'Masa Berlaku', 'required');
			$this->form_validation->set_rules('waktu_berakhir', 'Masa Berakhir', 'required');
			$this->form_validation->set_rules('pembayaran_atau_voucher', 'Masa Berakhir', 'required');
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
				$insert["kode_periode"] = $periode;
				$insert["nomor_induk"] = $nomor_induk;
				$insert["strata"] = $this->input->post("strata");
				$insert["angkatan"] = $this->input->post("angkatan");
				$insert["is_tagihan_aktif"] = $this->input->post("is_tagihan_aktif");
				$insert["urutan_antrian"] = $this->input->post("urutan_antrian");
				$insert["waktu_berlaku"] = $this->input->post("waktu_berlaku");
				$insert["waktu_berakhir"] = $this->input->post("waktu_berakhir");
				$insert["total_nilai_tagihan"] = str_replace(".", "", $this->input->post("total_nilai_tagihan"));
				$insert["pembayaran_atau_voucher"] = $this->input->post("pembayaran_atau_voucher");
				if ($insert["pembayaran_atau_voucher"] == "VOUCHER") {
					$insert["voucher_nama"] = $insert["nama"];
					$insert["voucher_nama_fakultas"] = $insert["nama_fakultas"];
					$insert["voucher_nama_prodi"] = $insert["nama_prodi"];
					$insert["voucher_nama_periode"] = $insert["nomor_pembayaran"];
				}
				$insert["jnsKode"] = 1;
				$data["msg_status"] = false;
				if ($model_tagihan["id_record_tagihan"] != null) {
					// echo "edit";
					if ($id_record_tagihan == $model_tagihan["id_record_tagihan"]) {
						if ($this->database->update($id_record_tagihan, $insert)) {
							$data["msg"] = "Tagihan berhasil diupdate";
							$data["msg_status"] = true;
						}
						else $data["msg"] = "Tagihan gagal diupdate";
					}
					else {
						$data["msg"] = "Id Tagihan tidak ditemukan";
					}
				}
				else {
					// echo "add";
					$where["nomor_pembayaran"] = nim($nomor_induk);
					$where["kode_periode"] = $periode;
					$hasil = $this->database->cek_tagihan($where);
					if ($hasil > 0) {
						$data["msg"] = "Tagihan periode ini gagal disimpan karena sudah ada di database";
					}
					else {
						if ($this->database->insert($insert)) {
							$data["msg"] = "Tagihan berhasil disimpan";
							$data["msg_status"] = true;
						}
						else $data["msg"] = "Tagihan gagal disimpan";
					}
				}
			}
			else {
				// echo "gagal";
			}
		}
		$this->layout->render('formulir_manual', $data);
	}

	public function set_waktuberlaku($periode)
	{
		$data["kode_periode"] = $periode;
		$data["label_nama_periode"] = null;
		$data["msg"] = "Tagihan gagal diupdate";
		$data["msg_status"] = false;
		$model = $this->periode->getOneSelect(array(
			'kode_periode' => $periode
		) , "*", TRUE, TRUE);
		if ($model["kode_periode"] != null) {
			$where["kode_periode"] = $periode;
			$insert["waktu_berlaku"] = $this->input->post("waktu_berlaku");
			$insert["waktu_berakhir"] = $this->input->post("waktu_berakhir");
			if ($this->database->updateByField($where, $insert)) {
				$data["msg"] = msg(true, "Tagihan berhasil diupdate");
				$data["msg_status"] = true;
			}
		}
		echo json_encode($data);
	}

	public function detail($id_record_tagihan = null)
	{
		$model_tagihan = $this->database->getOneSelect(array(
			'id_record_tagihan' => $id_record_tagihan
		) , "*", TRUE, TRUE);
		$dberlaku = new DateTime($model_tagihan['waktu_berlaku']);
		$dberakhir = new DateTime($model_tagihan['waktu_berakhir']);
		// echo $dberlaku->format("Y-m-d");
		// echo $dberakhir->format("Y-m-d");
		// print_r($model_tagihan);
		echo "<table class='table' width='100%'>";
		echo "<tr>";
		echo "<td width='30%'>Nomor Pembayaran</td>";
		echo "<td width='70%'>" . $model_tagihan['nomor_pembayaran'] . "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td width='30%'>NIM</td>";
		echo "<td width='70%'>" . $model_tagihan['nomor_induk'] . "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td width='30%'>Nama</td>";
		echo "<td width='70%'>" . $model_tagihan['nama'] . "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td width='30%'>Fakultas</td>";
		echo "<td width='70%'>" . $model_tagihan['nama_fakultas'] . "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td width='30%'>Prodi</td>";
		echo "<td width='70%'>" . $model_tagihan['nama_prodi'] . "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td width='30%'>Angkatan / Jenjang</td>";
		echo "<td width='70%'>" . $model_tagihan['angkatan'] . " / " . $model_tagihan["strata"] . "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td width='30%'>Nilai Tagihan</td>";
		echo "<td width='70%'>" . rupiah($model_tagihan['total_nilai_tagihan']) . "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td width='30%'>Masa Berlaku</td>";
		echo "<td width='70%'>" . tgl_indo($dberlaku->format("Y-m-d")) . " s.d " . tgl_indo($dberakhir->format("Y-m-d")) . "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td width='30%'>Status Tagihan</td>";
		echo "<td width='70%'>" . ($model_tagihan['is_tagihan_aktif'] == 1 ? "Aktif" : "Non Aktif") . "</td>";
		echo "</tr>";
		echo "</table>";
	}

	public function tagihan_delete()
	{
		$nomor = $this->input->get("id");
		$data["msg"] = msg(false, "Tagihan gagal dihapus");
		$data["msg_status"] = false;
		if (!$this->database->exist($nomor)) {
			if ($this->database->delete($nomor)) {
				$data["msg"] = msg(true, "Tagihan berhasil dihapus");
				$data["msg_status"] = true;
			}
		}
		echo json_encode($data);
	}

	public function cek_tagihan()
	{
		$nomor = $this->input->post("nomor_induk");
		$kode_periode = $this->input->post("kode_periode");
		$where["nomor_pembayaran"] = nim($nomor);
		$where["kode_periode"] = $kode_periode;
		// print_r($where); exit;
		$hasil = $this->database->cek_tagihan($where);
		// echo $this->db->last_query();
		if ($hasil > 0) {
			$response["status"] = false;
			$response["msg"] = "Tagihan SPP/UKT sudah ada pada periode ini.";
		}
		else {
			$response["status"] = true;
			$data = $this->simari->get_mhs($nomor);
			$data+= ["nomor_pembayaran" => nim($nomor) ];
			$response["data"] = $data;
		}
		echo json_encode($response);
	}

	public function ajax_list($periode = null)
	{
		$where['kode_periode'] = $periode;
		$where['jnsKode'] = 1;
		$list = $this->database->get_datatables($where);
		$data = array();
		$no = $_POST['start'];
		foreach($list as $tagihan) {
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
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Aksi
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                          <li><a href="' . base_url() . 'spp/formulir/' . $tagihan->kode_periode . '/' . $tagihan->id_record_tagihan . '">Edit</a></li>
                          <li><a href="#" onclick="hapus_dialog(' . $tagihan->id_record_tagihan . ')">Hapus</a></li>
                          <li><a href="#" onclick="detail(\'' . $tagihan->id_record_tagihan . '\')">Detail</a></li>
                        </ul>
                      </div>';
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->database->count_all($where) ,
			"recordsFiltered" => $this->database->count_filtered($where) ,
			"data" => $data,
		);
		// output to json format
		echo json_encode($output);
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
			) ,
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
				) ,
			) ,
		);

		$objTpl = PHPExcel_IOFactory::load("./assets/list_pembayaran.xls");
		$objTpl->setActiveSheetIndex(0); //set first sheet as active
		$objTpl->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1); //auto height cell
		$objTpl->getActiveSheet()->getProtection()->setPassword('sepertinyasayapusingsekalimelihattingkahlakukmusudahcukup');
		$objTpl->getActiveSheet()->getStyle("A1:L7")->getProtection()->setLocked(true);
		if ($angkatan != "all") 
			$where["sia_m_mahasiswa.mhsAngkatan"] = $angkatan;
		if ($fakultas != "all") 
			$where["sia_m_fakultas.fakKode"] = $fakultas;
		$peserta = $this->simari->getMahasiswaAktif($where);
		$no = 1;
		$rowID = 8;
		foreach($peserta as $row) 
		{
			$objTpl->getActiveSheet()->setCellValue('A' . $rowID, $no);
			$objTpl->getActiveSheet()->setCellValueExplicit('B' . $rowID, nim($row->mhsNiu) , PHPExcel_Cell_DataType::TYPE_STRING);
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
		$config['upload_path'] = $this->config->item("upload_path");//'./uploads/';
		$config['allowed_types'] = '*';
		//$config['max_size'] = '36000';
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('file')) {
			echo '<div id="status_error">error</div>';
			echo '<div id="message">' . $this->upload->display_errors() . '</div>';
		}
		else {
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
				if (isset($data['cells'][$i][1])) 
				{
					if (isset($data['cells'][$i][2])) 
						$no_bayar = $data['cells'][$i][2];
					else 
					{
						$valid = false;
						$hal = "No. Ujian";
						break;
					}
					$parameter=array(
						"nomor_pembayaran"=>$no_bayar,
						"kode_periode"=>$periode
					);
					if (isset($data['cells'][$i][3])) 
						$nomor_induk = $data['cells'][$i][3];
					else {
							$valid = false;
							$hal = "NIM";
							break;
					}
					if (isset($data['cells'][$i][4])) $nama = $data['cells'][$i][4];
					else {
						$pil1 = null;
					}
					if (isset($data['cells'][$i][5])) $kode_fakultas = $data['cells'][$i][5];
					else {
						$pil2 = null;
					}
					if (isset($data['cells'][$i][6])) 
						$nama_fakultas = $data['cells'][$i][6];
					else {
						$pil3 = null;
					}
					if (isset($data['cells'][$i][7])) 
						$kode_prodi = $data['cells'][$i][7];
					else {
						$pil3 = null;
					}
					
					if (isset($data['cells'][$i][8])) 
						$nama_prodi = $data['cells'][$i][8];
					else {
						$pil3 = null;
					}
					if (isset($data['cells'][$i][9])) 
						$strata = $data['cells'][$i][9];
					else {
						$pil3 = null;
					}
					if (isset($data['cells'][$i][10])) 
						$angkatan = $data['cells'][$i][10];
					else {
						$pil3 = null;
					}
					if (isset($data['cells'][$i][11])) 
						$total_nilai_tagihan = $data['cells'][$i][11];
					else {
						$total_nilai_tagihan = 0;
					}
					
					$insert=array(
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
					if (count($insert) > 0) 
					{
						if ($this->database->replace($insert)) {
							
						}					
					}
					$no_urut++;
					
				}
				else {
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
			}
			else {
				// print_r($data_xls);
				unlink($upload['full_path']);
				echo '<div id="box_gagal">';
				echo '<div id="status">Gagal.</div>';
				echo '<div id="message">Gagal menyimpan. Data template masih belum valid pada baris ' . $no_urut . ' karena <b>' . $hal . '</b>. Harap periksa kembali data anda.</div>';
				echo '</div>';
			}
		}
	}
}
?>