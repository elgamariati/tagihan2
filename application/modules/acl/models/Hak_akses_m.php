<?php

class Hak_akses_m extends MY_Model
{
    var $table = 'pembayaran_hak_akses';
    var $column_order = array(null, 'role', 'modul'); //set column field database for datatable orderable 
    var $column_search = array('role', 'modul'); //set column field database for datatable searchable 
    var $order = array('role' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->dbSimari = $this->load->database("simari", true);
    }

    public function get_all()
    {
        $this->dbSimari->select('*');
        $this->dbSimari->from('pembayaran_user_role');
        $result = $this->dbSimari->get();
        return $result;
    }

    public function get_ha($where)
    {
        $this->dbSimari->where($where);
        $this->dbSimari->from($this->table);
        $result = $this->dbSimari->get();
        return $result;
    }

    public function hapus_ha($where)
    {
        $this->dbSimari->where($where);
        $result = $this->dbSimari->delete($this->table);
        return $result;
    }

    public function insert($object)
    {
        if ($this->dbSimari->from($this->table)->where($object)->get()->num_rows() > 0) {
            if ($this->hapus_ha($object)) {
                return array(
                    "status" => true,
                    "ket" => "Berhasil"
                );
            } else {
                return array(
                    "status" => false,
                    "ket" => "Gagal menghapus data"
                );
            }
        } else {
            $object = $this->model->clean($object);

            if ($this->dbSimari->insert($this->table, $object)) {
                return array(
                    "status" => true,
                    "ket" => "Berhasil memasukan data"
                );
            } else {
                return array(
                    "status" => false,
                    "ket" => "Gagal memasukan data"
                );
            }
        }
    }

    public function clean($object)
    {
        foreach ($object as $key => $val) {
            $new_val = preg_replace('/[^A-Za-z0-9 _]/', '', $val);
            if (is_object($object)) {
                $object->$key = $new_val;
            } else if (is_array($object)) {
                $object[$key] = $new_val;
            }
        }
        return $object;
    }

    public function delete($key)
    {
        if ($this->dbSimari->where('modul', $key)->delete($this->table)) {
            return array(
                "status" => true,
                "ket" => "Berhasil menghapus"
            );
        } else {
            return array(
                "status" => false,
                "ket" => "Gagal menghapus"
            );
        }
    }

    // Start of get modul
    public function custom_get_dtb_modul_query()
    {
        $table = 'pembayaran_modul';
        $column_search = array('modul');
        $column_order = array(null, 'modul', null); //set column field database for datatable orderable 
        $order = array('modul' => 'asc');

        $this->dbSimari->from($table);

        $i = 0;

        foreach ($column_search as $item) // loop column 
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

                if (count($column_search) - 1 == $i) //last loop
                    $this->dbSimari->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->dbSimari->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($order)) {
            $order = $order;
            $this->dbSimari->order_by(key($order), $order[key($order)]);
        }
    }

    function custom_get_dtb_modul()
    {
        // $this->dbSimari->where('role', $key);
        $this->custom_get_dtb_modul_query();
        if ($_POST['length'] != -1)
            $this->dbSimari->limit($_POST['length'], $_POST['start']);
        $query = $this->dbSimari->get();

        return $query->result();
    }

    function custom_count_filtered_modul()
    {
        $this->custom_get_dtb_modul_query();
        $query = $this->dbSimari->get();
        return $query->num_rows();
    }

    public function custom_count_all_modul()
    {
        $this->dbSimari->from('pembayaran_modul');
        return $this->dbSimari->count_all_results();
    }
    // End of get modul
}
