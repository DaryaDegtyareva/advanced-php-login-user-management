<?php namespace App\Models;

use CodeIgniter\Model;
use App\Models\Online;
use App\Models\SiteGuardFile;

class Online extends Model {
	
	protected $table = 'online_users';
	public $allowedFields = array( "user_id" , "session" , "time" , "ip" , "country" , "city" , "address" , "current_page");
	protected $primaryKey = 'id';
	protected $returnType = '\App\Entities\User';
	
	public function get_specific_id($id='') {
		 $query = $this->getWhere(array('id' => $id))->getFirstRow();
		 if($query) {
			 return $query;	 
		 } else {
			 return false;
		 }
	}
	public function get_everything($query='' , $order='', $limit = 0) {
		 if($order) {
			 $this->orderBy($order);
		 }
		 if($limit) {
			 $this->limit($limit);
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
	public function createObj($data) {
		if(!empty($data)) {
			$this->insert($data);
			$insert_id = $this->db->insertID();
			return  $insert_id;
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
	public function update_session($session, $cur_page) {
		$data = array( "time" => time(),
							"current_page" => $cur_page);
		$query = $this->set($data)->where('session',$session)->update();
		if($this->db->affectedRows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	
	public function delete_session($time) {
		$query = $this->where('time <', $time)->delete();
		if($this->db->affectedRows() > 0) {
			return true;
		} else {
			return false;
		}
	}
}