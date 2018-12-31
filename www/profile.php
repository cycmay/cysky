<?php require_once("includes/inc_files.php"); 

/*
 * Copyright (c) 2012-2013 CODEC2I.NET
 * 对非商用为目的的用户采用GPL2开源协议。您可以将其在自己的服务器部署使用，但不可以修改后发布为闭源或者商业软件。以商用为目的的用户需要购买CODEC2I的商业授权，详情请邮件sv@codec2inet。使用CODEC2I众筹系统的网站需要在页面显著位置标明基于CODEC2I构建。
 * E-mail:sv@codec2i.net
 * 官方网址:http://www.codec2i.net
 */

$current_page = "profile";

if(isset($_GET['username'])){
	$username = clean_value($_GET['username']);
	$user_data = User::find_profile_data($username, "username");
	$profile_data = Profile::find_by_id($user_data->user_id);
	// preprint($user_data);
	// preprint($profile_data);
	$profile_messages = Profile::get_profile_messages("unread", $profile_data->user_id);
	// preprint($profile_messages);
	if($session->is_logged_in()) {
		$user = User::find_by_id($_SESSION['user_id']);

		if($user->user_id == $user_data->user_id){
			$owner = true;
		} else {
			$owner = false;
		}
	}
	
	if(!isset($owner)){
		$owner = false;
	}
} else {
	redirect_to("profiles.php");
}

?>

<?php $page_title = "我的资料"; require_once("includes/themes/".THEME_NAME."/header.php"); ?>

<?php echo output_message($message); ?>

<script>
$(document).ready(function(){
	$("#submit_wall_message").click(function(){
		var wall_message = $("#wall_message");
		var message = escape(wall_message.val());
		if(message){
			$.ajax({
				type: "POST",
				url: "data.php",
				data: "page=profile&profile=<?php echo $profile_data->user_id; ?>&message="+message,
				success: function(html){
					if(html == "false"){
						$("#submit_wall_message").html("Add Message");
					} else {
						wall_message.val("");
						$("#message").html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>Thank you, we have posted your message on <?php echo $user_data->username; ?>\'s wall.</div>');
						wall_message.removeClass("error");
						$("#wall_message_counter").html("250");
						$("#profile_messages").html(html);
						$("#submit_wall_message").html("Add Message");
					}
				},
				beforeSend: function(){
					$("#submit_wall_message").html("Working...");
				}
			});
		} else {
			wall_message.addClass("error");
			$("#message").html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button>请输入一个 <?php echo $user_data->username; ?> 的消息。</div>');
		}
	});
	var wall_message = $("#wall_message");
	var max_length = wall_message.attr('maxlength');
	if (max_length > 0) {
		wall_message.bind('keyup', function(e){
			length = new Number(wall_message.val().length);
			counter = max_length-length;
			$("#wall_message_counter").text(counter);
		});
	}
	
});

function display_messages(type){
	//if(empty($profile_data)) 
	//	$profile_data = new Profile;
	var profile = <?php echo $profile_data->user_id; ?>;
	console.log($profile_data);
	var limit = 5; // 5 for testing. This will be dynamically chosen by the user.
	var wall_message = $("#wall_message");
	// console.log(type);
	if(type == "unread"){
		$("#message_pills #all").removeClass("active");
		$("#message_pills #unread").addClass("active");
		limit = "";
	} else {
		$("#message_pills #all").addClass("active");
		$("#message_pills #unread").removeClass("active");
	}
	$.ajax({
		type: "POST",
		url: "data.php",
		data: "page=profile&profile=<?php echo $profile_data->user_id; ?>&get="+type+"&limit="+limit,
		success: function(html){
			if(html != "false"){
				$("#profile_messages").html(html);
			}
		}
	});
}

