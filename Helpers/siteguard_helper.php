<?php 

function getRealIpAddr() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) { //check ip from share internet
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  { //to check ip is pass from proxy
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
 	} else {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function now_db() {
  $unixdatetime = time();
  return strftime("%Y-%m-%d %H:%M:%S", $unixdatetime);
}

function escape_value($value='') {
	$value = strip_tags(htmlentities($value));
	return filter_var($value, FILTER_SANITIZE_STRING);
}
function escape_only($value='') {
	$value = strip_tags(htmlentities($value), '<b><i><u><p><a><img>');
	return filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
}

function redirect_to( $location = NULL ) {
  if ($location != NULL) {
    header("Location: {$location}");
    exit;
  }
}
function siteGuard_msg($key ='') {
	$msgs = array('restricted_page' => "Restricted access! You are not authorized to view this page." ,
	'restricted_privilege' => "Error! You are not authorized to do this action.",
	'timestamp-fail' => "Error! Invalid timestamp, please try again.",
	'submit-success' => "Success! Data submitted successfully.",
	'submit-fail' => "Unable to save data, No changes found. please try again.",
	'update-success' => "Success! Data updated successfully.",
	'update-fail' => "Unable to update data, No changes found. please try again.",
	'ban-success' => "Success! User banned successfully.",
	'activation-success' => "Success! User account activated successfully.",
	'delete-success' => "Success! Data deleted successfully.",
	'delete-fail' => "Unable to delete data, please try again.",
	'delete-alert' => "Are you want to delete this record?",
	'email-exists' => "Email already exists in database! please try again",
	'username-exists' => "Username already exists in database! please try again",
	'user-not_found' => "Unable to update data, User not found! please try again",
	'upload-failed' => "Cannot upload profile picture, please try again",
	'revoke-alert' => "Are you want to end this session?",
	'revoke-success' => "Session ended successfully.",
	'impersonate-alert' => "Are you sure you want to impersonate this user?",
	'ban-alert' => "Are you sure you want to ban this user?",
	'activation-alert' => "Are you sure you want to activate this user account?",
	'clear_usage-alert' => "Are you sure you want to clear usage reports?",
	'undefined' => "Unknown error happened, please try again",
	);
	
   if(key_exists($key, $msgs)) {
	   return $msgs[$key];
   } else {
	   return $msgs['undefined'];
   }
}

function greeting() {
	$time = strftime("%H:%M" , time());
	if($time >= "00:00" && $time < "11:59" ) {
		return "Good morning";
	} elseif($time >= "12:00" && $time < "17:59") {
		return "Good afternoon";
	} else {
		return "Good evening";
	}
}

function date_descriptive($datetime="") {
  $unixdatetime = strtotime($datetime);
  return strftime("%B %d, %Y", $unixdatetime);
}

function full_date_descriptive($datetime="") {
  $unixdatetime = strtotime($datetime);
  return strftime("%B %d, %Y %I:%M %p", $unixdatetime);
}

function date_ago($date) {
	$arr = calc_difference( strftime("%Y-%m-%d %H:%M:%S" , time()) , $date);
	$str = '';
	if($arr['years']) {
		$str .= $arr['years'] . " Year";
		if($arr['years'] > 1) { $str.= "s"; } $str .= ",";
	}
	if($arr['months']) {
		$str .= $arr['months'] . " Month";
		if($arr['months'] > 1) { $str.= "s"; } $str .= ",";
	}
	if($arr['days']) {
		$str .= $arr['days'] . " Day";
		if($arr['days'] > 1) { $str.= "s"; } $str .= ",";
	}
	if($arr['hours']) {
		$str .= $arr['hours'] . " Hour";
		if($arr['hours'] > 1) { $str.= "s"; }
	} elseif($arr['minuts']) {
		$str .= $arr['minuts'] . " Minute";
		if($arr['minuts'] > 1) { $str.= "s"; }
	}
	
	if($str) {
		$str = $str . " ago";
	} else {
		$str = 'Right Now';
	}
	
	return $str;
}
function calc_difference($newer_str, $older_str) {
	$older = new DateTime($older_str);
	$newer = new DateTime($newer_str);

  $Y1 = $older->format('Y'); 
  $Y2 = $newer->format('Y'); 
  $Y = $Y2 - $Y1; 

  $m1 = $older->format('m'); 
  $m2 = $newer->format('m'); 
  $m = $m2 - $m1; 

  $d1 = $older->format('d'); 
  $d2 = $newer->format('d'); 
  $d = $d2 - $d1; 

  $H1 = $older->format('H'); 
  $H2 = $newer->format('H'); 
  $H = $H2 - $H1; 

  $i1 = $older->format('i'); 
  $i2 = $newer->format('i'); 
  $i = $i2 - $i1; 

  $s1 = $older->format('s'); 
  $s2 = $newer->format('s'); 
  $s = $s2 - $s1; 

  if($s < 0) { 
    $i = $i -1; 
    $s = $s + 60; 
  } 
  if($i < 0) { 
    $H = $H - 1; 
    $i = $i + 60; 
  } 
  if($H < 0) { 
    $d = $d - 1; 
    $H = $H + 24; 
  } 
  if($d < 0) { 
    $m = $m - 1; 
    $d = $d + get_days_for_previous_month($m2, $Y2); 
  } 
  if($m < 0) { 
    $Y = $Y - 1; 
    $m = $m + 12; 
  } 
  $timespan_string = create_timespan_string($Y, $m, $d, $H, $i, $s); 
  return $timespan_string; 
} 

function mjencode ($plainText, $cryptKey) {
  $length   = 8;
  $cstrong  = true;
  $cipher   = 'aes-128-cbc';
  if (in_array($cipher, openssl_get_cipher_methods()))  {
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt(
      $plainText, $cipher, $cryptKey, $options=OPENSSL_RAW_DATA, $iv);
    $hmac = hash_hmac('sha256', $ciphertext_raw, $cryptKey, $as_binary=true);
    $encodedText = base64_encode( $iv.$hmac.$ciphertext_raw );
  }
  return $encodedText;
}

function mjdecode ($encodedText, $cryptKey) {
  $c = base64_decode($encodedText);
  $cipher   = 'aes-128-cbc';
  if (in_array($cipher, openssl_get_cipher_methods()))  {
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len=32);
    $ivlenSha2len = $ivlen+$sha2len;
    $ciphertext_raw = substr($c, $ivlen+$sha2len);
    try {
		$plainText = openssl_decrypt(
			$ciphertext_raw, $cipher, $cryptKey, $options=OPENSSL_RAW_DATA, $iv);
	} catch(Exception $e) {
		$plainText = NULL;
	}
  }
  return $plainText;
}


function get_days_for_previous_month($current_month, $current_year) { 
  $previous_month = $current_month - 1; 
  if($current_month == 1) { 
    $current_year = $current_year - 1; //going from January to previous December 
    $previous_month = 12; 
  } 
  if($previous_month == 11 || $previous_month == 9 || $previous_month == 6 || $previous_month == 4) { 
    return 30; 
  } 
  else if($previous_month == 2) { 
    if(($current_year % 4) == 0) { //remainder 0 for leap years 
      return 29; 
    } else { 
      return 28; 
    } 
  } else { 
    return 31; 
  } 
} 
function create_timespan_string($Y, $m, $d, $H, $i, $s) { 
  $timespan_string = array(); 
  //$found_first_diff = false; 
  $found_first_diff = true; 
  if($Y >= 1) {
    $found_first_diff = true; 
    $timespan_string['years']= $Y; 
  } else {
	$timespan_string['years']= 0; 
  }
  if($m >= 1 || $found_first_diff) { 
    $found_first_diff = true; 
    $timespan_string['months']= $m; 
  } 
  if($d >= 1 || $found_first_diff) { 
    $found_first_diff = true; 
    $timespan_string['days'] = $d; 
  } 
  if($H >= 1 || $found_first_diff) { 
    $found_first_diff = true; 
    $timespan_string['hours'] = $H; 
  } 
  if($i >= 1 || $found_first_diff) { 
    $found_first_diff = true; 
    $timespan_string['minuts'] = $i; 
  } 
  
  $timespan_string['seconds']= $s;
  return $timespan_string; 
} 

function secondsToTime($seconds) {
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    $dtF->diff($dtT)->format('%a days, %h hours, %i minutes, %s seconds');
	$return = '';
	if($dtF->diff($dtT)->format('%a') > 0 ) {
		$return .= $dtF->diff($dtT)->format('%a Days, ');
	}
	if($dtF->diff($dtT)->format('%h') > 0 ) {
		$return .= $dtF->diff($dtT)->format('%h Hours, ');
	}
	if($dtF->diff($dtT)->format('%i') > 0 ) {
		$return .= $dtF->diff($dtT)->format('%i Minutes, ');
	}
	
	if($dtF->diff($dtT)->format('%s') > 0 ) {
		$return .= $dtF->diff($dtT)->format('%s Seconds');
	}
	
	return $return;
	
}

function getAuthorizationHeader(){
	$headers = null;
	if (isset($_SERVER['Authorization'])) {
		$headers = trim($_SERVER["Authorization"]);
	}
	else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
		$headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
	} elseif (function_exists('apache_request_headers')) {
		$requestHeaders = apache_request_headers();
		$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
		if (isset($requestHeaders['Authorization'])) {
			$headers = trim($requestHeaders['Authorization']);
		}
	}
	return $headers;
}

function getBearerToken() {
    $headers = getAuthorizationHeader();
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
}