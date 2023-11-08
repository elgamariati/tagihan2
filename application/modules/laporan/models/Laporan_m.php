<?php
class Laporan_m extends MY_Model
{
    private $dbSimari;
    protected $table = "pembayaran";
    protected $pK = "id_record_pembayaran";
    var $column_search;
    var $column_order;

    public function __construct()
    {
        parent::__construct();
        $this->dbSimari = $this->load->database("simari", true);
        $this->load->model('atur_jenjang/Atur_jenjang_m', 'atur_jenjang');
    }


    public function mhs_aktif_download($where = null)
    {
        $this->dtb_mhs_aktif();
        $this->dtb_mhs_aktif_order();
        if (isset($where['kode_fakultas'])) {
            $this->dbSimari->where('fakKode', $where['kode_fakultas']);
        }
        if (isset($where['kode_prodi'])) {
            $this->dbSimari->where('prodiKode', $where['kode_prodi']);
        }
        $query = $this->dbSimari->get();

        //print_r( $this->db->query_times );
        return $query;
    }


    //? Start of mhs_aktif datatbles query
    public function dtb_mhs_aktif()
    {
        $this->dbSimari->select('mhsNiu,mhsNama,mhsAngkatan,prodiNamaResmi AS prodi,fakNamaResmi, prodiJjarKode');
        $this->dbSimari->from('sia_m_mahasiswa');
        $this->dbSimari->join('sia_m_prodi', 'prodiKode = mhsProdiKode');
        $this->dbSimari->join('sia_m_jurusan', 'jurKode = prodiJurKode');
        $this->dbSimari->join('sia_m_fakultas', 'fakKode = jurFakKode');
        $this->dbSimari->join('sia_t_keluar', 'klrMhsNim = mhsNiu AND (klrSemester <= ' . $this->session->userdata('user')['periode'] . ' OR klrSemester IS NULL)', 'left');
        $this->dbSimari->where('klrMhsNim');
        $this->dbSimari->where("prodiJjarKode IN (" . $this->session->userdata('user')['jenjang'] . ")");
    }

    public function dtb_mhs_aktif_order()
    {
        $this->dbSimari->order_by('mhsNiu', 'asc');
        $this->dbSimari->order_by('mhsNama', 'desc');
        $this->dbSimari->order_by('mhsAngkatan', 'desc');
        $this->dbSimari->order_by('fakNamaResmi', 'asc');
        $this->dbSimari->order_by('prodiNamaResmi', 'asc');
    }

    public function ma_dtb($where = null)
    {
        $this->dtb_mhs_aktif();
        $this->custom_get_datatables_query_dbSimari();
        if ($_POST['length'] != -1)
            $this->dbSimari->limit($_POST['length'], $_POST['start']);
        $query = $this->dbSimari->get();
        //print_r( $this->db->query_times );
        return $query;
    }

    public function ma_custom_count_all($where = null)
    {
        $this->dtb_mhs_aktif($where);
        return $this->dbSimari->count_all_results();
    }

    public function ma_count_filtered($where = null)
    {
        $this->dtb_mhs_aktif($where);
        $this->custom_get_datatables_query_dbSimari($where);
        $query = $this->dbSimari->get();
        return $query->num_rows();
    }

