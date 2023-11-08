<?php

class Prodi_ref_m extends MY_Model {

    protected $table = "sia_m_prodi";
    protected $pK = "sia_m_prodi.prodiKode";

    public $jllrKode;
    public $jllrNama;
    
    public function rules($scenario = null) {
        if($scenario === 'tetap'){
            $rules = array(
                array('field'=>'prodiId', 'label'=>'Kode jalur Nim', 'rules'=>'required')
            );
        }else{
            $rules = array(
                array('field'=>'jllrKode', 'label'=>'Kode',
                    'rules'=>array(
                        'required',
                        array('exist',array(Jalur_m::model(), 'exist'))
                    ),
                    'errors'=>array('exist'=>"%s sudah terdaftar")
                ),
                array('field'=>'jllrNama', 'label'=>'Nama Jalur', 'rules'=>'required')
            );
        }
        return $rules;
    }
    
    public function exist($value){
        $this->db->where($this->pK,$value);
        return !($this->db->count_all_results($this->table) > 0);
    }
	public function relations(){
        return array(
            'prodi_sirema'=>array(self::HAS_ONE,'reg_m_prodi','prodiKode','sia_m_prodi.prodiKode'),
            'jurusan_sia'=>array(self::HAS_ONE,'sia_m_jurusan','jurKode','sia_m_prodi.prodiJurKode'),
            'fakultas_sia'=>array(self::HAS_ONE,'sia_m_fakultas','fakKode','jurusan_sia.jurFakKode')
        );
    }
	
    
	
	public function getProdiRow($kode){
        $this->db->select('sia_m_prodi.prodiKode, sia_m_prodi.prodiNamaResmi, reg_m_prodi.prodiId, reg_m_prodi.prodiKode as kodeRegProdi, sia_m_jurusan.jurFakKode');
        $this->db->from("sia_m_prodi");
        $this->db->join("sia_m_jurusan","sia_m_jurusan.jurKode=sia_m_prodi.prodiJurKode");
        $this->db->join("reg_m_prodi","reg_m_prodi.prodiKode=sia_m_prodi.prodiKode","left");
        $this->db->where("sia_m_prodi.prodiKode",$kode);
        $this->db->order_by("sia_m_prodi.prodiKode","asc");
        
        $r = $this->db->get();
       
        return $r->row_array();
    }
	
	function insert_reg($data) {
      
       return $this->db->insert("reg_m_prodi", $data);
    }

    
    function update_reg($id, $data) {
       $this->db->set('prodiId', $data);
        $this->db->where("prodiKode", $id);
        return $this->db->update("reg_m_prodi");
    }
        
}
