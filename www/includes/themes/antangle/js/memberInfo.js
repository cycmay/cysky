uploadFileupload();

$.validate({
    modules: 'toggleDisabled',
    showErrorDialogs: true
});

   function uploadFileupload(){
        var url = '/uploadImage.json';
        $('.upload-avt').fileupload({
            url: url,
            dataType: 'json',
            fail: function (e, data) {
                // $(this).after('<p>Test</p>');
                console.info(data);
                //console.info(data._respone.jqXHR.responseText);

                swal(
                        "上传失败","", "error"
                );
            },
            done: function (e, data) {
                // alert(this);
                var url = (data.result.data);
                $(this).closest(".box-table-clone").find(".uploadImg").find("img").attr("src",'https://file.mayiangel.com/'+url);
                $(this).closest(".box-table-clone").find(".uploadImg").show();
                $(this).closest(".box-table-clone").find("input[type='hidden']").attr("value",url);
              // positionImg();
               // console.info(data);

            }
        }).prop('disabled', !$.support.fileInput)
                .parent().addClass($.support.fileInput ? undefined : 'disabled');

    }



//    422
initAllInput();
function initAllInput() {
    var groupIndex = 0;
    $(".box-table-clone").each(function (i) {
        $(this).find("input[type='text'],input[type='hidden'],textarea,select").each(function (ii) {
            if ($(this).hasClass("school")) {
                var ti = $(this).closest("dd").attr("sIndex");
                var td = $(this).attr("dataname");
                $(this).attr("name", "teams[" + i + "].college[" + ti + "]." + td);
            } else if ($(this).hasClass("work")) {
                var ti = $(this).closest("dd").attr("gIndex");
                var td = $(this).attr("dataname");
                $(this).attr("name", "teams[" + i + "].works[" + ti + "]." + td);
            } else {
                var td = $(this).attr("dataname")
                $(this).attr("name", "teams[" + i + "]." + td);
            }
        })
    })
}


var i = 1;
$(".click-clone-add").bind("click", function () {
    var cloneA;
    var btcL = $(".box-table-clone").length - 1;
    i++;
    $(".box-table-clone").each(function (i) {
        if (i == btcL) {
            cloneA = $(this).clone();
        }
    })
    cloneA.find("input").val("");
    cloneA.find("textarea").val("");
    cloneA.find(".addDD").remove();
    cloneA.find(".tit-a5").html('团队成员' +
            '<a class="tit-a5-a" onclick="removeCyt(this)" style="cursor: pointer">删除成员</a>')
    cloneA.removeClass("Cy" + (i - 1))
    cloneA.addClass("Cy" + i)
    $(".cloneAddDiv").before(cloneA);
    initAllInput();
    dataInit();
    uploadFileupload();
    cloneA.find(".uploadImg").find("img").attr("src","");
    cloneA.find(".uploadImg").hide();
    $.validate({
        modules: 'toggleDisabled',
        showErrorDialogs: true
    });
    return false;
})
function removeCyt(obj) {
    $(obj).closest(".box-table-clone").remove();

    return false;
}


$("#yccText").keyup(function () {
    if ($(this).val().length > 500) {
        $(".yccSpan").show().find("i").html($(this).val().length - 500)
    } else {
        $(".yccSpan").hide().find("i").html("0")
    }
})
dataInit();
function dataInit() {
    $(".date").datetimepicker({
        yearOffset: 0,
        lang: 'ch',
        timepicker: false,
        format: 'Y-m-d',
        formatDate: 'Y/m/d',
        minDate: '1950/01/01', // yesterday is minimum date
        maxDate: '2020/01/02' // and tommorow is maximum date calendar
    });
}

var sIndex = 1;
var gIndex = 1;
function z1Click(obj) {
    if ($(obj).html() == "添加教育经历+") {
        var tr = $(obj).closest('tr');
        var dl = tr.find('dl');
        var clone = tr.find('dd:eq(0)').clone();
        var dd = tr.find('dd').last();
        sIndex = parseInt(dd.attr("sindex"))+1;
        clone.attr("sIndex", sIndex)
        clone.find('input').val('').end().appendTo(dl).addClass('clone');
        clone.find('.x-z1').show();
        clone.addClass("addDD")
        initAllInput();
        dataInit();
        sIndex++
        return false;
    } else {
        var tr = $(obj).closest('tr');
        var dl = tr.find('dl');
        var clone = tr.find('dd:eq(0)').clone();
        var dd = tr.find('dd').last();
        gIndex = parseInt(dd.attr("gindex"))+1;
        clone.attr("gIndex", gIndex)
        clone.find('input').val('').end().appendTo(dl).addClass('clone');
        clone.find('.x-z1').show();
        clone.addClass("addDD")
        initAllInput();
        dataInit();
        gIndex++
        return false;
    }
}
;
$('body').on('click', '.clone .x-z1', function () {
    $(this).parents('dd').find(".x-z1-removeDiv").fadeIn(200);
//        alert($(this).parents('dd').find(".x-z1-removeDiv").length)
    return false;
})
$('body').on('click', '.clone .x-z1-removeDivCAN', function () {
    $(this).parents('.x-z1-removeDiv').fadeOut(200);
    return false;
})
$('body').on('click', '.clone .x-z1-removeDivOK', function () {
    $(this).parents('dd').remove();
    return false;
})


$(".txsl").hover(function () {
    $(this).parent().find(".txslDiv").fadeIn(200)
    return false;
}, function () {
    $(".txslDiv").stop();
    $(".txslDiv").fadeOut(200)
})
$(window).click(function () {
    $(".txslDiv").fadeOut(200)
    $(".txslDiv").click(function () {
        event.stopPropagation()
        return false;
    })
})

function submitForm() {
    if($("#submit").hasClass("disabled")){
        $.validate({
            modules: 'toggleDisabled',
            showErrorDialogs: true
        });
        return false;
    } else{

    }
}


$('#form').on('submit',function(){
    if(!$("#submit").hasClass("disabled")){
        $.post(
                "./includes/themes/antangle/createProjectTeams.json",
                $('#form').serialize(),
                function (data) {
                    if(data.succeed){
                        window.location.href = "./create-project.php?step=3&create_project=";
                    }
                }
        );
    }
    return false;
});

function saveNow() {
    $.post(
            "./includes/themes/antangle/createProjectTeams.json",
            $('#form').serialize(),
            function (data) {
            	console.log(data.succeed);
                if(data.succeed){
                      swal( "保存成功", "" ,
                                    "success"
                      );
                }
            }
    );
}
