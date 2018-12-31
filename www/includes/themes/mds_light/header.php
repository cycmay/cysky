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
	<link href="<?php echo WWW; ?>includes/global/css/custom.css" rel="stylesheet">

	<script src="<?php echo WWW; ?>includes/global/js/jquery-1.9.1.js"></script>
	<script src="assets/js/custom.js"></script>
	<script>var WWW = "<?php echo WWW ?>";</script>
	<script src="<?php echo WWW ?>includes/global/js/main.js"></script>
	<script>
	$(document).ready(function() {
		$(function() {
			$('.dropdown-toggle').dropdown();
			$('.dropdown, .dropdown input, .dropdown label').click(function(e) {
				e.stopPropagation();
			});
		});
	});
	$(function(){
		$("[rel='tooltip']").tooltip();
	});
	</script>

</head>

<body>
	<div id="header-wrapper">
		<div class="container">
			<header>
				<div class="row-fluid">
					<div class="span12">

						<div class="navbar">
							<div class="navbar-inner">
								<div class="container">
									<a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
									</a>
									<a href="<?php echo WWW; ?>" class="brand"><img src="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/img/logo.jpg" width="248" height="52" alt="Logo"></a>
									<div class="nav-collapse collapse navbar-responsive-collapse">
										<ul class="nav">
											<li<?php echo ($current_page == "home") ? " class='active'" : "" ?>><a href="<?php echo WWW; ?>index.php">首页</a></li>
											<li class="dropdown">
												<a href="#" class="dropdown-toggle" data-toggle="dropdown">搜索项目 <b class="caret"></b></a>
												<ul class="dropdown-menu">
													<form action="<?php echo WWW; ?>search.php" method="GET" id="search_form" style="margin-bottom: -8px;">
														<div class="row-fluid">
															<div class="span9 center">
														        <input type="text" class="span12" name="search" required="required" placeholder="搜索项目...">
															</div>
															<div class="span3">
														        <button class="btn btn-primary" style="margin: 0px 0px 0px -12px;" >搜索</button>
															</div>
														</div>
														<input type="hidden" class="span12" name="category" value="all">
													</form>
													<div style="text-align:center;margin-top: 3px;margin-bottom: 3px;"><a href="<?php echo WWW; ?>search.php">高级搜索</a></div>
												</ul>
											</li>
											<li<?php echo ($current_page == "create_project") ? " class='active'" : "" ?>><a href="create-project.php">创建项目</a></li>
											<li<?php echo ($current_page == "top_projects") ? " class='active'" : "" ?>><a href="top-projects.php">热门项目</a></li>
											<li<?php echo ($current_page == "top_investors") ? " class='active'" : "" ?>><a href="top-investors.php">支持者</a></li>
                      <li<?php echo ($current_page == "contact") ? " class='active'" : "" ?>><a href="contact.php">联系我们</a></li>
											<?php if($user->staff == 1){ echo '<li><a href="'.WWW.ADMINDIR.'">管理平台</a></li>'; } ?>
										</ul>
										<ul class="nav pull-right">
											<?php if($session->is_logged_in()) { ?>
												<li class="dropdown">
										          <a href="" class="dropdown-toggle" data-toggle="dropdown"><?php echo $user->username; ?><b class="caret"></b></a>
										          <ul class="dropdown-menu">
														<li><a href="<?php echo WWW; ?>settings.php">设置</a></li>
														<li><a href="<?php echo WWW; ?>profile.php?username=<?php echo $user->username; ?>">我的资料</a></li>
														<!--<li><a href="<?php echo WWW; ?>my_questions.php">My Questions</a></li>-->
														<li class="divider"></li>
														<li><a href="<?php echo WWW; ?>logout.php">退出</a></li>
										          </ul>
										        </li>
											<?php } else { ?>
												<li class="dropdown">
													<a href="#" class="dropdown-toggle" data-toggle="dropdown">登录 <b class="caret"></b></a>
													<ul class="dropdown-menu">
														<div id="login_form" onkeypress="if(event.keyCode == 13){login()}">
															<div id="message"></div>
															<div class="row-fluid">
																<div class="span12 center">
															        <input type="text" class="span12" id="username" required="required" placeholder="用户名">
																</div>
															</div>
															<div class="row-fluid">
																<div class="span12">
															        <input type="password" class="span12" id="password" required="required" placeholder="密码">
																</div>
															</div>
															<div class="row-fluid">
																<div class="span12">
																	<input type="checkbox" id="remember_me" />
																	<span>记得我?(使用Cookie)</span>
																</div>
															</div>
															<div class="row-fluid">
																<div class="span12">
																	<a href="reset_password.php">忘记密码?</a>
																</div>
															</div>
															<div class="row-fluid">
																<div class="span12">
																	<button class="btn btn-primary" type="submit" id="login_btn" onclick="login()" style="width: 100%">登录</button>
																</div>
															</div>

															<?php if(OAUTH == "ON"){ ?>
															<hr />

															<div class="row-fluid">
																<div class="span12 center">
																	<div style="margin-bottom: 5px;">
																		<a href="<?php echo WWW; ?>auth/sinaweibo" class="zocial sinaweibo">新浪微博</a>
																	</div>
																</div>
															</div>
															<div class="row-fluid">
																<div class="span12 center">
																	<div style="margin-bottom: 5px;">
																		<a href="<?php echo WWW; ?>auth/qqweibo" class="zocial qqweibo">腾讯微博</a>
																	</div>
																</div>
															</div>
															<div class="row-fluid">
																<div class="span12 center">
																	<div style="margin-bottom: 5px;">
																		<a href="<?php echo WWW; ?>auth/facebook" class="zocial facebook">Facebook</a>
																	</div>
																</div>
															</div>
															<div class="row-fluid">
																<div class="span12 center">
																	<a href="<?php echo WWW; ?>auth/twitter" class="zocial twitter">Twitter</a>
																</div>
															</div>

															<?php } ?>

														</div>
													</ul>
												</li>
												<li><a href="register.php">注册</a></li>
											<?php } ?>
										</ul>
									</div><!-- /.nav-collapse -->
								</div>
							</div>
						</div>

					</div>
				</div>
			</header>
		</div>
	</div>

	<div class="container">
		<div id="content" class="settings">

	<!-- Header End -->