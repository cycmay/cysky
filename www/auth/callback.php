<?php

define('CONF_FILE', dirname(__FILE__).'/'.'opauth.conf.php');
define('OPAUTH_LIB_DIR', dirname(__FILE__).'/lib/Opauth/');

require '../includes/configuration/config.php';
require '../includes/classes/session.class.php';
require '../includes/classes/functions.class.php';

if (!file_exists(CONF_FILE)){
	trigger_error('Config file missing at '.CONF_FILE, E_USER_ERROR);
	exit();
}
require CONF_FILE;

require OPAUTH_LIB_DIR.'Opauth.php';
$Opauth = new Opauth( $config, false );

$response = null;

switch($Opauth->env['callback_transport']){	
	case 'session':
		session_start();
		$response = $_SESSION['opauth'];
		unset($_SESSION['opauth']);
		break;
	case 'post':
		$response = unserialize(base64_decode( $_POST['opauth'] ));
		break;
	case 'get':
		$response = unserialize(base64_decode( $_GET['opauth'] ));
		break;
	default:
		echo '<strong style="color: red;">Error: </strong>Unsupported callback_transport.'."<br>\n";
		break;
}

//print_r($response);
//exit;

if (array_key_exists('error', $response)){
	echo '<strong style="color: red;">Authentication error: </strong> Opauth returns error auth response.'."<br>\n";
}

else{
	if (empty($response['auth']) || empty($response['timestamp']) || empty($response['signature']) || empty($response['auth']['provider']) || empty($response['auth']['uid'])){
		echo '<strong style="color: red;">Invalid auth response: </strong>Missing key auth response components.'."<br>\n";
	}
	elseif (!$Opauth->validate(sha1(print_r($response['auth'], true)), $response['timestamp'], $response['signature'], $reason)){
		echo '<strong style="color: red;">Invalid auth response: </strong>'.$reason.".<br>\n";
	}
	else{
		if($response['auth']['provider'] == "Facebook"){
			$data = User::find_by_sql("SELECT * FROM users WHERE oauth_provider = '0' AND oauth_uid = '{$response['auth']['raw']['id']}' ");
			$session = new Session;
			if(empty($data)){
				User::create_oauth_account($response['auth']['raw']['username'], $response['auth']['raw']['email'], $response['auth']['raw']['first_name'], $response['auth']['raw']['last_name'], $response['auth']['raw']['gender'], "0", $response['auth']['raw']['id']);
				$sql  = "SELECT * FROM users WHERE oauth_provider = '0' AND oauth_uid = '{$response['auth']['raw']['id']}' LIMIT 1";
				$result_array = User::find_by_sql($sql);
				$data = !empty($result_array) ? array_shift($result_array) : false;
				if(!empty($data) ){
						$session->logout();
						$session->login($data);
				}
				redirect_to("../index.php");
			} else {
				$sql  = "SELECT * FROM users WHERE oauth_provider = '0' AND oauth_uid = '{$response['auth']['raw']['id']}' LIMIT 1";
				$result_array = User::find_by_sql($sql);
				$data = !empty($result_array) ? array_shift($result_array) : false;
				if(!empty($data) ){
						$session->logout();
						$session->login($data);
				}
				redirect_to("../index.php");
			}
		} else if($response['auth']['provider'] == "Twitter"){
			$data = User::find_by_sql("SELECT * FROM users WHERE oauth_provider = '1' AND oauth_uid = '{$response['auth']['raw']['id']}' ");
			$session = new Session;
			if(empty($data)){
				$user_names = explode(" ", $response['auth']['raw']['name']);
				User::create_oauth_account($response['auth']['raw']['screen_name'], "NONE PROVIDED", $user_names[0], $user_names[1], "Male", "1", $response['auth']['raw']['id']);
				$sql  = "SELECT * FROM users WHERE oauth_provider = '1' AND oauth_uid = '{$response['auth']['raw']['id']}' LIMIT 1";
				$result_array = User::find_by_sql($sql);
				$data = !empty($result_array) ? array_shift($result_array) : false;
				if(!empty($data) ){
						$session->logout();
						$session->login($data);
				}
				redirect_to("../index.php");
			} else {
				$sql  = "SELECT * FROM users WHERE oauth_provider = '1' AND oauth_uid = '{$response['auth']['raw']['id']}' LIMIT 1";
				$result_array = User::find_by_sql($sql);
				$data = !empty($result_array) ? array_shift($result_array) : false;
				if(!empty($data) ){
						$session->logout();
						$session->login($data);
				}
				redirect_to("../index.php");
			}
		} elseif($response['auth']['provider'] == "SinaWeibo") {
			$data = User::find_by_sql("SELECT * FROM users WHERE oauth_provider = '1' AND oauth_uid = '{$response['auth']['raw']['id']}' ");
			$session = new Session;
			if(empty($data)){
				$gender = $response['auth']['raw']['gender'] == 'm' ? 'Male' : 'Female';
				$profile = array(
				  'profile_msg' => '',
				  'about_me' => $response['auth']['raw']['description'],
				  'profile_picture' => $response['auth']['raw']['avatar_large'],
				);
				User::create_oauth_account($response['auth']['raw']['name'], "NONE PROVIDED", '', $response['auth']['raw']['screen_name'], $gender, "1", $response['auth']['raw']['id'], $profile);
				$sql  = "SELECT * FROM users WHERE oauth_provider = '1' AND oauth_uid = '{$response['auth']['raw']['id']}' LIMIT 1";
				$result_array = User::find_by_sql($sql);
				$data = !empty($result_array) ? array_shift($result_array) : false;
				if(!empty($data) ){
						$session->logout();
						$session->login($data);
				}
				redirect_to("../index.php");
			} else {
				$sql  = "SELECT * FROM users WHERE oauth_provider = '1' AND oauth_uid = '{$response['auth']['raw']['id']}' LIMIT 1";
				$result_array = User::find_by_sql($sql);
				$data = !empty($result_array) ? array_shift($result_array) : false;
				if(!empty($data) ){
						$session->logout();
						$session->login($data);
				}
				redirect_to("../index.php");
			}
		} elseif($response['auth']['provider'] == "QQWeibo") {
			$data = User::find_by_sql("SELECT * FROM users WHERE oauth_provider = '1' AND oauth_uid = '{$response['auth']['raw']['id']}' ");
			$session = new Session;
			if(empty($data)){
				$gender = $response['auth']['raw']['sex'] ? 'Male' : 'Female';
				$profile = array(
				  'profile_msg' => '',
				  'about_me' => $response['auth']['raw']['introduction'],
				  'profile_picture' => $response['auth']['raw']['head'] . '/180',
				);
				User::create_oauth_account($response['auth']['raw']['name'], "NONE PROVIDED", '', $response['auth']['raw']['nick'], $gender, "1", $response['auth']['raw']['id'], $profile);
				$sql  = "SELECT * FROM users WHERE oauth_provider = '1' AND oauth_uid = '{$response['auth']['raw']['id']}' LIMIT 1";
				$result_array = User::find_by_sql($sql);
				$data = !empty($result_array) ? array_shift($result_array) : false;
				if(!empty($data) ){
						$session->logout();
						$session->login($data);
				}
				redirect_to("../index.php");
			} else {
				$sql  = "SELECT * FROM users WHERE oauth_provider = '1' AND oauth_uid = '{$response['auth']['raw']['id']}' LIMIT 1";
				$result_array = User::find_by_sql($sql);
				$data = !empty($result_array) ? array_shift($result_array) : false;
				if(!empty($data) ){
						$session->logout();
						$session->login($data);
				}
				redirect_to("../index.php");
			}
		}

	}
}