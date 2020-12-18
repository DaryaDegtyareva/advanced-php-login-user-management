<?php namespace App\Models;

use CodeIgniter\Model;

class MiscFunction extends Model {
	protected $table = 'functions';
	protected $allowedFields  = [ "function", "value" , "msg" ];
	
	public function get_function($function = '') {
		 $query = $this->getWhere(array('function' => $function))->getFirstRow();
		 if($query) {
			 return $query;
		 } else {
			 return false;
		 }
	}
	public function settings() {
		$query = $this->getWhere(array('function' => 'general_settings'))->getFirstRow();
		if($query) {
			return unserialize($query->value);
		} else {
			return false;
		}
	}
	public function updateObj($data) {
		$this->where('id',$this->id)->update($data);
		if($this->db->affectedRows() > 0) {
			 return true;
		 } else {
			 return false;
		 }
	}
} 