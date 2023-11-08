<?php
class Atur_rentang_m extends MY_Model
{
    public function set_jenjang($jenjang)
    {
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

    function update_rentang($data, $filter)
    {
        if ($data['jenis_tagihan'] == 'UKT') {
            $this->db->where("keterangan", 'UKT');
        } else if ($data['jenis_tagihan'] == 'Tes Psikologi') {
            $this->db->where("keterangan", 'Tes Psikologi');
        } else if ($data['jenis_tagihan'] == 'Tes Kesehatan') {
            $this->db->where("keterangan", 'Tes Kesehatan');
        } else if ($data['jenis_tagihan'] == 'Tes Bakat') {
            $this->db->where("keterangan", 'Tes Bakat');
        }
        $this->db->where("strata IN (" . $filter['jenjang'] . ")");
        $this->db->where("kode_periode", $data['periode']);

        $update_data = array(
            'waktu_berlaku' => (new datetime($data['waktu_berlaku']))->format('Y-m-d H:i:s'),
            'waktu_berakhir' => (new datetime($data['waktu_berakhir']))->format('Y-m-d H:i:s'),
        );

        // echo "<pre>";
        // print_r($this->db->get('tagihan')->result());
        // print_r($this->db->last_query());
        // echo "</pre>";
        // exit();

        $update = $this->db->update('tagihan', $update_data);
        if ($update) return 1;
        else return 0;
    }
}
