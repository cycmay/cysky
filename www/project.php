
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

	/*@import url(<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/css/bootstrap.css);
	@import url(<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/css/main.css);
	@import url(<?php echo WWW; ?>includes/global/css/custom.css);
	*/
	.table {
	    width: 100%;
	    margin-bottom: 20px;
	}
	table {
	    max-width: 100%;
	    background-color: transparent;
	    border-collapse: collapse;
	    border-spacing: 0;
	}
		
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
			if (comment == "") {
	            alert("请输入评论内容");
	            return;
	        }
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
		console.log('javascript:invest()');
		$('#confirm_investment #current_investment').attr("value",0);
		$('#confirm_investment').attr("style", "display:block");
	}

	function make_investment(){
		var amount = $("#confirm_investment #current_investment ").val();
		var payment =  "ethpay";//$("#confirm_investment #payment_option option:selected").val();
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

<style>
    .carry{
        color: #00a0e9;
    }
    .carry:hover .carry-tips{
        display: block;
    }

    .carry-tips{
        display: none;
        width: 320px;
        background-color: #ffffff;
        color: #333;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;
        position: absolute;
        z-index: 1;
        margin-left: -150px;
    }
</style>

<div class="box-team"
     style="background-image: url(<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/images/box-team.jpg)"
        >
    <div class="wp">
        <div class="team-txt mt40 mb50">
            <h2><?php echo $project_data->name; ?><span class="btn-m1 ml20">

            预热中
            <?php if(strtotime($project_data->expires) < time()){ ?>
			已关闭
			<?php } ?>
            </span></h2>

            <p><?php echo $project_data->investment_message; ?></p>
        </div>
        <div class="team-logo tc">
            <div class="pic">
                <img src="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/images/8870f65f-3b99-4fe0-95d5-d85379c5227d.png" alt="">
            </div>
            <a href="javascript:support(14)" class="zan" id="supportId">已有<?php echo Investments::count("made",$project_data->id); ?>人支持<i></i></a>
        </div>
        <div class="team-user c">
            <ul>
                <li><span>融资金额：</span><?php echo $project_data->investment_wanted ?></li>
                <li><span>创始人：</span><?php echo $project_creator->first_name." ".$project_creator->last_name; ?></li>
                <li class="i1"><span>团队人数：</span>5人</li>
                <li><span>出让股份：</span>10%</li>
                <li><span>标签：</span></li>
                <li class="i1"><span>地点：</span>上海市</li>
            </ul>
        </div>
    </div>
</div>

<div id="bd">
    <div class="wp">
        <div class="tit-a1">
            <h3>融资进度</h3>
        </div>
        <div class="rate">
            <div class="rate-tit">
                <table width="100%">
                    <tr>
                        <td><span class="tl">总认购金额：<?php echo $project_data->amount_invested?>ETH</span></td>
                        <td><span class="tc">认购人数：<?php echo Investments::count("made",$project_data->id); ?></span></td>
                        <td><span class="tr">剩余天数：<?php time() - strtotime($project_data->expires) ?>天</span></td>
                    </tr>
                </table>
            </div>
            <div class="rate-load">
                <div class="load load-ani">
                    <span style=width:<?php echo $percentage = Investments::get_percentage($project_data->amount_invested,$project_data->investment_wanted) ?>%></span><em><b><?php echo $percentage?></b>%</em>
                </div>
            </div>
            <div class="rate-btn">

            	<?php if($project_status == "open"){ ?>
					<?php if($session->is_logged_in()){?>
					
					<a class="a1" href="javascript:invest()">我要支持</a>
					
					<?php } else { ?>
					您必须 <a href="<?php echo WWW; ?>login.php">登录</a> 来支持。
					<?php }} else { ?>
					<div class="investment_message"><?php echo $project_data->project_closed_message; ?></div>
				<?php } ?>

                <a href="javascript:aplayInvest(0)" class="a1" id="btnInvest" style="display:none">我要投资<em>(3万元起投)</em></a>
                <a href="https://www.mayiangel.com/project/addAplayInvest/2343.htm" class="a1" id="btnAddInvest" style="display:none">追加认购</a>
                <a href="javascript:cancelInvest()" class="a1" id="btnCancelInvest" style="display:none">取消认购</a>
                <a href="https://www.mayiangel.com/project/payMoney/2343.htm" class="a1" id="btnNowPayMoney" style="display:none">立即打款</a>
                <a href="https://www.mayiangel.com/project/addPayMoney/2343.htm" class="a1" id="btnAddPayMoney" style="display:none">追加打款</a>
                <a href="" id="btnFinishPayMoney" style="display:none">已打款</a>
                <a href="https://www.mayiangel.com/project/signContract/2343.htm" class="a1" id="btnAfterStatus" style="display:none">待签署有限合伙协议</a>
                <a href="javascript:void()" class="a2 trigger-pop" id="btnTeamGroup" style="display:none">联系创业团队</a>
                <a href="javascript:void()" class="a2 trigger-pop" id="btnInvestGroup" style="display:none">加入投资群讨论</a>
                <a href="javascript:void()" class="a2 trigger-pop" id="btnGuDongGroup" style="display:none">加入股东群讨论</a>
                <?php if($project_status == "open"){ ?>
					<!-- Confirm Investment - Modal -->
					<!--
					<div id="confirm_investment" class="pop-box1 hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3 id="myModalLabel">确定支持</h3>
						</div>
						<div class="modal-body center">
							<div id="message"></div>
							
							<label>请确认您的支持金额:</label>
							<div class="input-prepend input-append">
								<input class="span2" id="confirm_investment" type="text">
								<span class="add-on">.00</span>
							</div>
						</div>
						<div class="modal-footer">
							<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
							<button class="btn btn-primary" onclick="make_investment();">提交支持</button>
						</div>
					</div>
					-->
					<div class="box-logreg hide" id="confirm_investment">
						<dl class="m1-z">
							<dt>
								<h3>蚂蚁天使</h3>
								<p>专注于种子轮的创投平台</p>
							</dt>
							<dd>
								<h1>确定支持</h1>
								<div id="message"></div>
									<tr>
										<td>请输入您的支持金额:</td>
									</tr>
									<tr>
										<td>
											<input type="text" id="current_investment" class="txt1"  />
										</td>
									</tr>
									<div class="c"></div>
									<div class="z-mk">
									
									</div>

									<div class="btn-pl">
										<button  class="btn-z" onclick="$('#confirm_investment').attr('style','display:none')">关 闭</button>
										<button onclick="make_investment()" class="btn-z btn-z1 r">提 交
									</div>
							</dd>
						</dl>
					</div>
				<?php } ?>
                <div class="pop-box1" id="aplayInvestDIV">
                    <em></em>
                    <div class="pad">
                        <h3>只有认证投资人才能投资项目</h3>
                        <p>只需三步您就可以认证投资人</p>
                    </div>
                    <div class="pop-btn"><a href="" class="fc6">取消</a><a href="/member/normal/applyInvestor.htm"><b>立即认证</b></a></div>
                </div>
            </div>
        </div>
    </div>
    <div class="bgf">
        <div class="wp">
        </div>
    </div>
    <div class="wp">
        <div class="tit-a1">
            <h3>项目动态</h3>
        </div>
        
        <!--<div class="tab-pane fade" id="updates" style="overflow:hidden;">
			
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
		-->

        <ul class="ul-news">
        	<?php if($project_data->creator_id == $user->user_id){ ?>
				<div style="max-width: 100px; margin: 0 auto 10px;">
					<a href="#add_update" class="btn-z" data-toggle="modal">添加更新</a>
				</div>
				
			<?php } ?>
        	<?php foreach($investments = Investments::get_investments($project_data->id) as $investment): ?>
			<li id="update<?php echo $investment->id; ?>" class="person-info">
				<h4 style="margin-top: 0px;">Title: <?php echo $investment->title; ?></h4>
				<p>
					Message: <?php echo nl2br($investment->message); ?>
				</p>
           		<span>
                	<?php echo datetime_to_text($investment->datetime); ?>
                </span>
                <?php if($user->user_id == $project_data->creator_id){ ?>
					<span class="right"><a href="JavaScript:void(0);" onclick="edit_update(<?php echo $investment->id; ?>);">编辑</a> - <a href="JavaScript:void(0);" onclick="delete_update(<?php echo $investment->id; ?>);">删除</a></span>
				<?php } ?>
                
            </li>
            <?php endforeach; if(empty($investments)){ ?>
				这个项目目前还没有任何更新。
			<?php } ?>
        </ul>
        <div class="h"></div>
        <div class="h50"></div>
    </div>
    
    <div class="bgf">
        <div class="wp">
            <div class="row-2a fix">
                <div class="col-l">
                    <ul class="ul-project fix mb25 TAB_CLICK" id=".tab-con1">
                        <li><a href="">项目详情 <em></em></a></li>
                    </ul>
                    <div class="tab-con1">
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
										<?php } ?>
							</div>
						</div>
                        <div class="h20"></div>
                        <div class="tit-c1">
                            <h3>项目简介</h3>
                        </div>
                        <div class="box-deta">
                            <div class="txt-box-j1">
                                <p class="txt">
                                	<?php echo $project_data->description; ?>
										<hr />
									<?php echo nl2br($project_data->main_description); ?>
                                </p>
                            </div>
                            <div class="tit-c1">
                                <h3>视频介绍</h3>
                            </div>
                            <div class="item-video">
                                <div class="game163">
                                    <div class="bigimg-box">
                                        <div class="bigImg">
                                            <iframe height="325" width="690" src="" frameborder=0 allowfullscreen=""></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tit-c1">
                                <h3>一句话介绍</h3>
                            </div>
                            <div class="txt-box">
                                <p class="txt"><?php echo $project_data->description; ?></p>
                                <a class="p-load">更多</a>
                            </div>
                       		<!--
                            <div class="tit-c1">
                                <h3>用户痛点</h3>
                            </div>
                            <div class="txt-box">
                                <p class="txt">我们用户是电动自行车高频使用用户，如快递、外卖从业人员和新零售配送人员。
                                    <br>
                                    <br>行业痛点：急需充电快、价格便宜的优质电池！
                                    <br>1、传统充电要8小时，导致配送效率低下
                                    <br>2、传统电池高频使用只能用半年，年年换新多花钱
                                    <br>3、市面现有锂电池动辄几千元，买不起</p>
                                <a class="p-load">更多</a>
                            </div>
                            <div class="tit-c1">
                                <h3>产品服务</h3>
                            </div>
                            <div class="txt-box">
                                <p class="txt">公司向目标用户租赁电池模组，电池组30min能充满，充满能跑60km，寿命能用5年。
                                    <br>公司向用户收取押金1500元，租金3元/天。</p>
                                <a class="p-load">更多</a>
                            </div>
                            <div class="tit-c1">
                                <h3>竞争对手</h3>
                            </div>
                            <div class="txt-box">
                                <p class="txt">1、我们的电池组充电速度全网最快、价格全网最低。
                                    <br>2、拥有上游最优质的电芯资源渠道
                                    <br>3、上万公里用户路测大数据， （调教出最优化经验参数）
                                    <br>4、已提前布局相关专利等知识产权</p>
                                <a class="p-load">更多</a>
                            </div>
                            <div class="tit-c1">
                                <h3>财务及用户数据分析</h3>
                            </div>
                            <div class="txt-box">
                                <p class="txt">目前已经在上海陆家嘴和张江落地运营。已经有一批种子用户，用户反馈良好，经过他们口碑相传，目前意向订单源源不断。
                                    <br>公司快充电池可产生1500元押金和89元/月的现金流，10个月可回收成本支出，5年投资收益率在222.5%。</p>
                                <a class="p-load">更多</a>
                            </div>
                            <div class="tit-c1">
                                <h3>核心价值及项目亮点</h3>
                            </div>
                            <div class="txt-box">
                                <p class="txt">核心价值：将电池性能做到最优的情况下，把价格打到最低。
                                    <br>
                                    <br>亮点：
                                    <br>1、先发优势。技术领先同行
                                    <br>2、订单源源不断
                                    <br>3、可快速复制推广，迅速占领市场
                                    <br>4、上游锂电资源和渠道丰富</p>
                                <a class="p-load">更多</a>
                            </div>
                            -->
                        </div>
                    	
                        <div class="tit-b1">
                            <span>全部问答</span>
                        </div>

						 <?php echo $investment_messages = Investments::display_investments_messages($project_data->id); ?>
							<?php if(empty($investment_messages)){ ?>
								这个项目目前还没有任何评论。
						<?php } ?>

						<!-- 回复框 -->

						<p class="mb5">我的提问</p>
						<div class="textarea1-j-box">
						    <textarea name="content" id="wall_message" placeholder="输入你要问的问题" id="" class="textarea1-j"  maxlength="600"></textarea>
						</div>

						<div class="btn-j tr">
							<button  class="btn-z " id="submit_project_comment"> 确定</button>
							<span id="wall_message_counter">600</span> 剩余字符 
						</div>
				</div>
				<div class="col-r">
                    <div class="h32"></div>
                    <div class="tit-c1">
                        <h3>建立者</h3>
                    </div>
                    <div class="box-name">


						<div class="pic">
							<?php if($pc_profile->profile_picture == "male.jpg" || $pc_profile->profile_picture == "female.jpg"){ ?>
							<img class="pic1" height="94" width="94" src="assets/img/profile/<?php echo $pc_profile->profile_picture; ?>" alt="头像">
							<?php } else { ?>
							<img class="pic1" height="94" width="94" src="assets/img/profile/<?php echo $pc_profile->user_id."/".$pc_profile->profile_picture; ?>" alt="头像">
							<?php } ?>
						</div>
						<div class="tit">
							<h2><?php echo $project_creator->first_name." ".$project_creator->last_name; ?></h2>
							<div class="clearfix" style="height: 6px;"></div>
							<!--<label>联系地址: <strong><?php echo $project_creator->country; ?></strong></label>-->
							<label>项目总数: <strong><?php echo Investments::count_started_projects($project_creator->user_id); ?></strong></label>
							<label>已获支持: <strong><?php echo Investments::count("made",$project_data->id); ?></strong></label>
							<label>资料信息: <strong><?php echo $pc_profile->profile_msg; ?></strong></label>
						</div>
                        <div class="txt c">
                            <h2>投资理由：</h2>
                            <p>通过近一个半月的调研，认为该项目具有可行性，值得投资。根据过往投资经验，认为本人符合作领投人的要求，具备领投人资格。
                                <br>领投理由如下：
                                <br>1、团队：团队搭配比较合理，创始人是多学科跨界型的创业者，年轻有激情，有野心；做事比较低调务实，这种性格对创业很有帮助。
                                <br>2、产品&技术：电允超级快充电池在性能上确实远超市场同类产品，具备颠覆性的技术优势。通过和李总以及用户的接触和沟通，用户体验和反馈确实非常不错。另外项目上下游供应链资源也有足够保障。
                                <br>3、商业模式：前期通过饿了么、达达平台的配送员已经打开市场。电池租赁业务带来的现金流稳定可靠。项目下一步即将展开的分布式充电桩增值服务具备足够的利润空间和想象力。
                                <br>4、市场：新零售的风口已经开启，1小时达、2小时达开始成为更多消费场景的标配。两轮电动车作为新零售配送基础设施，目前仍非常落后。这个市场足够诞生独角兽企业。
                                <br>5、估值：目前业务类似的同类创业公司估值最高已超过5亿。电允的产品和模式具备快速爆发的潜力，目前1300万的估值水平合理。
                                <br>总体而言，创业方向正确，未来前景较大，值得现在布局投资！</p>
                        </div>
                    </div>
                    <div class="h20"></div>
                    <div class="tit-c1">
                        <h3>投资人<span><?php echo Investments::count("made",$project_data->id); ?></span></h3>
                    </div>
                    <div class="tab-pane" id="investors">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>用户名</th>
									<th>支持金额</th>
									<th>支持时间</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($investors = Investments::get_investors($project_id) as $investment): $user_details = User::get_investor_details($investment->user_id); ?>
								<tr>
									
									<td><?php echo $user_details[0]->username; ?></td>
									<td><?php echo "ETH"." ".$investment->amount; ?></td>
									<td><?php echo date_to_text($investment->date_invested); ?></td>
								</tr>
								<?php endforeach; if(empty($investors)){ ?>
									<tr>
										<td colspan="5">还没有任何支持者。</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
                    
                    <div class="h"></div>
                    <div class="tit-c1 c">
                        <h3>分享</h3>
                    </div>
                    <div class="box-share">
                        <a href="" class="share-wx pl10"><img src="<?php echo WWW.'includes/themes/'.THEME_NAME.'/images/weixin.png' ?>" height="30" width="30" alt=""></a>
                        <div class="h"></div>
                        <div class="share">
                            <a href="" class="s-xbtn"></a>
                            <ul>
                                <li>
                                    <p>1.用微信扫一扫下面的二维码</p>
                                </li>
                                <li>
                                    <p>2.扫描成功后，点击右上角的按钮分享给微信好友或朋友圈</p>
                                </li>
                                <li><img src="<?php echo WWW.'includes/themes/'.THEME_NAME.'/images/share-02.png' ?>" height="154" width="155" alt=""></li>
                            </ul>
                        </div>
                    </div>
                    <div class="h"></div>
                    <div class="box-line">
                        <div class="txt">
                            <div class="tit">
                                <h2><a href=""><?php echo $project_data->name; ?></a></h2>
                                <p><?php echo $project_data->investment_message; ?></p>
                            </div>
                            <div class="cen">
                                <p class="p1">融资金额：<?php echo $project_data->investment_wanted ?></p>
                                <div class="load load-b1">
                                    <span style=width:<?php echo $percentage?>%>　</span><em><b><?php echo $percentage?></b>%</em>
                                </div>
                                <p class="link-user pt5">
                                    创建人：<?php echo $project_creator->first_name." ".$project_creator->last_name; ?></p>
                                <p class="s1">已有 <?php echo Investments::count("made",$project_data->id); ?> 人认购投资</p>
                                <p class="p2">
                                    <a href="">标签： </a>
                                    <span class="r">上海市</span>
                                </p>
                                <div class="h15"></div>
                                <a href="javascript:aplayLead(1)" class="btn-w1" id="btnAplayLead1" style="display:none">申请领投</a>
                                <a href="javascript:aplayInvest(1)" class="btn-w1" id="btnInvest1" style="display:none">我要投资<span>(3
                                        万元起投)</span></a>
                                <a href="https://www.mayiangel.com/project/addAplayInvest/2343.htm" class="btn-w1" id="btnAddInvest1" style="display:none">追加认购</a><br>
                                <a href="javascript:cancelInvest()" class="btn-w1" id="btnCancelInvest1" style="display:none">取消认购</a>
                                <a href="https://www.mayiangel.com/project/payMoney/2343.htm" class="btn-w1" id="btnNowPayMoney1" style="display:none">立即打款</a>
                                <a href="https://www.mayiangel.com/project/addPayMoney/2343.htm" class="btn-w1" id="btnAddPayMoney1" style="display:none">追加打款</a>
                                <a href="https://www.mayiangel.com/project/signContract/2343.htm" class="btn-w1" id="btnAfterStatus1" style="display:none">待签署有限合伙协议</a>
                                <div class="pop-box1" id="aplayLeadDIV1">
                                    <em></em>
                                    <div class="pad">
                                        <h3>只有认证领投人才能申请领投项目</h3>
                                        <p>只需五步您就可以申请认证领投人</p>
                                    </div>
                                    <div class="pop-btn"><a href="" class="fc6">取消</a><a href="/member/leader/applyInvestor.htm"><b>立即认证</b></a></div>
                                </div>
                                <div class="pop-box1" id="aplayInvestDIV1">
                                    <em></em>
                                    <div class="pad">
                                        <h3>只有认证领投人才能申请领投项目</h3>
                                        <p>只需五步您就可以申请认证领投人</p>
                                    </div>
                                    <div class="pop-btn"><a href="" class="fc6">取消</a><a href="/member/normal/applyInvestor.htm"><b>立即认证</b></a></div>
                                </div>
                                <div class="pop-box1" id="canInvest">
                                    <em></em>
                                    <div class="pad">
                                        <h3>只有认证领投人才能申请领投项目</h3>
                                        <p>只需五步您就可以申请认证领投人</p>
                                    </div>
                                    <div class="pop-btn"><a href="" class="fc6">取消</a><a href="/member/normal/applyInvestor.htm"><b>立即认证</b></a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="js/jquery.SuperSlide.js"></script>
<script type="text/javascript">
(function() {
    //回复
    var qa_form = $('.qa-form');
    $('.qa-reply').click(function() {
        var parent = $(this).parents('[class^="qa"]');
        qa_form.css({ "display": 'none' }).appendTo(parent).show();
    });

    $('.qa-form .a2').click(function(event) {
        qa_form.hide();
    });
})();

// 微信弹出
(function() {
    var share = $('.share');

    $('.share-wx').hover(function() {
        var timeout = $(this).data("timeout");
        if (timeout) clearTimeout(timeout);
        share.addClass('show-box');
    }, function() {
        $(this).data("timeout", setTimeout($.proxy(function() {
            share.removeClass('show-box');
        }, this), 500));
    });

    share.hover(function() {
        var timer = $('.share-wx').data('timeout');
        if (timer) clearTimeout(timer);
    }, function() {
        $(this).removeClass('show-box');
    });
    $('.s-xbtn').click(function() {
        share.removeClass('show-box');
        return false;
    })


})();
</script>
<script>
(function() {
    $('.txt-box .txt').each(function() {
        if ($(this).height() > 60) {
            $(this).addClass('h-60 long-des');
        } else {
            $(this).next('.p-load').hide();
        }
    })

    $('.txt-box .p-load').click(function() {
        var all_con = $('.txt-box .txt');
        this_con = $(this).parents('.txt-box').find('.txt');

        if (this_con.is('.h-60')) {
            this_con.removeClass('h-60');
            $(this).text('收起').addClass('p-load1');

        } else {
            if (this_con.is('.long-des')) {
                this_con.addClass('h-60');
                $(this).text('更多').removeClass('p-load1');
            }
        }

    });

})();
(function() {
    $('.box-pop').click(function() {
        var pop = $(this).siblings('.pop-box1');
        pop.show();
        return false;
    });
    $('.pop-btn .fc6').click(function(event) {
        var pop = $(this).parents('.pop-box1');
        pop.hide();
        return false;
    });
    $('body').click(function(e) {
        var pop = $(".pop-box1");
        if (!pop.is(e.target) &&
            pop.has(e.target).length === 0) {
            pop.hide();
        }
    })
})();
(function() {
    var float_box = $('.box-line');
    float_box.css("position", "static");
    float_box.show();
    var boxTop = float_box.offset().top;
    float_box.hide();
    if (float_box.length > 0) {
        //var stickyTop = float_box.offset().top;              
        $(window).scroll(function() {
            var windowTop = $(window).scrollTop();
            // console.log("盒子距离顶部：" + boxTop);
            // console.log("滚动条：" + windowTop);

            if (boxTop < windowTop) {
                float_box.css("position", "fixed");
            } else {
                float_box.css("position", "static");
            }
            if (boxTop - 700 < windowTop) {
                //float_box.css({ position: 'fixed', top: 0 });
                float_box.fadeIn(200)
            } else {
                //float_box.css('position','relative');
                float_box.fadeOut(200)
            }

        });
    }
})();
(function() {
    $('.ta-a1').each(function() {
        var num = $(this).find('tr').length;
        $(this).find('tr:first').find('th:first').attr('rowspan', num);
    })
})();
</script>



<?php if($session->is_logged_in()){ ?> 
<?php if($project_status == "open"){ ?>
<!-- Confirm Investment - Modal 
<div id="confirm_investment" class="pop-box1 hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
-->
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