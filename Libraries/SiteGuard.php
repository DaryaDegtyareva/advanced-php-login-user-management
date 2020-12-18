<?php namespace App\Libraries;

use App\Models\User;
use App\Models\Group;
use Firebase\JWT\JWT;

class SiteGuard {
	
	private $siteGuard_user;
	private $siteGuard_group;
	
	public $settings;
	public $config;
	public $session;
	public $csrf;
	
	public $version = 1.3;
	
	public function __construct() {
		$this->session = session();
        $this->check_login();
		if(!$this->settings) { $this->settings = new \App\Models\MiscFunction();
		$this->settings = $this->settings->settings();}
		if(!$this->config) { 
			$array = array("facebook" => array('id' =>env('facebook.id'), 'secret' => env('facebook.secret')), "google" => array('id' =>env('google.id'), 'secret' => env('google.secret')), "captcha" => array('sitekey' =>env('captcha.sitekey'), 'secret' => env('captcha.secret')));
			$this->config = $array;
		}
    }
	
	public function get_user($status = "check_logged_out") {
		if($status == "check_logged_in") {
			if ($this->is_logged_in() == true ) {
				redirect_to(base_url('index'));
				exit();
			} else {
				return false;
			}
		} else {
			if ($this->is_logged_in() != true ) {
				$cur_page = urlencode($_SERVER['REQUEST_URI']);
				redirect_to(base_url('login').'?next='.$cur_page); exit();
			} else {
				$cur_page = $_SERVER['REQUEST_URI'];
				if($this->siteGuard_user == '') {
					$userModel = new User();
					$groupModel = new Group();
					$current_user = $userModel->get_specific_id($this->get_admin_id());
					$this->siteGuard_user = $current_user;
					$this->siteGuard_group = $groupModel->get_specific_id($current_user->prvlg_group);	
				}
				
				$userModel->set_online($current_user->id, $cur_page);
				return $current_user;
			}
		}
	}
	public function get_group() {
		return $this->siteGuard_group ? $this->siteGuard_group : false;
	}
	public function clear_user() {
		$this->siteGuard_user = '';
		$this->siteGuard_group = '';
	}
	
	public function privilege($item = '') {
		$item = escape_value($item);
		$group_privileges = $this->siteGuard_group->privileges;
		$pos = strpos($group_privileges,'-'.$item.'-'); if($pos === false) { return false; } else { return true; }
	}
	
