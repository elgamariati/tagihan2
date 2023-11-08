<?php
class Pembayaran_m extends MY_Model
{
    protected $table = "pembayaran";
    protected $pK = "id_record_pembayaran";

    var $column_order = array(
        null, 'nomor_induk', 'nama', 'total_nilai_pembayaran', 'waktu_transaksi',
        'kanal_bayar_bank', 'kode_terminal_bank'
    ); //set column field database for datatable orderable
    var $column_search = array('nomor_induk', 'nama'); //set column field database for datatable searchable 
    var $order = array(''); // default order 

    public function sql_query($where = null)
    {
        $this->db->select('tagihan.*, pembayaran.*');
        $this->db->from($this->table);
        $this->db->join("tagihan", "pembayaran.id_record_tagihan = tagihan.id_record_tagihan");
        if ($where != null)
            $this->db->where($where);
        if ($this->session->user['role'] == 'admin_s1')
            $this->db->where_not_in('strata', array('S2', 'S3'));
        else if ($this->session->user['role'] == 'admin_s2')
            $this->db->where_in('strata', array('S2', 'S3'));
        $this->db->where('pembayaran.status_pembayaran', "1");
    }

    public function get_pembayaran_tagihan($where = null)
    {
        $where_strata = "strata IN (" . $this->session->userdata('user')['jenjang'] . ")";
        $where_non_ukt = "(keterangan NOT IN ('UKT','ukt','Cek Plagiasi') AND keterangan IS NOT NULL)";
        $where_ukt = "(keterangan IN ('UKT','ukt') or keterangan IS NULL)";
        $this->db->select('tagihan.*, pembayaran.*');
        $this->db->from($this->table);
        $this->db->group_start();
        $this->db->join("tagihan", "pembayaran.id_record_tagihan = tagihan.id_record_tagihan");
        $this->db->where($where_strata);
        if (isset($where['kode_periode'])) {
            $this->db->where('kode_periode', $where['kode_periode']);
        }
        if (isset($where['keterangan'])) {
            if (strtolower($where['keterangan']) == "nonukt") {
                $this->db->where($where_non_ukt);
            } else if (strtolower($where['keterangan']) == "ukt") {
                $this->db->where($where_ukt);
            } else {
                $this->db->where('keterangan', $where['keterangan']);
            }
        }
        $this->db->where('pembayaran.status_pembayaran', "1");
        if (isset($where['nomor_pembayaran'])) {
            $this->db->where('pembayaran.nomor_pembayaran LIKE "' . $where['nomor_pembayaran'] . '%"');
        } else {
            if (strtolower($where['keterangan']) == "nonukt") {
                $this->db->or_group_start();
                $this->db->or_where('(pembayaran.nomor_pembayaran LIKE "91%" OR pembayaran.nomor_pembayaran LIKE "92%" OR pembayaran.nomor_pembayaran LIKE "93%")');
                $this->db->where('kode_periode', $where['kode_periode']);
                $this->db->where('pembayaran.status_pembayaran', "1");
                $this->db->where($where_strata);
                $this->db->group_end();
            }
        }
        $this->db->group_end();

        return $this->db->get();
    }

    public function get_pembayaran_all($where)
    {
        $this->sql_query($where);
        $hasil = $this->db->get();
        return $hasil->result();
    }
}
