<?php

class User extends MY_Controller {

    private $modul = 'user';

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('User_m','database');        
    }

    public function index() {
        $aksi_modul = 'baca';
//        if(!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
//            show_error("",401);
        if($this->input->is_ajax_request()){
            $request = $this->input->get();
            $data = $this->database->getDataGrid($request);
            echo json_encode($data);
        }else{
            $this->load->library('form_validation');
            $data['listRole'] = $this->database->listRole();
            $this->layout->render('index',$data);
        }
    }

    public function simpan($id = null){
        $aksi_modul = 'tulis';
//        if(!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul)){
//            if($this->input->is_ajax_request())
//                echo '{"simpan":false,"pesan":"Anda tidak memiliki otorisasi untuk menambah data!"}';
//            else
//                show_error("",401);
//        }else{
            $model = $this->database->getByPrimary($id);
            $id = $model['username'];
            if($this->input->post()){    
                $model = $this->input->post();
                $this->load->library('form_validation');
                if ($id !== null) {
                    unset($model['password'],$model['ulangPassword']);
                    $this->form_validation->set_rules($this->database->rules('update'));
                }else{
                    $this->form_validation->set_rules($this->database->rules());
                }
                $this->form_validation->set_data($model);
                if ($this->form_validation->run()){
                    if($id !== null){
                        $this->database->update($id,$model);
                        echo '{"simpan":true,"pesan":"User berhasil diperbaharui !"}';
                    }else{
                        $model['password'] = $this->sirema->hashPassword($model['password']);
                        unset($model['ulangPassword']);
                        $this->database->insert($model);
                        echo '{"simpan":true,"pesan":"User berhasil ditambahkan !"}';
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
//        }
    }
    
    public function hapus(){
        $aksi_modul = 'hapus';
//        if(!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul)){
//            if($this->input->is_ajax_request())
//                echo '{"hapus":false,"pesan":"Anda tidak memiliki otorisasi untuk menghapus !"}';
//            else
//            show_error("",401);
//        }else{
            if($this->input->is_ajax_request()){
                $id = $this->input->get('id');
                if($this->database->delete($id))
                    echo '{"hapus":true,"pesan":"Jalur berhasil dihapus !"}';
                else
                    echo '{"hapus":false,"pesan":"Jalur gagal dihapus !"}';
            }else{
                $ids = $this->input->post('ids');
                $this->database->delete($ids);
                redirect (base_url('user'));
            }
//        }
    }
    
    public function reset(){
        $aksi_modul = 'update';
//        if(!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul)){
//            if($this->input->is_ajax_request())
//                echo '{"hapus":false,"pesan":"Anda tidak memiliki otorisasi untuk mereset password !"}';
//            else
//            show_error("",401);
//        }else {
            if($this->input->is_ajax_request()){
                $id = $this->input->get('id');
                if($id != null && $id != ''){
                    $pass = $this->sirema->generateRandomString(6);
                    $this->database->reset($id,$pass);
                    echo '{"reset":true,"pesan":"Password telah direset menjadi \"'.$pass.'\""}';
                }else
                    echo '{"reset":false,"pesan":"Password gagal direset !"}';
            }
//        }
    }
    
    public function ubah_password(){
        $this->load->library('form_validation');
        if($this->input->post()){
            $post = $this->input->post();
            $this->form_validation->set_rules($this->database->rules('ganti'));
            if ($this->form_validation->run()){
                if($this->database->ganti($this->session->user['username'],$post['password'])){
                    $this->session->set_flashdata("msg","Password berhasil diganti !");
                }
            }
        }
        $this->layout->render('ubah_password');
    }

}
