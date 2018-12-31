<?php require_once("includes/inc_files.php"); 

/*
 * Copyright (c) 2012-2013 CODEC2I.NET
 * 对非商用为目的的用户采用GPL2开源协议。您可以将其在自己的服务器部署使用，但不可以修改后发布为闭源或者商业软件。以商用为目的的用户需要购买CODEC2I的商业授权，详情请邮件sv@codec2inet。使用CODEC2I众筹系统的网站需要在页面显著位置标明基于CODEC2I构建。
 * E-mail:sv@codec2i.net
 * 官方网址:http://www.codec2i.net
 */

if($session->is_logged_in()) {
	$user = User::find_by_id($_SESSION['user_id']);
}

$current_page = "find";

if(isset($_GET['search'])){
	$search = true;
	$query = preg_replace('#[^a-z 0-9?!]#i', '', $_GET['search']);
	$end = "";
		
	if($_GET['category'] != "all"){
		$category_id = $_GET['category'];
		$end .= "category_id = '".$category_id."' ";
	}

	if(empty($_GET['filter']) || $_GET['filter'] == "title"){
		if($end != ""){
			$end = "AND ".$end;
		}
		$sql = "SELECT id,name,investment_wanted,amount_invested,description,thumbnail,expires FROM investments WHERE status = '1' AND name LIKE '%$query%' $end ORDER BY created DESC";
	} else if($_GET['filter'] == "description") {
		if($end != ""){
			$end = "AND ".$end;
		}
		$sql = "SELECT id,name,investment_wanted,amount_invested,description,thumbnail,expires FROM investments WHERE status = '1' AND description LIKE '%$query%' $end ORDER BY created DESC";
	} else if($_GET['filter'] == "started_by") {
		$search_username = User::find_id_by_username($query);
		if($end != ""){
			$end = "AND ".$end;
		}
		$sql = "SELECT id,name,investment_wanted,amount_invested,description,thumbnail,expires FROM investments WHERE status = '1' AND creator_id LIKE '%$search_username%' $end ORDER BY created DESC";
	} else {
		if($end != ""){
			$end = "AND ".$end;
		}
		
		$sql = "SELECT id,name,investment_wanted,amount_invested,description,thumbnail,expires FROM investments WHERE status = '1' $end ORDER BY created DESC";
	}
	
	$query_data = Investments::find_by_sql($sql);
	
	$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
	$per_page = 20;
	$total_count = count($query_data);
	$pagination = new Pagination($page, $per_page, $total_count);
	$sql .= " LIMIT {$per_page} OFFSET {$pagination->offset()}";
	$query_data = Investments::find_by_sql($sql);
} else {
	$search = false;
	$query_data = Investments::find_by_sql("SELECT id,name,investment_wanted,amount_invested,description,thumbnail,expires FROM investments WHERE status = '1' ORDER BY created DESC");
	$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
	$per_page = 20;
	$total_count = count($query_data);
	$pagination = new Pagination($page, $per_page, $total_count);
	$sql = "SELECT id,name,investment_wanted,amount_invested,description,thumbnail,expires FROM investments WHERE status = '1' ORDER BY created DESC LIMIT {$per_page} OFFSET {$pagination->offset()}";
	$query_data = Investments::find_by_sql($sql);	
}

if(!isset($query)){
	$query = "";
}

?>

<?php $page_title = "搜索项目"; require_once("includes/themes/".THEME_NAME."/header.php"); ?>

<div class="title">
	<h1><?php echo $page_title; ?></h1>
