<?php 
require_once("includes/inc_files.php"); 
require_once("gateway/ethpay.php"); 
$current_page = "project";

if($session->is_logged_in()) {
	$user = User::find_by_id($_SESSION['user_id']);
} else {
	$user = new User;
	$user->user_id = "";
}

if(isset($_GET['id'])){
	$project_id = clean_value($_GET['id']);
	$project_data = Investments::get_project_listing($project_id);
	
	if(empty($project_data)){
		$session->message("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>没有发现项目的ID。</div>");
		redirect_to(WWW."search.php");
	} else {
		$project_creator = User::find_profile_data($project_data->creator_id, "user_id");
		$pc_profile = Profile::find_by_id($project_data->creator_id);
		// preprint($pc_profile);

		if($project_data->status == 0 && $user->user_id != $project_data->creator_id){
			redirect_to("search.php");
		}

		if(!isset($owner)){
			$owner = false;
		}

		if(strtotime($project_data->expires) > time()){
			$project_status = "open";
		} else {
			$project_status = "closed";
		}
	}
} else {
	redirect_to("search.php");
}

?>

<?php $page_title = $project_data->name . " 项目"; require_once("includes/themes/".THEME_NAME."/header.php"); ?>

<?php echo output_message($message); ?>

<style>

<?php if($project_data->custom_theme == 0){ ?>
.project #main_project_image {
background: #DDD url('assets/project/templates/<?php echo $project_data->theme; ?>') no-repeat 0px 0px;
}
<?php } else { ?>
.project #main_project_image {
background: #DDD url('assets/project/<?php echo $project_data->id; ?>/themes/<?php echo $project_data->ctheme; ?>') no-repeat 0px 0px;
}
<?php } ?>
</style>

<script>

