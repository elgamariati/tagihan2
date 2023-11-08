<?php

class Atur_rentang extends MY_Controller
{
	private $modul = 'atur_rentang';
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
		$this->load->model('Atur_rentang_m', 'atur');
		$this->load->model('periode/Periode_m', 'periode');
	}

	public function index()
	{
		$aksi_modul = 'tulis';
		if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
			show_error($this->acl->body_text, 401, $this->acl->header_text);
		if ($this->input->is_ajax_request()) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('jenis_tagihan', 'Periode Target', 'required|trim');
			$this->form_validation->set_rules('waktu_berlaku', 'Waktu Tagihan Berlaku', 'required|trim');
			$this->form_validation->set_rules('waktu_berakhir', 'Waktu Tagihan Berakhir', 'required|trim');
			$this->form_validation->set_rules('jenjang[]', 'Jenjang', 'required|trim');

			if ($this->form_validation->run() == FALSE) {
				$validasi = [
					'status' => 0,
					'jenis_tagihan' => form_error('jenis_tagihan'),
					'waktu_berlaku' => form_error('waktu_berlaku'),
					'waktu_berakhir' => form_error('waktu_berakhir'),
					'jenjang' => form_error('jenjang[]'),
					'keterangan' => 'Data belum lengkap.',
				];
			} else {
				$validasi = [
					'status' => 0,
				];

				$jenis_tagihan = $this->input->post("jenis_tagihan");
				$waktu_berlaku = $this->input->post("waktu_berlaku");
				$waktu_berakhir = $this->input->post("waktu_berakhir");
				$periode = $this->session->userdata('user')['periode'];
				//cek apakah format tanggalnya benar dan waktu berakhir lebih lambat dibanding waktu berlaku
				if (cekFormatTanggal($waktu_berlaku) && cekFormatTanggal($waktu_berakhir) && (new DateTime($waktu_berakhir) > new DateTime($waktu_berlaku))) {
					if ($this->periode->cekPeriode($periode)) {
						$data = $this->periode->getPeriode($periode);
						$pass = array(
							'jenis_tagihan' => $jenis_tagihan,
							'waktu_berlaku' => $waktu_berlaku,
							'waktu_berakhir' => $waktu_berakhir,
							'periode' => $periode,
						);

						// $jenjang = "";
						// $i = 0;
						// $jenjang_sess = $this->input->post('jenjang');
						// foreach ($jenjang_sess as $j) {
						// 	$jenjang .= "'" . $j . "'";
						// 	if (array_key_exists($i + 1, $this->input->post('jenjang'))) {
						// 		$jenjang .= ", ";
						// 	}
						// 	$i++;
						// }
						$jenjang = "'" . implode("','", $_POST['jenjang']) . "'";

						$filter = array(
							'jenjang' => $jenjang,
						);

						$update = $this->atur->update_rentang($pass, $filter);

						if ($update) {
							$validasi = [
								'status' => 1,
								'keterangan' => 'Berhasil mengatur rentang.',
							];
						} else {
							$validasi['keterangan'] = 'Gagal mengatur rentang.';
						}
					} else {
						$validasi['keterangan'] =  'Gagal mengatur rentang.';
					}
				} else {
					if (!cekFormatTanggal($waktu_berlaku)) {
						$validasi['waktu_berlaku'] = 'Format Tanggal Salah.';
						$validasi['keterangan'] =  'Ada Kesalahan!.';
					}
					if (!cekFormatTanggal($waktu_berakhir)) {
						$validasi['waktu_berakhir'] = 'Format Tanggal Salah.';
						$validasi['keterangan'] =  'Ada Kesalahan!.';
					}
					if (!(new DateTime($waktu_berakhir) > new DateTime($waktu_berlaku))) {
						$validasi['keterangan'] = 'Tanggal Akhir Pembayaran harus setelah Tanggal Mulai Pembayaran.';
					}
				}
			}
			echo json_encode($validasi);
		} else {
			$pkg['sess'] = $this->session->userdata()['user'];
			$pkg['periode'] = $this->session->userdata('user')['periode_text'];
			$this->layout->render('Atur_rentang_v', $pkg);
		}
	}
}
