<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

/*
 * Copyright (c) 2012-2013 CODEC2I.NET
 * 对非商用为目的的用户采用GPL2开源协议。您可以将其在自己的服务器部署使用，但不可以修改后发布为闭源或者商业软件。以商用为目的的用户需要购买CODEC2I的商业授权，详情请邮件sv@codec2inet。使用CODEC2I众筹系统的网站需要在页面显著位置标明基于CODEC2I构建。
 * E-mail:sv@codec2i.net
 * 官方网址:http://www.codec2i.net
 */

class User {
	
	protected static $table_name="users";
	protected static $levels_table_name="user_levels";
	protected static $invites_table_name="invites";
	protected static $db_fields = array('id', 'user_id', 'first_name', 'last_name', 'gender', 'username', 'password', 'email', 'user_level', 'primary_group', 'activated', 'suspended', 'date_created', 'last_login', 'account_lock', 'signup_ip', 'last_ip', 'country', 'whitelist', 'ip_whitelist', 'credit', 'banked_credit', 'staff', 'investments_made', 'amount_invested', 'invited_by', 'created', 'expires', 'expiry_date', 'level_id', 'level_name', 'auto', 'datetime', 'ip_address', 'name', 'qty', 'status', 'redirect_page', 'access_time', 'time_type', 'amount', 'created', 'timed_access', 'expiry_date', 'expires', 'package_name','message','profile_picture','type','task','amount','options','credits','description');
	
	public $id;
	public $user_id;
	public $username;
	public $password;
	public $email;
	// public $user_level;
	// public $primary_group;
	public $activated;
	public $suspended;
	public $first_name;
	public $last_name;
	public $gender;
	public $date_created;
	public $last_login;
	// public $account_lock;
	public $signup_ip;
	public $last_ip;
	public $country;
	public $whitelist;
	public $ip_whitelist;
	public $credit;
	public $banked_credit;
	// public $level_expiry;
	// public $expiry_datetime;
	// public $invited_by;
	public $staff;
	public $investments_made;
	public $amount_invested;
	
	// public $level_id;
	// public $level_name;
	// public $auto;
	
	// public $created;
	// public $timed_access;
	// public $expiry_date;
	// public $redirect_page;
	// public $access_time;
	// public $time_type;
	// public $amount;
	// public $expires;
	
	// public $package_name;
	
	public $datetime;
	public $ip_address;

	public $message;
	public $profile_picture;
	
	// Table: user_activity
	
	// public $id;
	// public $user_id;
	// public $datetime;
	public $task;
	public $type;
	
	// Table: credit_packages
	
	// public $id;
	public $name;
	public $status;
	public $qty;
	
	// Table: credit_history
	
	// public $id;
	// public $user_id;
	public $credits;
	public $description;
	// public $datetime;
	// public $status;
	
	// Table: payout_requests
	
	// public $id;
	// public $user_id;
	public $amount;
	public $options;
	// public $status;
	
  	public function full_name() {
	    if(isset($this->first_name) && isset($this->last_name)) {
	      return $this->first_name . " " . $this->last_name;
	    } else {
	      return "";
	    }
  	}

