<?php
class Bypasspembayaran extends MY_Controller
{
	private $modul = 'bypasspembayaran';
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
		$this->load->model('Bypasspembayaran_m', 'database');
		$this->load->model('Spp_m', 'spp');
		$this->load->model('periode/Periode_m', 'periode');
		$this->load->model('simari/Simari_m', 'simari');
		$this->load->helper('datetime_helper');
		$this->load->helper('rupiah_helper');
		$this->load->helper('nim_helper');
		$this->load->helper('msg_helper');
	}

	/*
	public function datagrid($request, $where)
	{
		$select = "tagihan.*";
		$data = $this->database->getDataGrid($request, $select, $where);
		return json_encode($data);
	}
*/

	public function pembayaran()
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$periode = $this->session->userdata('user')['periode'];

		if (!$this->periode->cekPeriode($periode)) {
			echo "Periode yang anda cari tidak ada dalam database kami";
			die;
		}

		$data["kode_periode"] = $periode;
		$data["key_val_5"] = $periode;
		$model = $this->periode->getOneSelect(array('kode_periode' => $periode), "*", TRUE, TRUE);
		$data["label_nama_periode"] = ($model["nama_periode"] == null ? "" : "<b>Periode " . $model["nama_periode"] . "</b>");
		$data['label_jenjang'] = $this->get_label_jenjang();
		$this->layout->render('bypass_pembayaran/index', $data);
	}

	public function cek_pembayaran($mode = null)
	{
		$aksi_modul = 'baca';
		ini_set('memory_limit', '-1');
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		if ($mode == null) {
			$nomor = trim($this->input->post("nomor_induk"));
			$kode_periode = $this->input->post("kode_periode");

			$mhs_aktif = $maba = false;
			if ($this->simari->cek_mhs_aktif($nomor))
				$mhs_aktif = true;
			if (count($this->spp->cek_tagihan_maba($nomor, $kode_periode)) == 1)
				$maba = true;

			if ($mhs_aktif || $maba) {
				$where["pembayaran.nomor_pembayaran"] = nim($nomor);
				$where["tagihan.kode_periode"] = $kode_periode;
				$where['pembayaran.status_pembayaran'] = "1";
				$hasil = $this->database->cek_pembayaran($where);

				// echo "<pre>";
				// print_r($this->db->last_query());
				// echo "</pre>";
				// exit();

				if ($hasil > 0) {
					$response["status"] = false;
					$response["msg"] = "Bypass pembayaran gagal, karena tagihan sudah dibayar pada periode ini";
				} else {
					$response["status"] = true;
					$data = array();
					//$data = $this->simari->get_mhs($nomor);
					$where = array('nomor_induk' => nim($nomor), 'kode_periode' => $kode_periode);
					// $data = $this->spp->getOneSelect($where, "*");
					$data = $this->database->get_pembayaran_delete($where);
					if ($data->num_rows() !== 0) {
						$data = (array) $data->row_array();
						$jenjang = $data['strata'];
						//cek apakah ini tagihan mhs lama atau bukan
						if (isset($data) && count($data) > 0)
							$data += ["nomor_pembayaran" => nim($nomor)];
						$response["data"] = $data;
					} else {
						$response["status"] = false;
						$response["msg"] = "NIM " . $nomor . " tidak ditemukan didalam tagihan.";
					}
				}
			} else {
				$response["status"] = false;
				$response["msg"] = "NIM yang anda cari tidak ditemukan pada database mahasiswa";
			}
			echo json_encode($response);
		} else {
			echo "<pre>";
			print_r($this->database->get_from($mode));
			echo "</pre>";
		}
	}

	//tidak jadi digunakan
	/**/
	public function formulir($periode = null, $id_record_pembayaran = null)
	{
		$aksi_modul = 'tulis';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$periode = $this->session->userdata('user')['periode'];
		$data["kode_periode"] = $periode;
		$data["label_nama_periode"] = null;
		$model = $this->periode->getOneSelect(array('kode_periode' => $periode), "*", TRUE, TRUE);
		$data["label_nama_periode"] = ($model["nama_periode"] == null ? "" : "<b>Periode " . $model["nama_periode"] . "</b>");
		$data['label_jenjang'] = $this->get_label_jenjang();
		$model_pembayaran = $this->database->getOneSelect(array('id_record_pembayaran' => $id_record_pembayaran), "*", TRUE, TRUE);

		//$id_record_tagihan = null;
		//$model_tagihan = $this->datfabase->getOneSelect(array('id_record_tagihan' => $id_record_tagihan) , "*", TRUE, TRUE);

		if ($model["kode_periode"] == null)
			redirect("/spp/");

		$data["pembayaran"] = $model_pembayaran;
		//$data["pembayaran"] = $model_tagihan;

		if ($data["pembayaran"] != null)
			$data["mode_input"] = "edit";
		else
			$data["mode_input"] = "add";

		//$data["ref_fakultas"] = $this->simari->get_fakultas();
		$data["msg"] = "";
		$data["msg_status"] = false;

		if ($this->input->post()) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('nomor_induk', 'NIM', 'required|trim');
			$this->form_validation->set_rules('catatan', 'Alasan Bypass', 'required|trim');

			if ($this->form_validation->run()) {
				$nomor_induk = $this->input->post("nomor_induk");

				$mhs_aktif = $maba = false;
				if ($this->simari->cek_mhs_aktif($nomor_induk))
					$mhs_aktif = true;
				if (count($this->spp->cek_tagihan_maba($nomor_induk, $periode)) == 1)
					$maba = true;

				if ($mhs_aktif || $maba) {
					$insert["id_record_pembayaran"] = nim($nomor_induk) . "-keu-" . $periode;
					$insert["id_record_tagihan"] = substr($periode, 2, 3) . "01" . nim($nomor_induk);
					$insert["waktu_transaksi"] = date('Y-m-d H:i:s');
					$insert["nomor_pembayaran"] = nim($nomor_induk);
					$insert["kode_bank"] = "BYPASS";
					$insert["kanal_bayar_bank"] = "SI TAGIHAN";
					$insert["kode_terminal_bank"] = $this->session->user["username"];
					$insert["total_nilai_pembayaran"] = intval($this->input->post("total_nilai_tagihan"));
					$insert["status_pembayaran"] = 1;
					$insert["metode_pembayaran"] = "keuangan";
					$insert["catatan"] = $this->input->post("catatan");
					$insert["key_val_1"] = nim($nomor_induk);
					$insert["key_val_2"] = $nomor_induk;
					$insert["key_val_4"] = intval($this->input->post("total_nilai_tagihan"));
					$insert["key_val_5"] = $periode;
					//print_r($insert); die;

					$where["status_pembayaran"] = 1;
					$where["pembayaran.nomor_pembayaran"] = nim($nomor_induk);
					$where["tagihan.kode_periode"] = $periode;
					$hasil = $this->database->cek_pembayaran($where);

					if ($hasil > 0) {
						$data["msg"] = "Tagihan uang kuliah untuk $nomor_induk pada periode $periode sudah dibayar";
					} else {
						if ($this->database->insert($insert)) {
							$data["msg"] = "Pembayaran berhasil disimpan";
							$data["msg_status"] = true;
						} else
							$data["msg"] = "Maaf, terjadi kesalahan pada saat menyimpan pembayaran";
					}
				} else
					$data["msg"] = "NIM yang dimasukan tidak terdaftar sebagai mahasiswa/calon mahasiswa";
			} else
				$data["msg"] = "Input tidak lengkap";
		}
		$this->layout->render('bypass_pembayaran/formulir_manual', $data);
	}
	/**/

	public function detail($id_record_pembayaran)
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$where = array('id_record_pembayaran' => $id_record_pembayaran);
		$detail = $this->database->detail($where);
		// print_r($detail);
		if (count($detail)) {
			echo "<table class='table' width='100%'>";
			echo "<tr>";
			echo "<td width='30%'>Nomor Pembayaran</td>";
			echo "<td width='70%'>" . $detail['id_record_pembayaran'] . "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td width='30%'>NIM</td>";
			echo "<td width='70%'>" . $detail['nomor_induk'] . "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td width='30%'>Nama</td>";
			echo "<td width='70%'>" . $detail['nama'] . "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td width='30%'>Fakultas</td>";
			echo "<td width='70%'>" . $detail['nama_fakultas'] . "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td width='30%'>Prodi</td>";
			echo "<td width='70%'>" . $detail['nama_prodi'] . "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td width='30%'>Jenjang/Angkatan</td>";
			echo "<td width='70%'>" . $detail['strata'] . "/" . $detail["angkatan"] . "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td width='30%'>Nilai Pembayaran (Rp.)</td>";
			echo "<td width='70%'>" . rupiah($detail['total_nilai_pembayaran']) . "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td width='30%'>Di-nihilkan oleh/dari</td>";
			echo "<td width='70%'>" . $detail['kode_terminal_bank'] . "/" . $detail['kanal_bayar_bank'] . "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td width='30%'>Waktu Penihilan</td>";
			echo "<td width='70%'>" . $detail['waktu_transaksi'] . "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td width='30%'>Alasan Penihilan</td>";
			echo "<td width='70%'>" . $detail['catatan'] . "</td>";
			echo "</tr>";
			echo "</table>";
		} else
			echo "Data tidak ditemukan";
	}

	public function batal_bypass()
	{
		$aksi_modul = 'hapus';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$nomor = $this->input->get("id");
		$data["msg"] = msg(false, "Penihilan pembayaran gagal dibatalkan");
		$data["msg_status"] = false;
		if (!$this->database->exist($nomor)) {
			//hanya yang di-bypass saja yang boleh dibatalkan bypass-nya
			if ($this->database->cara_bayar("BYPASS", $nomor)) {
				if ($this->database->delete($nomor)) {
					$data["msg"] = msg(true, "Penihilan pembayaran berhasil dibatalkan");
					$data["msg_status"] = true;
				}
			} else {
				$data["msg"] = msg(false, "Tagihan ini dibayar melalui bank, sehingga penihilan-nya tidak bisa dibatalkan");
				$data["msg_status"] = false;
			}
		}
		// echo $this->db->last_query();
		echo json_encode($data);
	}

	public function batal_banyak()
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
			// $c = array();
			// $i = 0;
			// foreach ($checked as $c) {
			// 	$c[$i] = $checked;
			// 	$i++;
			// }

			$delete = $this->database->delete_pembayaran($checked);

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

	public function ajax_list($periode = null)
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$periode = $this->session->userdata('user')['periode'];
		$where['kode_periode'] = $periode;
		if ($this->input->post('date_awal') && $this->input->post('date_akhir')) {
			$date_awal = new DateTime($this->input->post('date_awal'));
			$date_akhir = new DateTime($this->input->post('date_akhir'));
			if ($date_awal < $date_akhir) {
				$validasi = [
					'status' => true,
				];
			} else {
				$validasi = [
					'status' => false,
				];
			}
			echo json_encode($validasi);
			return 1;
		}
		if ($this->input->post('awal') && $this->input->post('akhir')) {
			$awal = new DateTime($this->input->post('awal'));
			$akhir = new DateTime($this->input->post('akhir'));
		} else {
			$awal = null;
			$akhir = null;
		}
		ini_set('memory_limit', '-1');

		$list = $this->database->get_pembayaran($where);

		//$where['jnsKode'] = 1;
		$data = array();
		$no = 0;
		foreach ($list as $pembayaran) {
			$take = false;
			if ($awal && $akhir) {
				$waktu_transaksi = new DateTime($pembayaran->waktu_transaksi);
				if (($waktu_transaksi >= $awal) && ($waktu_transaksi <= $akhir)) {
					$take = true;
				} else {
				}
			} else {
				$take = true;
			}

			if ($take == true) {
				$no++;
				$row = array();
				$row[] = '<input type="checkbox" id="check" name="check[]" value="' . $pembayaran->id_record_pembayaran . '">';
				$row[] = $pembayaran->nomor_induk;
				$row[] = $pembayaran->nama;
				$row[] = rupiah($pembayaran->total_nilai_pembayaran);
				$row[] = $pembayaran->waktu_transaksi;
				//$row[] = $pembayaran->kode_bank;
				$row[] = $pembayaran->catatan;

				$row[] = $pembayaran->kanal_bayar_bank;
				$row[] = $pembayaran->kode_terminal_bank;
				$row[] = '<a href="#" class="btn" title="Batalkan" onclick="hapus_dialog(\'' . $pembayaran->id_record_pembayaran . '\')"><span style="color:#e33244" class="fa fa-trash"></span></a>
						  <a href="#" class="btn" title="Detail" onclick="detail(\'' . $pembayaran->id_record_pembayaran . '\')"><span class="fa fa-search" style="color:#1bb399"></span></a>';
				$data[] = $row;
			}
		}
		$output = array(
			"data" => $data,
		);
		echo json_encode($output);
	}

	function do_upload()
	{
		$aksi_modul = 'tulis';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$config['overwrite'] = FALSE;
		$config['upload_path'] = $this->config->item("upload_path"); // './uploads/';
		$config['allowed_types'] = 'xls';
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('file')) {
			echo '<div id="status_error">error</div>';
			echo '<div id="message">' . $this->upload->display_errors() . '</div>';
		} else {
			$this->benchmark->mark('mulai_proses');
			$kode_periode = $this->input->post('kode_periode');
			$upload = $this->upload->data();
			$this->load->library('excel_reader');
			$this->excel_reader->setOutputEncoding('CP1251');
			$file = $upload['full_path'];
			$this->excel_reader->read($file);
			$data = $this->excel_reader->sheets[0];
			$data_xls_error = "";
			$insert = array();
			$no_urut = 1;
			$valid = true;
			$hal = "<ul>";

			/*
			$this->benchmark->mark('cek_where_in_');
			for ($i = 8; $i <= $data['numRows']; $i++) 
			{
				if ($this->periode->cekPeriode($kode_periode) && isset($data['cells'][$i][2])) 
				{
					$daftar_nim[$i] = $data['cells'][$i][2];
				}
			}
			$hasil = $this->spp->cek_tagihan_massal($daftar_nim, $kode_periode);
			$this->benchmark->mark('selesai_cek_where_in_');
			//die($this->benchmark->elapsed_time('cek_where_in_', 'selesai_cek_where_in_'));
			*/

			for ($i = 8; $i <= $data['numRows']; $i++) {
				$this->benchmark->mark('cek_periode_' . $i);
				//cek apakah periodenya benar dan data di excel terisi NIM, alasan bypass dan jumlah bayarnya
				if ($this->periode->cekPeriode($kode_periode) && isset($data['cells'][$i][2]) && isset($data['cells'][$i][4]) && isset($data['cells'][$i][5])) {
					$this->benchmark->mark('selesai_cek_periode_' . $i);
					$nomor_induk = "";
					$catatan = "";
					$tagihan = "";
					if (isset($data['cells'][$i][2]))  $nomor_induk = trim($data['cells'][$i][2]);
					if (isset($data['cells'][$i][4]))  $catatan = trim($data['cells'][$i][4]);
					if (isset($data['cells'][$i][5])) $tagihan = $data['cells'][$i][5];
					//$tagihan = $this->spp->get_nilai_tagihan($nomor_induk, $kode_periode);	//query ini aman. dengan catatan, tagihan semua mhs aktif sudah didaftarkan

					$where = array('pembayaran.nomor_pembayaran' => nim($nomor_induk), 'tagihan.kode_periode' => $kode_periode);
					$this->benchmark->mark('cek_mhs_aktif_tagihan_' . $i);

					$mhs_aktif = false;
					$maba = false;

					if ($this->simari->cek_mhs_aktif($nomor_induk))
						$mhs_aktif = true;
					if (count($this->spp->cek_tagihan_maba($nomor_induk, $kode_periode)) == 1)
						$maba = true;

					//cek apakah mahasiswa ini aktif atau maba
					if ($mhs_aktif || $maba) // && $this->spp->cek_tagihan($where))
					{
						$tg = $this->database->get_tagihan_ukt($nomor_induk);

						// $tg = $this->spp->getOneSelect(array('nomor_induk' => $nomor_induk, 'kode_periode' => $kode_periode));
						if ($tg->num_rows() > 0) {
							$hasil = $this->database->cek_pembayaran($where);
							if ($hasil > 0) {
								$valid = false;
								$hal .= "<li>Tagihan uang kuliah untuk $nomor_induk pada periode $kode_periode sudah dibayar</li>";
								// $hal .= "<li>Mahasiswa berada distrata yang berbeda dari strata yang anda pilih </li>";
								break;
							} else {
								$this->benchmark->mark('selesai_cek_mhs_aktif_tagihan_' . $i);

								$insert["id_record_pembayaran"] = nim($nomor_induk) . "-keu-" . $kode_periode;
								$insert["id_record_tagihan"] = substr($kode_periode, 2, 3) . "01" . nim($nomor_induk);
								$insert["waktu_transaksi"] = date('Y-m-d H:i:s');
								$insert["nomor_pembayaran"] = nim($nomor_induk);
								$insert["kode_bank"] = "BYPASS";
								$insert["kanal_bayar_bank"] = "SI TAGIHAN";
								$insert["kode_terminal_bank"] = $this->session->user["username"];
								$insert["total_nilai_pembayaran"] = $tagihan;
								$insert["status_pembayaran"] = 1;
								$insert["metode_pembayaran"] = "keuangan";
								$insert["catatan"] = $catatan;
								$insert["key_val_1"] = nim($nomor_induk);
								$insert["key_val_2"] = $nomor_induk;
								$insert["key_val_4"] = $tagihan;
								$insert["key_val_5"] = $kode_periode;
								//print_r($insert); die;
								$this->benchmark->mark('replace_data_' . $i);
								$this->database->replace($insert);
								$this->benchmark->mark('selesai_replace_data_' . $i);
							}
						} else {
							$valid = false;
							$hal .= "<li>NIM yang anda cari tidak ditemukan pada tagihan</li>";
							$hal .= "<li>Mahasiswa tersebut tidak aktif</li>";
							// $hal .= "<li>Mahasiswa berada distrata yang berbeda dari strata yang anda pilih </li>";
							break;
						}
					} else {
						$valid = false;
						$hal .= "<li>NIM yang anda cari tidak ditemukan pada database mahasiswa, atau</li>";
						$hal .= "<li>Mahasiswa tersebut tidak aktif</li>";
						//$hal .= "<li>Tagihannya belum dimasukan ke tabel tagihan</li>";
						break;
					}
				} else {
					$valid = false;
					$hal .= "<li>Periode salah input, atau</li>";
					$hal .= "<li>Isian pada berkas unggahan belum lengkap</li>";
					break;
				}
				$no_urut++;
			}

			$hal .= "</ul>";
			$this->benchmark->mark('selesai_proses');

			if ($valid) {
				/*
				echo "<br />cek wherein = ".$this->benchmark->elapsed_time('cek_where_in_', 'selesai_cek_where_in_');
				for($i = 8; $i <= $data['numRows']; $i++)
				{
					$j=$i;
					echo "<br />cek periode ".$i." = ".$this->benchmark->elapsed_time('cek_periode_'.$i, 'selesai_cek_periode_'.$i);
					echo "<br />cek nilai tagihan ".$i." = ".$this->benchmark->elapsed_time('selesai_cek_periode_'.$i, 'cek_mhs_aktif_tagihan_'.$i);
					echo "<br />cek tagihan mhs aktif ".$i." = ".$this->benchmark->elapsed_time('cek_mhs_aktif_tagihan_'.$i, 'selesai_cek_mhs_aktif_tagihan_'.$i);
					echo "<br />cek input array ".$i." = ".$this->benchmark->elapsed_time('selesai_cek_mhs_aktif_tagihan_'.$i, 'replace_data_'.$i);
					echo "<br />cek replace data ".$i." = ".$this->benchmark->elapsed_time('replace_data_'.$i, 'selesai_replace_data_'.$i);
					echo "<br />cek perulangan ".$i." = ".$this->benchmark->elapsed_time('selesai_replace_data_'.$i, 'cek_periode_'.++$j);
				}
				echo "<br />cek waktu eksekusi = ".$this->benchmark->elapsed_time('mulai_proses', 'selesai_proses');
				*/

				$jumlah = $no_urut - 1;
				echo '<br><div class="alert alert-success">Berhasil memperbarui ' . $jumlah . ' baris data tagihan</div>';
				//unlink($upload['full_path']);				
			} else {
				unlink($upload['full_path']);
				echo '<div id="box_gagal">';
				echo '<div id="message">Data gagal tersimpan.<br /> Record pada baris no. ' . $no_urut . ' belum valid karena : ' . $hal . ' Harap periksa kembali data anda.</div>';
				echo '</div>';
			}
		}
	}

	function do_upload_penihilan()
	{
		$aksi_modul = 'tulis';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		$response["status"] = false;
		$response["msg"] = "Pembatalan penihilan gagal.";

		$config['overwrite'] = FALSE;
		$config['upload_path'] = $this->config->item("upload_path"); // './uploads/';
		$config['allowed_types'] = 'xls';
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('file')) {
			$response["status"] = false;
			$response["msg"] = "Gagal mengupload file.";
		} else {
			$this->benchmark->mark('mulai_proses');
			$kode_periode = $this->input->post('kode_periode');
			$upload = $this->upload->data();
			$this->load->library('excel_reader');
			$this->excel_reader->setOutputEncoding('CP1251');
			$file = $upload['full_path'];
			$this->excel_reader->read($file);
			$data = $this->excel_reader->sheets[0];
			$data_xls_error = "";
			$insert = array();
			$no_urut = 1;
			$valid = true;
			$hal = "<ul>";

			$error = [];
			for ($i = 8; $i <= $data['numRows']; $i++) {
				$this->benchmark->mark('cek_periode_' . $i);
				//cek apakah periodenya benar dan data di excel terisi NIM, alasan bypass dan jumlah bayarnya

				if (isset($data['cells'][$i][2])) {
					$this->benchmark->mark('selesai_cek_periode_' . $i);
					$nomor_induk = trim($data['cells'][$i][2]);
					//$tagihan = $this->spp->get_nilai_tagihan($nomor_induk, $kode_periode);	//query ini aman. dengan catatan, tagihan semua mhs aktif sudah didaftarkan



					$where = array('nomor_induk' => $nomor_induk);
					$get_data = $this->database->cek_bypass($where);
					if ($get_data->num_rows() > 0)
						$cek_bypass = true;

					// //cek apakah mahasiswa ini aktif atau maba
					if ($cek_bypass) // && $this->spp->cek_tagihan($where))
					{
						$response["status"] = true;
						$response["msg"] = "Pembatalan penihilan berhasil.";

						$delete = $this->database->delete_bypass(array('id_record_tagihan' => $get_data->row()->id_record_tagihan));
						if ($delete) {
						} else {
							$error[] = $nomor_induk;
						}
					} else {
						$error[] = $nomor_induk;
					}
				} else {
				}
				$no_urut++;
			}
			if (count($error) > 0) {
				$response['msg'] = 'Pembatalan penihilan berhasil namuna ada beberapa data yang gagal dibatalkan, yaitu : ';
				foreach ($error as $e) {
					$response['msg'] .= $nomor_induk . ', ';
				}
			}
		}
		echo json_encode($response);
	}

	function download_penihilan()
	{
		$aksi_modul = 'baca';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		ini_set('memory_limit', '-1');
		$where['kode_periode'] = $this->session->userdata('user')['periode'];
		$list = $this->database->get_pembayaran($where);

		// echo "<pre>";
		// print_r($list);
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
		$objTpl = PHPExcel_IOFactory::load("./assets/bypass_pembayaran_massal.xls");
		$objTpl->setActiveSheetIndex(0); //set first sheet as active
		$objTpl->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1); //auto height cell
		$objTpl->getActiveSheet()->getProtection()->setPassword('sepertinyasayapusingsekalimelihattingkahlakukmusudahcukup');
		$objTpl->getActiveSheet()->getStyle("A1:E7")->getProtection()->setLocked(true);

		$no = 1;
		$rowID = 8;
		foreach ($list as $row) {
			// $mhs = $this->database->get_info_mhs($row->nomor_induk)->row();
			$objTpl->getActiveSheet()->setCellValue('A' . $rowID, $no);
			// $objTpl->getActiveSheet()->setCellValueExplicit('B' . $rowID, nim($row->mhsNiu), PHPExcel_Cell_DataType::TYPE_STRING);
			// $objTpl->getActiveSheet()->setCellValueExplicit('C' . $rowID, $row->mhsNiu, PHPExcel_Cell_DataType::TYPE_STRING);
			// $objTpl->getActiveSheet()->setCellValue('B' . $rowID, html_entity_decode($row->mhsNama));
			$objTpl->getActiveSheet()->setCellValue('B' . $rowID, $row->nomor_induk);
			$objTpl->getActiveSheet()->setCellValue('C' . $rowID, $row->nama);
			$objTpl->getActiveSheet()->setCellValue('D' . $rowID, $row->catatan);
			$objTpl->getActiveSheet()->setCellValue('E' . $rowID, $row->total_nilai_pembayaran);

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
		header('Content-Disposition: attachment;filename="daftar_penihilan_' . (new dateTime())->format('d-m-Y H-i-s') . '.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = new PHPExcel_Writer_Excel5($objTpl);
		ob_end_clean();
		$objWriter->save('php://output');
	}
}
