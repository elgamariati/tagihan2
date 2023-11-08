<?php
class Spp_m extends MY_Model
{
    protected $table = "tagihan";
    protected $pK = "id_record_tagihan";

    private $dbSimari;

    public function __construct()
    {
        parent::__construct();
        $this->dbSimari = $this->load->database("simari", true);
    }

    var $column_order = array(null, 'nomor_pembayaran', 'nama_periode', 'nomor_induk', 'nama', 'nama_prodi', 'total_nilai_tagihan', 'keterangan'); //set column field database for datatable orderable
    var $column_search = array('nomor_pembayaran', 'nama_periode', 'nomor_induk', 'nama', 'nama_prodi', 'total_nilai_tagihan', 'keterangan'); //set column field database for datatable searchable 
    var $order = array('nomor_pembayaran' => 'desc'); // default order 

    public function sql_query($where = null)
    {
        $this->db->select('tagihan.*');
        $this->db->from($this->table);
        //$this->db->join("pembayaran","pembayaran.id_record_tagihan=tagihan.id_record_tagihan and pembayaran.key_val_5='".$where["key_val_5"]."'","left");
        if ($where != null)
            $this->db->where($where);
        if ($this->session->user['role'] == 'admin_s1')
            $this->db->where_not_in('strata', array('S2', 'S3'));
        else if ($this->session->user['role'] == 'admin_s2')
            $this->db->where_in('strata', array('S2', 'S3'));
    }

    public function get_prodi_new($fak = null, $kode = null)
    {
        $this->dbSimari->select("prodiKode, prodiNamaResmi,prodiJjarKode");
        $this->dbSimari->from("sia_m_prodi");
        $this->dbSimari->join("sia_m_jurusan", 'sia_m_jurusan.jurKode=sia_m_prodi.prodiJurKode');
        $this->dbSimari->where("prodiJjarKode IN (" . $this->session->userdata('user')['jenjang'] . ")");
        if($fak){
            $this->dbSimari->where('jurFakKode',$fak);
        }
        $query = $this->dbSimari->get();
        return $query->result();
    }


    //? Start of dtb tagihan
    public function get_dtb_tagihan($where = null)
    {
        $this->custom_query_tagihan_all($where);
        $this->custom_dtb_query($where);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        //print_r( $this->db->query_times );
        return $query;
    }

    public function tagihan_get_all_id($where = null)
    {
        $this->custom_query_tagihan($where);
        return $this->db->get();
    }

    public function custom_count_all_tagihan($where = null)
    {
        $this->custom_query_tagihan($where);
        return $this->db->count_all_results();
    }

