<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

/*
 * Copyright (c) 2012-2013 CODEC2I.NET
 * 对非商用为目的的用户采用GPL2开源协议。您可以将其在自己的服务器部署使用，但不可以修改后发布为闭源或者商业软件。以商用为目的的用户需要购买CODEC2I的商业授权，详情请邮件sv@codec2inet。使用CODEC2I众筹系统的网站需要在页面显著位置标明基于CODEC2I构建。
 * E-mail:sv@codec2i.net
 * 官方网址:http://www.codec2i.net
 */

defined("aa_title") ? null : define("aa_title", "管理面板");
defined("aa_overview") ? null : define("aa_overview", "概览");
defined("aa_users") ? null : define("aa_users", "用户");
defined("aa_credits") ? null : define("aa_credits", "积分");
defined("aa_categories") ? null : define("aa_categories", "分类");
defined("aa_projects") ? null : define("aa_projects", "项目");
defined("aa_awaiting_review") ? null : define("aa_awaiting_review", "等待审核");
defined("aa_payout_requests") ? null : define("aa_payout_requests", "支付请求");
defined("aa_settings") ? null : define("aa_settings", "设置");
defined("aa_site_settings") ? null : define("aa_site_settings", "站点配置");
defined("aa_credit_packages") ? null : define("aa_credit_packages", "Credit Packages");

defined("table_id") ? null : define("table_id", "ID");
defined("table_username") ? null : define("table_username", "用户名");
defined("table_email") ? null : define("table_email", "电邮");
defined("table_activated") ? null : define("table_activated", "激活的");
defined("table_suspended") ? null : define("table_suspended", "禁止的");
defined("table_edit") ? null : define("table_edit", "编辑");

defined("gen_male") ? null : define("gen_male", "男");
defined("gen_female") ? null : define("gen_female", "女");

defined("lang_total") ? null : define("lang_total", "总共");
defined("lang_active") ? null : define("lang_active", "激活");
defined("lang_inactive") ? null : define("lang_inactive", "未激活");
defined("lang_suspended") ? null : define("lang_suspended", "禁止");

defined("lang_account_info") ? null : define("lang_account_info", "账号信息");
defined("lang_send_new_password") ? null : define("lang_send_new_password", "发送新密码");
defined("lang_login_as_user") ? null : define("lang_login_as_user", "登陆");
defined("lang_access_logs") ? null : define("lang_access_logs", "访问日志");
defined("lang_email_user") ? null : define("lang_email_user", "电邮给用户");
defined("lang_delete_account") ? null : define("lang_delete_account", "删除账户");
defined("lang_first_name") ? null : define("lang_first_name", "姓");
defined("lang_last_name") ? null : define("lang_last_name", "姓名");
defined("lang_gender") ? null : define("lang_gender", "性别");
defined("lang_country") ? null : define("lang_country", "国家");
defined("lang_email_address") ? null : define("lang_email_address", "电邮地址");
defined("lang_username") ? null : define("lang_username", "用户名");
defined("lang_password") ? null : define("lang_password", "密码");
defined("lang_new_password") ? null : define("lang_new_password", "新密码");
defined("lang_repeat_password") ? null : define("lang_repeat_password", "确认密码");
defined("lang_ip_protection") ? null : define("lang_ip_protection", "IP 防护");
defined("lang_ip_whitelist") ? null : define("lang_ip_whitelist", "IP 白名单");
defined("lang_signup_date") ? null : define("lang_signup_date", "注册日期");
defined("lang_last_login") ? null : define("lang_last_login", "最后登陆时间");
defined("lang_signup_ip") ? null : define("lang_signup_ip", "注册IP");
defined("lang_last_ip") ? null : define("lang_last_ip", "最后登陆IP");
defined("lang_user_id") ? null : define("lang_user_id", "用户ID");
defined("lang_activated") ? null : define("lang_activated", "激活的");
defined("lang_suspended") ? null : define("lang_suspended", "禁止的");
defined("lang_staff") ? null : define("lang_staff", "员工");
defined("lang_active_credit") ? null : define("lang_active_credit", "有效积分");
defined("lang_credit_in_bank") ? null : define("lang_credit_in_bank", "Credit in Bank");
defined("lang_investments_made") ? null : define("lang_investments_made", "已支持总金额");
defined("lang_amount_invested") ? null : define("lang_amount_invested", "支持总金额");
defined("lang_update_settings") ? null : define("lang_update_settings", "更新设置");

?>