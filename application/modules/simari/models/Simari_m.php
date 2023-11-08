<?php
class Simari_m extends CI_Model 
{
    private $simari;
	public function __construct() {
        parent::__construct();
        $this->simari=$this->load->database('simari',true);
    }

    function get_fakultas($kode=null) 
    {
        $this->simari->select("fakKode, fakNamaSingkat");
        $this->simari->from("sia_m_fakultas");
        if ($kode!=null)
            $this->simari->where("fakKode",$kode);
    	$query=$this->simari->get();
        if ($kode!=null)
            return $query->row_array(); 
        else 
        {
            $rt = array();
            foreach ($query->result_array() as $row)
            $rt[$row['fakKode']] = $row['fakNamaSingkat'];
            return $rt;
        }  
    }
    
    function get_prodi($fak=null,$kode=null,$jenjang=false) 
    {
        $this->simari->select("prodiKode, prodiNamaResmi");
        $this->simari->from("sia_m_prodi");
        $this->simari->join("sia_m_jurusan",'sia_m_jurusan.jurKode=sia_m_prodi.prodiJurKode');  
        if($jenjang){
            if($this->session->user['role']=='admin_s1')
                $this->simari->where_not_in('prodiJjarKode',array('S2','S3'));
            else if($this->session->user['role']=='admin_s2')
                $this->simari->where_in('prodiJjarKode',array('S2','S3'));
        }
        if ($fak!=null){
            $this->simari->where("sia_m_jurusan.jurFakKode",$fak);
            $query=$this->simari->get();
            $rt = array();
            foreach ($query->result_array() as $row)
                $rt[$row['prodiKode']] = $row['prodiNamaResmi'];
            return $rt;
        }
        if ($kode!=null){
            $this->simari->where("sia_m_prodi.prodiKode",$kode);
            $query=$this->simari->get();
            return $query->row_array();
        }
    }

    function cek_mhs_aktif($nim)
    {
        $this->simari->from("sia_m_mahasiswa");
        $this->simari->join("sia_t_keluar", "klrMhsNim=mhsNiu", "left");
        $this->simari->where("mhsNiu",$nim);
        $this->simari->where("klrMhsNim is NULL", NULL, FALSE);
        $hasil = $this->simari->count_all_results();
        if($hasil == 1)
            return true;
        else
            return false;
    }

    function cek_detail_mhs($nim)
    {
        $hasil = $this->simari->query("
            SELECT
                mhsNama AS nama,
                mhsNiu AS nim,
                prodiKode AS kode_prodi,
                prodiNamaResmi AS nama_prodi,
                fakKode AS kode_fakultas,
                fakNamaSingkat AS nama_fakultas,
                prodiJjarKode AS strata,
                mhsAngkatan AS angkatan
            FROM 
                sia_m_mahasiswa
                JOIN sia_m_prodi ON prodiKode=mhsProdiKode
                JOIN sia_m_jurusan ON jurKode=prodiJurKode
                JOIN sia_m_fakultas ON fakKode=jurFakKode
            WHERE 
                mhsNiu=".$this->db->escape($nim)
        );
        return $hasil->row();
    }
    
    function get_mhs($kode=null) //ambil semua mahasiswa
    {
        $this->simari->select("sia_m_mahasiswa.mhsNama,"
                . " mhsAngkatan,"
                . " sia_m_prodi.prodiKode,"
                . " sia_m_prodi.prodiNamaResmi,"
                . "sia_m_prodi.prodiJjarKode, "
                . "sia_m_fakultas.fakKode, "
                . "sia_m_fakultas.fakNamaResmi,"
                . "sia_m_fakultas.fakNamaSingkat");
        $this->simari->from("sia_m_mahasiswa");
        $this->simari->join("sia_m_prodi","sia_m_mahasiswa.mhsProdiKode=sia_m_prodi.prodiKode");
        $this->simari->join("sia_m_jurusan","sia_m_jurusan.jurKode=sia_m_prodi.prodiJurKode");
        $this->simari->join("sia_m_fakultas","sia_m_fakultas.fakKode=sia_m_jurusan.jurFakKode");

        if ($kode!=null)
        {
            $this->simari->where("mhsNiu",$kode);
        }  
        $query=$this->simari->get();
        return $query->row_array();
    }
    
    function getMahasiswaAktif($where) 
    {
        $this->simari->select(""
                . "sia_m_mahasiswa.mhsNama,"
                . "sia_m_mahasiswa.mhsNiu,"
                . " mhsAngkatan,"
                . " sia_m_prodi.prodiKode,"
                . " sia_m_prodi.prodiNamaResmi,"
                . "sia_m_prodi.prodiJjarKode, "
                . "sia_m_fakultas.fakKode, "
                . "sia_m_fakultas.fakNamaResmi "
                . "");
        $this->simari->from("sia_m_mahasiswa");
            $this->simari->join("sia_m_prodi","sia_m_mahasiswa.mhsProdiKode=sia_m_prodi.prodiKode");
            $this->simari->join("sia_m_jurusan","sia_m_jurusan.jurKode=sia_m_prodi.prodiJurKode");
            $this->simari->join("sia_m_fakultas","sia_m_fakultas.fakKode=sia_m_jurusan.jurFakKode");
            $this->simari->join("sia_t_keluar","sia_t_keluar.klrMhsNim=sia_m_mahasiswa.mhsNiu and sia_t_keluar.klrMhsNim is null","left");
        if (isset($where)){
            $this->simari->where($where);
        }  
        $query=$this->simari->get();
        return $query->result();
    }       
}
