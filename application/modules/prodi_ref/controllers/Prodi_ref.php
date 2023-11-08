<?php

class Prodi_ref extends MY_Controller {

    private $modul = 'prodi_ref';

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Prodi_ref_m','database');        
    }

    public function index() {
        $aksi_modul = 'baca';
//        if(!$this->acl->cek_akses_module($this->role, $this->modul, $aksi_modul))
//            show_error("",401);
        if($this->input->is_ajax_request()){
            $request = $this->input->get();
			//$where = $this->filterRole;
            $data = $this->database->getDataGrid($request,'sia_m_prodi.prodiKode, sia_m_prodi.prodiNamaResmi, fakultas_sia.fakNamaResmi, prodi_sirema.prodiId');
			//echo $this->db->last_query();
            echo json_encode($data);
        }else{
            $this->load->library('form_validation');
            $this->layout->render('index1');
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
            $model = $this->database->getProdiRow($id);
			//print_r($model['jllrKode']);
			//echo $this->db->last_query();
			
            $id = $model['prodiKode'];
            if($this->input->post()){    
                $model_input = $this->input->post();
				//print_r($model);
                $this->load->library('form_validation');
				
				
                $this->form_validation->set_rules($this->database->rules('tetap'));
                
                $this->form_validation->set_data($model_input);
                if ($this->form_validation->run()){
                    if($model['kodeRegProdi']!==null){
						//echo $id;
						//print_r($model_input);
                        if ($this->database->update_reg($id,$model_input['prodiId']))
                        echo '{"simpan":true,"pesan":"Kode NIM untuk PRODI berhasil diperbaharui"}';
                    }else{
						$model_input['prodiKode']=$id;
						$model_input['prodiNama']=$model['prodiNamaResmi'];
						$model_input['prodiFakKode']=$model['jurFakKode'];
						//print_r($model_input);
                        if ($this->database->insert_reg($model_input))
                        echo '{"simpan":true,"pesan":"Kode NIM untuk PRODI berhasil ditambahkan !"}';
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

}
