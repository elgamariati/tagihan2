<?php

class Role_m extends MY_Model {

    protected $table = "reg_user_role";
    protected $pK = "role";

    public function rules($scenario = null) {
        if($scenario === 'tetap'){
            $rules = array(
                array('field'=>'role', 'label'=>'Role', 'rules'=>'required')
            );
        }else{
            $rules = array(
                array('field'=>'role', 'label'=>'Role',
                    'rules'=>array(
                        'required',
                        array('exist',array(Role_m::model(), 'exist'))
                    ),
                    'errors'=>array('exist'=>"%s sudah terdaftar")
                )
            );
        }
        return $rules;
    }
    
    public function exist($value){
        $this->db->where($this->pK,$value);
        return !($this->db->count_all_results($this->table) > 0);
    }
        
}
