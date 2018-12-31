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

$active_page = "credits";

$credit_packages = User::get_credit_packages();

$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 20;
$total_count = count($credit_packages);
$pagination = new Pagination($page, $per_page, $total_count);
$sql = "SELECT * FROM credit_packages LIMIT {$per_page} OFFSET {$pagination->offset()}";
$credit_packages = User::find_by_sql($sql);

if(isset($_POST['create'])){
	$name = $_POST['name'];
	$qty = $_POST['qty'];
	$status = $_POST['status'];
	if (DEMO_MODE == 'ON') {
		$session = new Session();
		$session->message("<div class='alert alert-warning'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, but you can't do that while demo mode is enabled.</div>");
		redirect_to("credits.php");
	} else {
		User::create_package($name,$qty,$status);	
	}
} else {
	$name = "";
	$qty = "";
	$status = "";
}

if(isset($_GET['edit'])){
	$id = trim($_GET['edit']);
	$package_data = User::get_package_data($id);
	$package_data = $package_data[0];
	if(isset($_POST['edit'])){
		$name = $_POST['name'];
		$qty = $_POST['qty'];
		$status = $_POST['status'];
		if (DEMO_MODE == 'ON') {
			$session = new Session();
			$session->message("<div class='alert alert-warning'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, but you can't do that while demo mode is enabled.</div>");
			redirect_to("credits.php");
		} else {
			User::edit_credit_package($id,$name,$qty,$status);
		}
	} else {
		$name = $package_data->name;
		$qty = $package_data->qty;
		$status = $package_data->status;
	}
}

if(isset($_GET['delete'])){
	$id = trim($_GET['delete']);
	if(isset($_POST['delete'])){
		if (DEMO_MODE == 'ON') {
			$session = new Session();
			$session->message("<div class='alert alert-warning'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, but you can't do that while demo mode is enabled.</div>");
			redirect_to("credits.php");
		} else {
			User::delete_credit_package($id);
		}
	}
}

?>

<?php $page_title = "积分套餐"; require_once("../includes/themes/".THEME_NAME."/admin_header.php"); ?>

	<div class="row-fluid">
		<?php require_once("../includes/global/admin_nav.php"); ?>
	</div>
	<div class="row-fluid">
	<div class="span12">
		<?php echo output_message($message); ?>
	
		<div class="title">
			<h1><?php echo $page_title; ?> <span class="btn-group"><a data-toggle="modal" href="#create" class="btn btn-primary">创建套餐</a></span></h1>
		</div>
	
		<?php if(empty($credit_packages)){ ?>
			<strong>对不起，没有发现积分套餐。</strong>
		<?php } else { ?>
		<table class="table table-condensed">
		  <thead>
		    <tr>
		      <th>ID</th>
		      <th>名称</th>
		      <th>数量</th>
				<th>状态</th>
				<th>操作</th>
		    </tr>
		  </thead>
		  <tbody>
			<?php foreach($credit_packages as $data): ?>
		    <tr>
				<td><?php echo $data->id; ?></td>
				<td><?php echo $data->name; ?></td>
				<td><?php echo $data->qty; ?></td>
				<td><?php echo User::convert_credit_status($data->status); ?></td>
				<td><a href="credits.php?edit=<?php echo $data->id; ?>">编辑</a> - <a href="credits.php?delete=<?php echo $data->id; ?>">删除</a></td>
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
						echo " <li><a href=\"find.php?search={$query}&amp;filter={$_GET['filter']}&amp;page={$i}\">{$i}</a></li> "; 
					}
				}

			}

			echo "</ul>";
		?>

		<?php } ?>
	

		<form action="credits.php?create=<?php echo $_GET['create']; ?>" method="POST" id="create" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; ">
		    <div class="modal-header"><a href="credits.php" class="close" data-dismiss="modal">×</a>
		        <h3 id="myModalLabel">创建套餐</h3>
		    </div>
		    <div class="modal-body">
		      <label>套餐名称</label>
			   <input type="text" required="required" style="width: 98%;" name="name" value="<?php echo $name; ?>" />
			   <label>积分数量</label>
			   <input type="text" required="required" style="width: 98%;" name="qty" value="<?php echo htmlentities($qty); ?>" />
				<label>状态</label>
				<select name="status" style="width: 533px;">
					<option value="0"<?php if($status == 0){echo " selected='selected'";} ?>>不可见</option>
					<option value="1"<?php if($status == 1){echo " selected='selected'";} ?>>可见的</option>
				</select>
		    </div>
		    <div class="modal-footer">
			   <a href="credits.php" class="btn">关闭</a>
			   <button class="btn btn-danger" type="submit" name="create">创建</button>
			 </div>
		</form>​
	
		<?php if(isset($_GET['edit'])) {?>
			<form action="credits.php?edit=<?php echo $_GET['edit']; ?>" method="POST" id="edit" class="modal">
			    <div class="modal-header"><a href="credits.php" class="close" data-dismiss="modal">×</a>
			        <h3 id="myModalLabel">编辑套餐</h3>
			    </div>
			    <div class="modal-body">
			      <label>套餐名称</label>
				   <input type="text" required="required" style="width: 98%;" name="name" value="<?php echo $name; ?>" />
				   <label>积分数量</label>
				   <input type="text" required="required" style="width: 98%;" name="qty" value="<?php echo htmlentities($qty); ?>" />
					<label>状态</label>
					<select name="status" style="width: 98%;">
						<option value="0"<?php if($status == 0){echo " selected='selected'";} ?>>不可见</option>
						<option value="1"<?php if($status == 1){echo " selected='selected'";} ?>>可见的</option>
					</select>
			    </div>
			    <div class="modal-footer">
				   <a href="credits.php" class="btn">关闭</a>
				   <button class="btn btn-danger" type="submit" name="edit">确定</button>
				 </div>
			</form>​
			<div class="modal-backdrop fade in"></div>
		<?php } ?>
	
		<?php if(isset($_GET['delete'])) {?>
			<form action="credits.php?delete=<?php echo $_GET['delete']; ?>" method="POST" id="delete" class="modal">
			    <div class="modal-header"><a href="credits.php" class="close" data-dismiss="modal">×</a>
			        <h3 id="myModalLabel">删除套餐</h3>
			    </div>
			    <div class="modal-body">
			      <strong>确定你要删除这个套餐吗？</strong>
			    </div>
			    <div class="modal-footer">
				   <a href="credits.php" class="btn">关闭</a>
				   <button class="btn btn-danger" type="submit" name="delete">确定</button>
				 </div>
			</form>​
			<div class="modal-backdrop fade in"></div>
		<?php } ?>
	
	</div>

	</div>


<div class="clear"><!-- --></div>

<?php require_once("../includes/themes/".THEME_NAME."/footer.php"); ?>