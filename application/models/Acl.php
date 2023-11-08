<?php

class Acl extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->header_text = '<a href="' . base_url('periode') . '"><center>Tidak Memiliki Akses, Tekan Untuk Kembali</center></a>';
        $this->body_text = '<center>Universitas Lambung Mangkurat</center>';
        $this->load->database('baca');
        $this->dbSimari = $this->load->database("simari", true);

        if (!isset($this->session->user)) {
            redirect(base_url('login/keluar'));
        }
    }

    function cek_akses_module($role, $modul, $hak)
    {
        if ($role != "superadmin") {
            $this->dbSimari->select('pembayaran_role');
            $this->dbSimari->where('role', $role);
            $this->dbSimari->where('modul', $modul);
            $this->dbSimari->where('hak', $hak);
            $this->dbSimari->from('pembayaran_hak_akses');
            if ($this->dbSimari->count_all_results() == 1)
                return true;
            else
                return false;
        }
        return true;
    }
}