    public function count_filtered_tagihan($where = null)
    {
        $this->custom_query_tagihan($where);
        $this->custom_dtb_query($where);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function isi_query_tagihan($where = null)
    {
        $where_strata = "strata IN (" . $this->session->userdata('user')['jenjang'] . ")";
        $this->db->from('tagihan');
        $this->db->group_start();
        if (isset($where['kode_periode'])) {
            $this->db->where('kode_periode', $where['kode_periode']);
        }
        if (isset($where['keterangan'])) {
            if (strtolower($where['keterangan']) == "nonukt") {
                $this->db->where("(keterangan NOT IN ('UKT','ukt','Cek Plagiasi') AND keterangan IS NOT NULL)");
            } else if (strtolower($where['keterangan']) == "ukt") {
                $this->db->where("(keterangan IN ('UKT','ukt') or keterangan IS NULL)");
            } else {
                $this->db->where('keterangan', $where['keterangan']);
            }
        }
        $this->db->where('(is_angsuran = 0 OR is_angsuran IS NULL)');
        $this->db->where($where_strata);
        if (isset($where['nomor_pembayaran'])) {
            $this->db->where('nomor_pembayaran LIKE "' . $where['nomor_pembayaran'] . '%"');
        } else {
            if (strtolower($where['keterangan']) == "nonukt") {
                $this->db->or_group_start();
                $this->db->or_where('(nomor_pembayaran LIKE "91%" OR nomor_pembayaran LIKE "92%" OR nomor_pembayaran LIKE "93%")');
                $this->db->where('kode_periode', $where['kode_periode']);
                $this->db->where($where_strata);
                $this->db->group_end();
            }
        }
        $this->db->group_end();
    }

    public function custom_query_tagihan_all($where = null)
    {
        $this->db->select('*');
        $this->isi_query_tagihan($where);
    }

    public function custom_query_tagihan($where = null)
    {
        $this->db->select('id_record_tagihan,nomor_pembayaran,nama_periode,nama,nama_prodi,total_nilai_tagihan,keterangan,kode_periode');
        $this->isi_query_tagihan($where);
    }

    public function custom_dtb_query($where = null)
    {
        $this->column_order =  array(null, 'nomor_pembayaran', 'nama_periode', 'nomor_induk', 'nama', 'nama_prodi', 'total_nilai_tagihan', 'keterangan'); //set column field database for datatable orderable
        $this->column_search =  array('nomor_pembayaran', 'nama_periode', 'nomor_induk', 'nama', 'nama_prodi', 'total_nilai_tagihan', 'keterangan'); //set column field database for datatable orderable

        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    //? End of dtb tagihan

    public function download_tagihan($where = null)
    {
        $this->custom_query_tagihan_all($where);
        $query = $this->db->get();
        return $query;
    }

    public function get_tagihan($where = null)
    {
        $this->db->select('id_record_tagihan,nomor_pembayaran,nama_periode,nama,nama_prodi,total_nilai_tagihan,keterangan,kode_periode');
        $this->db->from('tagihan');
        if (isset($where['kode_periode'])) {
            $this->db->where('kode_periode', $where['kode_periode']);
        }
        if (isset($where['keterangan'])) {
            if (strtolower($where['keterangan']) == "nonukt") {
                $this->db->where("(keterangan NOT IN ('UKT','ukt') AND keterangan IS NOT NULL)");
            } else if (strtolower($where['keterangan']) == "ukt") {
                $this->db->where("(keterangan IN ('UKT','ukt') or keterangan IS NULL)");
            } else {
                $this->db->where('keterangan', $where['keterangan']);
            }
        }
        $this->db->where('(is_angsuran = 0 OR is_angsuran IS NULL)');
        $this->db->where("strata IN (" . $this->session->userdata('user')['jenjang'] . ")");

        return $this->db->get();
    }

    public function get_tagihan_ukt()
    {
        $this->db->select('*');
        $this->db->from('tagihan');
        $this->db->where("strata IN (" . $this->session->userdata('user')['jenjang'] . ")");
        $this->db->where('(keterangan IN("UKT","ukt") or keterangan IS NULL)');
        $this->db->where('kode_periode', $this->session->userdata('user')['periode']);

        return $this->db->get();
    }

    public function get_tagihan_pembayaran_by_nim($nomor_induk)
    {
        $this->db->select('total_nilai_tagihan');
        $this->db->from('tagihan');
        // $this->db->join("pembayaran", "pembayaran.id_record_tagihan=tagihan.id_record_tagihan");
        $this->db->where("strata IN (" . $this->session->userdata('user')['jenjang'] . ")");
        $this->db->where('keterangan IN("UKT","ukt")');
        $this->db->where('kode_periode', $this->session->userdata('user')['periode']);
        $this->db->where('nomor_induk', $nomor_induk);
        $this->db->where('is_angsuran', '1');

        return $this->db->get();
    }

    public function get_tagihan_by_nim($nomor_induk)
    {
        $this->db->select('*');
        $this->db->from('tagihan');
        $this->db->where("strata IN (" . $this->session->userdata('user')['jenjang'] . ")");
        $this->db->where('keterangan IN ("UKT","ukt")');
        $this->db->where('kode_periode', $this->session->userdata('user')['periode']);
        $this->db->where('nomor_induk', $nomor_induk);
        $this->db->where('(is_angsuran = "0" OR is_angsuran IS NULL)');

        return $this->db->get();
    }


    public function get_info_mhs($mhsNiu)
    {
        $this->dbSimari->select('*');
        $this->dbSimari->from('sia_m_mahasiswa');
        $this->dbSimari->where('mhsNiu', $mhsNiu);

        return $this->dbSimari->get();
    }

    public function get_tagihan_with_strata($where = null)
    {
        $this->db->select('*');
        $this->db->from('tagihan');
        $this->db->where("strata IN (" . $this->session->userdata('user')['jenjang'] . ")");
        if ($where != null)
            $this->db->where($where);

        return $this->db->get();
    }

    public function get_tagihan_where($where = null)
    {
        $this->db->select('*');
        $this->db->from('tagihan');
        if ($where != null)
            $this->db->where('id_record_tagihan', $where);
        return $this->db->get();
    }

    public function get_tagihan_where_nopem($where = null)
    {
        $this->db->select('*');
        $this->db->from('tagihan');
        if ($where != null)
            $this->db->where('nomor_pembayaran', $where);
        return $this->db->get();
    }

    public function get_nilai_tagihan($nim, $kode_periode)
    {
        $this->db->select('total_nilai_tagihan');
        $this->db->from('tagihan');
        $this->db->where('nomor_induk', $nim);
        $this->db->where('kode_periode', $kode_periode);
        $hasil = $this->db->get();

        return $hasil->row_array();
    }

    public function batch_insert_pembayaran_non_ukt($where)
    {
    }


    public function batch_insert_tagihan_non_ukt($dataset)
    {
        $fail = [];
        foreach ($dataset as $ds) {
            if ($ds['total_nilai_tagihan'] !== "" && $ds['total_nilai_tagihan'] !== "0") {
                if (!$this->db->replace('tagihan', $ds)) {
                    $fail[] = $ds;
                }
            }
        }

        // $this->db->insert_batch('tagihan', $dataset);
        if (count($fail) > 0) {
            $nomor_pembayaran = '';
            foreach ($fail as $f) {
                $nomor_pembayaran .= $f['nomor_pembayaran'] . '<br>';
            }
            $validasi = [
                'status' => 1,
                'fail' => 'Ada beberapa peserta yang gagal dimasukkan yaitu : <br>' . $nomor_pembayaran,
            ];
            return $validasi;
            //     # Something went wrong.
            //     $this->db->trans_rollback();
            //     return 0;
        } else {
            $validasi = [
                'status' => 1,
                'fail' => '',
            ];
            return $validasi;
            //     # Everything is Perfect. 
            //     # Committing data to the database.
            //     $this->db->trans_commit();
            //     return 1;
        }
    }

    public function get_prodifakultas_list()
    {
        $this->dbSimari->select('*');
        $this->dbSimari->from('sia_m_prodi');
        $this->dbSimari->join('sia_m_jurusan', 'sia_m_jurusan.jurKode = sia_m_prodi.prodiJurKode');
        $this->dbSimari->join('sia_m_fakultas', 'sia_m_jurusan.jurFakKode = sia_m_fakultas.fakKode');
        $this->dbSimari->where("prodiJjarKode IN (" . $this->session->userdata('user')['jenjang'] . ")");
        $this->dbSimari->order_by('fakNamaResmi', 'asc');
        $this->dbSimari->order_by('prodiNamaResmi', 'asc');


        $r = $this->dbSimari->get();

        return $r;
    }

    public function get_prodi_data($prodi)
    {
        $this->dbSimari->select('*');
        $this->dbSimari->from('sia_m_prodi');
        $this->dbSimari->join('sia_m_jurusan', 'sia_m_jurusan.jurKode = sia_m_prodi.prodiJurKode');
        $this->dbSimari->join('sia_m_fakultas', 'sia_m_jurusan.jurFakKode = sia_m_fakultas.fakKode');
        $this->dbSimari->where('prodiKode', $prodi);
        $this->dbSimari->where("prodiJjarKode IN (" . $this->session->userdata('user')['jenjang'] . ")");


        $r = $this->dbSimari->get();

        return $r;
    }

    public function get_list_pendaftar($regProdiTerima, $daftarId)
    {
        $this->dbSimari->select('*');
        $this->dbSimari->from('reg_mahasiswa_baru');
        $this->dbSimari->join('reg_pendaftaran', 'reg_mahasiswa_baru.regJalurMasuk = reg_pendaftaran.daftarId');
        $this->dbSimari->where('regProdiTerima', $regProdiTerima);
        $this->dbSimari->where('daftarId', $daftarId);

        $hasil = $this->dbSimari->get();
        return $hasil;
    }

    public function get_reg_pendaftaran()
    {
        $this->dbSimari->select('*');
        $this->dbSimari->from('reg_pendaftaran');
        $this->dbSimari->order_by('daftarTahun');
        $this->dbSimari->order_by('daftarJalur');
        $this->dbSimari->where('daftarSemAwal', $this->session->userdata('user')['periode']);

        $hasil = $this->dbSimari->get();
        return $hasil;
    }


    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function upload_berkas($key, $filetype)
    {
        if (count($_FILES) == 0) {
            return 0;
        } else {
            // jika tidak terdapat error pada file
            if ($_FILES[$key]['error'] == 0) {
                $status_upload = $this->upload($key, $filetype);

                // jika upload tidak error
                if (!$status_upload['error']) {
                    $validasi['file'] = $status_upload['upload_data'];
                } else {
                    $validasi['nama_file'] = 'error';
                }
            }
        }
        return $validasi;
    }

    public function upload($key, $filetype)
    {
        $data = ['error' => ''];

        $rs = $this->generateRandomString(10);
        $config['upload_path']          = $this->config->item("upload_path");
        $config['allowed_types']        = $filetype;
        $config['file_name']            = $rs;
        // $config['overwrite']            = true;
        $config['max_size']             = 3000; // 1MB
        // $config['max_width']            = 1024;
        // $config['max_height']           = 768;

        $this->load->library('Handle_file', $config, 'upload');

        if (!$this->upload->do_upload($key)) {
            $error = array('error' => $this->upload->display_errors());
            return $error;
        } else {
            $data  = array(
                'upload_data' => $this->upload->data(),
                'error' => ''
            );
            return $data;
        }
    }

    public function get_mhs_tanpa_tagihan($kode_periode)
    {
        $hasil =  $this->dbSimari->query('
            SELECT mhsNiu, mhsNama
            FROM simari.sia_m_mahasiswa
            WHERE mhsNiu NOT IN (SELECT klrMhsNim FROM simari.sia_t_keluar) AND 
            mhsNiu NOT IN (SELECT nomor_induk FROM tagihan WHERE kode_periode=' . $this->dbSimari->escape($kode_periode));
        return $hasil;
    }

    public function list_strata_allowed($print)
    {
        $jenjang_sess = $this->session->userdata('user')['jenjang'];
        $jenjang_new = str_replace('\'', '', $jenjang_sess);
        if ($print != 1) {
            $jenjang_new = str_replace(' ', '', $jenjang_new);
            $jenjang_new = explode(",", $jenjang_new);
        }
        return $jenjang_new;
    }

    public function validasi_strata($strata = NULL)
    {
        $jenjang_arr = $this->list_strata_allowed(0);
        $allowed = 0;
        foreach ($jenjang_arr as $ja) {
            if ($strata == $ja) {
                $allowed = 1;
            }
        }
        return $allowed;
    }

    public function cek_tagihan_angsuran($where = null)
    {

        if ($where != null)
            $this->db->where($where);
        $this->db->where("strata IN (" . $this->session->userdata('user')['jenjang'] . ")");
        $this->db->where('(is_angsuran = "0" OR is_angsuran IS NULL)');


        return $this->db->count_all_results($this->table);
    }


    public function cek_tagihan($where = null)
    {

        if ($where != null)
            $this->db->where($where);
        $this->db->where("strata IN (" . $this->session->userdata('user')['jenjang'] . ")");

        return $this->db->count_all_results($this->table);
    }

    public function update_angsuran($data)
    {
        // Insert
        $this->db->where('id_record_tagihan', $data['id_record_tagihan']);
        $update = $this->db->update('tagihan', $data);

        if ($update) return 1;
        else return 0;
    }

    public function cek_tagihan_maba($no_ujian, $kode_periode)
    {
        $hasil =  $this->dbSimari->query('
            SELECT
            regNopes,
            regNama,
            daftarTahun AS angkatan,
            prodiJjarKode AS jenjang,
            fakKode,
            fakNamaSingkat,
            prodiKode,
            prodiNamaResmi,
            IFNULL(regBidikmisi,"TIDAK") AS bidikmisi
            FROM simari.reg_mahasiswa_baru
            JOIN simari.reg_pendaftaran ON daftarId=regJalurMasuk
            JOIN simari.sia_m_prodi ON prodiKode=regProdiTerima
            JOIN simari.sia_m_jurusan ON jurKode=prodiJurKode
            JOIN simari.sia_m_fakultas ON fakKode=jurFakKode
            WHERE daftarSemAwal=' . $this->dbSimari->escape($kode_periode) . ' AND regStatus=2 AND regNopes=' . $this->dbSimari->escape($no_ujian))->result_array();
        $tagihan = $this->db->where("nomor_induk", $no_ujian)->get("tagihan")->result_array();
        $simari = [];
        $h2h = [];
        foreach ($hasil as $r) {
            $simari[$r['regNopes']] = $r;
        }
        foreach ($tagihan as $t) {
            $h2h[$t['nomor_induk']] = ['id_record_tagihan' => $t['id_record_tagihan']];
        }
        $join = array_intersect_key($simari, $h2h);
        foreach ($join as $k => $r) {
            $join[$k]['id_record_tagihan'] = $h2h[$k]['id_record_tagihan'];
        }
        return $join;
    }

    public function cek_maba($no_ujian, $kode_periode)
    {
        $hasil =  $this->dbSimari->query('
            SELECT
                regNopes,
                regNama,
                daftarTahun AS angkatan,
                prodiJjarKode AS jenjang,
                fakKode,
                fakNamaSingkat,
                prodiKode,
                prodiNamaResmi
            FROM simari.reg_mahasiswa_baru
            JOIN simari.reg_pendaftaran ON daftarId=regJalurMasuk
            JOIN simari.sia_m_prodi ON prodiKode=regProdiTerima
            JOIN simari.sia_m_jurusan ON jurKode=prodiJurKode
            JOIN simari.sia_m_fakultas ON fakKode=jurFakKode
            WHERE 
                daftarSemAwal=' . $this->dbSimari->escape($kode_periode) . ' AND 
                regStatus=2 AND 
                regNopes=' . $this->dbSimari->escape($no_ujian));
        return $hasil->result();
    }

    public function detail_maba($no_ujian, $kode_periode)
    {
        $hasil =  $this->dbSimari->query('
            SELECT
                regNopes as nim,
                regNama as nama,
                daftarTahun AS angkatan,
                prodiJjarKode AS strata,
                fakKode as kode_fakultas,
                fakNamaSingkat as nama_fakultas,
                prodiKode as kode_prodi,
                prodiNamaResmi as nama_prodi
            FROM simari.reg_mahasiswa_baru
            JOIN simari.reg_pendaftaran ON daftarId=regJalurMasuk
            JOIN simari.sia_m_prodi ON prodiKode=regProdiTerima
            JOIN simari.sia_m_jurusan ON jurKode=prodiJurKode
            JOIN simari.sia_m_fakultas ON fakKode=jurFakKode
            WHERE 
                daftarSemAwal=' . $this->dbSimari->escape($kode_periode) . ' AND 
                regStatus=2 AND 
                regNopes=' . $this->dbSimari->escape($no_ujian));
        return $hasil->row();
    }

    public function cek_tagihan_massal($nim, $kode_periode)
    {
        $this->db->select('id_record_tagihan, nomor_induk, total_nilai_tagihan');
        $this->db->from('tagihan');
        $this->db->where_in('nomor_induk', $nim);
        $this->db->where('kode_periode', $kode_periode);
        $hasil = $this->db->get();
        return $hasil->row();
        /*
        if(count($hasil) > 0)
            return true;
        else 
            return false;
        */
    }

    public function get_prodi($prodi)
    {
        $this->dbSimari->select('*');
        $this->dbSimari->from('sia_m_prodi');
        $this->dbSimari->join('sia_m_jurusan', 'sia_m_jurusan.jurKode = sia_m_prodi.prodiJurKode');
        $this->dbSimari->join('sia_m_fakultas', 'sia_m_jurusan.jurFakKode = sia_m_fakultas.fakKode');
        $this->dbSimari->where('prodiKode', $prodi);

        $r = $this->dbSimari->get();

        return $r;
    }

    function insert_tagihan($data)
    {
        // check if duplicate
        $query = $this->db->get_where('tagihan', array(
            'id_record_tagihan' => $data['id_record_tagihan']
        ));
        $count = $query->num_rows();

        if ($count) {
            return 0;
        }

        // Insert
        $insert = $this->db->insert('tagihan', $data);

        if ($insert) return 1;
        else return 0;
    }

    function update_tagihan($data)
    {
        // Insert
        $this->db->where('id_record_tagihan', $data['id_record_tagihan']);
        $update = $this->db->update('tagihan', $data);

        if ($update) return 1;
        else return 0;
    }



    function salinTagihanMhsAktif($sumber, $target, $waktu_berlaku, $waktu_berakhir, $cb)
    {
        $id_record = substr($target["kode_periode"], 2, 3);
        $kode_periode = $target["kode_periode"];
        $nama_periode = $target["nama_periode"];
        $filter_jenjang = "strata IN (" . $this->session->userdata('user')['jenjang'] . ") AND";

        // jnsKode (1=aktif, 2=cuti, 3=daftar admisi)
        $jnsKode = "AND (jnsKode = 1 ";
        if ($cb['mhs_cuti'] == 1) {
            $jnsKode .= "or jnsKode = 2";
        }
        $jnsKode .= ")";

        // keringanan_ukt (0=tidak, 1=ya)
        $mhs_keringanan = "AND (keringanan_ukt = 0 or keringanan_ukt IS NULL ";
        if ($cb['mhs_keringanan'] == 1) {
            $mhs_keringanan .= "or keringanan_ukt = 1";
        }
        $mhs_keringanan .= ")";

        $query = $this->db->query("
            REPLACE INTO tagihan (
                id_record_tagihan,
                nomor_pembayaran,
                nama,
                kode_fakultas,
                nama_fakultas,
                kode_prodi,
                nama_prodi,
                kode_periode,
                nama_periode,
                is_tagihan_aktif,
                waktu_berlaku,
                waktu_berakhir,
                strata,
                angkatan,
                urutan_antrian,
                total_nilai_tagihan,
                nomor_induk,
                pembayaran_atau_voucher,
                voucher_nama,
                voucher_nama_fakultas,
                voucher_nama_prodi,
                voucher_nama_periode,
                jnsKode,
                keterangan,
                is_angsuran,
                parent_id_record_tagihan,
                keringanan_ukt
                ) 
                SELECT 
                    CONCAT(CONCAT(" . $this->db->escape($id_record) . ",'01'),REPLACE(nomor_pembayaran,'#','')) AS id_record_tagihan,
                    REPLACE(nomor_pembayaran,'#','') as nomor_pembayaran,
                    nama,
                    kode_fakultas,
                    nama_fakultas,
                    kode_prodi,
                    nama_prodi,
                    " . $this->db->escape($kode_periode) . " AS kode_periode,
                    " . $this->db->escape($nama_periode) . " AS nama_periode,
                    is_tagihan_aktif,
                    " . $this->db->escape($waktu_berlaku) . " as `waktu_berlaku`,
                    " . $this->db->escape($waktu_berakhir) . " as `waktu_berakhir`,
                    strata,
                    angkatan,
                    urutan_antrian,
                    `total_nilai_tagihan`,
                    `nomor_induk`,
                    `pembayaran_atau_voucher`,
                    `voucher_nama`,
                    `voucher_nama_fakultas`,
                    `voucher_nama_prodi`,
                    `voucher_nama_periode`,
                    `jnsKode`,
                    'UKT' as keterangan,
                    is_angsuran,
                    parent_id_record_tagihan,
                    keringanan_ukt
                FROM
                    tagihan
                WHERE
                    " . $filter_jenjang . "
                    kode_periode=" . $this->db->escape($sumber) . " 
                    " . $jnsKode . "
                    AND nomor_induk IN (SELECT mhsNiu FROM `simari`.`sia_m_mahasiswa`) 
                    AND nomor_induk IN ( SELECT key_val_2 FROM pembayaran WHERE key_val_5 = " . $this->db->escape($sumber) . " )
                    AND nomor_induk NOT IN (SELECT klrMhsNim FROM `simari`.`sia_t_keluar`) 
                    AND (is_angsuran = 0 OR is_angsuran IS NULL)
                    " . $mhs_keringanan . "
                    AND (keterangan IN ('UKT','ukt') or keterangan IS NULL)
                    ");
        //echo $query; die;
        //print_r($data);

        // echo "<pre>";
        // print_r($query->result());
        // echo "</pre>";
        // exit();

        return $query;
    }

    function updateByField($where, $data, $strata = false)
    {
        if ($strata) {
            if ($this->session->user['role'] == 'admin_s1')
                $this->db->where_not_in('strata', array('S2', 'S3'));
            else if ($this->session->user['role'] == 'admin_s2')
                $this->db->where_in('strata', array('S2', 'S3'));
        }
        $this->db->where($where);
        //print_r($data);
        return $this->db->update($this->table, $data);
    }
    public function update_batch($array)
    {
        return $this->db->update_batch($this->table, $array, 'id_record_tagihan');
    }
    public function insert_batch($array)
    {
        return $this->db->insert_batch($this->table, $array);
    }
    public function replace($array)
    {
        return $this->db->replace($this->table, $array);
    }

    public function delete_tagihan($where)
    {
        foreach ($where as $w) {
            $this->db->or_where("`id_record_tagihan`='" . $w . "'");
        }

        $this->db->delete('tagihan');
        return $this->db->affected_rows();
    }

    public function delete_tagihan_single($where)
    {
        $this->db->where("`id_record_tagihan`='" . $where . "'");
        $this->db->delete('tagihan');
        return $this->db->affected_rows();
    }
}
