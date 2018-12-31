<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

/*
 * Copyright (c) 2012-2013 CODEC2I.NET
 * 对非商用为目的的用户采用GPL2开源协议。您可以将其在自己的服务器部署使用，但不可以修改后发布为闭源或者商业软件。以商用为目的的用户需要购买CODEC2I的商业授权，详情请邮件sv@codec2inet。使用CODEC2I众筹系统的网站需要在页面显著位置标明基于CODEC2I构建。
 * E-mail:sv@codec2i.net
 * 官方网址:http://www.codec2i.net
 */

require_once("database.class.php"); 

function strip_zeros_from_date( $marked_string="" ) {
  $no_zeros = str_replace('*0', '', $marked_string);
  $cleaned_string = str_replace('*', '', $no_zeros);
  return $cleaned_string;
}

function redirect_to( $location = NULL ) {
  if ($location != NULL) {
    header("Location: {$location}");
    exit;
  }
}

function output_message($message="") {
  if (!empty($message)) { 
    return "<p class=\"message\">{$message}</p>";
  } else {
    return "";
  }
}

function generate_id($length = 6) {
	$id = '';
	for ($i=0;$i<$length;$i++){
		$id .= rand(1, 9);
	}
	return $id;
}

// function __autoload($class_name) {
//   $class_name = strtolower($class_name);
//   $path = "{$class_name}.class.php";
//   if(file_exists($path)) {
//     require_once($path);
//   } else {
// 		die("The file {$class_name}.class.php could not be found.");
// 	}
// }

function convert_boolean($boolean) {
	if($boolean == 0) {
		$end = "<span class=\"label label-warning\">否</span>";
	} else if($boolean == 1) {
		$end = "<span class=\"label label-info\">是</span>";
	}
	return $end;
}

function convert_boo($boolean) {
	if($boolean == 0) {
		$end = "否";
	} else if($boolean == 1) {
		$end = "是";
	}
	return $end;
}

function convert_boolean_sus($boolean) {
	if($boolean == 0) {
		$end = "<span class=\"label label-info\">否</span>";
	} else if($boolean == 1) {
		$end = "<span class=\"label label-important\">是</span>";
	}
	return $end;
}

function convert_boolean_full($boolean) {
	if($boolean == 0) {
		$end = "无效的";
	} else if($boolean == 1) {
		$end = "激活的";
	}
	return $end;
}

function convert_token_status($enum) {
	if($enum == 'c') {
		$end = "Credited";
	} else if($enum == 'd') {
		$end = "Debited";
	}
	return $end;
}

function convert_user_level($level) {
	global $database;
	
	$sql = "SELECT * FROM user_levels WHERE level_id = '{$level}'";
	$query = $database->query($sql);
	$row = $database->fetch_array($query);
	return $row['level_name'];
}

function datetime_to_text($datetime="") {
  $unixdatetime = strtotime($datetime);
  return date("Y年m月d日 H:i:s", $unixdatetime);
}

function date_to_text($date="") {
  $unixdatetime = strtotime($date);
  return strftime("%d %B %Y", $unixdatetime);
}

function date_text_month($date="") {
  $unixdatetime = strtotime($date);
  return strftime("%B", $unixdatetime);
}

function protect($users_level, $group_id, $redirect="index.php") {
	$user_levels = explode(",", $users_level);
	$groups = explode(",", $group_id);
	$flag = false;
	foreach($user_levels as $level){
		if(in_array($level, $groups)) {
			$flag = true;
			break;
		}
	}
	if($flag == false){
		redirect_to($redirect);
	}
}

// Encrypt Password	
function encrypt_password($password){
	$iterations = 10;
	$salt = DATABASE_SALT;
	$hash = crypt($password,$salt);
	for ($i = 0; $i < $iterations; ++$i){
	    $hash = crypt($hash.$password,$salt);
	}
	return $hash;
}

function login_check(){
	$session = new Session();
	if(!$session->is_logged_in()) {redirect_to("../login.php");}
}

// function check_user_access($user_id){
// 	$user = User::find_by_id($user_id);
// 	$user_access = User::get_user_levels($user_id);
// 	if(!empty($user_access)){	
// 		foreach($user_access as $access){
// 			if($access->expires == 1){
// 				if(strtotime($access->expiry_date) < strtotime(date('Y-m-d h:i:s', time()))){
// 					User::downgrade_access($access->id, $user->user_id, $access->level_id, $user->user_level);
// 				}
// 			}
// 		}
// 	}
// }

