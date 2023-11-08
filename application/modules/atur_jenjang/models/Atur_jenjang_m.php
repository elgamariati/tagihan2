<?php
class Atur_jenjang_m extends MY_Model
{
    public function set_jenjang($jenjang)
    {
        if ($jenjang !== "empty") {
            if ($jenjang == 1) {
                $jenjang = "'D3', 'S1', 'PR', 'S2', 'S3', 'Sp-1'";
            } else if ($jenjang == 2) {
                $jenjang = "'D3', 'S1', 'PR', 'Sp-1'";
            } else if ($jenjang == 3) {
                $jenjang = "'S2', 'S3'";
            }
            $get_sess = $this->session->userdata()['user'];
            $get_sess['jenjang'] = $jenjang;
            $this->session->set_userdata('user', $get_sess);

            $get_sess['jenjang_text'] = $this->list_strata_allowed(1);
            $this->session->set_userdata('user', $get_sess);

            return 1;
        } else {
            return 0;
        }
    }

    public function set_back_jenjang($jenjang = NULL)
    {
        if ($jenjang !== NULL) {
            $get_sess = $this->session->userdata()['user'];
            $get_sess['jenjang'] = $jenjang;
            $this->session->set_userdata('user', $get_sess);

            $get_sess['jenjang_text'] = $this->list_strata_allowed(1);
            $this->session->set_userdata('user', $get_sess);

            return 1;
        } else {
            return 0;
        }
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
}