</div>

	<?php echo output_message($message); ?>
	
	<form action="search.php" method="GET" class="form-search">
		<input type="text" placeholder="搜索项目..." name="search" class="input-xlarge" style="width:250px;">
		<select name="filter" style="width: 150px;">
			<option value="title" <?php if(isset($_GET['filter']) && $_GET['filter'] == "title"){echo "selected=\"selected\" ";} ?>>标题</option>
			<option value="description" <?php if(isset($_GET['filter']) && $_GET['filter'] == "description"){echo "selected=\"selected\" ";} ?>>描述</option>
			<option value="started_by" <?php if(isset($_GET['filter']) && $_GET['filter'] == "started_by"){echo "selected=\"selected\" ";} ?>>开始时间</option>
		</select>
		<select name="category">
			<option value="all" <?php if(isset($_GET['category']) && $_GET['category'] == "all"){echo "selected=\"selected\" ";} ?>>所有分类</option>
			<?php foreach(Investments::get_categories() as $category){ ?>
				<option value="<?php echo $category->id; ?>" <?php if(isset($_GET['category']) && $_GET['category'] == $category->id){echo "selected=\"selected\" ";} ?>><?php echo $category->name; ?></option>
			<?php } ?>
		</select>
		<button type="submit" class="btn btn-primary">搜索</button>
	</form>
	
	<div class="row-fluid">

		<div class="span3">
			<div class="well" style="max-width: 340px; padding: 8px 0;">
				<ul class="nav nav-list">
					<li class="nav-header">分类</li>
					<?php foreach(Investments::get_categories() as $category){ ?>
					<li <?php if(isset($_GET['category']) && $_GET['category'] == $category->id){echo "class=\"active\" ";} ?>><a href="<?php echo WWW."search.php?search=&filter=&category=".$category->id; ?>"><?php echo $category->name; ?></a></li>
					<?php } ?>
				</ul>
			</div>
		</div>

		<div class="span9">

			<?php if(empty($query_data)){ ?>
				<strong>对不起，我们无法找到任何搜索结果。</strong>
			<?php } else { ?>
			<ul class="thumbnails">
				<?php foreach($query_data as $data): ?>
				<li class="span3">
					<div class="thumbnail" style="position: relative; height: 320px;">
						<?php if(strtotime($data->expires) < time()){ ?>
						<div class="ribbon-wrapper-right"><div class="ribbon-red">CLOSED</div></div>
						<?php } ?>
						<img src="<?php echo WWW; ?>assets/project/<?php echo $data->id."/images/".$data->thumbnail; ?>" style="height:130px" alt="Image" />
						<a href="<?php echo WWW."project.php?id=".$data->id; ?>">
							<h4><?php echo $data->name; ?></h4>
						</a>
						<p><?php echo substr($data->description, 0, 100)."..."; ?></p>
						<div style="position: absolute; bottom: 0;width: 96%;">
							<span style="float:left;" class="progress_title"><?php echo CURRENCYSYMBOL.$data->amount_invested; ?></span><span style="float:right;" class="progress_title"><?php echo CURRENCYSYMBOL.$data->investment_wanted; ?></span>
							<div class="clear"></div>
							<div class="progress progress-success progress-striped" style="height:7px;margin-top: 4px;margin-bottom:3px;"><div class="bar" style="width: <?php echo Investments::get_percentage($data->amount_invested,$data->investment_wanted) ?>%"></div></div>
							<span style="float:left;" class="progress_title">目标</span><span style="float:right;" class="progress_title">目标</span>
							<div class="clear"></div>
						</div>
					</div>
				</li>
				<?php endforeach; ?>
			</ul>

			<?php
				if($pagination->total_pages() > 1) {
				echo "<div class='pagination pagination-centered'><ul>";

					for($i=1; $i <= $pagination->total_pages(); $i++) {
						if($i == $page) {
							echo " <li class='active'><a>{$i}</a></li> ";
						} else {
							echo " <li><a href=\"search.php?search={$query}&amp;filter={$_GET['filter']}&amp;page={$i}\">{$i}</a></li> "; 
						}
					}

				}

				echo "</ul>";
			?>
			<?php } ?>
		</div>

	</div>


<?php require_once("includes/themes/".THEME_NAME."/footer.php"); ?>