	public function only_for($level = '') {
		$level = escape_value($level);
		if(is_numeric($level)) {
			$level = " id = {$level}"; 
		} else {
			$level = " name = '{$level}'"; 
		}
		$groupModel = new Group();
		$group = $groupModel->get_everything($level , 1);
		if($group) {
			if($this->siteGuard_user != '') {
				if($this->siteGuard_user->prvlg_group == $group[0]->id) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function group_privilege($item = '' , $group = '') {
		
		$item = escape_value($item);
		$groupModel = new Group();
		$group_query = $groupModel->get_specific_id($group);
		$group_privileges = $group_query->privileges;
		
		$pos = strpos($group_privileges,'-'.$item.'-'); if($pos === false) { return false; } else { return true; }
	}
	
	public function page_access($page = '', $callback = 'index.php') {
		
		$page = escape_value($page);
		$group_privileges = $this->siteGuard_group->privileges;
		
		$pos = strpos($group_privileges,'-'.$page.'.read-'); if($pos === false) {
			$msg =  siteguard_msg('restricted_page');
			redirect_to(base_url("index")."?edit=fail&msg={$msg}"); exit();
		} else { return true; }
	}
	
	
	public function send_mail_to($receiver, $receiver_name, $msg, $title, $link) {
	
		$email = \Config\Services::email();
		
		$fromemail = "no-reply@".str_replace('www.', '', $_SERVER['HTTP_HOST']);
		
		$logo_link = base_url().'/SiteGuard/upl_files';
		
		$body = file_get_contents(FCPATH.'/SiteGuard/includes/template1.html');
		$body = str_replace('[logo_link]', $logo_link, $body);
		$body = str_replace('[msg_header]', $title, $body);
		$body = str_replace('[msg_body]', $msg, $body);
		if(is_array($link) && !empty($link) ) {
			$body = str_replace('[btn_text]', $link['text'], $body);
			$body = str_replace('[btn_link]', $link['link'] , $body);
		} else {
			$body = str_replace('[btn_text]', 'View Site', $body);
			$body = str_replace('[btn_link]', base_url() , $body);
		}
		$config=array(
			'charset'=>'utf-8',
			'wordwrap'=> TRUE,
			'mailType' => 'html'
		);
		
		
		if(isset($this->settings['smtp']) && $this->settings['smtp'] == 'on' ) {
			
			$config['protocol'] = 'smtp';
			$config['SMTPHost'] = urldecode($this->settings['smtphost']);
			$config['SMTPUser'] = $this->settings['smtpusername'];
			$config['SMTPPass'] = $this->settings['smtppassword'];
			$config['SMTPPort'] = $this->settings['smtpport'];
			$config['SMTPCrypto'] = $this->settings['smtpsecure'];
		}
		
		
		$email->initialize($config);

		$email->setTo($receiver);
		$email->setFrom($fromemail, $this->settings['site_name']);
		$email->setSubject($title);
		$email->setMessage($body);
		$email->setAltMessage($msg);
		return $email->send();
		
	}
	
	/**
	 * SiteGuard Session Functions
	 */
	 
	 public function is_logged_in() {
		if($this->session->has('login_confirm')) {
			return $this->session->get('login_confirm');
		} else {
			return false;
		}
	}

	public function get_admin_id() {
		if($this->session->has('admin_id')) {
			return $this->session->get('admin_id');
		} else {
			return false;
		}
	}

	public function login($user) {
		if($user){
			$data = array(
				't6VtjgJj5fe9hrKrynhFwVgH8Unm2Ka'  => $user->id,
				'admin_id' => $user->id,
				'login_confirm' => true,
			);
			$this->session->set($data);
		}
	}

	public function impersonate($user) {
		
		if($user){
			$data = array(
				't6VtjgJj5fe9hrKrynhFwVgH8Unm2Ka'  => $user->id,
				'anByj6e9cYFnrNGVqs4FEAD8Yt6xm5RP'  => $this->session->get('admin_id'),
				'impersonate_id' => $this->session->get('admin_id'),
				'admin_id' => $user->id,
				'login_confirm' => true,
			);
			$this->session->set($data);
		}
		
	}

	public function deimpersonate() {
		
		$data = array(
				't6VtjgJj5fe9hrKrynhFwVgH8Unm2Ka'  => $this->session->get('impersonate_id'),
				'admin_id' => $this->session->get('impersonate_id'),
				'login_confirm' => true,
			);
		$this->session->remove('impersonate_id');
		$this->session->remove('anByj6e9cYFnrNGVqs4FEAD8Yt6xm5RP');
		$this->session->set($data);
		
	}

	public function logout() {
	unset($_SESSION['t6VtjgJj5fe9hrKrynhFwVgH8Unm2Ka']);
	}

	private function check_login() {
		if($this->session->has('t6VtjgJj5fe9hrKrynhFwVgH8Unm2Ka')) {
			$data = array(
				'admin_id' => $this->session->get('t6VtjgJj5fe9hrKrynhFwVgH8Unm2Ka'),
				'login_confirm' => true,
			);
		  $this->session->set($data);
		  if($this->session->has('anByj6e9cYFnrNGVqs4FEAD8Yt6xm5RP')) {
			  $data = array(
				'impersonate_id' => $this->session->get('anByj6e9cYFnrNGVqs4FEAD8Yt6xm5RP')
			);
			$this->session->set($data);
		  }
		} else {
		  $this->session->remove('login_confirm');
		  $data = array(
				'login_confirm' => false
			);
		  $this->session->set($data);
		}
	
		//CSRF Protection
		if($this->session->has(csrf_token())) {
			$this->csrf = $this->session->get(csrf_token());
		} else {
			$this->csrf = csrf_hash(); 
			$data = array( csrf_token() => $this->csrf );
			$this->session->set($data);
		}
	
	
	
	}

	public function get_impersonate() {
		if($this->session->has('impersonate_id')) {
			return $this->session->get('impersonate_id');
		} else {
			return false;
		}
	}
	
	public function encode_jwt($public_key='') {
		
		$token = array(
			"iss" => $this->settings['site_name'],
			"iat" => time(),
			"exp" => time() + 60,
			"api_key" => $public_key
		);
		$jwt = JWT::encode($token, $this->settings['api_key'], "HS256");
		return $jwt;
	}
	
	public function decode_jwt($encodedJwtHash='') {
		try {
			$decoded = JWT::decode($encodedJwtHash, $this->settings['api_key'], array('HS256'));
			if($decoded->iss == $this->settings['site_name']) {
				$arr = array('type' => 'success' , 'api_key'=> $decoded->api_key );
			} else {
				throw new \InvalidArgumentException('Invalid issuer');
			}
		} catch(\UnexpectedValueException | \SignatureInvalidException | \BeforeValidException | \ExpiredException | \InvalidArgumentException | \UnexpectedValueException | \DomainException $e) {
			$arr = array('type' => 'error' , 'api_key'=> $e->getMessage() );
		}
		return json_encode($arr);
	}
	
}
?>