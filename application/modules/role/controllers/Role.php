<?php

class Role extends MY_Controller {

    private $modul = 'role';

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Role_m','database');        
    }

    public function index() {
        $aksi_modul = 'baca';
        if(!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
            show_error("",401);
        if($this->input->is_ajax_request()){ 
            $request = $this->input->get();
            $data = $this->database->getDataGrid($request);
            echo json_encode($data);
        }else{
            $this->load->library('form_validation');
            $this->layout->render('index');
        }
    }

    public function simpan($id = null){
        $aksi_modul = 'tulis';
        if(!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul)){
            if($this->input->is_ajax_request())
                echo '{"simpan":false,"pesan":"Anda tidak memiliki otorisasi untuk menambah data!"}';
            else
                show_error("",401);
        }else{
            $model = $this->database->getByPrimary($id);
            $id = $model['role'];
            if($this->input->post()){    
                $model = $this->input->post();
                $this->load->library('form_validation');
                if ($id === $model['role']) {
                    $this->form_validation->set_rules($this->database->rules('tetap'));
                }else{
                    $this->form_validation->set_rules($this->database->rules());
                }
                $this->form_validation->set_data($model);
                if ($this->form_validation->run()){
                    if($id !== null){
                        $this->database->update($id,$model);
                        echo '{"simpan":true,"pesan":"Role berhasil diperbaharui !"}';
                    }else{
                        $this->database->insert($model);
                        echo '{"simpan":true,"pesan":"Role berhasil ditambahkan !"}';
                    }
                }else{
                    $data['simpan'] = false;
                    $data['pesan'] = validation_errors();
                    echo json_encode($data);
                }
            }else{
                $data['model'] = $model;
                $data['simpan'] = true;
                echo json_encode($data);
            }
        }
    }
    
    public function hapus(){
        $aksi_modul = 'hapus';
        if(!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul)){
            if($this->input->is_ajax_request())
                echo '{"hapus":false,"pesan":"Anda tidak memiliki otorisasi untuk menghapus !"}';
            else
            show_error("",401);
        }else{
            if($this->input->is_ajax_request()){
                $id = $this->input->get('id');
                if($this->database->delete($id))
                    echo '{"hapus":true,"pesan":"Role berhasil dihapus !"}';
                else
                    echo '{"hapus":false,"pesan":"Role gagal dihapus !"}';
            }else{
                $ids = $this->input->post('ids');
                $this->database->delete($ids);
                redirect (base_url('role'));
            }
        }
    }

}
