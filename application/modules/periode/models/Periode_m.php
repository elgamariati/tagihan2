<?php
class Periode_m extends MY_Model
{
    protected $table = "sibaya_ref_periode";
    protected $pK = "kode_periode";
    var $column_order = array(null, 'kode_periode', 'nama_periode'); //set column field database for datatable orderable
    var $column_search = array('kode_periode', 'nama_periode'); //set column field database for datatable searchable 
    var $order = array('kode_periode' => 'desc'); // default order 

    public function sql_query($where = null)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        if ($where != null)
            $this->db->where($where);
    }

    //munculkan rekap pembayaran per semester
    //$jenis : jenis pembayaran, misal 1 : spp, 2 : cuti, dll
    public function getPeriodeTagihan($jenis, $pembayaran, $kode_periode = null)
    {
        //$this->db->select('si.kode_periode, si.`nama_periode`, datanya.jumlah, datanya.total, bayarnya.total as total_bayar');
        $this->db->select("si.kode_periode, si.`nama_periode`");
        $this->db->from('sibaya_ref_periode si');
        /*
            $this->db->join("(SELECT kode_periode, COUNT(*) AS jumlah, sum(total_nilai_tagihan) as total
                            FROM tagihan where jnsKode=$jenis and pembayaran_atau_voucher='$pembayaran'
                            GROUP BY kode_periode) datanya","datanya.kode_periode=si.`kode_periode`","left");
            $this->db->join("(SELECT kode_periode, COUNT(*) AS jumlah, sum(total_nilai_tagihan) as total
                            FROM tagihan join pembayaran on pembayaran.id_record_tagihan=tagihan.id_record_tagihan where tagihan.jnsKode=$jenis and tagihan.pembayaran_atau_voucher='$pembayaran'
                            GROUP BY tagihan.kode_periode) bayarnya","bayarnya.kode_periode=si.`kode_periode`","left");
            */
        if ($kode_periode != null)
            $this->db->where('si.kode_periode', $kode_periode);

        $this->db->order_by('si.kode_periode', 'desc');

        $query = $this->db->get();
        //echo $query; die;
        if ($kode_periode != null)
            return $query->row();
        else
            return $query->result();
    }

    //mengambil semua periode pembayaran yang ada di database
    public function getPeriode($kode = null)
    {
        $this->db->select('*');
        $this->db->from('sibaya_ref_periode');
        if ($kode != null)
            $this->db->where('kode_periode', $kode);
        $this->db->order_by('kode_periode', 'desc');
        $query = $this->db->get();
        //echo $query; die;

        if ($kode != null) {
            return $query->row_array();
        } else {
            $list = array();
            foreach ($query->result() as $row)
                $list[$row->kode_periode] = $row->nama_periode;
            return $list;
        }
    }

    public function getPeriodeAktif()
    {
        $this->db->select('kode_periode');
        $this->db->from('sibaya_ref_periode');
        // $this->db->where('is_aktif', 'Ya');
        $this->db->order_by('kode_periode', 'desc');

        $query = $this->db->get();
        return $query->result();
    }

    //cek apakah ada kode periode tertentu di tabel
    public function cekPeriode($kode = null)
    {
        $this->db->select('*');
        $this->db->from('sibaya_ref_periode');
        $this->db->where('kode_periode', $kode);
        $query = $this->db->get();
        //echo $query->num_rows(); die;
        if ($query->num_rows() == 1)
            return true;
        else
            return false;
    }
}