function edit_message(id){
	$("#edit_comment #confirm").attr('onclick','confirm_edit('+id+');');
	$.ajax({
		type: "POST",
		url: "data.php",
		data: "page=profile&get_message="+id,
		success: function(message){
			$("#edit_comment #textarea").html(message);
		}
	});
	$('#edit_comment').modal('show');
}
function confirm_edit(id){
	var message = $("#edit_comment #textarea").val();
	$.ajax({
		type: "POST",
		url: "data.php",
		data: "page=profile&confirm_edit="+id+"&message="+message,
		success: function(){
			location.reload();
		},
		beforeSend: function(){
			$("#edit_comment #confirm").html("Working...");
		}
	});
}

function delete_message(id){
	// console.log("Delete:"+id);
	$("#confirm_delete #confirm").attr('onclick','confirm_delete('+id+');');
	$('#confirm_delete').modal('show');
}
function confirm_delete(id){
	// console.log("Confmed:"+id);
	$.ajax({
		type: "POST",
		url: "data.php",
		data: "page=profile&delete_message=<?php echo $profile_data->user_id; ?>&id="+id,
		success: function(data){
			if(data == "failure"){
				location.reload();
			} else {
				$("#message").html(data);
				$("#message"+id).remove();
				$('#confirm_delete').modal('hide');
			}
		},
		beforeSend: function(){
			$("#confirm_delete #confirm").html("Working...");
		}
	});
}

function update_profile(){
	var profile_message = $("#edit_profile #profile_message").val();
	var about_me = $("#edit_profile #textarea").val();
	if(profile_message == ""){
		$("#edit_profile #message").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>Please complete all required fields.</div>")
	} else {
		$.ajax({
			type: "POST",
			url: "data.php",
			data: "page=profile&update_profile=<?php echo $profile_data->user_id; ?>&profile_message=="+profile_message+"&about_me="+about_me,
			success: function(data){
				if(data == "success"){
					// $("#edit #update_message").html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>Your updates to this project have been saved. Please refresh the page to see them.</div>");
					// $("#edit #submit").html("Update Project");
					location.reload();
				} else {
					$("#edit_profile #message").html(data);
				}
			},
			beforeSend: function(){
				$("#edit_profile #update").html("Working...");
			}
		});
	}
}

</script>

<div class="title">
	<h1><?php echo $page_title; ?></h1>
