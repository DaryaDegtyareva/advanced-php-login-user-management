<?php namespace App\Models;

use CodeIgniter\Model;

class Announcement extends Model {
	
	protected $table = 'announcements';
	public $allowedFields = array( "name", "user_id" , "type" , "message" , "visible_to" , "created_at" , "expire_after" , "seen" );
	protected $primaryKey = 'id';
	protected $returnType = '\App\Entities\Announcement';
	
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
	
	public function count_everything($query='') {
		 $query = $this->where($query);
		 if($query) {
			 return $query->countAllResults();
		 } else {
			 return false;
		 }
	}
	
	public function isValid($ann_id) {
		$ann = $this->get_specific_id($ann_id);
		if($ann->expire_after != 'never') {
			$expire = strtotime($ann->expire_after, strtotime($ann->created_at));
			if(time() <= $expire) {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}
	
	public function seen($ann_id) {
		$ann = $this->get_specific_id($ann_id);
		$data = array("seen" => $ann->seen + 1);
		$this->update($ann_id, $data);
	}
	
}