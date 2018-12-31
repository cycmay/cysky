<?php require_once("includes/inc_files.php"); 

/*
 * Copyright (c) 2012-2013 CODEC2I.NET
 * 对非商用为目的的用户采用GPL2开源协议。您可以将其在自己的服务器部署使用，但不可以修改后发布为闭源或者商业软件。以商用为目的的用户需要购买CODEC2I的商业授权，详情请邮件sv@codec2inet。使用CODEC2I众筹系统的网站需要在页面显著位置标明基于CODEC2I构建。
 * E-mail:sv@codec2i.net
 * 官方网址:http://www.codec2i.net
 */

if($session->is_logged_in()) {
	$user = User::find_by_id($_SESSION['user_id']);
} else {
	redirect_to("login.php");
}
//$_SESSION['current_step'] = 1;
$location = "create-project.php";
$current_page = "create_project";

if(isset($_SESSION['current_step'])){
	if(isset($_GET['step'])){
		$step = clean_value($_GET['step']);
	} else {
		$step = 1;
	}
	if($_SESSION['current_step'] != $step){
			redirect_to($location."?step=".$_SESSION['current_step']);
	}
	
	if($step == 1){
		if(isset($_POST['create_project'])){
			$title = clean_value($_POST['title']);
			$category = clean_value($_POST['category']);
			$goal = clean_value($_POST['goal']);
			$expires = clean_value($_POST['expires']);
			$investment_message = clean_value($_POST['investment_message']);
			$complete_message = clean_value($_POST['complete_message']);
			$description = clean_value($_POST['description']);
			$main_description = clean_value($_POST['main_description']);
			if($title != "" && $category != "" && $goal != "" && $expires != "" && $investment_message != "" && $complete_message != "" && $description != "" && $main_description != "" ){
				Investments::create_project($title,$category,$goal,$expires,$investment_message,$complete_message,$description,$main_description);
			} else {
				$message = "<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>请填写所有必填字段.</div>";
			}
		} else {
			$title = "";
			$category = "";
			$goal = "";
			$expires = "30";
			$investment_message = "感谢您抽出宝贵的时间看一下我们的项目...";
			$complete_message = "感谢你支持这个项目....";
			$description = "";
			$main_description = "";
		}
	}
	
} else {
	$_SESSION['current_step'] = 1;
	redirect_to($location);
}

// preprint(Investments::get_categories());


?>

<?php $page_title = "创建项目"; require_once("includes/themes/".THEME_NAME."/header.php"); ?>

<?php echo output_message($message); ?>


<div class="title">
	<h1><?php echo $page_title; ?></h1>
