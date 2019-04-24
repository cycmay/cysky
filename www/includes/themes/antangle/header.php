<?php require_once("includes/global/header.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo $page_title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="<?php echo SITE_KEYW; ?>">
    <meta name="description" content="<?php echo SITE_DESC; ?>">
    <meta name="author" content="cycmay@gihub.com">
    <meta name="generator" content="cycmay@gihub.com" />

    <link href="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/css/main.css" rel="stylesheet">
	<link href="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/css/cui.css" rel="stylesheet">
	<link href="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/css/lib.css" rel="stylesheet">
	<link href="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/css/style.css" rel="stylesheet">

	<link href="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/css/sweet-alert.css" rel="stylesheet">
	<link href="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/css/swiper.min.css" rel="stylesheet">
	<link href="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/css/jquery.datetimepicker.css" rel="stylesheet">

	<!-- <link href="<?php echo WWW; ?>includes/global/css/custom.css" rel="stylesheet"> -->

	<script type="text/javascript" src="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/js/lib.js"></script>
	<script type="text/javascript" src="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/js/cui.js"></script>
	<script type="text/javascript" src="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/js/sweet-alert.js"></script>
	<script type="text/javascript" src="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/js/swiper.jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/js/jquery.form-validator.js"></script>
	<script type="text/javascript" src="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/js/toggleDisabled.js"></script>
	<script type="text/javascript" src="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/js/jquery.ui.widget.js"></script>

	<script type="text/javascript" src="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/js/jquery.iframe-transport.js"></script>
	<script type="text/javascript" src="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/js/jquery.fileupload.js"></script>
	<script type="text/javascript" src="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/js/jquery.form-validator.js"></script>
	<script type="text/javascript" src="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/js/jquery.datetimepicker.js"></script>
	
	<script src="assets/js/custom.js"></script>
	<script>var WWW = "<?php echo WWW ?>";</script>
	<script src="<?php echo WWW ?>includes/global/js/main.js"></script>

	<script type="text/javascript" src="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/js/myMotion.js"></script>



</head>

<body>
	<div class="fixHead" >
	    <div class="index-head ">
	        <div class="wp" style="overflow: hidden">
	            <ul>
	                <a href="/member/normal/applyInvestor.htm"><li>认证投资人</li></a>
	                <a href="create-project.php"><li>创建项目</li></a>

	                                <a href="">
	                    <!-- <li class="index-hoveLi">
	                        
	                                 <i class="xxsl">35</i> 

	                    </li> -->
	                    <?php if($session->is_logged_in()) { ?>

	                    	<li class="index-hoveLi"> <?php echo $user->username; ?> <i class="xxsl">35</i> </li>

							<!--<li class="dropdown">
					          <a href="" class="dropdown-toggle" data-toggle="dropdown"><?php echo $user->username; ?><b class="caret"></b></a>
					          <ul class="dropdown-menu">
									<li><a href="<?php echo WWW; ?>settings.php">设置</a></li>
									<li><a href="<?php echo WWW; ?>profile.php?username=<?php echo $user->username; ?>">我的资料</a></li>
									<li><a href="<?php echo WWW; ?>my_questions.php">My Questions</a></li>
									<li class="divider"></li>
									<li><a href="<?php echo WWW; ?>logout.php">退出</a></li>
					          </ul>
					        </li>
					    	-->
						<?php } else { ?>
							<li><a href="login.php">登录 </a></li>
							<li><a href="register.php">注册</a></li>
							<?php } ?>
	                </a>
	            </ul>
	        </div>
	    </div>

	    <div class="wp" style="position: relative">
	        <ul class="index-uUl">
	            <li><a href="<?php echo WWW; ?>profile.php?username=<?php echo $user->username; ?>">个人中心</a></li>
	            <a href="/member/notifies.htm"><li>消息 <!--<i class="xxsl">35</i> --></li></a>
	            <li><a href="<?php echo WWW; ?>settings.php">设置</a></li>
	            <li><a href="<?php echo WWW; ?>logout.php">退出</a></li>
	        </ul>
	    </div>

	    <div class="index-nav">
	        <div class="wp" style="margin-top: 7px">
	            <div class="l logo">
	                <a href="<?php echo WWW; ?>" class="brand"><img src="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/images/logo.jpg" width="248" height="52" alt="Logo"></a>
	            </div>
	            <ul class="nav-ul">
	                <a href="/index.htm"><li class="checkedLi"><i class="index-syico"></i>首页</li></a>
	                <a href="/project/project.htm"><li >全部项目</li></a>
	                <a href="/allLeadingInventors.htm"><li >投资人</li></a>
	                <a href="/activity/miaotou.htm"><li >秒投日</li></a>
	                <a href="/mayicollege/jackaroo.htm" ><li >SKR学院</li></a>
	            </ul>
	        </div>
	    </div>
	 </div>

	<script>
	    $(".index-head ul li").hover(function(){
	        $(".index-uUl").fadeIn(0);
	    },function(){
	        $(".index-uUl").fadeOut(0);
	    })
	    $(".index-uUl").hover(function(){
	        $(".index-uUl").fadeIn(0);
	    },function(){
	        $(".index-uUl").fadeOut(0);
	    })
	    function la(){
	        var popUp = document.getElementById("popupcontent");
	        popUp.style.visibility = "visible";
	    }
	    function xz(){
	        window.location.href='https://www.mayiangel.com/mayicollege/news/179.htm';
	    }
	    function jx(){
	        window.location.href='/member/createNewProject.htm';
	    }
	</script>

	<div class="container">
		<div id="content" class="settings">

	<!-- Header End -->