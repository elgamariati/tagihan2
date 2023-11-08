<?php

class User_m extends MY_Model {

    protected $table = "reg_user";
    protected $pK = "username";

    public $password;
    public $nama;
    public $role;
    
    public function rules($scenario = null) {
        $rules = array(
            array('field'=>'username', 'label'=>'Username',
                'rules'=>array(
                    'required',
                    array('exist',array(User_m::model(), 'exist'))
                ),
                'errors'=>array('exist'=>"%s sudah terdaftar")
            ),
            array('field' => 'password', 'label' => 'Password', 'rules' => 'required'),
            array('field' => 'ulangPassword', 'label' => 'Ulang Password', 'rules' => 'required|matches[password]'),
            array('field' => 'nama', 'label' => 'Nama', 'rules' => 'required'),
            array('field' => 'role', 'label' => 'Role', 'rules' => 'required')
        );
        if($scenario == 'update')
            unset ($rules[0],$rules[1],$rules[2]);
        if($scenario == "ganti"){
            $rules = array(
                array('field'=>'password_old', 'label'=>'Password Lama',
                'rules'=>array(
                        'required',
                        array('checkOld',array(User_m::model(), 'checkOld'))
                    ),
                    'errors'=>array('checkOld'=>"%s Salah !")
                ),
                array('field' => 'password', 'label' => 'Password Baru', 'rules' => 'required'),
                array('field' => 'ulangPassword', 'label' => 'Ulang Password', 'rules' => 'required|matches[password]'),
            );
        }
        return $rules;
    }
    
    public function exist($value){
        $this->db->where($this->pK,$value);
        return !($this->db->count_all_results($this->table) > 0);
    }
        
    public function checkOld($value){
        $username = $this->session->user['username'];
        $this->db->where('username',$username);
        $data = $this->db->get('reg_user');
        $row = $data->row();
        return ($this->sirema->hashPassword($value) === $row->password);
    }
    
    public function listRole(){
        $this->db->select('role');
        $this->db->from('reg_user_role');
        $this->db->order_by('role ASC');
        $r = $this->db->get();
        foreach($r->result_array() as $row){
            $return[$row['role']] = "$row[role]";
        }
        return $return;
    }
    
    public function reset($id, $password) {
        $this->db->set('password', $this->sirema->hashPassword($password));
        $this->db->where($this->pK, $id);
        return $this->db->update($this->table);
    }
	
	
    
    public function ganti($id, $password) {
        $this->db->set('password', $this->sirema->hashPassword($password));
        $this->db->where($this->pK, $id);
        return $this->db->update($this->table);
    }
}
