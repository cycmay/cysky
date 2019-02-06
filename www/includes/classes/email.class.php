<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

$stylesheet = "
	<style type='text/css'>
		body {background: #f2f2f2}
		.email_container {width: 700px; margin: 20px auto 0; background: #fff;}
		.email_content {width: 100%; color: rgb(107, 107, 107); padding: 5px 15px 5px; border: 1px solid rgb(194, 194, 194); background: #fff}
		.logo {
			padding-top: 6px;
			font-size: 20px;
			margin: 8px 0px;
			color: rgb(109, 109, 109);
		}
	</style>
";

$header = "
	<body>
	<div class='email_container'>
		<div class='email_content'>
			<div class='logo'>".SITE_NAME."</div>
			<p />
";

$footer = "";

class Email{
	
	public function send_email($to, $from, $subject, $message,  $fromname = SITE_NAME,  $toname='') {
 		if($toname) {
			$to = '=?UTF-8?B?'.base64_encode($toname).'?= <' . $to . '>';
		}
		if($fromname) {
			$from = '=?UTF-8?B?'.base64_encode($fromname).'?= <' . $from . '>';
		}
		$headers = 'From: '.$from."\r\n".
		// Uncomment below line to have all emails Blind Caron Coppied to the site owners email address. (Warning: Site owner will recieve every email sent from the site including emails containing unencrypted passwords.)
		// 'Bcc: '.SITE_OWNER."\r\n" .
		"MIME-Version: 1.0\r\n" .
		"Content-Type: text/html; charset=UTF-8; format=flowed\r\n" .
		"Content-Transfer-Encoding: 8Bit\r\n" .
		'X-Mailer: PHP/' . phpversion();
		
		$subject = '=?UTF-8?B?'.base64_encode($subject).'?=';
		
		//send email
		//mail($to, $subject, $message, $headers);
	}

	public function email_template($template_name, $plain_password="", $username="", $email="", $hash="", $message="") {
		global $stylesheet;
		global $header;
		global $footer;
		
		if ($template_name == "registration_success") {
			// registration success template.
			$message = "
			{$stylesheet}
			{$header}
					<p>您的账户已经在 <a href='".WWW."'>".SITE_NAME."</a> 创建成功！</p>
					<p>用户名: {$username}
					<br />
					密码: {$plain_password} (数据库中已加密)</p>
			{$footer}";
			return $message;
		} else if ($template_name == "registration_activation") {
			// template asking the user to activate their account.
			$message = "
			{$stylesheet}
			{$header}
					<p>您的账户已经在 <a href='".WWW."'>".SITE_NAME."</a> 创建成功！</p>
					<p>用户名: {$username}
					<br />
					密码: {$plain_password} (数据库中已加密)</p>
					<p>但是您仍然需要激活您的帐户，您可以点击以下链接：<br />
					<a href='".WWW."activate.php?email={$email}&hash={$hash}'>激活帐户</a></p>
					<p>如果你不能点击上面的链接，你仍然可以激活你的帐户通过下面的链接。</p>
					<p>网址： ".WWW."activate.php <br />
					确认码：{$hash}</p>
			{$footer}";
			return $message;
		} else if ($template_name == "resend_activation_code") {
			// template asking the user to activate their account.
			$message = "
			{$stylesheet}
			{$header}
					<p>您可以激活您的帐户通过点击下面的链接：<br />
					<a href='".WWW."activate.php?email={$email}&hash={$hash}'>激活帐户</a></p>
					<p>如果你不能点击上面的链接，你仍然可以激活你的帐户通过下面的链接。</p>
					<p>网址： ".WWW."activate.php <br />
					确认码：{$hash}</p>
			{$footer}";
			return $message;
		} else if ($template_name == "update_all_account_settings") {
			// all account settings updated.
			$message = "
			{$stylesheet}
			{$header}
					<p>您的帐户设置已经成功修改。</p>
					<p>密码：{$plain_password} (数据库中已加密)</p>
			{$footer}";
			return $message;
		} else if ($template_name == "update_account_settings") {
			// all account settings apart from new password updated.
			return "
			{$stylesheet}
			{$header}
					<p>您的帐户设置已经成功修改。</p>
			{$footer}";
			// return $message;
		} else if ($template_name == "reset_password") {
			// template asking the user to activate their account.
			$message = "
			{$stylesheet}
			{$header}
					<p>您的帐户已请求新密码在 <a href='".WWW."'>".SITE_NAME."</a>。</p>
					<p>但是您需要确认这个行为通过点击下面的链接：<br />
					<a href='".WWW."reset_password.php?email={$email}&hash={$hash}'>重设密码</a></p>
					<p>如果您不能点击上面的链接，您仍然可以请求一个新密码。</p>
					<p>网址：".WWW."reset_password.php <br />
					确认码：{$hash}</p>
			{$footer}";
			return $message;
		} else if ($template_name == "new_password") {
			// template asking the user to activate their account.
			$message = "
			{$stylesheet}
			{$header}
					<p>您的帐户新密码是：{$plain_password}</p>
			{$footer}";
			return $message;
		} else if ($template_name == "resend_password_reset_code") {
			// template asking the user to activate their account.
			$message = "
			{$stylesheet}
			{$header}
					<p>网址: ".WWW."reset_password.php <br />
					确认码：{$hash}</p>
			{$footer}";
			return $message;
		} else if ($template_name == "resend_unlock_code") {
			// template asking the user to activate their account.
			$message = "
			{$stylesheet}
			{$header}
					<p>解锁码：{$hash}</p>
			{$footer}";
			return $message;
		} else if ($template_name == "custom_email") {
			// custom email template for email_user.php.
			$message = "
			{$stylesheet}
			{$header}
					{$message}
			{$footer}";
			return $message;
		}
		
	} // Email_Template end.

} // Class end.