$(document).ready(function(){
	$("#submit_project_comment").click(function(){
		var wall_message = $("#wall_message");
		//var comment = escape(wall_message.val());
		var comment = wall_message.val();
		if(comment){
			$.ajax({
				type: "POST",
				url: "data.php",
				data: "page=project&project=<?php echo $project_data->id; ?>&comment="+comment,
				success: function(html){
					if(html == "false"){
						$("#submit_project_comment").html("添加消息");
					} else {
						wall_message.val("");
						$("#message").html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>谢谢,您已经为这个项目添加了评论。</div>');
						wall_message.removeClass("error");
						$("#wall_message_counter").html("250");
						$("#project_messages").html(html);
						$("#submit_project_comment").html("添加消息");
					}
				},
				beforeSend: function(){
					$("#submit_project_comment").html("提交中...");
				}
			});
		} else {
			wall_message.addClass("error");
			$("#message").html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button>请输入评论。</div>');
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

function edit_message(id){
	$("#edit_comment #confirm").attr('onclick','confirm_edit('+id+');');
	$.ajax({
		type: "POST",
		url: "data.php",
		data: "page=project&get_message="+id,
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
		data: "page=project&confirm_edit="+id+"&message="+message,
		success: function(){
			location.reload();
		},
		beforeSend: function(){
			$("#edit_comment #confirm").html("提交中...");
		}
	});
}

function delete_message(id){
	$("#confirm_delete #confirm").attr('onclick','confirm_delete('+id+');');
	$('#confirm_delete').modal('show');
}
function confirm_delete(id){
	$.ajax({
		type: "POST",
		url: "data.php",
		data: "page=project&delete_message=<?php echo $project_data->user_id; ?>&id="+id,
		success: function(data){
			if(data == "failure"){
				location.reload();
			} else {
				$("#message").html(data);
				$("#message"+id).remove();
				$("#confirm_delete").modal('hide');
				$("#confirm_delete #confirm").html("确定");
			}
		},
		beforeSend: function(){
			$("#confirm_delete #confirm").html("提交中...");
		}
	});
}

// investments

function invest(){
	var amount = $("#amount").val();
	if(amount && amount > 0){
		$('#confirm_investment #current_investment').attr("value",amount);
		$('#confirm_investment').modal('show');
	}
}

function make_investment(){
	var amount = $("#confirm_investment #current_investment ").val();
	var payment = $("#confirm_investment #payment_option option:selected").val();
	var investment_type = $("#confirm_investment #investment_type option:selected").val();
	if(payment == "paypal"){
		$.ajax({
			type: "POST",
			url: "gateway/paypal.php",
			data: "invest=true&id=<?php echo $project_data->id; ?>&amount="+amount+"&investment_type="+investment_type,
			success: function(data){				
				// var paypal = null;
				// function centeredPopup(url,winName,w,h,scroll){
				// LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
				// TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
				// settings =
				// 'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',resizable'
				// paypal = window.open(url,winName,settings)
				// }
				// centeredPopup('about:blank','paypal','1000','700','100','200','yes');
				// paypal.document.write(data);	
				// var timer = setInterval(function() { 
				//   if(paypal.closed) {
				//       clearInterval(timer);
				//       location.reload();
				//   }
				// }, 1000);
				document.write(data);
				console.log("hello");
			},
			beforeSend: function(){
				$("#confirm_investment #confirm").html("提交中...");
			}
		});
		console.log("false");
	}
	else if(payment == "ethpay") {
		App.init();
		var contract_address = "<?php echo $project_data->contract_address ?>";

		var my_post = {
            type: "POST",
            url: "data.php",
            data: "page=project&make_payment=<?php echo $project_data->id; ?>&amount="+amount+"&payment="+payment+"&investment_type="+investment_type,
            success: function(data){
              if(data == "success"){
                location.reload();
              } else{
                $("#confirm_investment #message").html(data);
                $("#confirm_investment #confirm").html("确定");
              }     
            },
            beforeSend: function(){
              $("#confirm_investment #confirm").html("提交中...");
            }
          };

		App.thransferEasy(amount, contract_address, my_post);
		
	} 
	else {
		$.ajax({
			type: "POST",
			url: "data.php",
			data: "page=project&make_payment_a=<?php echo $project_data->id; ?>&amount="+amount+"&payment="+payment+"&investment_type="+investment_type,
			success: function(data){
				if(data == "success"){
					location.reload();
				} else{
					$("#confirm_investment #message").html(data);
					$("#confirm_investment #confirm").html("确定");
				}			
			},
			beforeSend: function(){
				$("#confirm_investment #confirm").html("提交中...");
			}
		});
	}
}

function contact_form(){
	var name = $("#contact_form #name").val();
	var email = $("#contact_form #email").val();
	var subject = $("#contact_form #subject").val();
	var message = $("#contact_form #mess").val();
	if(name != "" && email != "" && subject != "" && message != ""){
		if(valid_email(email)){
			$.ajax({
				type: "POST",
				url: "data.php",
				data: "page=project&send_email=<?php echo $project_data->id; ?>&name="+name+"&email="+email+"&subject="+subject+"&message="+message,
				success: function(){
					location.reload();
				},
				beforeSend: function(){
					$("#contact_form #submit").html("提交中...");
				}
			});
		} else {
			$("#message").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>请输入有效的电邮地址！</div>");
			$("#contact_form #name").addClass('success').removeClass('error');
			$("#contact_form #email").addClass('error');
			$("#contact_form #subject").addClass('success').removeClass('error');
			$("#contact_form #mess").addClass('success').removeClass('error');
			$("#contact_form #submit").html("发送消息");
		}
	} else {
		$("#message").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>请填写所有必填字段。</div>");
		$("#contact_form #name").addClass('error');
		$("#contact_form #email").addClass('error');
		$("#contact_form #subject").addClass('error');
		$("#contact_form #mess").addClass('error');
		$("#contact_form #submit").html("发送消息");
	}
	
}


<?php if($user->user_id == $project_data->creator_id){ ?>

// updates

function edit_update(id){
	$("#edit_update #confirm").attr('onclick','confirm_update_edit('+id+');');
	$.ajax({
		type: "POST",
		url: "data.php",
		dataType: "json",
		data: "page=project&get_update="+id,
		success: function(json){
			$("#edit_update #title").val(json.the_title);
			$("#edit_update #textarea").val(json.message);
		}
	});
	$('#edit_update').modal('show');
}
function confirm_update_edit(id){
	var title = $("#edit_update #title").val();
	var message = $("#edit_update #textarea").val();
	$.ajax({
		type: "POST",
		url: "data.php",
		data: "page=project&confirm_edit_update="+id+"&title="+title+"&message="+message,
		success: function(data){
			location.reload();
			// console.log(data);
		},
		beforeSend: function(){
			$("#edit_comment #confirm").html("提交中...");
		}
	});
}

function delete_update(id){
	$("#confirm_delete_update #confirm").attr('onclick','confirm_delete_update('+id+');');
	$('#confirm_delete_update').modal('show');
}

function confirm_delete_update(id){
	$.ajax({
		type: "POST",
		url: "data.php",
		data: "page=project&delete_update=<?php echo $project_data->id; ?>&id="+id,
		success: function(data){
			if(data == "failure"){
				location.reload();
			} else {
				$("#message").html(data);
				$("#update="+id).remove();
				$('#confirm_delete_update').modal('hide');
			}
		},
		beforeSend: function(){
			$("#confirm_delete #confirm").html("提交中...");
		}
	});
}

function add_update(id){
	var title = $("#add_update #title").val();
	var message = $("#add_update #textarea").val();
	if(title != "" && message != ""){
		$.ajax({
			type: "POST",
			url: "data.php",
			data: "page=project&add_update=<?php echo $project_data->id; ?>&title="+title+"&message="+message,
			success: function(){
				location.reload();
			},
			beforeSend: function(){
				$("#confirm_delete #confirm").html("提交中...");
			}
		});
	} else {
		$("#add_update #message").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>请填写所有必填字段。</div>");
	}
}


// faq

function edit_faq(id){
	$("#edit_faq #confirm").attr('onclick','confirm_update_faq('+id+');');
	$.ajax({
		type: "POST",
		url: "data.php",
		dataType: "json",
		data: "page=project&get_faq="+id,
		success: function(json){
			$("#edit_faq #title").val(json.the_title);
			$("#edit_faq #textarea").val(json.message);
		}
	});
	$('#edit_faq').modal('show');
}
function confirm_update_faq(id){
	var title = $("#edit_faq #title").val();
	var message = $("#edit_faq #textarea").val();
	$.ajax({
		type: "POST",
		url: "data.php",
		data: "page=project&confirm_edit_faq="+id+"&title="+title+"&message="+message,
		success: function(data){
			location.reload();
			// console.log(data);
		},
		beforeSend: function(){
			$("#edit_faq #confirm").html("提交中...");
		}
	});
}

function delete_faq(id){
	$("#confirm_delete_faq #confirm").attr('onclick','confirm_delete_faq('+id+');');
	$('#confirm_delete_faq').modal('show');
}

function confirm_delete_faq(id){
	$.ajax({
		type: "POST",
		url: "data.php",
		data: "page=project&delete_faq=<?php echo $project_data->id; ?>&id="+id,
		success: function(data){
			if(data == "failure"){
				location.reload();
			} else {
				$("#message").html(data);
				$("#faq"+id).remove();
				$('#confirm_delete_faq').modal('hide');
			}
		},
		beforeSend: function(){
			$("#confirm_delete_faq #confirm").html("提交中...");
		}
	});
}

function add_faq(id){
	var title = $("#add_faq #title").val();
	var message = $("#add_faq #textarea").val();
	if(title != "" && message != ""){
		$.ajax({
			type: "POST",
			url: "data.php",
			data: "page=project&add_faq=<?php echo $project_data->id; ?>&title="+title+"&message="+message,
			success: function(){
				location.reload();
			},
			beforeSend: function(){
				$("#add_faq #confirm").html("提交中...");
			}
		});
	} else {
		$("#add_faq #message").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>请填写所有必填字段。</div>");
	}
}

// faq end

function delete_image(id,name){
	$.ajax({
		type: "POST",
		url: "data.php",
		data: "page=project&delete_image=<?php echo $project_data->id; ?>&name="+name,
		success: function(data){
			if(data == "success"){
				$("#image"+id).remove();
				$("#message").html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>图片已经被删除。</div>");
			} else {
				$("#message").html(data);
			}
		},
		beforeSend: function(){
			$("#confirm_delete #confirm").html("提交中...");
		}
	});
}

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
		},
		beforeSend: function(){
			$("#confirm_delete #confirm").html("提交中...");
		}
	});
}

function set_theme(id,name){
	$.ajax({
		type: "POST",
		url: "data.php",
		data: "page=project&set_theme=<?php echo $project_data->id; ?>&name="+name,
		success: function(data){
			if(data == "success"){
				$("#message").html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>这个项目的主题已经更新。</div>");
				$("#the_theme").attr("src", '<?php echo "./assets/project/templates/" ?>'+name);
			} else {
				$("#message").html(data);
			}
		},
		beforeSend: function(){
			$("#confirm_delete #confirm").html("提交中...");
		}
	});
}

function update_project(){
	var title = $("#edit #title").val();
	var goal = $("#edit #goal").val();
	var investment_message = $("#edit #investment_message").val();
	var project_closed_message = $("#edit #project_closed_message").val();
	var description = $("#edit #description").val();
	var main_description = $("#edit #main_description").val();
	var top_theme = $("#edit #top_theme option:selected").val();
	var custom_theme = $("#edit #custom_theme option:selected").val();
	var video = $("#edit #video").val();
	if(title == "" || goal == "" || investment_message == "" || project_closed_message == "" || description == "" || main_description == "" || custom_theme == ""){
		$("#message").html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>×</button>请填写所有必填字段。</div>")
	} else {
		$.ajax({
			type: "POST",
			url: "data.php",
			data: "page=project&update_project=<?php echo $project_data->id; ?>&title="+title+"&created="+created+"&expires="+expires+"&goal="+goal+"&investment_message="+investment_message+"&project_closed_message="+project_closed_message+"&description="+description+"&main_description="+main_description+"&top_theme="+top_theme+"&custom_theme="+custom_theme+"&video="+video,
			success: function(data){
				if(data == "success"){
					$("#message").html("<div class='alert alert-success'><button type='button' class='close' data-dismiss='alert'>×</button>您这个项目的更新已经被保存。请刷新页面查看更新后的页面。</div>");
					$("#edit #submit").html("更新项目");
				} else {
					$("#message").html(data);
				}
			},
			beforeSend: function(){
				$("#edit #submit").html("提交中...");
			}
		});
	}
}

<?php } ?>

</script>

<div class=" project">
	
	<?php if($project_data->top_theme == 1){ ?>
	
	<div id="main_project_image" style="position:relative">
		<div id="title"><?php echo $project_data->name; ?></div>
		<div id="project_id">项目ID: <?php echo $project_data->id; ?></div>
		<?php if(strtotime($project_data->expires) < time()){ ?>
		<div class="ribbon-wrapper-right"><div class="ribbon-red">已关闭</div></div>
		<?php } ?>
	</div>
	<hr />
	
	<?php } else { ?>
	
		<div class="title" style="position:relative">
			<h1><?php echo $project_data->name; ?> <?php if(strtotime($project_data->expires) < time()){ ?><span class="label label-important" style="font-size: 16px;padding: 5px 6px;">已关闭</span><?php } ?></h1>
		</div>
	
	<?php } ?>
	
	<div class="row-fluid">
		<div class="span5">
			
			<?php if($project_data->video != ""){ ?>
			<ul id="mediatabs" class="nav nav-tabs">
				<li class="active"><a href="#images" data-toggle="tab">图片</a></li>
				<li><a href="#video" data-toggle="tab">视频</a></li>
			</ul>
			
			<div id="mediatabscontent" class="tab-content">
			  <div class="tab-pane active" id="images">
				<?php } ?>
			  	<div id="myCarousel" class="carousel slide">
					<div class="carousel-inner">
						<?php
						$first = false;
						foreach(glob('./assets/project/'.$project_data->id.'/images/*.*') as $filename){
							if($first == false){
								echo "<div class=\"item active\"><img src=\"".$filename."\"/></div>";
								$first = true;
							} else {
								echo "<div class=\"item \"><img src=\"".$filename."\"/></div>";
							}
						}
						?>
					</div>
					<a class="left carousel-control" style="text-align:center" href="#myCarousel" data-slide="prev">‹</a>
					<a class="right carousel-control" style="text-align:center" href="#myCarousel" data-slide="next">›</a>
				</div>
				<?php if($project_data->video != ""){ ?>
			  </div>
			  <div class="tab-pane" id="video">
				<?php if(strpos($project_data->video,'youtube.com') !== false) { 
					preg_match('/[\\?\\&]v=([^\\?\\&]+)/',$project_data->video,$youtube_id);
					
					?>
					
					<div id="ytplayer"></div>

					<script>
					  // Load the IFrame Player API code asynchronously.
					  var tag = document.createElement('script');
					  tag.src = "https://www.youtube.com/player_api";
					  var firstScriptTag = document.getElementsByTagName('script')[0];
					  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

					  // Replace the 'ytplayer' element with an <iframe> and
					  // YouTube player after the API code downloads.
					  var player;
					  function onYouTubePlayerAPIReady() {
					    player = new YT.Player('ytplayer', {
					      height: '255',
					      width: '100%',
					      videoId: '<?php echo $youtube_id[1]; ?>'
					    });
					  }
					</script>
				<?php } else if(strpos($project_data->video,'vimeo.com') !== false){
				sscanf(parse_url($project_data->video, PHP_URL_PATH), '/%d', $vimeo_id); ?>
					<iframe src="http://player.vimeo.com/video/<?php echo $vimeo_id; ?>" width="100%" height="255" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></p>
					
				<?php } ?>
			  	
			  </div>
			</div>
			
			<script>
			  $(function () {
			    // $('#myTab a:last').tab('show');
			  })
			</script>
			
			<?php } ?>
			
		</div>
		<div class="span7">
			<h3><?php echo $project_data->title; ?></h3>
			<label>开始时间: <span style="font-size: 16px;font-weight: bolder;color: rgb(77, 77, 77);;" id="started"><?php echo datetime_to_text($project_data->created); ?></span></label>
			<label>结束时间: <span style="font-size: 16px;font-weight: bolder;color: #F72C2C;" id="time_left"><?php echo Investments::convert_expiry_time($project_data->expires); ?></span> (<?php echo datetime_to_text($project_data->expires); ?>)</label>
			<hr />
			<span style="float:left;" class="progress_title"><?php echo CURRENCYSYMBOL.$project_data->amount_invested; ?> (<?php echo $percentage = Investments::get_percentage($project_data->amount_invested,$project_data->investment_wanted) ?>%)</span><span style="float:right;" class="progress_title"><?php echo CURRENCYSYMBOL.$project_data->investment_wanted; ?></span>
			<div class="clear"></div>
			<div class="progress progress-success progress-striped" style="height:20px;margin-top: 2px;margin-bottom:3px;"><div class="bar" style="width: <?php echo $percentage; ?>%"></div></div>
			<span style="float:left;" class="progress_title">已获支持</span><span style="float:right;" class="progress_title">目标</span>
			<div class="clear"></div>
			<hr />
			<?php if($project_status == "open"){ ?>
			<div class="investment_message"><?php echo $project_data->investment_message; ?></div>
			<?php if($session->is_logged_in()){?>
			<div class="input-prepend input-append">
				<span class="add-on"><?php echo CURRENCYSYMBOL; ?></span>
				<input class="span3" id="amount" type="text">
				<span class="add-on">.00</span>
				<button class="btn btn-success" onclick="invest();">支持</button>
			</div>
			<?php } else { ?>
			您必须 <a href="<?php echo WWW; ?>login.php">登录</a> 来支持。
			<?php }} else { ?>
			<div class="investment_message"><?php echo $project_data->project_closed_message; ?></div>
			<?php } ?>
		</div>
	</div>
	
	<br />
	
	<div class="row-fluid">
		<div class="span12">
			<ul id="myTab" class="nav nav-tabs">
				<li class="active"><a href="#description" data-toggle="tab">描述</a></li>
				<li class=""><a href="#creator" data-toggle="tab">项目创建者信息</a></li>
				<li class=""><a href="#comments" data-toggle="tab">评论 <span class="badge badge-info"><?php echo Investments::count("messages",$project_data->id); ?></span></a></li>
				<li class=""><a href="#investors" data-toggle="tab">支持者 <span class="badge badge-success"><?php echo Investments::count("made",$project_data->id); ?></span></a></li>
				<li class=""><a href="#updates" data-toggle="tab">更新 <span class="badge badge-warning"><?php echo Investments::count("updates",$project_data->id); ?></span></a></li>
				<li class=""><a href="#faq" data-toggle="tab">FAQ</span></a></li>
				<?php if($project_data->creator_id == $user->user_id){ ?>
				<li class=""><a href="#edit" data-toggle="tab">编辑项目</a></li>
				<?php } ?>
			</ul>
			<div id="message"></div>
			<div id="myTabContent" class="tab-content">
				<div class="tab-pane fade active in" id="description" style="overflow:hidden;">
					<?php echo $project_data->description; ?>
					<hr />
					<?php echo nl2br($project_data->main_description); ?>
				</div>
				<div class="tab-pane fade project_creator" id="creator" style="overflow:hidden">
					<div class="span2 center">
						<?php if($pc_profile->profile_picture == "male.jpg" || $pc_profile->profile_picture == "female.jpg"){ ?>
						<img src="assets/img/profile/<?php echo $pc_profile->profile_picture; ?>" alt="头像">
						<?php } else { ?>
						<img src="assets/img/profile/<?php echo $pc_profile->user_id."/".$pc_profile->profile_picture; ?>" alt="头像">
						<?php } ?>
					</div>
					<div class="span10 project_creator">
						<a href="<?php echo WWW."profile.php?username=".$project_creator->username; ?>" style="font-size: 16px;"><?php echo $project_creator->first_name." ".$project_creator->last_name; ?></a>
						<div class="clearfix" style="height: 6px;"></div>
						<!--<label>联系地址: <strong><?php echo $project_creator->country; ?></strong></label>-->
						<label>项目总数: <strong><?php echo Investments::count_started_projects($project_creator->user_id); ?></strong></label>
						<label>已获支持: <strong><?php echo Investments::count_user_investments($project_creator->user_id); ?></strong></label>
						<label>资料信息: <strong><?php echo $pc_profile->profile_msg; ?></strong></label>
					</div>

					<div class="clearfix"></div>
					
					<hr />
					
					<h3>联系我们</h3>
					
					<div id="contact_form">
					
					<div class="row-fluid">
						<div class="span4">
							<input type="text" id="name" class="span12" required="required" placeholder="真实姓名" value="" />
						</div>
						<div class="span4">
							<input type="email" id="email" class="span12" required="required" placeholder="电邮地址" value="" />
						</div>
						<div class="span4">
							<input type="text" id="subject" class="span12" required="required" placeholder="标题" value="" />
						</div>
					</div>
					<br />
					<div class="row-fluid">
						<div class="span12">
							<textarea type="text" class="span12" style="height:111px;" id="mess" placeholder="内容" required="required"></textarea>
						</div>
					</div>

					<button class="btn btn-primary right" id="submit" name="submit" onclick="contact_form();">发送消息</button>
					
					</div>
					
					<div class="clearfix"></div>
					
					
				</div>
				<div class="tab-pane fade" id="comments">
					<?php if($session->is_logged_in()) : ?>
					<div class="create_wp_container">
						<label>给这个项目写一个评论</label>
						<textarea class="span12" style="height: 80px;" id="wall_message" maxlength="250" required="required"></textarea>
						<span id="wall_message_counter">250</span> 剩余字符
						<button class="btn btn-success right" id="submit_project_comment">添加评论</button>
						<div class="clearfix"></div>
					</div>
					<?php endif; ?>

					<div id="project_messages">
					<?php echo $investment_messages = Investments::display_investments_messages($project_data->id); ?>
					</div>
					<?php if(empty($investment_messages)){ ?>
						这个项目目前还没有任何评论。
					<?php } ?>
				</div>
				<div class="tab-pane fade" id="investors">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>姓名</th>
								<th>用户名</th>
								<th>支持金额</th>
								<th>支持时间</th>
								<th>联系地址</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($investors = Investments::get_investors($project_id) as $investment): $user_details = User::get_investor_details($investment->user_id); ?>
							<tr>
								<?php if($investment->status == 1){ ?>
								<td colspan="2">匿名支持者</td>
								<?php } else { ?>
								<td><a href="<?php echo WWW."profile.php?username=".$user_details[0]->username; ?>"><?php echo $user_details[0]->first_name." ".$user_details[0]->last_name; ?></a></td>
								<td><?php echo $user_details[0]->username; ?></td>
								<?php } ?>
								<td><?php echo CURRENCYSYMBOL." ".$investment->amount; ?></td>
								<td><?php echo date_to_text($investment->date_invested); ?></td>
								<td><?php echo $user_details[0]->country; ?></td>
							</tr>
							<?php endforeach; if(empty($investors)){ ?>
								<tr>
									<td colspan="5">还没有任何支持者。</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<div class="tab-pane fade" id="updates" style="overflow:hidden;">
					<?php if($project_data->creator_id == $user->user_id){ ?>
					<div class="well" style="max-width: 400px; margin: 0 auto 20px;">
						<a href="#add_update" class="btn btn-large btn-block btn-primary" data-toggle="modal">添加更新</a>
					</div>
					<?php } ?>
					<?php foreach($investments = Investments::get_investments($project_data->id) as $investment): ?>
					<div id="update<?php echo $investment->id; ?>">
						<h4 style="margin-top: 0px;"><?php echo $investment->title; ?></h4>
				
						<?php echo nl2br($investment->message); ?>
				
						<div style="margin-top: 5px; font-size: 12px; margin-bottom: -12px;">更新发布于: <?php echo datetime_to_text($investment->datetime); ?></div> 
						<?php if($user->user_id == $project_data->creator_id){ ?>
						<span class="right"><a href="JavaScript:void(0);" onclick="edit_update(<?php echo $investment->id; ?>);">编辑</a> - <a href="JavaScript:void(0);" onclick="delete_update(<?php echo $investment->id; ?>);">删除</a></span>
						<?php } ?>
					</div>
					<hr />
					<?php endforeach; if(empty($investments)){ ?>
						这个项目目前还没有任何更新。
					<?php } ?>
				</div>
				<div class="tab-pane fade" id="faq" style="overflow:hidden;">
					<?php if($project_data->creator_id == $user->user_id){ ?>
					<div class="well" style="max-width: 400px; margin: 0 auto 20px;">
						<a href="#add_faq" class="btn btn-large btn-block btn-primary" data-toggle="modal">添加FAQ</a>
					</div>
					<?php } ?>
					<?php foreach($faqs = Investments::get_faq($project_data->id) as $faq): ?>
					<div id="faq<?php echo $faq->id; ?>">
						<h4 style="margin-top: 0px;"><?php echo $faq->title; ?></h4>
				
						<?php echo nl2br($faq->message); ?>
				
						<?php if($user->user_id == $project_data->creator_id){ ?>
						<span class="right"><a href="JavaScript:void(0);" onclick="edit_faq(<?php echo $faq->id; ?>);">编辑</a> - <a href="JavaScript:void(0);" onclick="delete_faq(<?php echo $faq->id; ?>);">删除</a></span>
						<?php } ?>
					</div>
					<hr />
					<?php endforeach; if(empty($faqs)){ ?>
						这个项目目前还没有任何FAQ。
					<?php } ?>
				</div>
				<?php if($project_data->creator_id == $user->user_id){ ?>
				<div class="tab-pane fade" id="edit" style="overflow:hidden;">
					<div id="update_message"></div>
					<div class="row-fluid">
						<div class="span3">
							<label>项目标题 <em class="req">*</em></label>
					        <input type="text" name="title" id="title" class="span12" required="required" value="<?php echo $project_data->name; ?>" />
						</div>
						<div class="span3">
							<label>开始时间 <em class="req">*</em></label>
					        <input type="text" name="created" id="created" class="span12" required="required" disabled="disabled" value="<?php echo check_plain($project_data->created); ?>" />
						</div>
						<div class="span3">
							<label>结束时间 <em class="req">*</em></label>
					        <input type="text" name="expires" id="expires" class="span12" required="required" disabled="disabled" value="<?php echo check_plain($project_data->expires); ?>" />
						</div>
						<div class="span3">
							<label>目标 <em class="req">*</em></label>
							<div class="input-prepend input-append">
								<span class="add-on"><?php echo CURRENCYSYMBOL; ?></span>
								<input class="span9" id="goal" type="text" required="required" value="<?php echo check_plain($project_data->investment_wanted); ?>">
								<span class="add-on">.00</span>
							</div>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span6">
							<label>支持消息 <em class="req">*</em></label>
					        <input type="text" name="investment_message" id="investment_message" class="span12" required="required" value="<?php echo check_plain($project_data->investment_message); ?>" />
						</div>
						<div class="span6">
							<label>开始时间 <em class="req">*</em></label>
					        <input type="text" name="project_closed_message" id="project_closed_message" class="span12" required="required" value="<?php echo check_plain($project_data->project_closed_message); ?>" />
						</div>
					</div>
					<div class="row-fluid">
						<div class="span12">
							<label>描述 <em class="req">*</em></label>
					        <input type="text" name="description" id="description" class="span12" required="required" value="<?php echo check_plain($project_data->description); ?>" />
						</div>
					</div>
					<div class="row-fluid">
						<div class="span12">
							<label>主要描述 <em class="req">*</em></label>
							<textarea type="text" name="main_description" id="main_description" class="span12 wysiwyg" style="height: 100px;" required="required"><?php echo check_plain($project_data->main_description); ?></textarea>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span2">
							<label>热门主题</label>
							<select name="top_theme" id="top_theme" class="span12" value="<?php echo $top_theme ?>">
								<option value="1" <?php if($project_data->top_theme == '1') { echo 'selected="selected"';} else { echo ''; } ?>>启用</option>
								<option value="0" <?php if($project_data->top_theme == '0') { echo 'selected="selected"';} else { echo ''; } ?>>弃用</option> 
							</select>
						</div>
						<div class="span2">
							<label>自定义主题</label>
							<select name="custom_theme" id="custom_theme" class="span12" value="<?php echo $custom_theme ?>">
								<option value="1" <?php if($project_data->custom_theme == '1') { echo 'selected="selected"';} else { echo ''; } ?>>启用</option>
								<option value="0" <?php if($project_data->custom_theme == '0') { echo 'selected="selected"';} else { echo ''; } ?>>弃用</option> 
							</select>
						</div>
						<div class="span6">
							<label>视频地址 (http://www.youtube.com/watch?v=YE7VzlLtp-4 <strong>OR</strong> http://vimeo.com/1084537)</label>
					        <input type="text" name="video" id="video" class="span12" required="required" value="<?php echo check_plain($project_data->video); ?>" />
						</div>
					</div>
					<div class="row-fluid">
						<div class="span12 center">
							<button class="btn btn-primary" id="submit" onclick="update_project();">更新项目</button>
						</div>
					</div>
					
					<hr />
					
					<h3>项目图片</h3>
					
					<div class="well" style="max-width: 400px; margin: 0 auto 20px;">
						<a href="#image_upload" class="btn btn-large btn-block btn-primary" data-toggle="modal">上传图片</a>
					</div>
					<!-- Image Upload - Modal -->
					<div id="image_upload" class="modal hide fade uploader" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3 id="myModalLabel">上传图片</h3>
						</div>
						<div class="modal-body">
							<script type="text/javascript" src="assets/js/multiupload.js"></script>
							<script type="text/javascript">
							var config = {
								support : "image/jpg,image/png,image/bmp,image/jpeg,image/gif",
								form: "upload_form",
								dragArea: "dragAndDropFiles",
								uploadUrl: "includes/upload/project_upload.php?id=<?php echo $project_data->id; ?>"
							}
							$(document).ready(function(){
								initMultiUploader(config);
							});
							</script>
							<link href="<?php echo WWW; ?>assets/css/multiupload.css" type="text/css" rel="stylesheet" />
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
							
						</div>
						<div class="modal-footer">
							<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
						</div>
					</div>
					<ul class="thumbnails">
						<li class="span2">
							<div class="thumbnail">
								<img id="the_thumbnail" src="<?php echo WWW."/assets/project/".$project_data->id."/images/".$project_data->thumbnail; ?>" alt="Thumbnail" />
								<div class="clear"></div>
								<label>当前缩略图</label>
							</div>
						</li>
					</ul>
					<ul class="thumbnails">
						<?php
						$counter = 1;
						foreach(glob('./assets/project/'.$project_data->id.'/images/*.*') as $image): $image_name = str_replace('./assets/project/'.$project_data->id.'/images/','', $image); ?>
						<li class="span2" id="image<?php echo $counter; ?>">
							<div class="thumbnail">
								<img src="<?php echo WWW.$image; ?>" alt="Image <?php echo $counter; ?>" />
								<div class="clear"></div>
								<button class="btn btn-link" onclick="set_thumbnail('<?php echo $counter; ?>','<?php echo $image_name; ?>');">设为缩略图</button><br />
								<button class="btn btn-link" onclick="delete_image('<?php echo $counter; ?>','<?php echo $image_name; ?>');">删除</button>
								<div class="clear"></div>
							</div>
						</li>
						<?php $counter++; endforeach; ?>
					</ul>
					
					<hr />
					
					<h3>项目主题</h3>

					<ul class="thumbnails">
						<li class="span12">
							<div class="thumbnail">
								<img id="the_theme" src="<?php echo WWW."/assets/project/templates/".$project_data->theme; ?>"  style="height:250px" alt="项目主题" />
							</div>
						</li>
					</ul>
					<ul class="thumbnails">
						<?php
						$counter = 1;
						foreach(glob('./assets/project/templates/*.jpg') as $image): $image_name = str_replace('./assets/project/templates/','', $image); ?>
						<li class="span2" id="image<?php echo $counter; ?>">
							<div class="thumbnail">
								<img src="<?php echo WWW.$image; ?>" alt="Image <?php echo $counter; ?>" style="height: 60px;" />
								<div class="clear"></div>
								<button class="btn btn-link" onclick="set_theme('<?php echo $counter; ?>','<?php echo $image_name; ?>');">设置主题</button><br />
								<div class="clear"></div>
							</div>
						</li>
						<?php $counter++; endforeach; ?>
					</ul>
					
					<div class="well" style="max-width: 400px; margin: 0 auto 20px;">
						<a href="project_theme.php?id=<?php echo $project_data->id; ?>" class="btn btn-large btn-block btn-primary" >自定义主题</a>
					</div>
					
				</div>
				
				<?php } ?>
			</div>
			
			
			
		</div>
	</div>

</div>

<?php if($session->is_logged_in()){ ?> 
<?php if($project_status == "open"){ ?>
<!-- Confirm Investment - Modal -->
<div id="confirm_investment" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">确定支持</h3>
	</div>
	<div class="modal-body center">
		<div id="message"></div>
			
		<label>当前有效的积分</label>
        <label><span style="font-size: 23px;"><?php echo CURRENCYSYMBOL.number_format($user->credit, 0, '.', ',') ?></span></label>
		
		<hr />
		
		<label>请确认您的支持金额:</label>
		<div class="input-prepend input-append">
			<span class="add-on"><?php echo CURRENCYSYMBOL; ?></span>
			<input class="span2" id="current_investment" type="text">
			<span class="add-on">.00</span>
		</div>
		
		<label>请选择支付方式:</label>
		<select id="payment_option">
			<?php if($user->credit > 0){ ?>
			<option value="credit">账户积分</option>
			<?php } ?>
			<option value="ethpay">ETH以太坊</option>
			<option value="paypal">贝宝</option>
		</select>
		
		<label>请选择支持类型:</label>
		<select id="investment_type">
			<option value="0">公开</option>
			<option value="1">隐私</option>
		</select>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
		<button class="btn btn-primary" onclick="make_investment();">提交支持</button>
	</div>
</div>
<?php } ?>
<!-- Edit Comment - Modal -->
<div id="edit_comment" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">编辑评论</h3>
	</div>
	<div class="modal-body">
		<textarea id="textarea" class="span12" style="height: 100px; width: 97%;"></textarea>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
		<button id="confirm" class="btn btn-primary">更新</button>
	</div>
</div>

<!-- Confirm Delete - Comment - Modal -->
<div id="confirm_delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">确定删除</h3>
	</div>
	<div class="modal-body">
		<p>你确定删除这个评论? <br /> <strong>这个动作不能被逆转!</strong></p>
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">取消</button>
		<button id="confirm" class="btn btn-danger">确定</button>
	</div>
</div>

<!-- EditEdit Update - Modal -->
<div id="edit_update" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">编辑更新</h3>
	</div>
	<div class="modal-body">
		<label>标题</label>
		<input type="text" id="title" required="required" style="width: 97%;" value="" />
		<label>内容</label>
		<textarea id="textarea" required="required" style="height: 100px; width: 97%;"></textarea>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
		<button id="confirm" class="btn btn-primary">更新</button>
	</div>
</div>

<!-- Confirm Delete - Update - Modal -->
<div id="confirm_delete_update" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">确定删除</h3>
	</div>
	<div class="modal-body">
		<p>你确定删除这个评论? <br /> <strong>这个动作不能被逆转!</strong></p>
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">取消</button>
		<button id="confirm" class="btn btn-danger">确定</button>
	</div>
</div>

<!-- Add Update - Modal -->
<div id="add_update" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">添加更新</h3>
	</div>
	<div class="modal-body">
		<div id="message"></div>
		<label>标题</label>
		<input type="text" id="title" required="required" style="width: 97%;" value="" />
		<label>内容</label>
		<textarea id="textarea" required="required" style="height: 100px; width: 97%;"></textarea>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
		<button id="confirm" class="btn btn-primary" onclick="add_update();">添加</button>
	</div>
</div>

<!-- Edit Faq - Modal -->
<div id="edit_faq" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">编辑FAQ</h3>
	</div>
	<div class="modal-body">
		<label>标题</label>
		<input type="text" id="title" required="required" style="width: 97%;" value="" />
		<label>内容</label>
		<textarea id="textarea" required="required" style="height: 100px; width: 97%;"></textarea>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
		<button id="confirm" class="btn btn-primary">更新</button>
	</div>
</div>

<!-- Confirm Delete - Faq - Modal -->
<div id="confirm_delete_faq" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">确定删除</h3>
	</div>
	<div class="modal-body">
		<p>你确定删除这个FAQ吗?  <br /> <strong>这个动作不能被逆转!</strong></p>
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">关闭</button>
		<button id="confirm" class="btn btn-danger">确定</button>
	</div>
</div>

<!-- Add Faq - Modal -->
<div id="add_faq" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">添加FAQ</h3>
	</div>
	<div class="modal-body">
		<div id="message"></div>
		<label>标题</label>
		<input type="text" id="title" required="required" style="width: 97%;" value="" />
		<label>内容</label>
		<textarea id="textarea" required="required" style="height: 100px; width: 97%;"></textarea>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
		<button id="confirm" class="btn btn-primary" onclick="add_faq();">添加</button>
	</div>
</div>

<?php } ?>

<?php require_once("includes/themes/".THEME_NAME."/footer.php"); ?>