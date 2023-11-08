<?php

class Pembayaran extends MY_Controller
{
    private $modul = 'pembayaran';

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->model('Pembayaran_m', 'database');
        $this->load->model('Spp_m', 'spp');
        $this->load->model('periode/Periode_m', 'periode');
        $this->load->model('simari/Simari_m', 'simari');
        $this->load->helper('datetime_helper');
        $this->load->helper('rupiah_helper');
        $this->load->helper('nim_helper');
        $this->load->helper('msg_helper');
    }

    public function index()
    {
        $aksi_modul = 'baca';
        if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
            show_error($this->acl->body_text, 401, $this->acl->header_text);
        $periode = $this->session->userdata('user')['periode'];
        $data["label_jenjang"] = $this->get_label_jenjang();
        $data["ref_aktif"] = array("1" => "Aktif", "0" => "Tidak Aktif");
        $data["ref_jnsKode"] = array("1" => "Aktif Kuliah", "2" => "Cuti", "3" => "Daftar Admisi");
        $this->layout->render('pembayaran/cari_history', $data);
    }

    public function daftar()
    {
        $aksi_modul = 'baca';
        if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
            show_error($this->acl->body_text, 401, $this->acl->header_text);
        $periode = $this->session->userdata('user')['periode'];
        $data["kode_periode"] = $periode;
        $data["key_val_5"] = $periode;
        $model = $this->periode->getOneSelect(array('kode_periode' => $periode), "*", TRUE, TRUE);
        $data["label_nama_periode"] = ($model["nama_periode"] == null ? "" : "<b>Periode " . $model["nama_periode"] . "</b>");
        $data["label_jenjang"] = $this->get_label_jenjang();
        $this->layout->render('pembayaran/Pembayaran_ukt_v', $data);
    }

    public function daftar_non_ukt()
    {
        $aksi_modul = 'baca';
        if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
            show_error($this->acl->body_text, 401, $this->acl->header_text);
        $periode = $this->session->userdata('user')['periode'];
        $data["kode_periode"] = $periode;
        $data["key_val_5"] = $periode;
        $model = $this->periode->getOneSelect(array('kode_periode' => $periode), "*", TRUE, TRUE);
        $data["label_nama_periode"] = ($model["nama_periode"] == null ? "" : "<b>Periode " . $model["nama_periode"] . "</b>");
        $data["label_jenjang"] = $this->get_label_jenjang();

        $this->layout->render('pembayaran/Pembayaran_non_ukt_v', $data);
    }

    public function ajax_list($periode = null, $keterangan = null)
    {
        $aksi_modul = 'baca';
        if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
            show_error($this->acl->body_text, 401, $this->acl->header_text);
        ini_set('memory_limit', '-1');
        $periode = $this->session->userdata('user')['periode'];
        $where['kode_periode'] = $periode;
        $where['keterangan'] = urldecode($keterangan);
        if (in_array($where['keterangan'], array("91", "92", "93"))) {
            $where['nomor_pembayaran'] = $where['keterangan'];
            $where['keterangan'] = "nonukt";
        }
        //$where['jnsKode'] = 1;
        $list = $this->database->get_pembayaran_tagihan($where)->result();

        $data = array();
        $no = 0;
        foreach ($list as $pembayaran) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $pembayaran->nomor_induk;
            $row[] = $pembayaran->nama;
            $row[] = rupiah($pembayaran->total_nilai_pembayaran);
            $row[] = $pembayaran->waktu_transaksi;
            //$row[] = $pembayaran->kode_bank;
            $row[] = $pembayaran->kanal_bayar_bank;

            $row[] = $pembayaran->kode_bank;
            $np = substr($pembayaran->nomor_pembayaran, 0, 2);
            if (strtolower($where['keterangan']) == "nonukt" && ($np == "91" || $np == "92" || $np == "93")) {
                $row[] = $np == "91" ? "ADMISI S1 & D3" : ($np == "92" ? "ADMISI PROFESI" : ($np == "93" ? "ADMISI PASCA" : "Lainnya"));
            } else {
                $row[] = $pembayaran->keterangan;
            }
            $data[] = $row;
        }
        $output = array(
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function get_mahasiswa_bayar()
    {
        $aksi_modul = 'baca';
        if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
            show_error($this->acl->body_text, 401, $this->acl->header_text);
        $response["status"] = false;
        $response["msg"] = "NIM yang anda cari tidak ada di database mahasiswa atau statusnya bukan mahasiswa aktif";
        $periode = $this->session->userdata('user')['periode'];

        $this->load->library('form_validation');
        $this->form_validation->set_rules('nomor_induk', 'NIM', 'required');

        if ($this->form_validation->run()) {
            $nomor = $this->input->post("nomor_induk");
            $data = $this->simari->get_mhs($nomor);
            $jj = $data['prodiJjarKode'];
            if ($this->simari->cek_mhs_aktif($nomor) && count($data) > 0) {
                if (!strstr(strtolower($this->session->userdata('user')['jenjang_text']), strtolower($jj))) {
                    $response["msg"] = $nomor . " merupakan mahasiswa pada jenjang " . $jj;
                } else {

                    $response["status"] = true;
                    $response["data"] = $data;
                }
            }
        }
        echo json_encode($response);
    }

    public function getPembayaran()
    {
        $aksi_modul = 'baca';
        if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
            show_error($this->acl->body_text, 401, $this->acl->header_text);
        $periode = $this->session->userdata('user')['periode'];

        $id_record_pembayaran = $this->input->post("id_record_pembayaran");
        $model_pembayaran = $this->database->getOneSelect(array('id_record_pembayaran' => $id_record_pembayaran), "*", TRUE, TRUE);
        echo json_encode($model_pembayaran);
    }

    public function simpan_pembayaran()
    {
        $aksi_modul = 'ubah';
        if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
            show_error($this->acl->body_text, 401, $this->acl->header_text);
        $periode = $this->session->userdata('user')['periode'];
        if ($this->input->post()) {
            //$id_record_tagihan=$this->input->post("id_record_tagihan");
            $this->load->library('form_validation');
            $this->form_validation->set_rules('id_record_pembayaran', 'Id Record Pembayaran', 'required');
            $this->form_validation->set_rules('total_nilai_pembayaran', 'Nilai Pembayaran', 'required');

            if ($this->form_validation->run()) {
                $pembayaran = array();
                $where['id_record_pembayaran'] = $this->input->post('id_record_pembayaran');
                if ($this->database->getOne($where) != 0) {
                    $insert["id_record_pembayaran"] = $this->input->post('id_record_pembayaran');
                    $insert["total_nilai_pembayaran"] = intval(str_replace(".", "", $this->input->post("total_nilai_pembayaran")));

                    $data["status"] = false;
                    if ($this->database->update($insert["id_record_pembayaran"], $insert)) {
                        $data["msg"] = msg(true, "Tagihan berhasil diupdate");
                        $data["status"] = true;
                    } else
                        $data["msg"] = msg(false, "Tagihan gagal diupdate");
                } else {
                    $data["status"] = false;
                    $data["msg"] = msg(false, validation_errors('<div class="error">', '</div>') . "Data gagal disimpan. Id Record Pembayaran tidak ditemukan");
                }
            }
        }
        echo json_encode($data);
    }

    public function delete()
    {
        $aksi_modul = 'hapus';
        if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
            show_error($this->acl->body_text, 401, $this->acl->header_text);
        $data["msg"] = msg(false, "Tagihan gagal dihapus");
        $data["msg_status"] = false;

        $id_record_pembayaran = $this->input->get("id");
        if (!$this->database->exist($id_record_pembayaran)) {
            if ($this->database->delete($id_record_pembayaran)) {
                $data["msg"] = msg(true, "Tagihan berhasil dihapus");
                $data["msg_status"] = true;
            }
        }
        // echo $this->db->last_query();
        echo json_encode($data);
    }

    public function ajax_mhs_bayar()
    {
        $aksi_modul = 'baca';
        if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
            show_error($this->acl->body_text, 401, $this->acl->header_text);
        $periode = $this->session->userdata('user')['periode'];
        $where['key_val_2'] = $this->input->post("nomor_induk");
        $list = $this->database->get_datatables($where);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $pembayaran) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $pembayaran->nomor_induk;
            $row[] = $pembayaran->nama;
            $row[] = rupiah($pembayaran->total_nilai_pembayaran);
            $row[] = $pembayaran->waktu_transaksi;
            $row[] = $pembayaran->kanal_bayar_bank;
            $row[] = $pembayaran->kode_bank;
            $row[] = $pembayaran->key_val_5;
            $row[] = '<div class="dropdown">
						<button class="btn btn-primary" type="button" onclick="edit_modal(\'' . $pembayaran->id_record_pembayaran . '\')"><i class="fa fa-pencil"></i></button>				
						<button class="btn btn-primary" type="button" onclick="hapus_dialog(\'' . $pembayaran->id_record_pembayaran . '\')"><i class="fa fa-trash"></i></button>				
					</div>';
            $row[] = $pembayaran->jnsKode;
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->database->count_all($where),
            "recordsFiltered" => $this->database->count_filtered($where),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function download_xls($periode, $keterangan)
    {
        $aksi_modul = 'baca';
        if (!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
            show_error($this->acl->body_text, 401, $this->acl->header_text);
        ini_set('memory_limit', '-1');
        $this->load->library('Excel');
        $periode = $this->session->userdata('user')['periode'];
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
        $objTpl = PHPExcel_IOFactory::load("./assets/data_pembayaran.xls");
        $objTpl->setActiveSheetIndex(0); //set first sheet as active
        $objTpl->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1); //auto height cell
        $where["kode_periode"] = $periode;
        $where["keterangan"] = urldecode($keterangan);

        $tagihan = $this->database->get_pembayaran_tagihan($where);
        $no = 1;
        $rowID = 8;

        foreach ($tagihan->result() as $row) {
            $objTpl->getActiveSheet()->setCellValue('A' . $rowID, $no);
            $objTpl->getActiveSheet()->setCellValueExplicit('B' . $rowID, $row->nomor_induk, PHPExcel_Cell_DataType::TYPE_STRING);
            $objTpl->getActiveSheet()->setCellValueExplicit('C' . $rowID, html_entity_decode($row->nama), PHPExcel_Cell_DataType::TYPE_STRING);
            $objTpl->getActiveSheet()->setCellValue('D' . $rowID, $row->nama_fakultas);
            $objTpl->getActiveSheet()->setCellValue('E' . $rowID, $row->strata);
            $objTpl->getActiveSheet()->setCellValue('F' . $rowID, $row->nama_prodi);
            $objTpl->getActiveSheet()->setCellValue('G' . $rowID, $row->total_nilai_pembayaran);
            $objTpl->getActiveSheet()->setCellValue('H' . $rowID, $row->waktu_transaksi);
            $objTpl->getActiveSheet()->setCellValue('I' . $rowID, $row->kanal_bayar_bank);
            $objTpl->getActiveSheet()->setCellValue('J' . $rowID, $row->kode_bank);
            $no++;
            $rowID++;
        }
        $objTpl->getActiveSheet()->getPageSetup()->setPrintArea("A1:" . "G" . ($rowID + 1));
        $objTpl->getActiveSheet()->getPageSetup()->setPrintArea("A1:" . "G" . ($rowID + $v));
        //$objTpl->getActiveSheet()->getProtection()->setSheet(true);
        $rowID--;
        $objTpl->getActiveSheet()->getStyle("K8:" . "K" . ($rowID))->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
        header("Content-Type: application/vnd.ms-excel");
        header('Content-Disposition: attachment;filename="' . $periode . '-Pembayaran.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = new PHPExcel_Writer_Excel5($objTpl);
        ob_end_clean();
        $objWriter->save('php://output');
    }
}
