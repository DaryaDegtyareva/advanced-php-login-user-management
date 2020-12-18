<?php namespace App\Models;

use CodeIgniter\Model;

class SiteGuardFile extends Model {
	protected $table = 'file_upload';
	protected $allowedFields = [ "filename" , "type" , "size" , "title" ];
	protected $db;
	
	public function __construct() {
		$this->db = db_connect();
    }
	
	public function image_path($id='') {
		$query = $this->getWhere(array('id' => $id))->getFirstRow();
		if($query) {
			return 'SiteGuard/upl_files/'.$query->filename;
		} else {
			return false;
		}
	}
	
	private $temp_path;
	protected $upload_dir="SiteGuard/upl_files";
	public $errors = array();
	protected $upload_errors = array(
	
	UPLOAD_ERR_OK 					 => "No errors.",
	UPLOAD_ERR_INI_SIZE  		 => "Larger than upload_max_filesize.",
	UPLOAD_ERR_FORM_SIZE 	 => "Larger than form MAX_FILE_SIZE.",
	UPLOAD_ERR_PARTIAL 		 => "Partial upload.",
	UPLOAD_ERR_NO_FILE 			 => "No file.",
	UPLOAD_ERR_NO_TMP_DIR	 => "No temporary directory.",
	UPLOAD_ERR_CANT_WRITE	 => "Can't write to disk.",
	UPLOAD_ERR_EXTENSION		 => "File upload stopped by extension."
	);

	public function attach_file($file , $f) {
		
		$n = $f ;
		
		if(!$file || empty($file) || !is_array($file)  ) {
			$this->errors[]= "No files were chosen!";
			return false;
		}
		elseif( $file['error'][$n] != 0 ) {
			$this->errors[] = $this->upload_errors[$file['error'][$n]];
			return false;
		} else {
		
		$size = getimagesize($file['tmp_name'][$n]);
		if(!$size) {
			$this->errors[] = "Invalid file extension, System only accepts Images, please try again.";
			return false;
		}
		
		$valid_types = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP);
		if(!in_array($size[2],  $valid_types)) {
			$this->errors[] = "Invalid file extension, System only accepts Images, Text files and Archives, Given file is " . $size[2];
			return false;
		}
		
		$ext = escape_value(substr(basename($file['name'][$n]),-4));
		$var= uniqid();
		$temp = substr($var,-10).$ext;
		$invalid_chars = array('-','/','+','=','*',';',',','@','~','!','#','$','%','^','&','(',')','|');
		$temp_name = str_replace($invalid_chars,'',$temp);
		$this->temp_path = $file['tmp_name'][$n];
		$this->filename =  $temp_name;
		$this->type = $file['type'][$n];
		$this->size = $file['size'][$n];
		return true;
		}
	
	}
	public function ajax_attach_file($file) {
		
		
		if(!$file || empty($file) || !is_array($file)  ) {
			$this->errors[]= "No files were chosen!";
			return false;
		}
		elseif( $file['error'] != 0 ) {
			$this->errors[] = $this->upload_errors[$file['error']];
			return false;
		}
		 else {
		
		$size = getimagesize($file['tmp_name']);
		if(!$size) {
			$this->errors[] = "Invalid file extension, System only accepts Images, please try again.";
			return false;
		}
		
		$valid_types = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP);
		if(!in_array($size[2],  $valid_types)) {
			$this->errors[] = "Invalid file extension, System only accepts Images, Given file is: " . $size[2];
			return false;
		}
		
		$ext = escape_value(substr(basename($file['name']),-4));
		$var= uniqid();
		$temp = substr($var,-10).$ext;
		$invalid_chars = array('-','/','+','=','*',';',',','@','~','!','#','$','%','^','&','(',')','|');
		$temp_name = str_replace($invalid_chars,'',$temp);
		$this->temp_path = $file['tmp_name'];
		$this->filename =  $temp_name;
		$this->type = $file['type'];
		$this->size = $file['size'];
		return true;
		}
	
	}
	public function filepath() {
		return $this->upload_dir."/".$this->filename;
	}
	public function save_file($crop = false) {
	
			//check for errors first ..
			if(!empty($this->errors)) {
				return false;
			}
			
			$target_path = FCPATH .DIRECTORY_SEPARATOR. $this->upload_dir .DIRECTORY_SEPARATOR. $this->filename;
			if(file_exists($target_path)){
				$this->errors[] = "The file {$this->filename} already exists.";
				return false;				
			}
			
			if($this->type == 'image/jpeg') {
				$img = imagecreatefromjpeg ($this->temp_path);
				if($crop) {
					$img = imagecrop($img, $crop);
				}
				$test = imagejpeg ($img, $target_path, 100);
			} elseif($this->type == 'image/png') {
				
				$img = imagecreatefrompng ($this->temp_path);
				$origSize = getimagesize($this->temp_path);
				
				$background = imagecolorallocate($img , 0, 0, 0);
				imagecolortransparent($img, $background);
				imagealphablending($img, false);
				imagesavealpha($img, true);
     
				if($crop) {
					if($crop['x'] < 0) {
						if(($crop['width'] += $crop['x']) > $origSize[0] ) {
							$crop['width'] = $origSize[0];
						}
						$crop['x'] = 0;
					}if($crop['y'] < 0) {
						if(($crop['height'] += $crop['y']) > $origSize[1] ) {
							$crop['height'] = $origSize[1];
						}
						$crop['y'] = 0;
					}
					$img = imagecrop($img, $crop);
				}
				
				$test = imagepng ($img, $target_path);
				
			} elseif($this->type == 'image/gif') {
				$img = imagecreatefromgif ($this->temp_path);
				if($crop) {
					$img = imagecrop($img, $crop);
				}
				$test = imagegif ($img, $target_path);
			} elseif($this->type == 'image/bmp') {
				$img = imagecreatefromwbmp ($this->temp_path);
				if($crop) {
					$img = imagecrop($img, $crop);
				}
				$test = imagewbmp ($img, $target_path);
			} else {
				$this->errors[] = "The file upload failed, Cannot read image details";
				return false;
			}
			//if (move_uploaded_file($this->temp_path , $target_path)) {
			if($test) {
				imagedestroy ($img);
				
				$data = array("filename" => $this->filename,
							"type" =>$this->type,
							"size" => $this->size
							);
				
				if ($this->create($data)) {
					unset($temp_path);
					return $this->db->insertID();
				}
			} else {
				$this->errors[] = "The file upload failed, possibly due to incorrect permissions on the upload folder.";
				return false;						
			}	
		}

	public function size_as_text() {
		if ($this->size < 1024 ) {
			$size_bytes = $this->size . " Bytes";
			return $size_bytes;
		} elseif ($this->size < 1048576 ) {
			$size_kb = round($this->size / 1024) . " KBs";
			return $size_kb;
		} else {
			$size_mb = round($this->size / 1048576 , 1 ) . " MBs";
			return $size_mb;			
			
		}
	}

	public function destroy($file_id) {
			$file = $this->get_specific_id($file_id);
			if($this->delete()) {
				// remove the file ..
				$target_path = FCPATH.DIRECTORY_SEPARATOR.$this->filepath();
				return unlink($target_path) ? true : false;
			} else {
				return false;
			}		
	}
	
	
	public function get_specific_id($id='') {
		 $query = $this->getWhere(array('id' => $id))->getFirstRow();
		 if($query) {
			 return $query;
		 } else {
			 return false;
		 }
	}
	public function create($data) {
		if($this->insert($data)) { return true; } else { return false; }
	}
	
	
	
}