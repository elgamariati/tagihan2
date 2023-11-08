<?php

class Modul_m extends MY_Model
{
    var $table = 'pembayaran_modul';
    var $column_order = array(null, 'modul'); //set column field database for datatable orderable 
    var $column_search = array('modul'); //set column field database for datatable searchable 
    var $order = array('modul' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->dbSimari = $this->load->database("simari", true);
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

    public function update($object, $key)
    {
        $this->dbSimari->where('modul', $key);
        if ($this->dbSimari->update($this->table, $object)) {
            return array(
                "status" => true,
                "ket" => "Berhasil memperbaharui"
            );
        } else {
            return array(
                "status" => false,
                "ket" => "Gagal memperbaharui"
            );
        }
    }

    public function insert($object)
    {
        if ($this->dbSimari->from($this->table)->where($object)->get()->num_rows() > 0) {
            return array(
                "status" => false,
                "ket" => "Duplikat data"
            );
        } else {
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

    public function custom_get_datatables_query()
    {

        $this->dbSimari->from($this->table);

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
        }
    }

    function custom_get_datatables()
    {
        $this->custom_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->dbSimari->limit($_POST['length'], $_POST['start']);
        $query = $this->dbSimari->get();
        return $query->result();
    }

    function custom_count_filtered()
    {
        $this->custom_get_datatables_query();
        $query = $this->dbSimari->get();
        return $query->num_rows();
    }

    public function custom_count_all()
    {
        $this->dbSimari->from($this->table);
        return $this->dbSimari->count_all_results();
    }
}
