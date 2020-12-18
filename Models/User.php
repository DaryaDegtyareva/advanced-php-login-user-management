<?php namespace App\Models;

use CodeIgniter\Model;
use \App\Models\Online;
use \App\Models\SiteGuardFile;

class User extends Model {
    
	protected $table = 'users';
	protected $primaryKey = 'id';
	protected $returnType = 'App\Entities\User';
	protected $allowedFields = array( "name" , "prvlg_group", "username", "password", "reset_hash",  "email", "mobile" , "address", "about" , "closed", "disabled","deleted" , 'registered' ,"last_seen",  "avatar" , "tfa" , "tfa_codes", "tfa_secret" , "invalid_logins" ,"api_key", "pending" , "hybridauth_provider_name" , "hybridauth_provider_uid" , "throttle_from" , "throttle_time" );

	public function get_specific_id($id='') {
		 $query = $this->getWhere(array('id' => $id))->getFirstRow();
		 if($query) {
			 return $query;	 
		 } else {
			 return false;
		 }
	}
	public function get_everything($query='' , $order='', $limit = 0, $offset = 0) {
		 if($order) {
			 $this->orderBy($order);
		 }
		 if($limit) {
			 $this->limit($limit);
		 }
		 if($offset) {
			 $this->offset($offset);
		 }
		 $query = $this->getWhere($query)->getResult();
		 if($query) {
			 return $query;
		 } else {
			 return false;
		 }
	}
	public function exists($col='',$value='') {
		$query = $this->select('id')->where($col,$value);
		if( $query->countAllResults() > 0 ) {
			return true;
		} else {
			return false;
		}
	}
	public function exists_except($col='',$value='', $id= '') {
		$query = $this->select('id')->where($col,$value)->where('id !=',$id);
		if( $query->countAllResults() > 0 ) {
			return true;
		} else {
			return false;
		}
	}
	
	public function hash_authenticate($username='') {
		 $query = $this->getWhere(array('username' => $username))->getFirstRow();
		 if($query) {
			 return $query;
		 } else {
			 return false;
		 }
	}

	
	public function count_everything($query='') {
		 $query = $this->where($query);
		 if($query) {
			 return $query->countAllResults();
		 } else {
			 return false;
		 }
	}
	
	
	public function set_online($user_id, $cur_page) {
		
		 $data = array('last_seen' => time());
		 $this->update($user_id, $data);
		 
			$session = session_id();
			$time = time();
			$time_check = $time - 300;     //We Have Set Time 5 Minutes
			
			//Current Session
			$onlineModel = new Online();
			
			$currently_online = $onlineModel->count_everything(" session = '{$session}' ");
			//If count is 0 , then enter the values
			if($currently_online == "0") {
				$new = new \App\Entities\Online();
				$new->session = $session;
				$new->user_id = $user_id;
				$new->time = $time;
				$new->ip = getRealIpAddr();
				$new->current_page = $cur_page;
							
				$onlineModel->save($new);
			} else {
				$update_session = $onlineModel->update_session($session, $cur_page);
			}
			
			// after 5 minutes, session will be deleted 
			$delete_session = $onlineModel->delete_session($time_check);
		 
	}
	
	public function check_online($user_id) {
		$online_diff = strtotime("-5 Minutes" , time());
		$user = $this->get_specific_id($user_id);
		if($user->last_seen >= $online_diff) {
			return true;
		} else {
			return false;
		}
	}
	
	public function get_avatar($user_id) {
		$user = $this->get_specific_id($user_id);
		if($user->avatar) {
			$file = new SiteGuardFile();
			$img = $file->image_path($user->avatar);
			$dev_avatar = base_url().'/'.$img;
			$dev_avatar_path = FCPATH.$img;
			if (!file_exists($dev_avatar_path)) {
				$dev_avatar = base_url().'/SiteGuard/images/avatar.jpg';
			}
		} else {
			$dev_avatar = base_url().'/SiteGuard/images/avatar.jpg';
		}
		return $dev_avatar;
	}
	
	public function clear_invalid_login($user_id) {
		$data = array ('invalid_logins' => 0,
							'throttle_from' => '',
							'throttle_time' => '');
		$this->update($user_id, $data);
	}
	
	public function get_for_hybridauth($provider="",$identifier="") {
	
		$query = $this->getWhere(array('deleted' => '0','hybridauth_provider_uid' => $identifier, 'hybridauth_provider_name' => $provider))->getFirstRow();
		 if($query) {
			 return $query;
		 } else {
			 return false;
		 }
		
	}
	
	public function invalid_login($user_id, $attempts) {
		$user = $this->get_specific_id($user_id);
		$user->invalid_logins += 1;
		$attempts = str_replace('-','',$attempts);
		if($user->invalid_logins >= $attempts) {
			$throttle_from = time();
			if($user->throttle_time) {
				$throttle_time = $user->throttle_time * 2;
			} else {
				$throttle_time = 60;
			}
			$user->throttle_from = $throttle_from;
			$user->throttle_time = $throttle_time;
			$log = new SiteGuardLog();
			$log->log_action($user->id , "Malicious Login Attempt" , "Malicious attempt to login this account, Login disabled for " . secondsToTime($throttle_time) );
		}
		
		$this->update($user_id, $user);
	}
}