</div>
	
	<ul id="steps">
		<li class="<?php if($step == 1){echo "current";} ?>">第1步<span>基本信息</span></li>
		<li class="<?php if($step == 2){echo "current";} ?>">第2步<span>上传图片</span></li>
		<li class="<?php if($step == 3){echo "current";} ?>">第3步<span>检查信息</span></li>
	</ul>
	
	<hr />
	
	<?php if($step == 1) { ?>
	<form action="<?php echo $location; ?>?step=1" method="post">
		<script>
		$(document).ready(function() {
			$("#goal").keydown(function(event) {
				if ( event.keyCode == 46 || event.keyCode == 8 ) {
				} else {
					if (event.keyCode < 95) {
						if (event.keyCode < 48 || event.keyCode > 57 ) {
							event.preventDefault();	
						}
					} else {
						if (event.keyCode < 96 || event.keyCode > 105 ) {
							event.preventDefault();	
						}
					}
				}
			});
			$("#expires").keydown(function(event) {
				if ( event.keyCode == 46 || event.keyCode == 8 ) {
				} else {
					if (event.keyCode < 95) {
						if (event.keyCode < 48 || event.keyCode > 57 ) {
							event.preventDefault();	
						}
					} else {
						if (event.keyCode < 96 || event.keyCode > 105 ) {
							event.preventDefault();	
						}
					}
				}
			});
		});
		</script>
		<div class="row-fluid">
			<div class="span3">
				<label>标题</label>
		      <input type="text" class="span12" name="title" required="required" value="<?php echo $title; ?>" />
			</div>
			<div class="span3">
				<label>分类</label>
				<select name="category" class="span12 chzn-select" required="required" value="<?php echo $categories; ?>">
					<?php foreach(Investments::get_categories() as $category): ?>
					<option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="span3">
				<label>目标</label>
				<div class="input-prepend input-append">
					<span class="add-on"><?php echo CURRENCYSYMBOL; ?></span>
					<input class="span9" name="goal" type="text" id="goal" required="required" value="<?php echo htmlentities($goal); ?>">
					<span class="add-on">.00</span>
				</div>
			</div>
			<div class="span3">
				<label>结束 (天)</label>
		      <input type="text" class="span12" name="expires" id="expires" required="required" value="<?php echo $expires; ?>" />
			</div>
		</div>	
	
		<div class="row-fluid">
			<div class="span6">
				<label>投资信息</label>
		      <input type="text" class="span12" name="investment_message" required="required" value="<?php echo $investment_message; ?>" />
			</div>
			<div class="span6">
				<label>项目完整消息</label>
		      <input type="text" class="span12" name="complete_message" required="required" value="<?php echo $complete_message; ?>" />
			</div>
		</div>
	
		<div class="row-fluid">
			<div class="span12">
				<label>描述</label>
		      <input type="text" class="span12" name="description" required="required" value="<?php echo $description; ?>" />
			</div>
		</div>

		<div class="row-fluid">
			<div class="span12">
				<label>主要内容</label>
				<textarea class="span12" name="main_description"><?php echo $main_description; ?></textarea>
			</div>
		</div>

		<div class="form-actions" style="text-align: center;margin: 20px -10px -30px;">
			<input class="btn btn-primary" type="submit" name="create_project" value="创建项目" />
		</div>
	</form>
	<?php } else if($step == 2) { ?>
		<link href='http://fonts.googleapis.com/css?family=Boogaloo' rel='stylesheet' type='text/css'>
		<script src="<?php echo WWW; ?>includes/global/js/jquery-1.9.1.js"></script>
		<script type="text/javascript" src="assets/js/multiupload.js"></script>
		<script type="text/javascript">
		var config = {
			support : "image/jpg,image/png,image/bmp,image/jpeg,image/gif",
			form: "upload_form",
			dragArea: "dragAndDropFiles",
			uploadUrl: "includes/upload/project_upload.php?id=<?php echo $_SESSION['new_project_id']; ?>"
		}
		$(document).ready(function(){
			initMultiUploader(config);
		});
		var int=self.setInterval(function(){check_uploaded()},1000);
		function check_uploaded(){
			// document.getElementById("uploaded");
			// if ($('#uploaded').length !== 0) {
			//     $('#next_stage').show();
			// };
			if ($('#uploaded').is(':empty')){
				
			} else {
				$('#next_stage').removeClass('disabled').attr('onclick','next_stage();');
			}
		}
		function next_stage(){
			$.ajax({
				type: "POST",
				url: "data.php",
				data: "page=project&check_project_images=true",
				success: function(html){
					if(html == "false"){
						$("#message").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Please upload at least 1 image to continue.</div>");
					} else {
						window.location.replace($.trim(html));
					}
				}
			});
		}
		</script>
		<link href="assets/css/multiupload.css" type="text/css" rel="stylesheet" />
		<div id="message"></div>
		<div id="dragAndDropFiles" class="uploadArea">
			<h1>删除图片</h1>
		</div>
		<form name="upload_form" id="upload_form" enctype="multipart/form-data">
		<input type="file" name="multiUpload" id="multiUpload" multiple />
		<input type="submit" name="submitHandler" id="submitHandler" value="上传" class="btn btn-primary" />
		</form>

		<div class="progressBar">
			<div class="status"></div>
		</div>
		
		<div id="uploaded"></div>
		
		<div class="form-actions" style="text-align: center;margin: 20px -10px -10px;">
			<button id="next_stage" class="btn btn-primary disabled">检查信息</button>
		</div>
	<?php } else if($step == 3) { ?>
		<?php
		$project_id = $_SESSION['new_project_id'];
		$project_images = glob('./assets/project/'.$project_id.'/images/*.*');
		Investments::set_thumbnail($project_id,str_replace('./assets/project/'.$project_id.'/images/','', $project_images[0]));
		$project_data = Investments::get_project_listing($project_id);
		?>
		<script>
		function set_thumbnail(id,name){
			$.ajax({
				type: "POST",
				url: "data.php",
				data: "page=project&set_thumbnail=<?php echo $project_data->id; ?>&name="+name,
				success: function(data){
					if(data == "success"){
						$("#message").html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>这个项目的缩略图已经更新。</div>");
						$("#the_thumbnail").attr("src", '<?php echo "./assets/project/".$project_data->id."/images/" ?>'+name);
					} else {
						$("#message").html(data);
					}
				}
			});
		}
		function cancel_listing(){
			$.ajax({
				type: "POST",
				url: "data.php",
				data: "page=project&cancel_listing=true",
				success: function(url){
					window.location.replace($.trim(url));
				}
			});
		}
		function publish_listing(){
			var title = $("#title").val();
			var category = $("#category option:selected").val();
			var goal = $("#goal").val();
			var investment_message = $("#investment_message").val();
			var complete_message = $("#complete_message").val();
			var description = $("#description").val();
			var main_description = $("#main_description").val();
			if(title != "" && category != "" && goal != "" && investment_message != "" && complete_message != "" && description != "" && main_description != "" ){
				$.ajax({
					type: "POST",
					url: "data.php",
					data: "page=project&publish_listing=true&title="+title+"&category="+category+"&goal="+goal+"&investment_message="+investment_message+"&project_closed_message="+complete_message+"&description="+description+"&main_description="+main_description,
					success: function(url){
						window.location.replace($.trim(url));
					}
				});
			} else {
				
			}
			
		}
		</script>
		
		<fieldset>
			<legend>项目信息</legend>
			<div class="row-fluid">
				<div class="span3">
					<label>标题</label>
			      <input type="text" class="span12" id="title" required="required" value="<?php echo $project_data->name; ?>" />
				</div>
				<div class="span3">
					<label>分类</label>
					<select id="category" class="span12 chzn-select" required="required" value="<?php echo $categories; ?>">
						<?php foreach(Investments::get_categories() as $category): ?>
						<option value="<?php echo $category->id; ?>"<?php if($project_data->category_id == $category->id){echo " selected='selected'";} ?>><?php echo $category->name; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="span3">
					<label>目标</label>
					<div class="input-prepend input-append">
						<span class="add-on"><?php echo CURRENCYSYMBOL; ?></span>
						<input class="span9" id="goal" type="text" required="required" value="<?php echo htmlentities($project_data->investment_wanted); ?>">
						<span class="add-on">.00</span>
					</div>
				</div>
				<div class="span3">
					<label>结束时间</label>
			      <input type="text" class="span12" id="expires" required="required" disabled="disabled" value="<?php echo $project_data->expires; ?>" />
				</div>
			</div>	

			<div class="row-fluid">
				<div class="span6">
					<label>投资消息</label>
			      <input type="text" class="span12" id="investment_message" required="required" value="<?php echo $project_data->investment_message; ?>" />
				</div>
				<div class="span6">
					<label>项目完整消息</label>
			      <input type="text" class="span12" id="complete_message" required="required" value="<?php echo $project_data->project_closed_message; ?>" />
				</div>
			</div>

			<div class="row-fluid">
				<div class="span12">
					<label>描述</label>
			      <input type="text" class="span12" id="description" required="required" value="<?php echo $project_data->description; ?>" />
				</div>
			</div>

			<div class="row-fluid">
				<div class="span12">
					<label>主要描述</label>
					<textarea class="span12" id="main_description"><?php echo $project_data->main_description; ?></textarea>
				</div>
			</div>
		</fieldset>
		
		<hr />
		
		<fieldset>
			<legend>项目图片</legend>
			
			<div id="message"></div>
			
			<ul class="thumbnails">
				<li class="span2">
					<div class="thumbnail">
						<img id="the_thumbnail" src="<?php echo WWW; ?><?php echo "/assets/project/".$project_data->id."/images/".$project_data->thumbnail; ?>" alt="Thumbnail" />
						<div class="clear"></div>
						<label>当前缩略图</label>
					</div>
				</li>
			</ul>
			<ul class="thumbnails">
				<?php
				$counter = 1;
				$project_id = $_SESSION['new_project_id'];
				foreach($project_images as $image): $image_name = str_replace('./assets/project/'.$project_id.'/images/','', $image); ?>
				<li class="span2" id="image<?php echo $counter; ?>">
					<div class="thumbnail">
						<img src="<?php echo WWW; ?><?php echo $image; ?>" alt="Image <?php echo $counter; ?>" />
						<div class="clear"></div>
						<button class="btn btn-link" onclick="set_thumbnail('<?php echo $counter; ?>','<?php echo $image_name; ?>');">设为缩略图</button><br />
						<div class="clear"></div>
					</div>
				</li>
				<?php $counter++; endforeach; ?>
			</ul>
		</fieldset>
		
		<div class="form-actions" style="text-align: center;margin: 20px -10px -10px;">
			<button class="btn btn-danger" onclick="cancel_listing();">取消发布</button> <button class="btn btn-primary" onclick="publish_listing();">确定发布</button>
		</div>
	<?php } ?>

<?php require_once("includes/themes/".THEME_NAME."/footer.php"); ?>