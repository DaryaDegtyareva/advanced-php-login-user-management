<?php namespace App\Models;

use CodeIgniter\Model;

class SiteGuardLog extends Model {
	protected $table = 'logs';
	protected $allowedFields = ["id" , "user_id" , "action","msg","done_at" , "ip"];

	public function log_action($user_id, $action, $msg) {
		$data = array(
			'user_id'=>escape_value($user_id),
			'action'=>escape_value($action),
			'msg'=>escape_value($msg),
			'done_at'=>strftime("%Y-%m-%d %H:%M:%S" , time()),
			'ip'=> getRealIpAddr(),
		);

		if($this->insert($data)) {
			return true;
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
	
	public function createObj($data) {
		if(!empty($data)) {
			$this->insert($data);
			$insert_id = $this->insertID();
			return  $insert_id;
		} else {
			return false;
		}
	}
	public function updateObj($data) {
		$this->where('id',$this->id)->update($data);
		if( $this->affectedRows() > 0 ) {
			return true;
		} else {
			return false;
		}
	}
	
	public function deleteObj() {
		 $this->db->where('id', $this->id);
		 $query = $this->delete();
		 if($this->db->affectedRows() > 0) {
			 return true;
		 } else {
			 return false;
		 }
	}
	public function get_actions() {
		$this->distinct();
		$this->select('action');
		$query = $this->get()->getResult();
		if($query) {
			return $query;
		} else {
			return false;
		 }
	}
}