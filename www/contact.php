<?php

/*
 * Copyright (c) 2012-2013 CODEC2I.NET
 * 对非商用为目的的用户采用GPL2开源协议。您可以将其在自己的服务器部署使用，但不可以修改后发布为闭源或者商业软件。以商用为目的的用户需要购买CODEC2I的商业授权，详情请邮件sv@codec2inet。使用CODEC2I众筹系统的网站需要在页面显著位置标明基于CODEC2I构建。
 * E-mail:sv@codec2i.net
 * 官方网址:http://www.codec2i.net
 */

require_once("includes/inc_files.php");

if($session->is_logged_in()) {$user = User::find_by_id($_SESSION['user_id']);}

$location = "contact.php";

$current_page = "contact";

if (isset($_POST['submit'])) { 
		
	$name = trim($_POST['name']);
	$email = trim($_POST['email']);
	$subject = trim($_POST['subject']);
	$mess = trim($_POST['mess']);
	
	if ((!$name == '') && (!$email == '') && (!$subject == '') && (!$mess == '')) {
		// $message = "Success";
		
		$headers = "From: {$email}\r\n".
		"MIME-Version: 1.0\r\n" .
		"Content-Type: text/html; charset=UTF-8; format=flowed\r\n" .
		"Content-Transfer-Encoding: 8Bit\r\n" .
		'X-Mailer: PHP/' . phpversion();
		
		$current_ip = $_SERVER['REMOTE_ADDR'];
		
		$html_message = nl2br($mess);
		
		$sub = "联系表单: ".$subject;
		$sub = '=?UTF-8?B?'.base64_encode($sub).'?=';
		//send email
		$to = $from = '=?UTF-8?B?'.base64_encode(SITE_NAME).'?= <' . SITE_EMAIL . '>';
		$the_mess = "IP: ".$current_ip." <br />
				来自: ".$email."<br />
				消息: <p />"."$html_message";
					
		mail($to, $sub, $the_mess, $headers);
	
		$message = "<div class='notification-box success-notification-box'><p>谢谢，您的信息已经被发送成功。</p><a href='#' class='notification-close success-notification-close'>x</a></div><!--.notification-box .notification-box-success end-->";		
		
	} else {
		$message = "<div class='notification-box error-notification-box'><p>请填写所有必填字段。</p><a href='#' class='notification-close error-notification-close'>x</a></div><!--.notification-box .notification-box-error end-->";
	}
  
} else {
  $name = "";
  $email = "";
  $subject = "";
  $mess = "";
  $message = "";
}

?>

<?php $page_title = "联系我们"; require_once("includes/themes/".THEME_NAME."/header.php"); ?>

<div class="title">
	<h1><?php echo $page_title; ?></h1>
</div>

	<?php echo output_message($message); ?>

	<form action="<?php echo $location; ?>" method="post" class="form-horizontal">
	
	<div class="row-fluid">
		<div class="span4">
			<input type="text" name="name" class="span12" required="required" placeholder="真实姓名" value="<?php echo $name; ?>" />
		</div>
		<div class="span4">
			<input type="email" name="email" class="span12" required="required" placeholder="电邮地址" value="<?php echo htmlentities($email); ?>" />
		</div>
		<div class="span4">
			<input type="text" name="subject" class="span12" required="required" placeholder="标题" value="<?php echo $subject; ?>" />
		</div>
	</div>
	<br />
	<div class="row-fluid">
		<div class="span12">
			<textarea type="text" class="span12" style="height:111px;" name="mess" placeholder="内容" required="required"><?php echo $mess; ?></textarea>
		</div>
	</div>

	<div class="clear"></div>
	<div class="form-actions" style="text-align: center;">
		<input class="btn btn-primary" type="submit" name="submit" value="发送消息" />
	</div>

	</form>


<?php require_once("includes/themes/".THEME_NAME."/footer.php"); ?>