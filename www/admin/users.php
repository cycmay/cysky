<?php 

/*
 * Copyright (c) 2012-2013 CODEC2I.NET
 * 对非商用为目的的用户采用GPL2开源协议。您可以将其在自己的服务器部署使用，但不可以修改后发布为闭源或者商业软件。以商用为目的的用户需要购买CODEC2I的商业授权，详情请邮件sv@codec2inet。使用CODEC2I众筹系统的网站需要在页面显著位置标明基于CODEC2I构建。
 * E-mail:sv@codec2i.net
 * 官方网址:http://www.codec2i.net
 */

require_once("../includes/inc_files.php"); 
require_once("../includes/classes/admin.class.php");

if(!$session->is_logged_in()) {redirect_to("../login.php");}

$admin = User::find_by_id($_SESSION['user_id']);
$users = User::find_all();

$active_page = "users";

if(isset($_GET['search']) && $_GET['search'] != ""){
	$search = true;
	$query = preg_replace('#[^a-z 0-9?!]#i', '', $_GET['search']);
	if($_GET['filter'] == "username"){
		$sql = "SELECT * FROM users WHERE username LIKE '%$query%'";
	} else if($_GET['filter'] == "first name") {
		$sql = "SELECT * FROM users WHERE first_name LIKE '%$query%'";
	} else if($_GET['filter'] == "last name") {
		$sql = "SELECT * FROM users WHERE last_name LIKE '%$query%'";
	} else if($_GET['filter'] == "full name"){
		$sql = "SELECT * FROM users WHERE CONCAT(first_name,' ',last_name) like '%$query%'";
	} else if($_GET['filter'] == "user_id"){
		$sql = "SELECT * FROM users WHERE user_id LIKE '%$query%'";
	} else if($_GET['filter'] == "country"){
		$sql = "SELECT * FROM users WHERE country LIKE '%$query%'";
	}
	$query_data = User::find_by_sql($sql);
	
	$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
	$per_page = PAGINATION_PER_PAGE;
	$total_count = count($query_data);
	$pagination = new Pagination($page, $per_page, $total_count);
	$sql .= " LIMIT {$per_page} OFFSET {$pagination->offset()}";
	$query_data = User::find_by_sql($sql);
} else {
	$search = true;
	$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
	$per_page = PAGINATION_PER_PAGE;
	$total_count = User::count_all();
	$pagination = new Pagination($page, $per_page, $total_count);
	$sql = "SELECT * FROM users LIMIT {$per_page} OFFSET {$pagination->offset()}";
	$query_data = User::find_by_sql($sql);
}

?>

<?php $page_title = "用户管理"; require_once("../includes/themes/".THEME_NAME."/admin_header.php"); ?>

	<div class="row-fluid">
		<?php require_once("../includes/global/admin_nav.php"); ?>
	</div>
	<div class="row-fluid">
	<div class="span12">
		<?php echo output_message($message); ?>
	
		<div class="title">
			<h1><?php echo $page_title; ?></h1>
		</div>
	
		<form action="users.php" method="GET" class="form-search">
			<input type="text" placeholder="搜索..." name="search" class="input-xlarge">
			<select name="filter">
				<option value="full name">姓名</option>
				<option value="username">用户名</option>
				<option value="user_id">用户ID</option>
				<!--<option value="country">Country</option>-->
			</select>
			<button type="submit" class="btn">搜索</button>
		</form>
	
		<table class="table table-condensed">
			<thead>
				<tr>
					<th><?php echo table_id; ?></th>
					<th><?php echo table_username; ?></th>
					<th><?php echo table_email; ?></th>
					<th><?php echo table_activated; ?></th>
					<th><?php echo table_suspended; ?></th>
					<th><?php echo table_edit; ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($query_data as $user) : ?>
				<tr>
					<td><?php echo $user->user_id ?></td>
					<td><?php echo $user->username ?></td>
					<td><?php echo $user->email ?></td>
					<td><?php echo convert_boolean($user->activated) ?></td>
					<td><?php echo convert_boolean_sus($user->suspended) ?></td>
					<td><a href="user_settings.php?user_id=<?php echo $user->user_id ?>"><img src="../assets/img/pencil.png" alt="edit" class="edit_button" /></a></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
			if($pagination->total_pages() > 1) {
			echo "<div class='pagination pagination-centered'><ul>";

				for($i=1; $i <= $pagination->total_pages(); $i++) {
					if($i == $page) {
						echo " <li class='active'><a>{$i}</a></li> ";
					} else {
						echo " <li><a href=\"users.php?page={$i}\">{$i}</a></li> "; 
					}
				}

			}

			echo "</ul>";
		?>
	
	</div>

	</div>

<div class="clear"><!-- --></div>

<?php require_once("../includes/themes/".THEME_NAME."/footer.php"); ?>