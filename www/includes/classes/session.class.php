<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

/*
 * Copyright (c) 2012-2013 CODEC2I.NET
 * 对非商用为目的的用户采用GPL2开源协议。您可以将其在自己的服务器部署使用，但不可以修改后发布为闭源或者商业软件。以商用为目的的用户需要购买CODEC2I的商业授权，详情请邮件sv@codec2inet。使用CODEC2I众筹系统的网站需要在页面显著位置标明基于CODEC2I构建。
 * E-mail:sv@codec2i.net
 * 官方网址:http://www.codec2i.net
 */

include 'user.class.php';

class Session {
	
	private $logged_in=false;
	public $id;
	public $user_id;
	public $message;
	public $data;
	public $last_login;
	
	public $primary_group;
	
	function __construct() {
		$this->check_message();
		$this->check_login();
	}
	
  public function is_logged_in() {
    return $this->logged_in;
  }

	public function login($user, $remember="") {
    // database should find user based on username/password
    if($user){
	  global $database;
      $this->user_id = $_SESSION['user_id'] = $user->user_id;
	  	$datetime = strftime("%Y-%m-%d %H:%M:%S", time());
	  	$sql = "UPDATE users SET last_login = '{$datetime}' WHERE user_id = '{$this->user_id}' ";
	  	$database->query($sql);
		$current_ip = $_SERVER['REMOTE_ADDR'];
		$database->query("INSERT INTO access_logs VALUES('','{$this->user_id}','{$current_ip}','{$datetime}') ");
      $this->logged_in = true;
		$_SESSION['username'] = User::find_username_by_id($_SESSION['user_id'])->username;
		if($remember == "yes"){
			setcookie("auth", $user->user_id, time()+60*60*24*30, "/");
			setcookie("authun", $user->username, time()+60*60*24*30, "/");
		}
    }
  }

  	public function admin_login_as_user($user_id){
		$sql  = "SELECT * FROM users WHERE user_id = '$user_id' LIMIT 1";
		$result_array = User::find_by_sql($sql);
		$data = !empty($result_array) ? array_shift($result_array) : false;
		if(!empty($data) ){
			self::logout();
			self::login($data);
		}
	}

  public function logout() {
    unset($_SESSION['user_id']);
    unset($this->user_id);
	 setcookie("auth", $user->user_id, time()-60*60*24*30, "/");
	 setcookie("authun", $user->username, time()-60*60*24*30, "/");
    $this->logged_in = false;
  }

	public function message($msg="") {
	  if(!empty($msg)) {
	    // then this is "set message"
	    // make sure you understand why $this->message=$msg wouldn't work
	    $_SESSION['message'] = $msg;
	  } else {
	    // then this is "get message"
			return $this->message;
	  }
	}

	private function check_login() {
	  	if(isset($_SESSION['user_id'])) {
	      $this->user_id = $_SESSION['user_id'];
	      $this->logged_in = true;
	    } else {
			if(isset($_COOKIE['auth'])){
				$this->user_id = $_COOKIE['auth'];
				// $this->logged_in = true;
				self::cookie_login($this->user_id);
			} else {
				unset($this->user_id);
			    $this->logged_in = false;
			}
	    }
  	}

	public function cookie_login($user_id){
		$sql  = "SELECT * FROM users WHERE user_id = '$user_id' LIMIT 1";
		$result_array = User::find_by_sql($sql);
		$data = !empty($result_array) ? array_shift($result_array) : false;
		// print_r($data);
		if(!empty($data) && isset($_COOKIE['authun'])){
			if($data->username == $_COOKIE['authun']){
				self::login($data);
				unset($data);
			}
		} else {
			unset($data);
		}
	}
  
	private function check_message() {
		// Is there a message stored in the session?
		if(isset($_SESSION['message'])) {
			// Add it as an attribute and erase the stored version
      $this->message = $_SESSION['message'];
      unset($_SESSION['message']);
    } else {
      $this->message = "";
    }
	}
	
}

$session = new Session();
$message = $session->message();
