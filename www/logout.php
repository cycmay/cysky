<?php require_once("includes/inc_files.php"); 
/*
 * Copyright (c) 2012-2013 CODEC2I.NET
 * 对非商用为目的的用户采用GPL2开源协议。您可以将其在自己的服务器部署使用，但不可以修改后发布为闭源或者商业软件。以商用为目的的用户需要购买CODEC2I的商业授权，详情请邮件sv@codec2inet。使用CODEC2I众筹系统的网站需要在页面显著位置标明基于CODEC2I构建。
 * E-mail:sv@codec2i.net
 * 官方网址:http://www.codec2i.net
 */
?>
<?php	
    $session->logout();
	
	$msg = $_GET['msg'];
	
	if (isset($_GET['msg'])) {
		if ($msg == "suspended") {
			$session->message("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Your account has been suspended, please contact support.</div>");
		} else if ($msg == "not_found") {
			$session->message("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, but we are unable to find your account, please contact support.</div>");
		} else if ($msg == "maintenance") {
			$session->message("<div class='alert alert-info'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, but we are currently doing some maintenance work.</div>");
		}
	} else {
		$session->message("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>You have successfully been logged out.</div>");
	}

	redirect_to("login.php");
?>