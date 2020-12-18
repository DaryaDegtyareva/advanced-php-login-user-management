<?php namespace App\Models;

use CodeIgniter\Model;

class Group extends Model {
	
	protected $table = 'groups';
	protected $primaryKey = 'id';
	protected $returnType = 'App\Entities\Group';
	protected $allowedFields  = array( "name" , "privileges" , "max_connections", "default_index",  "deleted" );
	
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
}