function formatOffset($offset) {
        $hours = $offset / 3600;
        $remainder = $offset % 3600;
        $sign = $hours > 0 ? '+' : '-';
        $hour = (int) abs($hours);
        $minutes = (int) abs($remainder / 60);

        if ($hour == 0 AND $minutes == 0) {
            $sign = ' ';
        }
        return $sign . str_pad($hour, 2, '0', STR_PAD_LEFT) .':'. str_pad($minutes,2, '0');

}

function preprint($data){
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}

function clean_value($value){
	global $database;
  static $php525;
  if (!isset($php525)) {
    $php525 = version_compare(PHP_VERSION, '5.2.5', '>=');
  }
  $text = $database->escape_value(trim($value));
  if ($php525) {
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
  }
  return (preg_match('/^./us', $text) == 1) ? htmlspecialchars($text, ENT_QUOTES, 'UTF-8') : '';
}

function check_plain($text) {
  static $php525;

  if (!isset($php525)) {
    $php525 = version_compare(PHP_VERSION, '5.2.5', '>=');
  }
  // We duplicate the preg_match() to validate strings as UTF-8 from
  // drupal_validate_utf8() here. This avoids the overhead of an additional
  // function call, since check_plain() may be called hundreds of times during
  // a request. For PHP 5.2.5+, this check for valid UTF-8 should be handled
  // internally by PHP in htmlspecialchars().
  // @see http://www.php.net/releases/5_2_5.php
  // @todo remove this when support for either IE6 or PHP < 5.2.5 is dropped.

  if ($php525) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
  }
  return (preg_match('/^./us', $text) == 1) ? htmlspecialchars($text, ENT_QUOTES, 'UTF-8') : '';
}

