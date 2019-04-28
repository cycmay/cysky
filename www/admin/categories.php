<?php 

require_once("../includes/inc_files.php"); 
require_once("../includes/classes/admin.class.php");

if(!$session->is_logged_in()) {redirect_to("../login.php");}

$active_page = "categories";

$categories = Investments::get_categories('confirmed');

$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 20;
$total_count = count($categories);
$pagination = new Pagination($page, $per_page, $total_count);
$sql = "SELECT * FROM categories LIMIT {$per_page} OFFSET {$pagination->offset()}";
$categories = Investments::find_by_sql($sql);

if(isset($_POST['create'])){
	$name = $_POST['name'];
	$status = $_POST['status'];
	if (DEMO_MODE == 'ON') {
		$session = new Session();
		$session->message("<div class='alert alert-warning'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, but you can't do that while demo mode is enabled.</div>");
		redirect_to("categories.php");
	} else {
		Investments::create_category($name,$status);	
	}
} else {
	$name = "";
	$status = "";
}

if(isset($_GET['edit'])){
	$id = trim($_GET['edit']);
	$category_data = Investments::get_category_data($id);
	$category_data = $category_data[0];
	if(isset($_POST['edit'])){
		$name = $_POST['name'];
		$status = $_POST['status'];
		if (DEMO_MODE == 'ON') {
			$session = new Session();
			$session->message("<div class='alert alert-warning'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, but you can't do that while demo mode is enabled.</div>");
			redirect_to("categories.php");
		} else {
			Investments::update_cateory($id,$name,$status);
		}
	} else {
		$name = $category_data->name;
		$status = $category_data->status;
	}
}

if(isset($_GET['delete'])){
	$id = trim($_GET['delete']);
	if(isset($_POST['delete'])){
		if (DEMO_MODE == 'ON') {
			$session = new Session();
			$session->message("<div class='alert alert-warning'><button type='button' class='close' data-dismiss='alert'>×</button>Sorry, but you can't do that while demo mode is enabled.</div>");
			redirect_to("categories.php");
		} else {
			Investments::delete_category($id);
		}
	}
}

?>

<?php $page_title = "分类"; require_once("../includes/themes/".THEME_NAME."/admin_header.php"); ?>

	<div class="row-fluid">
		<?php require_once("../includes/global/admin_nav.php"); ?>
	</div>
	<div class="row-fluid">
	<div class="span12">
		<?php echo output_message($message); ?>
	
		<div class="title">
			<h1><?php echo $page_title; ?> <span class="btn-group"><a data-toggle="modal" href="#create" class="btn btn-primary">创建分类</a></span></h1>
		</div>
	
		<?php if(empty($categories)){ ?>
			<strong>对不起，没有发现任何分类。</strong>
		<?php } else { ?>
		<table class="table table-condensed">
		  <thead>
		    <tr>
		      <th>ID</th>
		      <th>名称</th>
			  <th>状态</th>
			  <th>操作</th>
		    </tr>
		  </thead>
		  <tbody>
			<?php foreach($categories as $data): ?>
		    <tr>
				<td><?php echo $data->id; ?></td>
				<td><?php echo $data->name; ?></td>
				<td><?php echo Admin::convert_category_status($data->status); ?></td>
				<td><a href="categories.php?edit=<?php echo $data->id; ?>">编辑</a> - <a href="categories.php?delete=<?php echo $data->id; ?>">删除</a></td>
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
						echo " <li><a href=\"categories.php?search={$query}&amp;filter={$_GET['filter']}&amp;page={$i}\">{$i}</a></li> "; 
					}
				}

			}

			echo "</ul>";
		?>

		<?php } ?>
	

		<form action="categories.php?create=<?php if(isset($_GET['create'])) {echo $_GET['create'];} ?>" method="POST" id="create" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; ">
		    <div class="modal-header"><a href="categories.php" class="close" data-dismiss="modal">×</a>
		        <h3 id="myModalLabel">创建分类</h3>
		    </div>
		    <div class="modal-body">
		      <label>名称</label>
			   <input type="text" required="required" style="width: 98%;" name="name" value="<?php echo htmlentities($name); ?>" />
				<label>状态</label>
				<select name="status" style="width: 533px;">
					<option value="0"<?php if($status == 0){echo " selected='selected'";} ?>>不可见</option>
					<option value="1"<?php if($status == 1){echo " selected='selected'";} ?>>可见的</option>
				</select>
		    </div>
		    <div class="modal-footer">
			   <a href="categories.php" class="btn">关闭</a>
			   <button class="btn btn-danger" type="submit" name="create">创建</button>
			 </div>
		</form>​
	
		<?php if(isset($_GET['edit'])) {?>
			<form action="categories.php?edit=<?php echo $_GET['edit']; ?>" method="POST" id="edit" class="modal">
			    <div class="modal-header"><a href="categories.php" class="close" data-dismiss="modal">×</a>
			        <h3 id="myModalLabel">编辑分类</h3>
			    </div>
			    <div class="modal-body">
			      <label>名称</label>
				   <input type="text" required="required" style="width: 98%;" name="name" value="<?php echo htmlentities($name); ?>" />
					<label>状态</label>
					<select name="status" style="width: 98%;">
						<option value="0"<?php if($status == 0){echo " selected='selected'";} ?>>不可见</option>
						<option value="1"<?php if($status == 1){echo " selected='selected'";} ?>>可见的</option>
					</select>
			    </div>
			    <div class="modal-footer">
				   <a href="categories.php" class="btn">关闭</a>
				   <button class="btn btn-danger" type="submit" name="edit">确定</button>
				 </div>
			</form>​
			<div class="modal-backdrop fade in"></div>
		<?php } ?>
	
		<?php if(isset($_GET['delete'])) {?>
			<form action="categories.php?delete=<?php echo $_GET['delete']; ?>" method="POST" id="delete" class="modal">
			    <div class="modal-header"><a href="categories.php" class="close" data-dismiss="modal">×</a>
			        <h3 id="myModalLabel">删除分类</h3>
			    </div>
			    <div class="modal-body">
			      <strong>你确定你要删除这个分类吗？</strong>
			    </div>
			    <div class="modal-footer">
				   <a href="categories.php" class="btn">关闭</a>
				   <button class="btn btn-danger" type="submit" name="delete">确定</button>
				 </div>
			</form>​
			<div class="modal-backdrop fade in"></div>
		<?php } ?>
	
	</div>

	</div>
	
<div class="clear"><!-- --></div>

<?php require_once("../includes/themes/".THEME_NAME."/footer.php"); ?>