

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
			//console_log($_SESSION['current_step']);
			$title = clean_value($_POST['title']);
			//$category = clean_value($_POST['category']);
			$goal = clean_value($_POST['goal']);
			//$expires = clean_value($_POST['expires']);
			$description = clean_value($_POST['description']);

			$_SESSION['current_step'] = 2;
			redirect_to(WWW."create-project.php?step=2");
			
		} else {
			$title = "";
			$goal = "";
			$description = "";
		}
	} else if ($step ==2) {
		if(isset($_POST['create_project'])){
			$_SESSION['current_step'] = 3;
			redirect_to(WWW."create-project.php?step=3");
		}else {
			console_log("hello!!!!");
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
	            <li class="s1 <?php if($step == 1){echo "on";} ?>">项目基本信息</li>
	            <li class="s2 <?php if($step == 2){echo "on";} ?>">团队成员</li>
	            <li class="s3 <?php if($step == 3){echo "on";} ?>">填写项目概要</li>
	            <li class="s4 <?php if($step == 4){echo "on";} ?>">完善项目材料</li>
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
	                    <td><strong>* 项目名称：</strong></td>
	                    <td><input type="text" data-validation="nomessage" name="title" value="<?php echo $title; ?>" id=""
	                               class="txt txt2" placeholder="项目名称，最多10字"/></td>
	                </tr>
	                <tr>
	                    <td><strong>* 一句话介绍：</strong></td>
	                    <td><input type="text" data-validation="nomessage" name="shortSentence" id=""
	                               value="<?php echo $description; ?>" class="txt txt2" placeholder="一句话介绍你的项目，最多20字"/></td>
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
	                <tr>
	                    <td><strong>* 融资金额：</strong></td>
	                    <td><select name="projectmoney">  <option value="0">无</option>    
	                        <option 
	                                 value="1">1-5ETH</option>
	                    
	                        <option 
	                                 value="2">51-100 ETH</option>
	                    
	                        <option 
	                                 value="3">101-200 ETH</option>
	                    
	                        <option 
	                                 value="4">201-500 ETH</option>
	                    </select>
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

	<div id="pop-ping" class="pop-ping pop-pay" style="width: 450px">
	    <div class="pad" style="padding: 43px 20px;">
	        <a class="pop-x x" href=""></a>
	        <h3 class="c-c7" style="color: red;font-size: 22px">你还有项目草稿，是否要继续编辑？</h3>
	        <div class="h60"></div>
	        <a href="javascript:void(0)" class="pop-btn-return x"
	           style="background: #ff9a01;color: #ffffff;width: 187px;border: 1px solid #ff9a01;">是，继续编辑</a>
	        <a href="javascript:delProjectCache()" class="pop-btn-return x"
	           style="width: 187px;border: 1px solid #00a0e9;color:#00a0e9 ">否，舍弃草稿重新创新</a>
	    </div>
	</div>
	<?php } ?>
	<?php if($step == 2) { ?>
		<link href="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/css/memberInfo.css" rel="stylesheet">
		
		<form id="form" action="<?php echo $location; ?>?step=3" method="post">
        	<div class="box-table-za box-table-za3">


            <div class="box-table-clone">
                <div class="tit-a5" style="margin-top: 20px">
                    团队成员 <font style="font-size: 13px">（*为必填项）至少填写两名成员</font>
                    <a class="tit-a5-a" href="javascript:saveNow()">立即保存</a>
                </div>
                <table>
                    <tr>
                        <td><strong>* 姓名：</strong></td>
                        <td><input data-validation="nomessage" type="text" name="" value="" id="" class="txt txt2" dataName="name"
                                   placeholder="真实姓名"/><span>* 年龄：</span><input data-validation="nomessage"
                                type="text" name="" value="" id="" class="txt txt2" dataName="age" placeholder="年龄"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>* 职位：</strong></strong>
                        </td>
                        <td>
                            <input data-validation="nomessage" type="text" name="" id="" class="txt txt2"
                                   placeholder="具体职位" dataName="position"/><span>股份占比：</span><input
                                type="text" name="" id="" style="width: 200px" class="txt txt2" dataName="stock"
                                placeholder="百分比"/><span
                                style="width: auto;margin-left: 10px">%股</span>
                        </td>
                    </tr>
                    <!--<tr>

                        <td valign="top"><strong class="pt15">* 头像：</strong></td>
                        <td class="tx415" style="padding: 0 0 10px">
                            <div class="upload-avtDiv" >
                                点击选择头像上传
                                <input class="upload-avt"  type="file" name="Filedata"    data-form-data='{"type": "imageHead"}'  multiple>
                                <input name="" type="hidden" data-validation="nomessage" dataName="headImage">
                                <div class="uploadImg">
                                    <p>点击重新上传</p>
                                    
                                </div>
                            </div>
                        </td>
                    </tr>
                		-->

                   <tr>
                        <td valign="top"><strong class="pt15">* 个人简介：</strong></td>
                        <td style="position: relative"><textarea data-validation="nomessage" name="" id="yccText" cols="30" dataName="introduce"
                                                                 rows="10" maxlength=""
                                                                 placeholder="简单介绍下你自己，最少20字"></textarea>
                            <span class="yccSpan" style="display: none;">已超出<i>10</i>字</span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class="txslTd"><a  class="txsl">填写示例</a>

                            <div class="txslDiv">
                                <h3>填写示例</h3>

                                <p>
                                    11年市场营销工作经历，2010年开始从事互联网，专职产品和市场营销领域，最早一批的O2O践行者。 2014年头加入爱代驾，在爱代驾3000单/日不破半年情况下，通过架构调整，人员布局和市场策略，快速将业务增长到年底12000单/日，并持续每月20-30%的环比增长！ 曾服务过的品牌：可口可乐、康宝莱、百胜集团-KFC、面包新语-Breadtalk等。
                                </p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top"><strong class="pt20">* 教育经历：</strong></td>
                        <td class="pt15">
                            <dl class="m4-z">
                                <dt>
                                    <b class="s0">起止时间</b>
                                    <b class="s2">学校</b>
                                    <b class="s3">专业</b>
                                    <b>学历</b>
                                </dt>
                                <dd style="position: relative" sIndex="0">
                                    <input type="text" name="start0" dataName="startTime"
                                           style="width: 110px;padding-left: 0" id=""
                                           class="txt txt3 school date"> -
                                    <input type="text" name="start0" dataName="endTime"
                                           style="width: 110px;padding-left: 0" id=""
                                           class="txt txt3 school date">
                                    <input type="text" style="padding-left: 0" name="school" id="" dataName="school"
                                           class="txt txt4 school">
                                    <input type="text" style="padding-left: 0" name="major" id="" dataName="major"
                                           class="txt txt5 school">
                                    <select style="padding-left: 0" name="job0" id="" dataName="diploma"
                                            class="txt txt6 school">
                                        <option value=""></option>
                                        <option value="高中">高中</option>
                                        <option value="大专">大专</option>
                                        <option value="本科">本科</option>
                                        <option value="研究生">研究生</option>
                                        <option value="博士">博士</option>
                                    </select>
                                    <a class="x-z1" href="">删除</a>

                                    <div class="x-z1-removeDiv">
                                        <h3>确定删除此教育经历？</h3>
                                        <button class="x-z1-removeDivOK">确定</button>
                                        <button class="x-z1-removeDivCAN">取消</button>
                                    </div>
                                </dd>
                            </dl>
                            <div class="c"></div>
                            <a style="cursor: pointer" class="z1" onclick="z1Click(this)">添加教育经历+</a>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td><strong class="pt4">* 工作经历：</strong></td>
                        <td>
                            <dl class="m4-z">
                                <dt>
                                    <b class="s0">起止时间</b>
                                    <b class="s2">公司</b>
                                    <b class="s3">部门</b>
                                    <b>职位</b>
                                </dt>
                                <dd style="position: relative" gIndex="0">
                                    <input type="text" name="start0" dataName="startTime"
                                           style="width: 110px;padding-left: 0" id=""
                                           class="txt txt3 work date"> -
                                    <input type="text" name="start0" dataName="endTime"
                                           style="width: 110px;padding-left: 0" id=""
                                           class="txt txt3 work date">
                                    <input type="text" style="padding-left: 0" name="" id="" dataName="company"
                                           class="txt txt4 work">
                                    <input type="text" style="padding-left: 0" name="" id="" dataName="department"
                                           class="txt txt5 work">
                                    <input type="text" style="padding-left: 0" name="" id="" dataName="position"
                                           class="txt txt6 work">
                                    <a class="x-z1" href="">删除</a>

                                    <div class="x-z1-removeDiv">
                                        <h3>确定删除此教育经历？</h3>
                                        <button class="x-z1-removeDivOK">确定</button>
                                        <button class="x-z1-removeDivCAN">取消</button>
                                    </div>
                                </dd>
                            </dl>
                            <div class="c"></div>
                            <a style="cursor: pointer" class="z1" onclick="z1Click(this)">添加工作经历+</a>
                        </td>
                    </tr>
                </table>
            </div>


            <div class="cloneAddDiv"></div>


            <div class="tit-a5 mt20 click-clone-add">
                点击新增一名团队成员</font>
            </div>
            <div class="btn-zz mb90"><a href="create-project.php?step=1">上一步</a> <a  id="submit" href="javascript:$('#form').submit()"  class="a2 disabled validation-submit">下一步：填写项目概要</a>
            </div>
        	</div>
        	<div class="error"></div>
   		 </form>
   		 <script type="text/javascript" src="<?php echo WWW; ?>includes/themes/<?php echo THEME_NAME; ?>/js/memberInfo.js"></script>


   		 
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