	public static function authenticate($username="", $password="") {
    global $database;
    $username = $database->escape_value($username);
    $password = $database->escape_value(encrypt_password($password));

    $sql  = "SELECT * FROM ".self::$table_name." WHERE username = '{$username}' AND password = '{$password}' LIMIT 1";
    $result_array = self::find_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	
	public static function check_user($table, $entry) {
	    global $database;
		 $table = $database->escape_value($table);
	    $entry = $database->escape_value($entry);

	    $sql  = "SELECT * FROM ".self::$table_name." WHERE {$table} = '{$entry}' LIMIT 1";
	    $result_array = self::find_by_sql($sql);
		 return !empty($result_array) ? true : false;
	}
	
	public static function check_activation($username) {
    global $database;
	$username = $database->escape_value($username);

    // $sql  = "SELECT '{$username}' FROM users WHERE {$table} = {$entry} LIMIT 1";
	$sql = "SELECT * FROM ".self::$table_name." WHERE username = '{$username}' AND activated = '1' LIMIT 1";
    $result_array = self::find_by_sql($sql);
		return !empty($result_array) ? true : false;
	}
	
	public static function check_if_suspended($username) {
    global $database;
	$username = $database->escape_value($username);

    // $sql  = "SELECT '{$username}' FROM users WHERE {$table} = {$entry} LIMIT 1";
	$sql = "SELECT * FROM ".self::$table_name." WHERE username = '{$username}' AND suspended = '1' LIMIT 1";
    $result_array = self::find_by_sql($sql);
		return !empty($result_array) ? true : false;
	}
	
	public static function check_current_password($username, $password) {
    global $database;
	$username = $database->escape_value($username);
	$password = $database->escape_value($password);

	// $sql = "SELECT * FROM users WHERE username = '{$username}' AND password = {$password}";
	$sql = "SELECT * FROM  ".self::$table_name." WHERE username = '{$username}' AND password = '{$password}' LIMIT 1";
    $result_array = self::find_by_sql($sql);
		return !empty($result_array) ? true : false;
	}
	
	public static function check_whitelist($username) {
    global $database;
	$username = $database->escape_value($username);

    // $sql  = "SELECT '{$username}' FROM users WHERE {$table} = {$entry} LIMIT 1";
	$sql = "SELECT * FROM ".self::$table_name." WHERE username = '{$username}' AND whitelist = '1' LIMIT 1";
	
    $result_array = self::find_by_sql($sql);
		return !empty($result_array) ? true : false;
	}
	
	public static function check_login($username, $password, $current_ip, $remember) {
		// instantiate Session Class
		$session = new Session();
		
	    // Check database to see if username/password exist.
		$found_user = self::authenticate($username, $password);

		// lets see if the users account has been activated
	    $activated = self::check_activation($username);
		// lets see if the users account has been suspended
	    $suspended = self::check_if_suspended($username);
	
		// lets see if the users account has has ip whitelist enabled
		$whitelist = self::check_whitelist($username);

	  if ($found_user) {
	   	 if($activated){
		   	if(!$suspended){
				if($whitelist) {
					global $database;
					$sql = "SELECT ip_whitelist FROM users WHERE username = '{$username}'";
					$result = $database->query($sql);
					$array = $database->fetch_array($result);
					$exp = $array['ip_whitelist'];
					// print_r($exp);
					$whitelist = explode(",", $exp);
					// print_r($whitelist);
					if (in_array($current_ip, $whitelist)) {
						// echo "success";
						$session->login($found_user, $remember);
						$sql = "UPDATE ".self::$table_name." SET last_ip = '{$current_ip}' WHERE username = '{$username}' ";
						$database->query($sql);
						// redirect_to("index.php");
						// return "true";
						$user = User::find_by_id($_SESSION['user_id']);
						// return User::get_login_redirect($user->primary_group);
						return "index.php";
					} else {
						// echo "failure";
						$session->message("<div class='alert alert-warning'><button type='button' class='close' data-dismiss='alert'>×</button>This account currently has IP Protection enabled and your IP ($current_ip) is not in the whitelist.</div>");
						return "false";
					}
				} else {
					$session->login($found_user, $remember);
					global $database;
					$sql = "UPDATE ".self::$table_name." SET last_ip = '{$current_ip}' WHERE username = '{$username}' ";
					$database->query($sql);
			      // redirect_to("index.php");
					// return "true";
					$user = User::find_by_id($_SESSION['user_id']);
					// return User::get_login_redirect($user->primary_group);
					return "index.php";
				}
			 } else {
				$session->message("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Your account has been suspended, please contact support.</div>");
				return "false";
			 }
		 } else {
			$session->message("<div class='alert alert-info'><button type='button' class='close' data-dismiss='alert'>×</button>您的账户尚未激活,请检查你的电子邮件。<a href='activate.php'>点击这里</a>重新发送代码</div>");
			return "false";
		 }
	  } else {
	    // username/password combo was not found in the database
	    $session->message("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>用户名或者密码不正确。</div>");
		 return "false";
	  }
   }

	public static function get_login_redirect($primary_group){
		$data = self::find_by_sql("SELECT redirect_page FROM user_levels WHERE level_id = '{$primary_group}' LIMIT 1");
		return $data[0]->redirect_page;
  	}
	
	public function create_account($username, $password, $email, $first_name, $last_name, $plain_password, $signup_ip, $country, $gender){
		global $database;
		$session = new Session();
		// Genetate the users ID.
		$user_id = generate_id();
		
		$flag = false;
		//until flag is false
		while ($flag == false){
			//check if the user id exists
			$sql = "SELECT * FROM ".self::$table_name." WHERE user_id = '{$user_id}'";
			$query = $database->query($sql);
			$rows = $database->num_rows($query);
			//if it does try again till you find an id that does not exist
			if ($rows){
				$user_id = generate_id();
			}else{
				//if it does not exist, exit the loop
				$flag = true;
			}
		}
		if ($flag == true){
			//insert into db the data
			$datetime = strftime("%Y-%m-%d %H:%M:%S", time());
			if(VERIFY_EMAIL == "NO"){$activated = 1;} else if(VERIFY_EMAIL == "YES"){$activated = 0;}
			$sql = "INSERT INTO ".self::$table_name." VALUES ('', '$user_id', '$first_name', '$last_name', '$gender', '$username', '$password', '$email', '$activated', '0', '$datetime', '', '$signup_ip', '$signup_ip', '$country', '0', '0', '', '0', '0','0','0','0','')";
			$database->query($sql);
			
			if($gender == "Male"){
				$profile_picture = "male.jpg";
			} else {
				$profile_picture = "female.jpg";
			}
			
			$database->query("INSERT INTO profile VALUES ('', '$user_id', '', '', '$profile_picture')");
						
			// Send and email to the user.
			if(VERIFY_EMAIL == "NO") {
				// Initialize functions.
				$email_class = new Email();

				// Email sent to the user if logged in.
				$from = SITE_EMAIL;
				$subject = "欢迎注册 ".SITE_NAME." ";

				$message = $email_class->email_template('registration_success', "$plain_password", "$username", "", "");
				$email_class->send_email($email, $from, $subject, $message);
			} else if(VERIFY_EMAIL == "YES") {
				//$activation_hash = Activation::set_activation_link($email)
				Activation::set_activation_link($plain_password, $username, $email);
			}
			
			
			// Create the message that will be displayed on the login screen once the user has been redirected.
			$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>您的帐户已成功创建。请检查您的电子邮件，点击激活链接。</div>");
			
			// redirect the user to the login page.
			redirect_to('login.php');
		}
	}
	
	public function create_oauth_account($username, $email, $first_name, $last_name, $gender, $oauth_provider, $oauth_id){
		global $database;
		$session = new Session();
		// Genetate the users ID.
		$user_id = generate_id();
		
		$flag = false;
		//until flag is false
		while ($flag == false){
			//check if the user id exists
			$sql = "SELECT * FROM ".self::$table_name." WHERE user_id = '{$user_id}'";
			$query = $database->query($sql);
			$rows = $database->num_rows($query);
			//if it does try again till you find an id that does not exist
			if ($rows){
				$user_id = generate_id();
			}else{
				//if it does not exist, exit the loop
				$flag = true;
			}
		}
		if ($flag == true){
			//insert into db the data
			$datetime = strftime("%Y-%m-%d %H:%M:%S", time());
			$password = encrypt_password($email.$username);
			$signup_ip = $_SERVER['REMOTE_ADDR'];
			$sql = "INSERT INTO ".self::$table_name." VALUES ('', '$user_id', '$first_name', '$last_name', '$gender', '$username', '$password', '$email', '1', '0', '$datetime', '$datetime', '$signup_ip', '$signup_ip', '', '0', '0', '', '0', '0','0','0','$oauth_provider','$oauth_id')";
			$database->query($sql);
						
		}
	}
	
	public function update_account($value, $first_name, $last_name, $password, $email, $plain_password, $country, $gender, $whitelist, $ip_whitelist){
			global $database;
			// Initialize functions.
			$email_class = new Email();
			$session = new Session();

			// Email sent to the user if logged in.
			$from = SITE_EMAIL;
			$subject = "Account Settings Changed";
			
			if(($whitelist == 1) && (empty($ip_whitelist))){
				$whitelist = 0;
			}
			
			if ($value == 1) {
				$sql = "UPDATE ".self::$table_name." SET password = '{$password}', email = '{$email}', first_name = '{$first_name}', last_name = '{$last_name}', gender = '{$gender}', country = '{$country}', whitelist = '{$whitelist}', ip_whitelist = '{$ip_whitelist}' WHERE user_id = '{$this->user_id}'";
				
				// HTML Message Content.
				// $message = $email_class->email_template('update_all_settings', $plain_password, "");
				$message = $email_class->email_template('custom_email', "", "", "", "", "<p>Your account settings have been changed.</p><p>Password: {$plain_password} (Encrypted in our database.)</p>");
				
			} else if ($value == 2) {
				$sql = "UPDATE ".self::$table_name." SET email = '{$email}', first_name = '{$first_name}', last_name = '{$last_name}', gender = '{$gender}', country = '{$country}', whitelist = '{$whitelist}', ip_whitelist = '{$ip_whitelist}' WHERE user_id = '{$this->user_id}' ";
				
				// HTML Message Content.
				// $message = $email_class->email_template('update_settings', "", "");
				$message = $email_class->email_template('custom_email', "", "", "", "", "<p>Your account settings have been successfully changed.</p>");
				
			} 
			$database->query($sql);
			// $session = new Session();
			// $session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>Settings successfully updated.</div>");
			$session->message($the_message);
			
			// Finally send the email to the user.
			$email_class->send_email($email, $from, $subject, $message);
			
			redirect_to('settings.php');
	}
	
	public static function find_username_by_id($user_id) {
    $result_array = self::find_by_sql("SELECT username FROM ".self::$table_name." WHERE user_id = '{$user_id}' LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
   }
	
	// public static function downgrade_user($user_id, $location){
	// 	global $database;
	// 	
	// 	$sql = "SELECT level_id, level_name FROM user_levels WHERE auto = '1' LIMIT 1";
	// 	$result = $database->query($sql);
	// 	$group = $database->fetch_array($result);
	// 	
	// 	$new_level = $group['level_id'];
	// 	
	// 	$sql = "UPDATE users SET user_level = '{$new_level}', level_expiry = '0', expiry_datetime = '0000-00-00 00:00:00' WHERE user_id = '{$user_id}' ";
	// 	$database->query($sql);
	// 
	// 	redirect_to($location);
	// }
	
	public static function downgrade_access($id, $user_id, $level_id, $access_levels, $redirect="settings.php"){
		global $database;
		
		$database->query("DELETE FROM levels WHERE id = '{$id}' AND user_id = '{$user_id}' ");
		
		$access_levels = explode(",", $access_levels);
		$new_access_levels = array_diff($access_levels, array($level_id));
		$new_access_levels = implode(",", $new_access_levels);
		
		if($new_access_levels == ""){
			$row = $database->fetch_array($database->query("SELECT * FROM ".self::$levels_table_name." WHERE auto = '1'"));
			$user_level = $row['level_id'];
			$database->query("UPDATE users SET user_level = '{$user_level}' WHERE user_id = '{$user_id}' ");
		} else {
			$database->query("UPDATE users SET user_level = '{$new_access_levels}' WHERE user_id = '{$user_id}' ");
		}

		redirect_to($redirect);
	}
	
	public static function purchase_access($user_id, $id){
		global $database;
		$session = new Session;
		
		$user = User::find_by_id($user_id);
		$access_package = self::find_by_sql("SELECT * FROM user_levels WHERE level_id = '{$id}' LIMIT 1");
		$package = $access_package[0];
		
		if($user->credit < $package->amount){
			$session->message("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, you don't have enough active credit for this package.</div>");
		} else {
			$current_access = explode(",", $user->user_level);
			if(in_array($id, $current_access)){
				$session->message("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, but you already have this access level.</div>");
			} else {
				$new_credit = $user->credit - $package->amount;
				array_push($current_access, $id);
				$new_access = implode(",", $current_access);
				$database->query("UPDATE users SET user_level = '{$new_access}', credit = '{$new_credit}' WHERE user_id = '{$user->user_id}' ");
				$datetime = date('Y-m-d h:i:s', time());
				if($package->timed_access == 1){
					$time = "+".$package->access_time." ".User::convert_time_type($package->time_type);
					$new_date = strtotime($time, strtotime($datetime));
					$expiry_date = date( 'Y-m-d H:i:s', $new_date );
				} else {
					$expiry_date = "0000-00-00 00:00:00";
				}
				$database->query("INSERT INTO levels VALUES('','{$user_id}','{$id}','{$datetime}','{$package->timed_access}','{$expiry_date}')  ");
				$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>Thank you! Your purchase is now active.</div>");
			}
		}
		redirect_to("settings.php");
	}
	
	public static function count_invites($username){
		global $database;
		$result = $database->query("SELECT COUNT(*) FROM users WHERE invited_by = '{$username}'");
		$row = $database->fetch_array($result);
    	return array_shift($row);
	}

	public static function get_site_levels(){
		return self::find_by_sql("SELECT level_id,level_name FROM user_levels");
	}
	
	public static function get_purch_levels(){
		return self::find_by_sql("SELECT * FROM user_levels WHERE purchasable = '1' ");
	}
	
	public static function get_level_name($level_id){
		$return = self::find_by_sql("SELECT level_name FROM user_levels WHERE level_id = '{$level_id}' ");
		return $return[0]->level_name;
	}
	
	public static function get_user_levels($user_id){
		return self::find_by_sql("SELECT * FROM levels WHERE user_id = '{$user_id}' ");
	}
	
	public static function convert_time_type($type){
		if($type == 0){
			return "天";
		} else if($type == 1){
			return "周";
		} else if($type == 2){
			return "月";
		}
	}
	
	// credit
	
	public static function get_credit_history($user_id=0) {
		return self::find_by_sql("SELECT * FROM credit_history WHERE user_id= '{$user_id}' ORDER BY datetime DESC");
	}
	
	public static function get_payout_history($user_id=0) {
		return self::find_by_sql("SELECT * FROM payout_requests WHERE user_id= '{$user_id}'");
	}
	
	public static function convert_payout_status($status){
		if($status == 0){
			return "未处理";
		} else if($status == 1){
			return "接受的";
		} else if($status == 2){
			return "拒绝的";
		}
	}
	
	public static function get_payout_requests() {
		return self::find_by_sql("SELECT * FROM payout_requests WHERE status = '0' ");
	}
	
	public static function get_payout($id) {
		$data = self::find_by_sql("SELECT * FROM payout_requests WHERE id = '{$id}' LIMIT 1 ");
		$user = User::find_by_id($data[0]->user_id);
		$array = array('amount' => $data[0]->amount, 'option' => $data[0]->options, 'current_status' => $data[0]->status, 'user_id' => $data[0]->user_id, 'current_credit' => $user->credit );
		return json_encode($array);
	}
	
	public static function mark_as_paid($id){
		global $database;
		$data = self::find_by_sql("SELECT * FROM payout_requests WHERE id = '{$id}' LIMIT 1 ");
		if($data != ""){
			$user = User::find_by_id($data[0]->user_id);
			if($data[0]->amount > $user->credit){
				$database->query("UPDATE payout_requests SET status = '2' WHERE id = '{$id}' ");
				return "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>The user doesn't have enough credit so the request has been rejected.</div>";
			} else {
				$new_credit = $user->credit - $data[0]->amount;
				$database->query("UPDATE users SET credit = '{$new_credit}' ");
				$database->query("UPDATE payout_requests SET status = '1' WHERE id = '{$id}' ");
				$session = new Session();
				$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>The users request has been marked as paid.</div>");
				return "success";
			}
		} else {
			return "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, but something has gone wrong. Please refresh and try again.</div>";
		}
	}
	
	public static function remove_credit($user_id,$required,$message){
		global $database;
		
		$current_credit = User::get_current_credit("active",$user_id);
		
		$new_credit = $current_credit - $required;
		
		$sql = "UPDATE ".self::$table_name." SET credit = '{$new_credit}' WHERE user_id = '{$user_id}' ";
		$database->query($sql);
		
		// add credit history			
		$datetime = date('Y-m-d H:i:s');
		$sql = "INSERT INTO credit_history VALUES('','$user_id','$required','$message','$datetime','d')";
		$database->query($sql);
	}

	public static function add_credit($user_id, $credit){
		global $database;
		$session = new Session();
		
		$user = User::find_by_id($user_id);
		
		$current_credit = User::get_current_credit("active",$user_id);
		
		$new_credit = $current_credit + $credit;
		
		$sql = "UPDATE ".self::$table_name." SET credit = '{$new_credit}' WHERE user_id = '{$user_id}' ";
		$database->query($sql);
		
		// add credit history			
		$datetime = date('Y-m-d H:i:s');
		// $sql = "INSERT INTO credit_history VALUES('','$user_id','$credit','Purchased credit','$datetime','c')";
		$sql = "INSERT INTO credit_history VALUES('','$user->user_id','$credit','Purchased credit','$datetime','c')";
		$database->query($sql);
		
		$email_class = new Email();
		$from = SITE_EMAIL;
		$subject = "Credit Added";
		$message = "Thank you for purchasing credit. <br /><br /> We have added <strong>".CURRENCYSYMBOL.$credit."</strong> to your account.";
		$message = $email_class->email_template('custom_email', "", "", "", "", $message);
		$email_class->send_email($user->email, $from, $subject, $message);
		
	}
	
	public static function request_payout($user_id,$amount,$options){
		global $database;
		$session = new Session();
		$database->query("INSERT INTO payout_requests (id,user_id,amount,options,status) VALUES ('','{$user_id}','{$amount}','{$options}','0') ");
		$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>Your payout of <strong>".$amount."</strong> credits has been submitted to us.</div>");
		return "complete";
	}
	
	public static function add_i_credit($user_id, $current_credit, $credit_package, $location = NULL){
		global $database;
		$session = new Session();
		
		$new_credit = $current_credit + $credit_package;
		
		$sql = "UPDATE ".self::$table_name." SET credit = '{$new_credit}' WHERE user_id = '{$user_id}' ";
		$database->query($sql);
		
		// add credit history			
		$datetime = date('Y-m-d H:i:s');
		$sql = "INSERT INTO credit_history VALUES('','$user_id','$credit_package','Invited User','$datetime','c')";
		$database->query($sql);		
	}
	
	public static function get_current_credit($type,$user_id){
		$data = self::find_by_sql("SELECT credit,banked_credit FROM users WHERE user_id = '{$user_id}' ");
		if($type == "active"){
			return $data[0]->credit;	
		} else if($type == "banked"){
			return $data[0]->banked_credit;
		}
	}
	
	public static function transfer_credit($action, $amount){
		global $database;
		$user = User::find_by_id($_SESSION['user_id']);
		if($action == "to"){
			$new_banked = $user->banked_credit + $amount;
			$new_current = $user->credit - $amount;
			$database->query("UPDATE users SET credit = '{$new_current}', banked_credit = '{$new_banked}' WHERE user_id = '{$user->user_id}' ");
		} else if($action == "from"){
			$new_banked = $user->banked_credit - $amount;
			$new_current = $user->credit + $amount;
			$database->query("UPDATE users SET credit = '{$new_current}', banked_credit = '{$new_banked}' WHERE user_id = '{$user->user_id}' ");
		}
	}
	
	// Top Investors
	public static function get_top_investors($max = 50){
		return self::find_by_sql("SELECT first_name,last_name,username,country,investments_made,amount_invested FROM users ORDER BY investments_made DESC LIMIT $max ");
	}
	public static function get_investment_rank($top_investors){
		$counts = array();
		foreach($top_investors as $key=>$subarr){
			if(isset($counts[$subarr->investments_made])){
				$counts[$subarr->investments_made]++;
			} else {
				$counts[$subarr->investments_made] = 1;
				$counts[$subarr->investments_made] = isset($counts[$subarr->investments_made]) ? $counts[$subarr->investments_made]++ : 1;
			}
		}
		return $counts;
	}
	public static function get_investor_details($user_id){
		return self::find_by_sql("SELECT first_name,last_name,username,country FROM users WHERE user_id = '{$user_id}' LIMIT 1 ");
	}
	
	// public static function buy_package($user_id, $current_credit, $credit_package, $package_name, $package_desc, $level_expiry, $expiry_datetime, $location){
	// 	global $database;
	// 	$session = new Session();
	// 	
	// 	if($current_credit < $credit_package) {
	// 		$session->message("<div class='notification-box error-notification-box'><p>Sorry, you don't have enough active credit for this package.</p><a href='#' class='notification-close error-notification-close'>x</a></div><!--.notification-box .notification-box-error end-->");
	// 		// redirect_to($location);
	// 		redirect_to('spend_credits.php');
	// 	} else {
	// 		$new_credit = $current_credit - $credit_package;
	// 		
	// 		$sql = "UPDATE ".self::$table_name." SET credit = '{$new_credit}' WHERE user_id = '{$user_id}' ";
	// 		$database->query($sql);
	// 		
	// 		// add credit history			
	// 		$datetime = date('Y-m-d H:i:s');
	// 		$sql = "INSERT INTO credit_history VALUES('','$user_id','$credit_package','$package_desc','$datetime','d')";
	// 		$database->query($sql);
	// 				
	// 		if($package_name == "vip1w"){
	// 			if($level_expiry == '1'){
	// 				$new_date = strtotime('+1 week', strtotime($expiry_datetime));
	// 				$datetime = date( 'Y-m-d H:i:s', $new_date );
	// 			} else {
	// 				$datetime = date('Y-m-d H:i:s', strtotime("+1 week"));
	// 			}		
	// 			$sql = "UPDATE users SET user_level = '748969', level_expiry = '1', expiry_datetime = '{$datetime}' WHERE user_id = '{$user_id}'";
	// 		} else if($package_name == "vip3w"){
	// 			if($level_expiry == '1'){
	// 				$new_date = strtotime('+3 weeks', strtotime($expiry_datetime));
	// 				$datetime = date( 'Y-m-d H:i:s', $new_date );
	// 			} else {
	// 				$datetime = date('Y-m-d H:i:s', strtotime("+1 week"));
	// 			}		
	// 			$sql = "UPDATE users SET user_level = '748969', level_expiry = '1', expiry_datetime = '{$datetime}' WHERE user_id = '{$user_id}'";
	// 		} else if($package_name == "vip5w"){
	// 			if($level_expiry == '1'){
	// 				$new_date = strtotime('+5 weeks', strtotime($expiry_datetime));
	// 				$datetime = date( 'Y-m-d H:i:s', $new_date );
	// 			} else {
	// 				$datetime = date('Y-m-d H:i:s', strtotime("+1 week"));
	// 			}			
	// 			$sql = "UPDATE users SET user_level = '748969', level_expiry = '1', expiry_datetime = '{$datetime}' WHERE user_id = '{$user_id}'";
	// 		}
	// 		
	// 		$database->query($sql);
	// 
	// 		$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>The {$package_name} package has been successfully applied to your account and will expire on ".datetime_to_text($datetime).".</div>");
	// 		redirect_to($location);
	// 	}
	// 	
	// }
	
	public static function bank($user_id, $current_credit, $bank, $credit, $bw, $location){
		global $database;
		$session = new Session();		
		
		// $credit = preg_replace("/[^0-9]/", '', $credit);
				
		if($bw == "bank"){
			$active_credit = $current_credit - $credit;
			$banked_credit = $bank + $credit;
			$msg = "deposited";
		} else if($bw == "withdraw"){
			$banked_credit = $bank - $credit;
			$active_credit = $current_credit + $credit;
			$msg = "withdrawn";
		}
		
		$sql = "UPDATE ".self::$table_name." SET credit = '{$active_credit}', banked_credit = '{$banked_credit}' WHERE user_id = '{$user_id}' ";
		$database->query($sql);
		
		$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>".number_format($credit, 0, '.', ',')." credit have been successfully {$msg}.</div>");
		redirect_to($location);
	}
	
	public static function get_package_data($id){
		return self::find_by_sql("SELECT * FROM credit_packages WHERE id = '{$id}' LIMIT 1");
	}
	
	public static function get_credit_packages(){
		return self::find_by_sql("SELECT * FROM credit_packages ");
	}
	
	// public static function get_current_credit($user_id){
	// 	$return_data = self::find_by_sql("SELECT credit FROM users WHERE user_id = '{$user_id}' ");
	// 	return $return_data[0]->credit;
	// }
	
	public static function create_package($name,$qty,$status){
		global $database;
		$session = new Session;
		$database->query("INSERT INTO credit_packages VALUES('','{$name}','{$status}','{$qty}')");
		$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>积分套餐已经创建。</div>");
		redirect_to("credits.php");		
	}
	
	public static function edit_credit_package($id,$name,$qty,$status){
		global $database;
		$session = new Session;
		$database->query("UPDATE credit_packages SET name = '{$name}', qty = '{$qty}', status = '{$status}' WHERE id = '{$id}' ");
		$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>积分套餐已经更新。</div>");
		redirect_to("credits.php");		
	}
	
	public static function delete_credit_package($id){
		global $database;
		$session = new Session;
		$database->query("DELETE FROM credit_packages WHERE id = '{$id}' ");
		$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>积分套餐已经删除。</div>");
		redirect_to("credits.php");		
	}
	
	public static function convert_credit_status($status){
		if($status == 0){
			return "不可见";
		} else if($status == 1){
			return "可见的";
		}
	}
	
	public static function convert_credit($type){
		if($type == "c"){
			return "Credit";
		} else if($type == "d"){
			return "Debit";
		}
	}
	
	// public static function transfer_credit($user_id, $username, $amount){
	// 	global $database;
	// 	$session = new Session();
	// 	if($amount > User::get_current_credit($user_id)){
	// 		$session->message("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, but you don't have enough credit to do that.</div>");
	// 	} else {
	// 		$rec_id = self::find_id_by_username($username);
	// 		if(empty($rec_id->user_id)){
	// 			$session->message("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, that user could not be found.</div>");
	// 		} else {
	// 			$new_credit = User::get_current_credit($user_id) - $amount;
	// 			$database->query("UPDATE users SET credit = '{$new_credit}' WHERE user_id = '{$user_id}' ");
	// 			$datetime = date('Y-m-d H:i:s');
	// 			$database->query("INSERT INTO credit_history VALUES('','$user_id','$amount','credit Transfer to $username','$datetime','d')");
	// 			
	// 			$sen_id = self::find_username_by_id($user_id);
	// 			$new_credit = User::get_current_credit($rec_id->user_id) + $amount;
	// 			$database->query("UPDATE users SET credit = '{$new_credit}' WHERE user_id = '{$rec_id->user_id}' ");
	// 			$datetime = date('Y-m-d H:i:s');
	// 			$database->query("INSERT INTO credit_history VALUES('','$rec_id->user_id','$amount','credit Transfer from $sen_id->username','$datetime','c')");
	// 			
	// 			$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>credit Transferred.</div>");
	// 		}
	// 	}
	// 	
	// 	// $session->message("");
	// 	redirect_to("settings.php");
	// }
	
	public static function get_access_logs($user_id){
		return self::find_by_sql("SELECT * FROM access_logs WHERE user_id = '{$user_id}' ORDER BY datetime DESC ");
  	}
	
	public static function count_all_levels() {
	  global $database;
	  $sql = "SELECT COUNT(*) FROM user_levels";
    $result_set = $database->query($sql);
	  $row = $database->fetch_array($result_set);
    return array_shift($row);
	}
	
	public static function find_profile_message_owner($id=0) {
		global $database;
		// $id = 815788;
		$sql = "SELECT users.user_id, users.username, users.first_name, users.last_name, profile.user_id, profile.profile_picture FROM users, profile WHERE users.user_id = '{$id}'";
		return self::find_by_sql($sql);
		// return self::find_by_sql("SELECT user_id,first_name,last_name,username FROM users WHERE user_id = '{$id}' LIMIT 1 ");
   }
	
	// Common Database Methods
	public static function find_all() {
		return self::find_by_sql("SELECT * FROM ".self::$table_name);
  	}
  	
	public static function count_by_sql($sql) {
	  global $database;
	  // $sql = "SELECT COUNT(*) FROM user_levels";
     $result_set = $database->query($sql);
	  $row = $database->fetch_array($result_set);
    return array_shift($row);
	}

	public static function find_id_by_username($username) {
    $result_array = self::find_by_sql("SELECT user_id FROM ".self::$table_name." WHERE username = '{$username}' LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
   }

	public static function find_profile_data($data, $target) {
    $result_array = self::find_by_sql("SELECT user_id,username,first_name,last_name,country FROM ".self::$table_name." WHERE {$target} = '{$data}' LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
   }

  	public static function find_by_id($id=0) {
    $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE user_id={$id} LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
    }
  	
	public static function find_by_username($username) {
    $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE username = '{$username}' LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
    }

  	public static function find_by_sql($sql="") {
    global $database;
    $result_set = $database->query($sql);
    $object_array = array();
    while ($row = $database->fetch_array($result_set)) {
      $object_array[] = self::instantiate($row);
    }
    return $object_array;
    }

	public static function count_all() {
	  global $database;
	  $sql = "SELECT COUNT(*) FROM ".self::$table_name;
    $result_set = $database->query($sql);
	  $row = $database->fetch_array($result_set);
    return array_shift($row);
	}

	private static function instantiate($record) {
		// Could check that $record exists and is an array
    	$object = new self;
		
		// More dynamic, short-form approach:
		foreach($record as $attribute=>$value){
		  if($object->has_attribute($attribute)) {
		    $object->$attribute = $value;
		  }
		}
		return $object;
	}
	
	private function has_attribute($attribute) {
	  // We don't care about the value, we just want to know if the key exists
	  // Will return true or false
	  return array_key_exists($attribute, $this->attributes());
	}

	protected function attributes() { 
		// return an array of attribute names and their values
	  $attributes = array();
	  foreach(self::$db_fields as $field) {
	    if(property_exists($this, $field)) {
	      $attributes[$field] = $this->$field;
	    }
	  }
	  return $attributes;
	}
	
	protected function sanitized_attributes() {
	  global $database;
	  $clean_attributes = array();
	  // sanitize the values before submitting
	  // Note: does not alter the actual value of each attribute
	  foreach($this->attributes() as $key => $value){
	    $clean_attributes[$key] = $database->escape_value($value);
	  }
	  return $clean_attributes;
	}

}
