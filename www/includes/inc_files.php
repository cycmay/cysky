<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

if(is_dir("install")){
	header("Content-type: text/html; charset=utf-8"); 
	echo "请点击 <a href='install/'>这里</a> 来安装系统。如果你已经完成安装，请删除安装目录并刷新此页面。";
    exit;
} 

require_once("configuration/config.php");
require_once("classes/database.class.php");
require_once("classes/functions.class.php");
require_once("classes/pagination.class.php");
require_once("classes/session.class.php");
require_once("classes/user.class.php");
require_once("classes/email.class.php");
require_once("classes/activation.class.php");
require_once("classes/reset_password.class.php");
require_once("classes/credit_bank.class.php");
require_once("classes/profile.class.php");
//require_once("classes/paypal.class.php");
require_once("classes/alipay.class.php");
require_once("classes/investments.class.php");
require_once("classes/api.class.php");

require_once("language/en_language.php");