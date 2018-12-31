<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

/*
 * Copyright (c) 2012-2013 CODEC2I.NET
 * 对非商用为目的的用户采用GPL2开源协议。您可以将其在自己的服务器部署使用，但不可以修改后发布为闭源或者商业软件。以商用为目的的用户需要购买CODEC2I的商业授权，详情请邮件sv@codec2inet。使用CODEC2I众筹系统的网站需要在页面显著位置标明基于CODEC2I构建。
 * E-mail:sv@codec2i.net
 * 官方网址:http://www.codec2i.net
 */

class Activation {
	
	protected static $table_name="activation_links";
	protected static $user_table_name="users";
	protected static $db_fields = array('', 'email', 'hash', 'done');
	
	private static function generate_hash($lenth = 15) { 
	    $aZ09 = array_merge(range('A', 'Z'), range('a', 'z'),range(0, 9)); 
	    $out =''; 
	    for($c=0;$c < $lenth;$c++) { 
	       $out .= $aZ09[mt_rand(0,count($aZ09)-1)]; 
	    } 
	    return $out; 
	}
	
	public static function set_activation_link($plain_password, $username, $email) {
		global $database;
		$session = new Session();
		// Genetate the hash.
		$hash = self::generate_hash();

		//insert into db the data
		$sql = "INSERT INTO ".self::$table_name." VALUES ('', '$email', '$hash', '0')";
		$database->query($sql);
		
		// Initialize functions.
		$email_class = new Email();
		
		// Email sent to the user if logged in.
		$from = SITE_EMAIL;
		$subject = "Welcome to ".SITE_NAME." ";
		
		// Send and email to the user.
		$message = $email_class->email_template('registration_activation', "$plain_password", "$username", "$email", "$hash");
		
		$email_class->send_email($email, $from, $subject, $message);
	}
	
  	public static function check_activation($email, $hash) {
		global $database;
		//check if the user id exists
		$sql = "SELECT * FROM ".self::$user_table_name." WHERE activated = '0' AND email = '{$email}'";
		$query = $database->query($sql);
		$rows = $database->num_rows($query);
		//if it does try again till you find an id that does not exist
		if ($rows) {
			// Check their is an activation link associated with the account
			$sql = "SELECT * FROM ".self::$table_name." WHERE email = '{$email}' AND hash = '{$hash}' AND done = '0'";
			$query = $database->query($sql);
			$rows = $database->num_rows($query);
			if ($rows) {
				// Ok you can now activate the account.
				self::activate_account($email, $hash);
			} else {
				// No activation link found.
				$session = new Session();
				$session->message("<div class='notification-box error-notification-box'><p>No activation link found.</p><a href='#' class='notification-close error-notification-close'>x</a></div><!--.notification-box .notification-box-error end-->
				");
				redirect_to('activate.php');
			}
		} else {
			// Throw error back to the user.
			$session = new Session();
			$session->message("<div class='notification-box error-notification-box'><p>Your account has already been activated.</p><a href='#' class='notification-close error-notification-close'>x</a></div><!--.notification-box .notification-box-error end-->
			");
			redirect_to('activate.php');
		}
	}
	
	private static function activate_account($email, $hash) {
		global $database;
		$sql = "UPDATE ".self::$user_table_name." SET activated = '1' WHERE email = '{$email}'";
		$database->query($sql);
		self::delete_activation_link($email, $hash);
	}
	
	private static function delete_activation_link($email, $hash) {
		// Delete activation link
		global $database;
		$sql = "DELETE FROM ".self::$table_name." WHERE email = '{$email}' AND hash = '{$hash}' ";
		$database->query($sql);
		$session = new Session();
		$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>账户已激活，您现在可以登录。</div>");
		redirect_to('login.php');
	}
	
	public static function check_resend_code($email) {
		global $database;
		//check if the user exists
		$sql = "SELECT * FROM ".self::$table_name." WHERE email = '{$email}'";
		$query = $database->query($sql);
		$rows = $database->num_rows($query);
		//if it does try again till you find an id that does not exist
		if ($rows) {
			// Check their is an activation link associated with the account
			$sql = "SELECT * FROM ".self::$table_name." WHERE email = '{$email}'";
			$query = $database->query($sql);
			$row = $database->fetch_array($query);
			$hash = $row[2];
			$rows = $database->num_rows($query);
			if ($rows) {
				// Ok you can now activate the account.
				self::resend_code($email, $hash);
			} else {
				// No resend link found.
				$session = new Session();
				$session->message("<div class='notification-box error-notification-box'><p>No password link found.</p><a href='#' class='notification-close error-notification-close'>x</a></div><!--.notification-box .notification-box-error end-->
				");
				redirect_to('login.php');
			}
		} else {
			// Throw error back to the user.
			$session = new Session();
			$session->message("<div class='notification-box error-notification-box'><p>Their is not activation code associated with the email you have provided.</p><a href='#' class='notification-close error-notification-close'>x</a></div><!--.notification-box .notification-box-error end-->
			");
			redirect_to('login.php');
		}
	}
	
	public static function resend_code($email, $hash) {
		// Initialize functions.
		$email_class = new Email();
		
		// Email sent to the user if logged in.
		$from = SITE_EMAIL;
		$subject = "您的激活确认码";
		
		// Send and email to the user.
		$content = $email_class->email_template('resend_activation_code', "", "", "$email", "$hash");
		
		$email_class->send_email($email, $from, $subject, $content);
		
		$session = new Session();
		$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>您的激活确认码已经被发送至：{$email}.</div>");
		
		redirect_to('activate.php');
	}
	
}
