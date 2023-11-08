<?php

/** @property CI_Session $session Description */
class LoginM extends MY_Model
{

    protected $table = 'simari_user';
    protected $pK = 'username';
    public $username;
    public $password;

    public function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database("simari", TRUE);
        $this->load->model('atur_rentang/Atur_rentang_m', 'atur_rentang');
        $this->load->model('periode/Periode_m', 'periode');
    }

    function rules()
    {
        return array(
            array('field' => 'username', 'label' => 'Username', 'rules' => 'required'),
            array('field' => 'password', 'label' => 'Password', 'rules' => 'required')
        );
    }

    function setUserSession($username, $username_backdoor = null)
    {
        $this->load->library('session');
        $user = $this->getAkun($username)->row_array();
        $user['jenjang'] = null;
        $user['jenjang_text'] = null;

        $user['periode'] = $this->periode->getPeriodeAktif()[0]->kode_periode;
        $user['periode_text'] = $this->atur_rentang->periode_convert($user['periode']);
        $user['username_backdoor'] = $username_backdoor;

        $this->session->set_userdata('user', $user);
    }

    function clearUserSession()
    {
        $this->load->library('session');
        $this->session->unset_userdata('user');
    }

    function getAkun($username)
    {
        $this->db->select('simari_user.username,simari_user.nama,simari_user.password,simari_role.role');
        $this->db->from('simari_user');
        $this->db->join('simari_role', 'simari_role.username=simari_user.username');
        $this->db->where('simari_user.username', $username);
        $this->db->where('simari_role.aplikasi', 'sibaya');
        $this->db->where("simari_role.role IN ('superadmin','keuangan_pasca','keuangan_rektorat','ptik')");
        $query = $this->db->get();

        return $query;
    }

    public function hashPassword($pass)
    {
        $key = '4336c1ba641b8f6c98d647915e722f4a';
        $pass = md5($pass);
        return md5($pass . $key);
    }

    function autheticate($username, $password)
    {

        $query = $this->getAkun($username);
        $result = $query->row();
        $attempt = $this->attempLogin();
        if ($attempt === TRUE) {
            if ($result !== null) {
                if ($this->hashPassword($password) === $result->password) {
                    $this->session->unset_userdata('attempt');
                    return true;
                }
            }
            return '<div class="alert alert-danger">Login tidak valid !!!</div>';
        }
        return $attempt;
    }


    /**
     * 
     * @param int $timeWait jumlah waktu tunggu dalam menit jika telah melebihi batas percobaan
     * @param int $timeAttempt maksimal kali percobaan login
     * @return boolean|string
     */
    private function attempLogin($timeWait = 10, $timeAttempt = 10)
    {
        $this->load->library('session');
        if ($this->session->has_userdata('last_attempt')) {
            $now = time();
            $last_attempt = $this->session->last_attempt;
            $m = ceil(abs($now - $last_attempt) / 60);
            if ($m >= $timeWait) {
                $this->session->unset_userdata('last_attempt');
                $this->session->set_userdata('attempt', 0);
                return true;
            }
            $minute = $timeWait - $m;
            return "<div class='alert alert-danger'>Coba lagi setelah $minute menit</div>";
        }
        if ($this->session->has_userdata('attempt')) {
            $attempt = $this->session->attempt;
            if ($attempt > $timeAttempt) {
                $this->session->set_userdata('last_attempt', time());
                return "<div class='alert alert-danger'>Coba lagi setelah 10 menit !</div>";
            }
            $attempt++;
            $this->session->set_userdata('attempt', $attempt);
            return true;
        }
        $this->session->set_userdata('attempt', 1);
        return true;
    }

    function get_pass($nim)
    {
        $query = $this->db->query("Select password from reg_user where username=" . $this->db->escape($nim) . "");
        $result = $query->row();
        return $result;
    }

    function check_username($nim)
    {
        $this->db->where('username', $nim);
        return ($this->db->count_all_results('reg_user') > 0);
    }

    function update_user($noreg, $data)
    {
        $this->db->where('username', $noreg);
        return $this->db->update('reg_user', $data);
    }
}
