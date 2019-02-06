<?php 
require_once("../includes/inc_files.php"); 
require_once("../includes/classes/admin.class.php");

if(!$session->is_logged_in()) {redirect_to("../login.php");}

$admin = User::find_by_id($_SESSION['user_id']);

$active_page = "awaiting";

$projects = Investments::get_all_awaiting_approval();

?>

<?php $page_title = "等待审核"; require_once("../includes/themes/".THEME_NAME."/admin_header.php"); ?>

	<div class="row-fluid">
		<?php require_once("../includes/global/admin_nav.php"); ?>
	</div>
	<div class="row-fluid">
	<div class="span12">
		<?php echo output_message($message); ?>
	
		<div class="title">
			<h1><?php echo $page_title; ?></h1>
		</div>
	
		<table class="table table-condensed">
			<thead>
				<tr>
					<th>项目ID</th>
					<th>项目标题</th>
					<th>创建用户ID</th>
					<th>状态</th>
					<th>目标金额</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($projects as $data) : ?>
				<tr>
					<td><?php echo $data->id ?></td>
					<td><?php echo "<a href='".WWW."project.php?id=".$data->id."'>".$data->name."</a>"; ?></td>
					<td><?php echo "<a href='".WWW."user_settings.php?user_id=".$data->creator_id."'>".$data->creator_id."</a>"; ?></td>
					<td><?php echo $data->status ? '已审核' : '等待审核'; ?></td>
					<td><?php echo $data->investment_wanted ?></td>
				</tr>
				<?php endforeach; ?>
				<?php if(empty($projects)){ ?>
				<tr>
					<td colspan="5">目前没有项目等待审核。</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	
	</div>

	</div>

<div class="clear"><!-- --></div>

<?php require_once("../includes/themes/".THEME_NAME."/footer.php"); ?>