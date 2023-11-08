<?php
class Bypasspembayaran_m extends MY_Model
{
    protected $table = "pembayaran";
    protected $pK = "id_record_pembayaran";

    var $column_order = array(
        null, 'nomor_induk', 'nama', 'total_nilai_pembayaran', 'waktu_transaksi',
        'kanal_bayar_bank', 'kode_terminal_bank'
    ); //set column field database for datatable orderable
    var $column_search = array('nomor_induk', 'nama'); //set column field database for datatable searchable 
    var $order = array('waktu_transaksi' => 'desc'); // default order 
    public function __construct()
    {
        parent::__construct();
        $this->dbs = $this->load->database("simari", true);
    }

    public function get_tagihan_ukt($nomor_induk)
    {
        $this->db->select('*');
        $this->db->from('tagihan');
        $this->db->where('nomor_induk', $nomor_induk);
        $this->db->where('kode_periode', $this->session->userdata('user')['periode']);
        $this->db->where("strata IN (" . $this->session->userdata('user')['jenjang'] . ")");

        return $this->db->get();
    }

    public function get_pembayaran($where = null)
    {
        $this->db->select('*');
        $this->db->from('pembayaran');
        $this->db->join('tagihan', 'pembayaran.id_record_tagihan = tagihan.id_record_tagihan');
        $this->db->where('kode_periode', $where['kode_periode']);
        $this->db->where('kode_bank', 'BYPASS');

        $this->db->where("strata IN (" . $this->session->userdata('user')['jenjang'] . ")");
        $this->db->where('pembayaran.status_pembayaran', "1");

        $hasil = $this->db->get();
        return $hasil->result();
    }

    public function cek_bypass($data)
    {
        // Insert
        $this->db->from('pembayaran');
        $this->db->join('tagihan', 'pembayaran.id_record_tagihan = tagihan.id_record_tagihan');
        $this->db->where('tagihan.nomor_pembayaran', $data['nomor_induk']);
        $this->db->where('kode_bank', 'BYPASS');
        $this->db->where('kode_periode', $this->session->userdata('user')['periode']);
        $this->db->where("strata IN (" . $this->session->userdata('user')['jenjang'] . ")");
        $this->db->where('pembayaran.status_pembayaran', "1");

        $get = $this->db->get();

        return $get;
    }

    public function delete_bypass($data)
    {
        $this->db->where('id_record_tagihan', $data['id_record_tagihan']);
        $this->db->where('kode_bank', 'BYPASS');

        $delete = $this->db->delete('pembayaran');

        if ($delete) return 1;
        else return 0;
    }


    public function delete_pembayaran($where)
    {
        foreach ($where as $w) {
            $this->db->or_where("`id_record_pembayaran`='" . $w . "'");
        }
        $this->db->delete('pembayaran');
        return $this->db->affected_rows();
    }

    public function get_pembayaran_delete($where)
    {
        $this->db->select('*');
        $this->db->from('tagihan');
        $this->db->where('nomor_pembayaran', $where['nomor_induk']);
        $this->db->where('kode_periode', $where['kode_periode']);
        $this->db->where("strata IN (" . $this->session->userdata('user')['jenjang'] . ")");

        $hasil = $this->db->get();
        return $hasil;
    }

    public function sql_query($where = null)
    {
        $this->db->select('tagihan.*, pembayaran.*');
        $this->db->from($this->table);
        $this->db->join("tagihan", "pembayaran.id_record_tagihan=tagihan.id_record_tagihan and pembayaran.kode_bank='BYPASS'");
        if ($where != null)
            $this->db->where($where);
        if ($this->session->user['role'] == 'admin_s1')
            $this->db->where_not_in('strata', array('S2', 'S3'));
        else if ($this->session->user['role'] == 'admin_s2')
            $this->db->where_in('strata', array('S2', 'S3'));
        $this->db->where('pembayaran.status_pembayaran', "1");
    }

    function updateByField($where, $data)
    {
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

    public function cek_pembayaran($where = null)
    {
        $this->db->join('tagihan', 'pembayaran.id_record_tagihan = tagihan.id_record_tagihan');
        if ($where != null)
            $this->db->where($where);
        return $this->db->count_all_results($this->table);
    }

    public function get_from($from = null)
    {
        // $this->dbs->select('');

        // $this->dbs->from();
        return $this->dbs->get($from)->result();
    }

    public function cek_tagihan($where)
    {
        $this->db->where($where);
        $data = $this->db->get('tagihan');
        return $data;
    }

    public function detail($where)
    {
        $this->db->select('tagihan.*, pembayaran.*');
        $this->db->from('pembayaran');
        $this->db->join("tagihan", "pembayaran.id_record_tagihan=tagihan.id_record_tagihan and pembayaran.kode_bank='BYPASS'");
        $this->db->where('pembayaran.status_pembayaran', "1");

        $this->db->where($where);
        $hasil = $this->db->get();

        return $hasil->row_array();
    }

    public function cara_bayar($cara_bayar = "BYPASS", $no_bayar)
    {
        $this->db->select('*');
        $this->db->from('pembayaran');
        $this->db->where('id_record_pembayaran', $no_bayar);
        $this->db->where('kode_bank', $cara_bayar);
        $hasil = $this->db->get();

        if ($hasil->num_rows() == 1)
            return true;
        else
            return false;
    }

    public function dummy()
    {
        $data = array();
        for ($i = 0; $i < 20; $i++) {
            $object = new stdClass();
            $object->nama = $i;
            $object->nomor_induk = "12312";
            $object->total_nilai_pembayaran = "123123";
            $object->waktu_transaksi = "2016-03-01";
            $object->kanal_bayar_bank = "BNI";
            $object->kode_terminal_bank = "123";
            $object->id_record_pembayaran = "32";
            $data[] =  $object;
        }

        return $data;
    }
}