    public function custom_get_datatables_query_dbSimari($where = null)
    {
        $this->column_search = array('mhsNiu', 'mhsNama', 'mhsAngkatan', 'prodiJjarKode', 'prodiNamaResmi', 'fakNamaResmi'); //set column field database for datatable searchable 
        $this->column_order = array(null, 'mhsNiu', 'mhsNama', 'mhsAngkatan', 'prodiJjarKode', 'prodiNamaResmi', 'fakNamaResmi'); //set column field database for datatable orderable

        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->dbSimari->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->dbSimari->like($item, $_POST['search']['value']);
                } else {
                    $this->dbSimari->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->dbSimari->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->dbSimari->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->dbSimari->order_by(key($order), $order[key($order)]);
        } else {
            $this->dtb_mhs_aktif_order();
        }
    }
    //? End of mhs_aktif datatbles query

    public function data_mhs_download($where = null)
    {
        $this->dtb_data_mhs();
        $this->dtb_data_mhs_order();
        if (isset($where['kode_fakultas'])) {
            $this->dbSimari->where('fakKode', $where['kode_fakultas']);
        }
        if (isset($where['kode_prodi'])) {
            $this->dbSimari->where('prodiKode', $where['kode_prodi']);
        }
        $query = $this->dbSimari->get();

        //print_r( $this->db->query_times );
        return $query;
    }

    public function dtb_data_mhs()
    {
        $this->dbSimari->select('mhsNomorTes, mhsNiu, mhsNama, mhsKotaKodeLahir, mhsTanggalLahir, mhsNoHp, mhsEmail, mhsJenisKelamin, mhsAngkatan, mhsProdiKode, prodiNamaResmi AS prodi,fakNamaResmi, prodiJjarKode, mhsortuNoTelpOrangTua, mhsortuNoTelpWali');
        $this->dbSimari->from('sia_m_mahasiswa');
        $this->dbSimari->join('sia_m_mahasiswa_orang_tua', 'mhsortuMhsNiu = mhsNiu', 'left');
        // $this->dbSimari->join('sia_r_kota', 'kotaKode = mhsKotaKodeLahir', 'left');
        $this->dbSimari->join('sia_m_prodi', 'prodiKode = mhsProdiKode');
        $this->dbSimari->join('sia_m_jurusan', 'jurKode = prodiJurKode');
        $this->dbSimari->join('sia_m_fakultas', 'fakKode = jurFakKode');
        $this->dbSimari->join('sia_t_keluar', 'klrMhsNim = mhsNiu AND (klrSemester <= ' . $this->session->userdata('user')['periode'] . ' OR klrSemester IS NULL)', 'left');
        $this->dbSimari->where('klrMhsNim');
        $this->dbSimari->where("prodiJjarKode IN (" . $this->session->userdata('user')['jenjang'] . ")");
    }

    public function dtb_data_mhs_order()
    {
        $this->dbSimari->order_by('mhsNiu', 'asc');
        $this->dbSimari->order_by('mhsNama', 'desc');
        $this->dbSimari->order_by('mhsAngkatan', 'desc');
        $this->dbSimari->order_by('fakNamaResmi', 'asc');
        $this->dbSimari->order_by('prodiNamaResmi', 'asc');
    }

    public function mhs_dtb($where = null)
    {
        $this->dtb_data_mhs();
        $this->custom_get_datatables_query_dbSimari_data_mhs();
        if ($_POST['length'] != -1)
            $this->dbSimari->limit($_POST['length'], $_POST['start']);
        $query = $this->dbSimari->get();
        //print_r( $this->db->query_times );
        return $query;
    }
    
    public function mhs_custom_count_all($where = null)
    {
        $this->dtb_data_mhs($where);
        return $this->dbSimari->count_all_results();
    }
    public function mhs_count_filtered($where = null)
    {
        $this->dtb_data_mhs($where);
        $this->custom_get_datatables_query_dbSimari($where);
        $query = $this->dbSimari->get();
        return $query->num_rows();
    }

    public function custom_get_datatables_query_dbSimari_data_mhs($where = null)
    {
        $this->column_search = array('mhsNomorTes', 'mhsNiu', 'mhsNama', 'mhsAngkatan', 'prodiJjarKode', 'prodiNamaResmi', 'fakNamaResmi'); //set column field database for datatable searchable 
        $this->column_order = array(null, 'mhsNomorTes', 'mhsNiu', 'mhsNama', 'mhsAngkatan', 'prodiJjarKode', 'prodiNamaResmi', 'fakNamaResmi'); //set column field database for datatable orderable

        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->dbSimari->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->dbSimari->like($item, $_POST['search']['value']);
                } else {
                    $this->dbSimari->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->dbSimari->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->dbSimari->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->dbSimari->order_by(key($order), $order[key($order)]);
        } else {
            $this->dtb_data_mhs_order();
        }
    }

    public function get_periode()
    {
        $this->db->select('*');
        $this->db->from('sibaya_ref_periode');
        $this->db->order_by('kode_periode', 'desc');
        $query = $this->db->get();
        return $query;
    }



    public function custom_count_all($where = null)
    {
        $this->custom_basic_query($where);
        return $this->db->count_all_results();
    }

    public function count_filtered($where = null)
    {
        $this->custom_basic_query($where);
        $this->custom_get_datatables_query($where);
        $query = $this->db->get();
        return $query->num_rows();
    }

    //? Keringanan UKT DTB
    public function custom_get_datatables_ku($where = null)
    {
        $this->custom_basic_query_ku($where);
        $this->custom_get_datatables_query_ku($where);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        //print_r( $this->db->query_times );
        return $query;
    }
    public function custom_count_all_ku($where = null)
    {
        $this->custom_basic_query_ku($where);
        return $this->db->count_all_results();
    }
    public function count_filtered_ku($where = null)
    {
        $this->custom_basic_query_ku($where);
        $this->custom_get_datatables_query_ku($where);
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function get_pembayaran_download_ku($where = null)
    {
        $get_sess = $this->session->userdata('user');
        $this->custom_basic_query_ku($where);
        return $this->db->get();
    }
    public function custom_basic_query_ku($where = null)
    {
        $this->db->select('*');
        $this->db->from('tagihan');
        $this->db->where('kode_periode', $where['kode_periode']);
        if (isset($where['keterangan'])) {
            if (strtolower($where['keterangan']) == "nonukt") {
                $this->db->where("(keterangan NOT IN ('UKT','ukt','Cek Plagiasi') AND keterangan IS NOT NULL)");
            } else if (strtolower($where['keterangan']) == "ukt") {
                $this->db->where("(keterangan IN ('UKT','ukt') or keterangan IS NULL)");
            } else {
                $this->db->where('keterangan', $where['keterangan']);
            }
        }
        if (isset($where['kode_fakultas'])) {
            $this->db->where('kode_fakultas', $where['kode_fakultas']);
        }
        if (isset($where['kode_prodi'])) {
            $this->db->where('kode_prodi', $where['kode_prodi']);
        }
        if (isset($where['tanggal_mulai'])) {
            $this->db->where('waktu_transaksi >= ', $where['tanggal_mulai']);
        }
        if (isset($where['tanggal_akhir'])) {
            $this->db->where('waktu_transaksi <= ', $where['tanggal_akhir']);
        }
        $this->db->where('(is_angsuran = 0 OR is_angsuran IS NULL)');
        $this->db->where("strata IN (" . $this->session->userdata('user')['jenjang'] . ")");
        $this->db->where('keringanan_ukt', '1');
    }
    public function custom_get_datatables_query_ku($where = null)
    {
        $this->column_search = array('nomor_induk', 'nama', 'total_nilai_tagihan', 'keterangan'); //set column field database for datatable searchable 
        $this->column_order = array(null, 'nomor_induk', 'nama', 'total_nilai_tagihan', 'keterangan'); //set column field database for datatable orderable

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

    //? End Of Keringan UKT DTB 


    public function custom_get_datatables($where = null)
    {
        $this->custom_basic_query($where);
        $this->custom_get_datatables_query($where);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        //print_r( $this->db->query_times );
        return $query;
    }

    public function custom_get_datatables_admisi($where = null, $id_rec = null)
    {
        $this->custom_basic_query($where);
        $this->custom_basic_query_admisi($where, $id_rec);
        $this->custom_get_datatables_query($where);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        //print_r( $this->db->query_times );
        return $query;
    }

    public function custom_count_all_admisi($where = null, $id_rec = null)
    {
        $this->custom_basic_query($where);
        $this->custom_basic_query_admisi($where, $id_rec);

        return $this->db->count_all_results();
    }

    public function count_filtered_admisi($where = null, $id_rec = null)
    {
        $this->custom_basic_query($where);
        $this->custom_basic_query_admisi($where, $id_rec);
        $this->custom_get_datatables_query($where);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function custom_basic_query_admisi($where = null, $id_rec = null)
    {
        if ($id_rec !== "semua") {
            $this->db->where('pembayaran.nomor_pembayaran LIKE "' . $id_rec . '%"');
        } else {
            $this->db->where('(pembayaran.nomor_pembayaran LIKE "91%" OR pembayaran.nomor_pembayaran LIKE "92%" OR pembayaran.nomor_pembayaran LIKE "93%")');
        }
    }


    public function custom_basic_query($where = null)
    {
        $this->db->select('*');
        $this->db->from('pembayaran');
        $this->db->join('tagihan', 'pembayaran.id_record_tagihan = tagihan.id_record_tagihan');
        $this->db->where('kode_periode', $where['kode_periode']);
        if (isset($where['keterangan'])) {
            if (strtolower($where['keterangan']) == "nonukt") {
                $this->db->where("(keterangan NOT IN ('UKT','ukt','Cek Plagiasi') AND keterangan IS NOT NULL)");
            } else if (strtolower($where['keterangan']) == "ukt") {
                $this->db->where("(keterangan IN ('UKT','ukt') or keterangan IS NULL)");
            } else {
                $this->db->where('keterangan', $where['keterangan']);
            }
        }
        if (isset($where['kode_fakultas'])) {
            $this->db->where('kode_fakultas', $where['kode_fakultas']);
        }
        if (isset($where['kode_prodi'])) {
            $this->db->where('kode_prodi', $where['kode_prodi']);
        }
        if (isset($where['tanggal_mulai'])) {
            $this->db->where('waktu_transaksi >= ', $where['tanggal_mulai']);
        }
        if (isset($where['tanggal_akhir'])) {
            $this->db->where('waktu_transaksi <= ', $where['tanggal_akhir']);
        }
        $this->db->where('(is_angsuran = 0 OR is_angsuran IS NULL)');
        $this->db->where("strata IN (" . $this->session->userdata('user')['jenjang'] . ")");
        $this->db->where('pembayaran.status_pembayaran', "1");
    }

    //? Start of Tidak Bayar
    public function custom_basic_query_tidak_bayar($where = null)
    {
        $this->db->select('*');
        $this->db->from('tagihan');
        // $this->db->join('pembayaran', 'pembayaran.id_record_tagihan = tagihan.id_record_tagihan', 'LEFT');
        // $this->db->where('pembayaran.id_record_tagihan IS NULL');
        $this->db->where('id_record_tagihan NOT IN (SELECT id_record_tagihan FROM pembayaran)');
        $this->db->where('kode_periode', $this->session->userdata('user')['periode']);
        if (isset($where['keterangan'])) {
            if (strtolower($where['keterangan']) == "nonukt") {
                $this->db->where("(keterangan NOT IN ('UKT','ukt') AND keterangan IS NOT NULL)");
            } else if (strtolower($where['keterangan']) == "ukt") {
                $this->db->where("(keterangan IN ('UKT','ukt') or keterangan IS NULL)");
            } else {
                $this->db->where('keterangan', $where['keterangan']);
            }
        }
        if (isset($where['kode_fakultas'])) {
            $this->db->where('kode_fakultas', $where['kode_fakultas']);
        }
        if (isset($where['kode_prodi'])) {
            $this->db->where('kode_prodi', $where['kode_prodi']);
        }
        $this->db->where('(is_angsuran = 0 OR is_angsuran IS NULL)');
        $this->db->where("strata IN (" . $this->session->userdata('user')['jenjang'] . ")");
    }

    public function custom_get_datatables_tidak_bayar($where = null)
    {
        $this->custom_basic_query_tidak_bayar($where);
        $this->custom_get_datatables_query($where);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        //print_r( $this->db->query_times );
        return $query;
    }

    public function custom_count_all_tidak_bayar($where = null)
    {
        $this->custom_basic_query_tidak_bayar($where);
        return $this->db->count_all_results();
    }

    public function count_filtered_tidak_bayar($where = null)
    {
        $this->custom_basic_query_tidak_bayar($where);
        $this->custom_get_datatables_query($where);
        $query = $this->db->get();
        return $query->num_rows();
    }
    //? end of tidak bayar

    public function custom_get_datatables_query($where = null)
    {
        $this->column_search = array('nomor_induk', 'nama', 'total_nilai_tagihan', 'keterangan'); //set column field database for datatable searchable 
        $this->column_order = array(null, 'nomor_induk', 'nama', 'total_nilai_tagihan', null, 'keterangan', null, null); //set column field database for datatable orderable

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

    public function get_pembayaran($where = null, $group_by = null)
    {
        $this->db->select('*');
        $this->db->from('pembayaran');
        $this->db->join('tagihan', 'pembayaran.id_record_tagihan = tagihan.id_record_tagihan');
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
        if (isset($where['kode_bank'])) {
            $this->db->where('kode_bank', $where['kode_bank']);
        }
        if (isset($group_by['kode_bank'])) {
            $this->db->group_by($group_by['kode_bank']);
        }
        $this->db->where('(is_angsuran = 0 OR is_angsuran IS NULL)');
        $this->db->where("strata IN (" . $this->session->userdata('user')['jenjang'] . ")");

        return $this->db->get();
    }

    function get_prodi($fak = null, $kode = null)
    {
        $this->dbSimari->select("*");
        $this->dbSimari->from("sia_m_prodi");
        $this->dbSimari->join("sia_m_jurusan", 'sia_m_jurusan.jurKode=sia_m_prodi.prodiJurKode');
        $this->dbSimari->where("prodiJjarKode IN (" . $this->session->userdata('user')['jenjang'] . ")");
        if ($fak != null) {
            $this->dbSimari->where("sia_m_jurusan.jurFakKode", $fak);
        }
        if ($kode != null) {
            $this->dbSimari->where("sia_m_prodi.prodiKode", $kode);
        }
        $this->dbSimari->order_by('prodiNamaResmi', 'asc');
        $this->dbSimari->order_by('prodiJjarKode', 'asc');

        $query = $this->dbSimari->get();

        return $query->result();
    }

    public function get_pembayaran_download($where = null)
    {
        $get_sess = $this->session->userdata('user');
        $this->atur_jenjang->set_jenjang($where['jenjang']);
        $this->custom_basic_query($where);
        $this->atur_jenjang->set_back_jenjang($get_sess['jenjang']);
        return $this->db->get();
    }

    public function get_pembayaran_download_admisi($where = null, $id_rec = null)
    {
        $get_sess = $this->session->userdata('user');
        $this->atur_jenjang->set_jenjang($where['jenjang']);
        $this->custom_basic_query($where);
        $this->custom_basic_query_admisi($where, $id_rec);
        $this->atur_jenjang->set_back_jenjang($get_sess['jenjang']);
        return $this->db->get();
    }

    public function get_pembayaran_download_tidak_bayar($where = null)
    {
        $get_sess = $this->session->userdata('user');
        $this->atur_jenjang->set_jenjang($where['jenjang']);
        $this->custom_basic_query_tidak_bayar($where);
        $this->atur_jenjang->set_back_jenjang($get_sess['jenjang']);
        return $this->db->get();
    }

    public function periode_convert($periode)
    {
        $ref_semester = array("1" => "Ganjil", "2" => "Genap", "3" => "Antara");
        $split = str_split($periode, 4);

        $text = '';
        if ($split[1] == 1) {
            $text = 'Ganjil';
        } else if ($split[1] == 2) {
            $text = 'Genap';
        } else if ($split[1] == 3) {
            $text = 'Antara';
        }

        $tt =  (int) $split[0] + 1;
        $tahun = $split[0] . '/' . $tt;

        $fulltext = $text . ' ' . $tahun;

        return $fulltext;
    }

    public function get_mhs_aktif()
    {
        $this->dbSimari->select('mhsNiu,mhsNama,mhsAngkatan,prodiNamaResmi AS prodi,fakNamaResmi, prodiJjarKode');
        $this->dbSimari->from('sia_m_mahasiswa');
        $this->dbSimari->join('sia_m_prodi', 'prodiKode = mhsProdiKode');
        $this->dbSimari->join('sia_m_jurusan', 'jurKode = prodiJurKode');
        $this->dbSimari->join('sia_m_fakultas', 'fakKode = jurFakKode');
        $this->dbSimari->join('sia_t_keluar', 'klrMhsNim = mhsNiu AND (klrSemester <= 20191 OR klrSemester IS NULL)', 'left');
        $this->dbSimari->where('klrMhsNim');
        $this->dbSimari->where("prodiJjarKode IN (" . $this->session->userdata('user')['jenjang'] . ")");
        $this->dbSimari->order_by('mhsNiu', 'asc');
        $this->dbSimari->order_by('mhsNama', 'desc');
        $this->dbSimari->order_by('mhsAngkatan', 'desc');
        $this->dbSimari->order_by('fakNamaResmi', 'asc');
        $this->dbSimari->order_by('prodiNamaResmi', 'asc');

        return $this->dbSimari->get();
    }
}