</div>
	
	<div class="row-fluid">
		<div class="span3 center">
			<?php if($profile_data->profile_picture == "male.jpg" || $profile_data->profile_picture == "female.jpg"){ ?>
			<img src="assets/img/profile/<?php echo $profile_data->profile_picture; ?>" alt="Profile Picture">
			<?php } else { ?>
			<img src="assets/img/profile/<?php echo $profile_data->user_id."/".$profile_data->profile_picture; ?>" alt="我的头像">
			<?php } ?>
			<br /><br />
			<ul class="nav nav-tabs nav-stacked" style="text-align: left;">
				<?php if($owner == true): ?>
				<li><a href="profile_picture.php">修改头像</a></li>
				<li><a href="#edit_profile" data-toggle="modal">修改资料</a></li>
				<?php endif; // profile owner check ?>
			</ul>
		</div>
		<div class="span9">
			<label style="font-size: 20px;"><?php echo $user_data->first_name." ".$user_data->last_name." (".$user_data->username.")"; ?></label>
			<label><?php echo $user_data->country; ?></label>
			<br />
			<ul id="myTab" class="nav nav-tabs">
				<li class="active"><a href="#wall" data-toggle="tab">我的消息</a></li>
				<li class=""><a href="#about_me" data-toggle="tab">关于我</a></li>
				<li class=""><a href="#investments" data-toggle="tab">我的投资</a></li>
				<li class=""><a href="#projects" data-toggle="tab">我的项目</a></li>
			</ul>
			<div id="message"></div>
			<div id="myTabContent" class="tab-content">
				<div class="tab-pane fade active in" id="wall">
					<?php if($session->is_logged_in()) : ?>
					<div class="create_wp_container">
						<label>写一个消息关于 <?php echo $user_data->username; ?> 的消息</label>
						<textarea class="span12" style="height: 80px;" id="wall_message" maxlength="250" required="required"></textarea>
						剩余 <span id="wall_message_counter">250</span> 个字符
						<button class="btn btn-success right" id="submit_wall_message">添加消息</button>
						<div class="clearfix"></div>
					</div>
					<?php endif; ?>
					
					<div class="clearfix"></div>
					<div id="profile_messages">
					<?php echo Profile::display_profile_messages("unread", $profile_data->user_id); ?>
					</div>
				</div>
				<div class="tab-pane fade" id="about_me">
					<?php if($profile_data->about_me != ""){ echo nl2br($profile_data->about_me); } else { echo "很抱歉，".$user_data->username." 在这个部分还没有输入任何的消息。"; } ?>
				</div>
				<div class="tab-pane fade" id="investments">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>项目名称</th>
								<th>投资金额</th>
								<th>投资日期</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($projects = Investments::get_user_investments($profile_data->user_id) as $project): $project_details = Investments::get_project_details($project->investment_id); ?>
							<tr>
								<td><a href="<?php echo WWW."project.php?id=".$project_details[0]->id; ?>"><?php echo $project_details[0]->name; ?></a></td>
								<td><?php echo CURRENCYSYMBOL." ".$project->amount; ?></td>
								<td><?php echo date_to_text($project->date_invested); ?></td>
							</tr>
							<?php endforeach; if(empty($projects)){ ?>
								<tr>
									<td colspan="4">没有发现投资。</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<div class="tab-pane fade" id="projects">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>项目名称</th>
								<th>已获得资金</th>
								<th>目标</th>
								<th>结束时间</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($projects = Investments::get_user_projects($profile_data->user_id) as $project): ?>
							<tr>
								<td><a href="<?php echo WWW."project.php?id=".$project->id; ?>"><?php echo $project->name; ?></a></td>
								<td><?php echo CURRENCYSYMBOL." ".$project->amount_invested; ?></td>
								<td><?php echo CURRENCYSYMBOL." ".$project->investment_wanted; ?></td>
								<td><span style="font-weight: bolder;color: #F72C2C;" id="time_left"><?php echo Investments::convert_expiry_time($project->expires); ?></span> (<?php echo datetime_to_text($project->expires); ?>)</td>
							</tr>
							<?php endforeach; if(empty($projects)){ ?>
								<tr>
									<td colspan="4">没有发现投资。</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			
		</div>
	</div>

 
<?php if($session->is_logged_in()){ ?>
<!-- Edit Comment - Modal -->
<div id="edit_comment" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Edit Comment</h3>
	</div>
	<div class="modal-body">
		<textarea id="textarea" class="span12" style="height: 100px; width: 97%;"></textarea>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		<button id="confirm" class="btn btn-primary">Update</button>
	</div>
</div>

<!-- Confirm Delete - Comment - Modal -->
<div id="confirm_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Confirm Deletion</h3>
	</div>
	<div class="modal-body">
		<p>Are you sure about deleting this comment? <br /> <strong>This action can't be reversed!</strong></p>
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Cancel</button>
		<button id="confirm" class="btn btn-danger">Confirm</button>
	</div>
</div>

<!-- 修改资料 - Modal -->
<div id="edit_profile" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">修改资料</h3>
	</div>
	<div class="modal-body">
		<div id="message"></div>
		<label>我的消息</label>
		<input type="text" id="profile_message" required="required" style="width: 97%;" value="<?php echo $profile_data->profile_msg; ?>" />
		<label>关于我</label>
		<textarea id="textarea" class="span12" style="height: 100px; width: 97%;"><?php echo $profile_data->about_me; ?></textarea>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">取消</button>
		<button id="update" class="btn btn-primary" onclick="update_profile();">更新</button>
	</div>
</div>

<?php } ?>

<?php require_once("includes/themes/".THEME_NAME."/footer.php"); ?>