$timezones = array (
  '(GMT-12:00) International Date Line West' => 'Pacific/Wake',
  '(GMT-11:00) Midway Island' => 'Pacific/Apia',
  '(GMT-11:00) Samoa' => 'Pacific/Apia',
  '(GMT-10:00) Hawaii' => 'Pacific/Honolulu',
  '(GMT-09:00) Alaska' => 'America/Anchorage',
  '(GMT-08:00) Pacific Time (US &amp; Canada); Tijuana' => 'America/Los_Angeles',
  '(GMT-07:00) Arizona' => 'America/Phoenix',
  '(GMT-07:00) Chihuahua' => 'America/Chihuahua',
  '(GMT-07:00) La Paz' => 'America/Chihuahua',
  '(GMT-07:00) Mazatlan' => 'America/Chihuahua',
  '(GMT-07:00) Mountain Time (US &amp; Canada)' => 'America/Denver',
  '(GMT-06:00) Central America' => 'America/Managua',
  '(GMT-06:00) Central Time (US &amp; Canada)' => 'America/Chicago',
  '(GMT-06:00) Guadalajara' => 'America/Mexico_City',
  '(GMT-06:00) Mexico City' => 'America/Mexico_City',
  '(GMT-06:00) Monterrey' => 'America/Mexico_City',
  '(GMT-06:00) Saskatchewan' => 'America/Regina',
  '(GMT-05:00) Bogota' => 'America/Bogota',
  '(GMT-05:00) Eastern Time (US &amp; Canada)' => 'America/New_York',
  '(GMT-05:00) Indiana (East)' => 'America/Indiana/Indianapolis',
  '(GMT-05:00) Lima' => 'America/Bogota',
  '(GMT-05:00) Quito' => 'America/Bogota',
  '(GMT-04:00) Atlantic Time (Canada)' => 'America/Halifax',
  '(GMT-04:00) Caracas' => 'America/Caracas',
  '(GMT-04:00) La Paz' => 'America/Caracas',
  '(GMT-04:00) Santiago' => 'America/Santiago',
  '(GMT-03:30) Newfoundland' => 'America/St_Johns',
  '(GMT-03:00) Brasilia' => 'America/Sao_Paulo',
  '(GMT-03:00) Buenos Aires' => 'America/Argentina/Buenos_Aires',
  '(GMT-03:00) Georgetown' => 'America/Argentina/Buenos_Aires',
  '(GMT-03:00) Greenland' => 'America/Godthab',
  '(GMT-02:00) Mid-Atlantic' => 'America/Noronha',
  '(GMT-01:00) Azores' => 'Atlantic/Azores',
  '(GMT-01:00) Cape Verde Is.' => 'Atlantic/Cape_Verde',
  '(GMT) Casablanca' => 'Africa/Casablanca',
  '(GMT) Edinburgh' => 'Europe/London',
  '(GMT) Greenwich Mean Time : Dublin' => 'Europe/London',
  '(GMT) Lisbon' => 'Europe/London',
  '(GMT) London' => 'Europe/London',
  '(GMT) Monrovia' => 'Africa/Casablanca',
  '(GMT+01:00) Amsterdam' => 'Europe/Berlin',
  '(GMT+01:00) Belgrade' => 'Europe/Belgrade',
  '(GMT+01:00) Berlin' => 'Europe/Berlin',
  '(GMT+01:00) Bern' => 'Europe/Berlin',
  '(GMT+01:00) Bratislava' => 'Europe/Belgrade',
  '(GMT+01:00) Brussels' => 'Europe/Paris',
  '(GMT+01:00) Budapest' => 'Europe/Belgrade',
  '(GMT+01:00) Copenhagen' => 'Europe/Paris',
  '(GMT+01:00) Ljubljana' => 'Europe/Belgrade',
  '(GMT+01:00) Madrid' => 'Europe/Paris',
  '(GMT+01:00) Paris' => 'Europe/Paris',
  '(GMT+01:00) Prague' => 'Europe/Belgrade',
  '(GMT+01:00) Rome' => 'Europe/Berlin',
  '(GMT+01:00) Sarajevo' => 'Europe/Sarajevo',
  '(GMT+01:00) Skopje' => 'Europe/Sarajevo',
  '(GMT+01:00) Stockholm' => 'Europe/Berlin',
  '(GMT+01:00) Vienna' => 'Europe/Berlin',
  '(GMT+01:00) Warsaw' => 'Europe/Sarajevo',
  '(GMT+01:00) West Central Africa' => 'Africa/Lagos',
  '(GMT+01:00) Zagreb' => 'Europe/Sarajevo',
  '(GMT+02:00) Athens' => 'Europe/Istanbul',
  '(GMT+02:00) Bucharest' => 'Europe/Bucharest',
  '(GMT+02:00) Cairo' => 'Africa/Cairo',
  '(GMT+02:00) Harare' => 'Africa/Johannesburg',
  '(GMT+02:00) Helsinki' => 'Europe/Helsinki',
  '(GMT+02:00) Istanbul' => 'Europe/Istanbul',
  '(GMT+02:00) Jerusalem' => 'Asia/Jerusalem',
  '(GMT+02:00) Kyiv' => 'Europe/Helsinki',
  '(GMT+02:00) Minsk' => 'Europe/Istanbul',
  '(GMT+02:00) Pretoria' => 'Africa/Johannesburg',
  '(GMT+02:00) Riga' => 'Europe/Helsinki',
  '(GMT+02:00) Sofia' => 'Europe/Helsinki',
  '(GMT+02:00) Tallinn' => 'Europe/Helsinki',
  '(GMT+02:00) Vilnius' => 'Europe/Helsinki',
  '(GMT+03:00) Baghdad' => 'Asia/Baghdad',
  '(GMT+03:00) Kuwait' => 'Asia/Riyadh',
  '(GMT+03:00) Moscow' => 'Europe/Moscow',
  '(GMT+03:00) Nairobi' => 'Africa/Nairobi',
  '(GMT+03:00) Riyadh' => 'Asia/Riyadh',
  '(GMT+03:00) St. Petersburg' => 'Europe/Moscow',
  '(GMT+03:00) Volgograd' => 'Europe/Moscow',
  '(GMT+03:30) Tehran' => 'Asia/Tehran',
  '(GMT+04:00) Abu Dhabi' => 'Asia/Muscat',
  '(GMT+04:00) Baku' => 'Asia/Tbilisi',
  '(GMT+04:00) Muscat' => 'Asia/Muscat',
  '(GMT+04:00) Tbilisi' => 'Asia/Tbilisi',
  '(GMT+04:00) Yerevan' => 'Asia/Tbilisi',
  '(GMT+04:30) Kabul' => 'Asia/Kabul',
  '(GMT+05:00) Ekaterinburg' => 'Asia/Yekaterinburg',
  '(GMT+05:00) Islamabad' => 'Asia/Karachi',
  '(GMT+05:00) Karachi' => 'Asia/Karachi',
  '(GMT+05:00) Tashkent' => 'Asia/Karachi',
  '(GMT+05:30) Chennai' => 'Asia/Calcutta',
  '(GMT+05:30) Kolkata' => 'Asia/Calcutta',
  '(GMT+05:30) Mumbai' => 'Asia/Calcutta',
  '(GMT+05:30) New Delhi' => 'Asia/Calcutta',
  '(GMT+05:45) Kathmandu' => 'Asia/Katmandu',
  '(GMT+06:00) Almaty' => 'Asia/Novosibirsk',
  '(GMT+06:00) Astana' => 'Asia/Dhaka',
  '(GMT+06:00) Dhaka' => 'Asia/Dhaka',
  '(GMT+06:00) Novosibirsk' => 'Asia/Novosibirsk',
  '(GMT+06:00) Sri Jayawardenepura' => 'Asia/Colombo',
  '(GMT+06:30) Rangoon' => 'Asia/Rangoon',
  '(GMT+07:00) Bangkok' => 'Asia/Bangkok',
  '(GMT+07:00) Hanoi' => 'Asia/Bangkok',
  '(GMT+07:00) Jakarta' => 'Asia/Bangkok',
  '(GMT+07:00) Krasnoyarsk' => 'Asia/Krasnoyarsk',
  '(GMT+08:00) Beijing' => 'Asia/Hong_Kong',
  '(GMT+08:00) Chongqing' => 'Asia/Hong_Kong',
  '(GMT+08:00) Hong Kong' => 'Asia/Hong_Kong',
  '(GMT+08:00) Irkutsk' => 'Asia/Irkutsk',
  '(GMT+08:00) Kuala Lumpur' => 'Asia/Singapore',
  '(GMT+08:00) Perth' => 'Australia/Perth',
  '(GMT+08:00) Singapore' => 'Asia/Singapore',
  '(GMT+08:00) Taipei' => 'Asia/Taipei',
  '(GMT+08:00) Ulaan Bataar' => 'Asia/Irkutsk',
  '(GMT+08:00) Urumqi' => 'Asia/Hong_Kong',
  '(GMT+09:00) Osaka' => 'Asia/Tokyo',
  '(GMT+09:00) Sapporo' => 'Asia/Tokyo',
  '(GMT+09:00) Seoul' => 'Asia/Seoul',
  '(GMT+09:00) Tokyo' => 'Asia/Tokyo',
  '(GMT+09:00) Yakutsk' => 'Asia/Yakutsk',
  '(GMT+09:30) Adelaide' => 'Australia/Adelaide',
  '(GMT+09:30) Darwin' => 'Australia/Darwin',
  '(GMT+10:00) Brisbane' => 'Australia/Brisbane',
  '(GMT+10:00) Canberra' => 'Australia/Sydney',
  '(GMT+10:00) Guam' => 'Pacific/Guam',
  '(GMT+10:00) Hobart' => 'Australia/Hobart',
  '(GMT+10:00) Melbourne' => 'Australia/Sydney',
  '(GMT+10:00) Port Moresby' => 'Pacific/Guam',
  '(GMT+10:00) Sydney' => 'Australia/Sydney',
  '(GMT+10:00) Vladivostok' => 'Asia/Vladivostok',
  '(GMT+11:00) Magadan' => 'Asia/Magadan',
  '(GMT+11:00) New Caledonia' => 'Asia/Magadan',
  '(GMT+11:00) Solomon Is.' => 'Asia/Magadan',
  '(GMT+12:00) Auckland' => 'Pacific/Auckland',
  '(GMT+12:00) Fiji' => 'Pacific/Fiji',
  '(GMT+12:00) Kamchatka' => 'Pacific/Fiji',
  '(GMT+12:00) Marshall Is.' => 'Pacific/Fiji',
  '(GMT+12:00) Wellington' => 'Pacific/Auckland',
  '(GMT+13:00) Nuku\'alofa' => 'Pacific/Tongatapu',
);

function rrmdir($dir) {
  if (is_dir($dir)) {
    $objects = scandir($dir);
    foreach ($objects as $object) {
      if ($object != "." && $object != "..") {
        if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
      }
    }
    reset($objects);
    rmdir($dir);
  }
}
