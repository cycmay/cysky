<?php

if (__FILE__ == $_SERVER["SCRIPT_FILENAME"]) exit("No direct access allowed.");

/*
 * Copyright (c) 2012-2013 CODEC2I.NET
 * 对非商用为目的的用户采用GPL2开源协议。您可以将其在自己的服务器部署使用，但不可以修改后发布为闭源或者商业软件。以商用为目的的用户需要购买CODEC2I的商业授权，详情请邮件sv@codec2inet。使用CODEC2I众筹系统的网站需要在页面显著位置标明基于CODEC2I构建。
 * E-mail:sv@codec2i.net
 * 官方网址:http://www.codec2i.net
 */

ob_start();
ob_clean();
session_start();
defined("DB_SERVER") ? null : define("DB_SERVER", "localhost");
defined("DB_USER")   ? null : define("DB_USER", "root");
defined("DB_PASS")   ? null : define("DB_PASS", "19971215");
defined("DB_NAME")   ? null : define("DB_NAME", "democyc");
require("core_settings.class.php");
$core_settings = Core_Settings::find_by_sql("SELECT * FROM core_settings");
$count = count($core_settings);
for($i=0;$i <= $count-1;$i++){
	defined($core_settings[$i]->name) ? null : define($core_settings[$i]->name, $core_settings[$i]->data);
}
defined("IMAGES") ? null : define("IMAGES", WWW."img/"); 
date_default_timezone_set(TIMEZONE);

defined("AUTHPATH")   ? null : define("AUTHPATH", "/auth/");
defined("AUTHSALT")   ? null : define("AUTHSALT", "PASTE_RANDOM_CODE_HERE");

defined("FACEBOOK_APP_ID")   ? null : define("FACEBOOK_APP_ID", "HERE");
defined("FACEBOOK_APP_SECRET")   ? null : define("FACEBOOK_APP_SECRET", "HERE");

defined("TWITTER_CONSUMER_KEY")   ? null : define("TWITTER_CONSUMER_KEY", "HERE");
defined("TWITTER_CONSUMER_SECRET")   ? null : define("TWITTER_CONSUMER_SECRET", "HERE");

?>
