<?php
class Layout
{
    private $ci;
    function __construct()
    {
        $this->ci = &get_instance();;
    }
    public $head = 'template/head';
    public $header = 'template/header';
    public $footer = 'template/footer';

    function render($view, $data = null, $menu = NULL)
    {
        $this->ci->load->model('periode/Periode_m', 'periode');
        $this->ci->load->model('atur_rentang/Atur_rentang_m', 'atur_rentang');

        $periode["aktif"] = array(
            'kode_periode' => $this->ci->session->userdata('user')['periode'],
            'nama_periode' => $this->ci->session->userdata('user')['periode_text'],
            'is_aktif' => 'Ya',
        );
        $list_periode_new = array();
        foreach ($this->ci->periode->getPeriode() as $key => $val) {
            $list_periode_new[$key] = $this->ci->atur_rentang->periode_convert($key);
        }
        $periode['list_periode'] = $list_periode_new;

        $this->ci->load->view($this->head);
        $this->ci->load->view($this->header, $periode);
        $this->ci->load->view($view, $data);
        $this->ci->load->view($this->footer);
    }

    function renderPartial($view, $data = null)
    {
        $this->ci->load->view($view, $data);
    }
}
