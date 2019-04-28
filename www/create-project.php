

<?php require_once("includes/inc_files.php"); 
require_once("gateway/ethpay.php");  

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
			//redirect_to($location."?step=".$_SESSION['current_step']);
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
<div class="wp topline">
	    <div class="tit-a1">
	        <h3>创业项目</h3>
	    </div>
	    <div class="setup-step">
	        <ul>
	            <li class="s1 <?php if($step == 1){echo "on";} ?>">填写基本信息</li>
	            <li class="s2 <?php if($step == 2){echo "on";} ?>">上传项目图片</li>
	            <li class="s3 <?php if($step == 3){echo "on";} ?>">填写项目概要</li>
	        </ul>
	    </div>
<?php echo output_message($message); ?>
	<?php if($step == 1) { ?>	
	    <div class="tit-a5">
	        项目基本信息<font style="font-size: 13px;margin-left: 10px">（ * 为必填 ）</font>
	    </div>

	    <div class="box-table-za box-table-za2 pt25">
	        <form id="form" action="<?php echo $location; ?>?step=1" method="post">


	            <ul class="zxXXX">
	                <li>蚂蚁天使专注于种子轮项目的投资，我们希望项目的特点是：</li>
	                <li><font>小：</font>估值2500万以下</li>
	                <li><font>新：</font>创新与高成长潜力</li>
	                <li><font>鲜：</font>成立时间不超过18个月</li>
	                <li>以下填写的文字非常重要，是投资人判断项目的重要依据，可以提高融资效率，每一项都请认真填写！
	                </li>
	            </ul>
	            <table>
	                <tr>
	                    <td><strong>* 标题：</strong></td>
	                    <td><input type="text" data-validation="nomessage" name="title" value="<?php echo $title; ?>" id=""
	                               class="txt txt2" placeholder="项目名称，最多10字"/></td>
	                </tr>
	                <tr>
	                	<td><strong>* 分类</strong></td>
	                	<td><select name="category">
	                		<?php foreach(Investments::get_categories() as $category): ?>
							<option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
							<?php endforeach; ?>
	                		</select>
	                	</td>
	                </tr>
	                <tr>
	                    <td valign='top'><strong class="pt15">* 融资信息：</strong></td>
	                    <td>
	                        <div class="link">
	                            <p class="l">融资 <input data-validation="nomessage" type="text" name="goal"
	                                                   value="<?php echo htmlentities($goal); ?>"
	                                                   id="goal" class="rzInput txt txt3"> ETH<span
	                                    style="font-size: 14px"></span></p>
	                            <p class="r">估值 <input data-validation="nomessage"
	                                                   type="text"
	                                                   name="" id="in-b" class="gzInput txt txt3">ETH <span
	                                    style="font-size: 14px"></span></p>
	                            <input id="stock" type="hidden" name="stock" value="">
	                            <input type="hidden" name="create_project" value="" />
	                        </div>
	                    </td>
	                </tr>
	                <tr>
	                    <td valign='top'><strong class="">出让股份比例：</strong></td>
	                    <td style="padding-bottom: 40px">
	                        <span class="crgfbl"
	                              style="font-size: 18px;color: #00a0e9">
	                            0% </span>
	                    </td>
	                </tr>
	                <tr>
	                    <td><strong>* 结束 (天)</strong></td>
	                    <td><input type="text" data-validation="nomessage" name="expires" value="<?php echo $expires; ?>" id="expires" class="txt txt2"/></td>
	                </tr>
	                <tr>
	                    <td><strong>* 投资信息：</strong></td>
	                    <td><input type="text" data-validation="nomessage" name="investment_message" id="" value="<?php echo $investment_message; ?>" class="txt txt2" placeholder="一句话介绍你的项目"/></td>
	                </tr>
	                <tr>
	                    <td><strong>* 完整消息：</strong></td>
	                    <td><input type="text" data-validation="nomessage" name="complete_message" id="" value="<?php echo $complete_message; ?>" class="txt txt2" /></td>
	                </tr>
	                <tr>
	                    <td><strong>* 描述：</strong></td>
	                    <td><input type="text" data-validation="nomessage" name="description" id="" value="<?php echo $description; ?>" class="txt txt2" /></td>
	                </tr>
	                <tr>
	                    <td><strong>* 主要内容：</strong></td>
	                    <td><input type="text" data-validation="nomessage" name="main_description" id="" value="<?php echo $main_description; ?>" class="txt txt" /></td>
	                </tr>

	                <tr>

	                    <td><strong>* 所在地区：</strong></td>
	                    <td><select id="receipt_area_province"
	                                value="" name="cityFirstId"
	                                data-id="" >
	                    </select></td>
	                    <!-- <td><select id="receipt_area_province"
	                                value="" name="cityFirstId"
	                                data-id="" onchange="loadCitySecond(this.value);">
	                    </select> <select id="receipt_area_city" name="citySecondId"
	                                      value="" data-id=""
	                                      onchange="loadCity();">
	                    </select> <input type="hidden" id="cityFirstName" name="cityFirstName"></input>
	                        <input type="hidden" id="citySecondName" name="citySecondName"></input></td>
						-->
	                </tr>
	                <tr>
	                    <td><strong>* 所属行业：</strong></td>
	                    <td><select name="projectindustry" value=""> <option value="0">无</option> 
	                        <option 
	                                value="1">人工智能</option>
	                    
	                        <option 
	                                value="2">硬件</option>
	                    
	                        <option 
	                                value="3">软件工具</option>
	                    
	                        <option 
	                                value="4">社交</option>
	                    
	                        <option 
	                                value="5">企业服务</option>
	                    
	                        <option 
	                                value="6">新技术</option>
	                    
	                        <option 
	                                value="7">教育</option>
	                    
	                        <option 
	                                value="8">医疗</option>
	                    
	                        <option 
	                                value="9">金融</option>
	                    
	                        <option 
	                                value="10">房产家居</option>
	                    
	                        <option 
	                                value="11">交通出行</option>
	                    
	                        <option 
	                                value="12">物流</option>
	                    
	                        <option 
	                                value="13">电子商务</option>
	                    
	                        <option 
	                                value="14">共享经济</option>
	                    
	                        <option 
	                                value="15">消费生活</option>
	                    
	                        <option 
	                                value="16">体育</option>
	                    
	                        <option 
	                                value="17">文化娱乐</option>
	                    
	                        <option 
	                                value="18">媒体广告</option>
	                    
	                        <option 
	                                value="19">游戏动漫</option>
	                    
	                        <option 
	                                value="20">旅游</option>
	                    
	                        <option 
	                                value="21">能源环保</option>
	                    
	                        <option 
	                                value="22">农业</option>
	                    </select>
	                </tr>
	                <tr>
	                    <td><strong>* 教育背景：</strong></td>
	                    <td><select name="projecteducation">  <option value="0">其他</option>     
	                        <option 
	                                value="1">学霸创业</option>
	                    
	                        <option 
	                                value="2">普通大学</option>
	                    
	                        <option 
	                                value="3">海归</option>
	                    
	                        <option 
	                                value="4">985</option>
	                    
	                        <option 
	                                value="5">211</option>
	                    
	                        <option 
	                                value="6">双一流</option>
	                    </select>
	                </tr>
	                <tr>
	                    <td><strong>* 工作背景：</strong></td>
	                    <td><select name="projectwork">   <option value="0">其他</option>    
	                        <option 
	                                value="1">高管创业</option>
	                    
	                        <option 
	                                value="2">业内资深</option>
	                    
	                        <option 
	                                value="3">技术大牛</option>
	                    
	                        <option 
	                                value="4">BATJ背景</option>
	                    
	                        <option 
	                                value="5">连续创业者</option>
	                    
	                        <option 
	                                value="6">成功创业者</option>
	                    </select>
	                </tr>
	                <tr>
	                    <td><strong>* 项目进展：</strong></td>
	                    <td><select name="projectprocess">   <option value="0">无</option>    
	                        <option 
	                                value="1">想法创意</option>
	                    
	                        <option 
	                                value="2">研发中</option>
	                    
	                        <option 
	                                value="3">上线运营</option>
	                    
	                        <option 
	                                value="4">已有收入</option>
	                    
	                        <option 
	                                value="5">盈亏平均</option>
	                    
	                        <option 
	                                value="6">快速成长</option>
	                    </select>
	                </tr>
	                <tr>
	                    <td><strong>* 年龄：</strong></td>
	                    <td><select name="projectage">  <option value="0">其他</option>    
	                        <option 
	                                value="1">70后</option>
	                    
	                        <option 
	                                value="2">80后</option>
	                    
	                        <option 
	                                value="3">85后</option>
	                    
	                        <option 
	                                value="4">90后</option>
	                    
	                        <option 
	                                value="5">95后</option>
	                    
	                        <option 
	                                value="6">00后</option>
	                    </select>
	                </tr>       
	            </table>
	        <div class="error"></div>
	        </form>
    	</div>
    	<div class="btn-zz btn-zz1 mb90">
      		<a class="validation-submit" href="javascript:submit();" >下一步：填写团队成员</a>
    	</div>
    </div>

    <script type="text/javascript">
    	$.validate({
	        modules: 'toggleDisabled',
	        showErrorDialogs: true
   		});
    	$(".gzInput,.rzInput").keyup(function () {
		    var rz = parseInt($(".rzInput").val());
		    var gz = parseInt($(".gzInput").val());
		    var result = Math.round((rz / gz) * 100 * 100) / 100;
		    $(".crgfbl").html(result + "%");
		    $("#stock").val(result);
		    if ($(".crgfbl").html() == "NaN%" || $(".crgfbl").html() == "Infinity%") {
		        $(".crgfbl").html("0%")
		    }
		});	
    </script>

	
	<?php } ?>
	<?php if($step == 2) { ?>
		<link href="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/css/memberInfo.css" rel="stylesheet">
		<link href="assets/css/multiupload.css" type="text/css" rel="stylesheet" />
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

				var my_post = {
					type: "POST",
					url: "data.php",
					data: "page=project&publish_listing=true&title="+title+"&category="+category+"&goal="+goal+"&investment_message="+investment_message+"&project_closed_message="+complete_message+"&description="+description+"&main_description="+main_description,
					}

				App.init();
				App.creatFunding(title, goal, my_post);	
				
			} else {
				
			}		
		}
		</script>
		
		<fieldset>
			<legend>项目信息</legend>
			<div class="box-table-za box-table-za3">
				<div class="box-table-clone">

				<table>
                    <tr>
                        <td><strong>标题：</strong></td>
			      		<td><input type="text" class="txt txt2" id="title" disabled="disabled" value="<?php echo $project_data->name; ?>" /></td>
			      	</tr>
			      	<tr>
                        <td><strong>分类：</strong></td>
			      		<td><select name="category" disabled="disabled">
	                		<?php foreach(Investments::get_categories() as $category): ?>
							<option value="<?php echo $category->id; ?>"<?php if($project_data->category_id == $category->id){echo " selected='selected'";} ?>><?php echo $category->name; ?></option>
							<?php endforeach; ?>
	                		</select>
	                	</td>
			      	</tr>
			      	<tr>
                        <td><strong>目标：</strong></td>
			      		<td><input type="text" class="txt txt2" id="title" disabled="disabled" value="<?php echo htmlentities($project_data->investment_wanted); ?>" /></td>
			      	</tr>
			      	<tr>
	                    <td><strong>结束时间：</strong></td>
	                    <td><input type="text" data-validation="nomessage" name="expires" disabled="disabled" value="<?php echo $project_data->expires; ?>" id="expires" class="txt txt2"/></td>
	                </tr>
	                <tr>
	                    <td><strong>投资信息：</strong></td>
	                    <td><p class="txt"><?php echo $project_data->investment_message; ?></p></td>
	                </tr>
	                <tr>
	                    <td><strong>完整消息：</strong></td>
	                    <td><p class="txt"><?php echo $project_data->project_closed_message; ?></p></td>
	                </tr>
	                <tr>
	                    <td><strong>描述：</strong></td>
	                    <td><p class="txt"><?php echo $project_data->description; ?></p></td>
	                </tr>
	                <tr>
	                    <td><strong>主要内容：</strong></td>
	                    <td><p class="txt"><?php echo $project_data->main_description; ?></p></td>
	                </tr>

				</table>
				</div>
			</div>
		</fieldset>
		
		<div class="form-actions" style="text-align: center;margin: 20px -10px -10px;">
			<button class="btn btn-danger" onclick="cancel_listing();">取消发布</button> <button class="btn btn-primary" onclick="publish_listing();">确定发布</button>
		</div>
	<?php } ?>

<?php require_once("includes/themes/".THEME_NAME."/footer.php"); ?>