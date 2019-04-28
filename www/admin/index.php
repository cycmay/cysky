<?php 


require_once("../includes/inc_files.php"); 
require_once("../includes/classes/admin.class.php");

if(!$session->is_logged_in()) {redirect_to("../login.php");}

$admin = User::find_by_id($_SESSION['user_id']);

$active_page = "overview";

?>

<?php $page_title = "管理面板"; require_once("../includes/themes/".THEME_NAME."/admin_header.php"); ?>

	<div class="row-fluid">
		<?php require_once("../includes/global/admin_nav.php"); ?>
	</div>
	<div class="row-fluid">
	<div class="span12">
		<div class="title">
			<h1><?php echo $page_title; ?></h1>
		</div>
	   
		<div class="row-fluid">
			<div class="span4">
				<h2>用户性别</h2>
				<table class="table table-bordered">
				  <tbody>
				    <tr>
				      <td><?php echo gen_male; ?></td>
						<td><?php echo Admin::count_users('gender','<?php echo gen_male; ?>'); ?></td>
				    </tr>
					<tr>
				      <td><?php echo gen_female; ?></td>
						<td><?php echo Admin::count_users('gender','<?php echo gen_female; ?>'); ?></td>
				    </tr>
					<tr>
				      <td><strong><?php echo lang_total; ?>:</strong></td>
						<td><?php echo Admin::count_all_users(); ?></td>
				    </tr>
				  </tbody>
				</table>
				<p><a class="btn" href="users.php">查看所有用户 &raquo;</a></p>
			</div><!--/span-->
			<div class="span4">
				<h2>用户帐户统计</h2>
				<table class="table table-bordered">
				  <tbody>
				    <tr>
				      <td><?php echo lang_active; ?></td>
						<td><?php echo Admin::count_users('activated',1); ?></td>
				    </tr>
					<tr>
				      <td><?php echo lang_inactive; ?></td>
						<td><?php echo Admin::count_users('activated',0); ?></td>
				    </tr>
					<tr>
				      <td><?php echo lang_suspended; ?></td>
						<td><?php echo Admin::count_users('suspended',1); ?></td>
				    </tr>
					<tr>
				      <td><strong><?php echo lang_total; ?>:</strong></td>
						<td><?php echo Admin::count_all_users(); ?></td>
				    </tr>
				  </tbody>
				</table>
			</div><!--/span-->
		</div><!--/row-->

	</div><!--/span-->

	</div>

<?php require_once("../includes/themes/".THEME_NAME."/footer.php"); ?>
