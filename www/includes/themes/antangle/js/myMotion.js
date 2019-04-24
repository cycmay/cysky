
var mySwiper = new Swiper('.swiper-container', {
    direction: 'horizontal',
    loop: true,
    paginationClickable: true,
    autoplay: 5000,
    prevButton: '.swiper-button-prev',
    nextButton: '.swiper-button-next',
    // 如果需要分页器
    pagination: '.swiper-pagination',

})

function hbShow(){
    $('.fixFooter').hide();
}



/**
 *  by Mantou qq:676015863
 *  数字滚动插件 v1.0
 */
;
(function ($) {
    $.fn.numberAnimate = function (setting) {
        var defaults = {
            speed: 1000,//动画速度
            num: "", //初始化值
            iniAnimate: true, //是否要初始化动画效果
            symbol: '',//默认的分割符号，千，万，千万
            dot: 0 //保留几位小数点
        }
        //如果setting为空，就取default的值
        var setting = $.extend(defaults, setting);

        //如果对象有多个，提示出错
        if ($(this).length > 1) {
            alert("just only one obj!");
            return;
        }

        //如果未设置初始化值。提示出错
        if (setting.num == "") {
            alert("must set a num!");
            return;
        }
        var nHtml = '<div class="mt-number-animate-dom" data-num="{{num}}">\
        <span class="mt-number-animate-span">0</span>\
        <span class="mt-number-animate-span">1</span>\
        <span class="mt-number-animate-span">2</span>\
        <span class="mt-number-animate-span">3</span>\
        <span class="mt-number-animate-span">4</span>\
        <span class="mt-number-animate-span">5</span>\
        <span class="mt-number-animate-span">6</span>\
        <span class="mt-number-animate-span">7</span>\
        <span class="mt-number-animate-span">8</span>\
        <span class="mt-number-animate-span">9</span>\
        <span class="mt-number-animate-span">.</span>\
      </div>';

        //数字处理
        var numToArr = function (num) {
            num = parseFloat(num).toFixed(setting.dot);
            if (typeof(num) == 'number') {
                var arrStr = num.toString().split("");
            } else {
                var arrStr = num.split("");
            }
            //console.log(arrStr);
            return arrStr;
        }

        //设置DOM symbol:分割符号
        var setNumDom = function (arrStr) {
            var shtml = '<div class="mt-number-animate">';
            for (var i = 0, len = arrStr.length; i < len; i++) {
                if (i != 0 && (len - i) % 3 == 0 && setting.symbol != "" && arrStr[i] != ".") {
                    shtml += '<div class="mt-number-animate-dot">' + setting.symbol + '</div>' + nHtml.replace("{{num}}", arrStr[i]);
                } else {
                    shtml += nHtml.replace("{{num}}", arrStr[i]);
                }
            }
            shtml += '</div>';
            return shtml;
        }

        //执行动画
        var runAnimate = function ($parent) {
            $parent.find(".mt-number-animate-dom").each(function () {
                var num = $(this).attr("data-num");
                num = (num == "." ? 10 : num);
                var spanHei = $(this).height() / 11; //11为元素个数
                var thisTop = -num * spanHei + "px";
                if (thisTop != $(this).css("top")) {
                    if (setting.iniAnimate) {
                        //HTML5不支持
                        if (!window.applicationCache) {
                            $(this).animate({
                                top: thisTop
                            }, setting.speed);
                        } else {
                            $(this).css({
                                'transform': 'translateY(' + thisTop + ')',
                                '-ms-transform': 'translateY(' + thisTop + ')', /* IE 9 */
                                '-moz-transform': 'translateY(' + thisTop + ')', /* Firefox */
                                '-webkit-transform': 'translateY(' + thisTop + ')', /* Safari 和 Chrome */
                                '-o-transform': 'translateY(' + thisTop + ')',
                                '-ms-transition': setting.speed / 1000 + 's',
                                '-moz-transition': setting.speed / 1000 + 's',
                                '-webkit-transition': setting.speed / 1000 + 's',
                                '-o-transition': setting.speed / 1000 + 's',
                                'transition': setting.speed / 1000 + 's'
                            });
                        }
                    } else {
                        setting.iniAnimate = true;
                        $(this).css({
                            top: thisTop
                        });
                    }
                }
            });
        }

        //初始化
        var init = function ($parent) {
            //初始化
            $parent.html(setNumDom(numToArr(setting.num)));
            runAnimate($parent);
        };

        //重置参数
        this.resetData = function (num) {
            var newArr = numToArr(num);
            var $dom = $(this).find(".mt-number-animate-dom");
            if ($dom.length < newArr.length) {
                $(this).html(setNumDom(numToArr(num)));
            } else {
                $dom.each(function (index, el) {
                    $(this).attr("data-num", newArr[index]);
                });
            }
            runAnimate($(this));
        }
        //init
        init($(this));
        return this;
    }
})(jQuery);

$(function () {

    //初始化
    var numRun = $(".numberRun").numberAnimate({num: '52248000', dot: 0, speed: 2000, symbol: ","});
//        var nums = 2305;
//        setInterval(function () {
//            //nums += 3433.24;
//            numRun.resetData(nums);
//        }, 3000);

    var numRun3 = $(".numberRun3").numberAnimate({num: '55', dot: 0, speed: 2000});
//        var nums3 = 78.3;
//            setInterval(function(){
//                nums3+= 454.521;
//                numRun3.resetData(nums3);
//            },4000);

    var numRun4 = $(".numberRun4").numberAnimate({num: '2306', speed: 2000});
//        var nums4 = 1121;
//            setInterval(function(){
//                nums4+= 682;
//                numRun4.resetData(nums4);
//            },3500);

});


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


var Pop = (function () {
    var popVer = function (modal_id) {
        var overlay = $("<div id='lean_overlay'></div>");
        $("body").append(overlay);
        $("#lean_overlay").fadeTo(200, .8);
        var modal_height = $(modal_id).outerHeight();
        var modal_width = $(modal_id).outerWidth();
        $(modal_id).css({
            "display": "block",
            "position": "fixed",
            "opacity": 0,
            "z-index": 11000,
            "left": 50 + "%",
            "top": 50 + "%",
            "margin-left": -(modal_width / 2) + "px",
            "margin-top": -(modal_height / 2) + "px",
        });
        $(modal_id).fadeTo(200, 1);
    }
    var closePop = function (modal_id) {
        $(modal_id).fadeOut();
        $("#lean_overlay").fadeOut();
    }
    $('.x').click(function () {
        closePop('.pop-pay');
        //return false;
    });
    return {
        popVer: popVer
    };
})();


function delProjectCache() {
    $.post(
            "./includes/themes/antangle/delProjectCache.json",
            function (data) {
                if (data.succeed) {
                    window.location.href = window.location.href;
                } else {
                    console.error(data.msg);
                }
            }
    );
}

function submit() {
    $("#form").submit();
}

$(function () {
    loadCityFirst();
});

function loadCity() {
    $("#citySecondName").val(
            $("#receipt_area_city option:selected").text());
}

function loadCityFirst() {
    $
            .ajax({
                url: "./includes/themes/antangle/getCityFirst.json",
                dataType: "json",
                success: function (message) {
                    var options;
                    for (var i = 0; i < message.length; i++) {
                        var value = message[i].id;
                        var text = message[i].name;
                        if (value == '') {
                            options += "<option value=" + value
                                    + " selected='selected'>"
                                    + text + "</option>";
                        } else {
                            options += "<option value=" + value + ">"
                                    + text + "</option>";
                        }
                    }

                    $("#receipt_area_province").html(options);
                    loadCitySecond("");
                }
            });
}

function loadCitySecond(id) {
    if (id == "") {
        id = $("#receipt_area_province").val();
    }
    $
            .ajax({
                url: "./includes/themes/antangle/getCityFirst.json?parentId=" + id,
                dataType: "json",
                success: function (message) {
                    var options;
                    for (var i = 0; i < message.length; i++) {
                        var value = message[i].id;
                        var text = message[i].name;
                        if (value == '') {
                            options += "<option value=" + value
                                    + " selected='selected'>"
                                    + text + "</option>";
                        } else {
                            options += "<option value=" + value + ">"
                                    + text + "</option>";
                        }
                    }

                    $("#receipt_area_city").html(options);
                    $("#cityFirstName").val(
                            $("#receipt_area_province option:selected")
                                    .text());
                    $("#citySecondName").val(
                            $("#receipt_area_city option:selected")
                                    .text());
                }
            });
}



//    function positionImg(){
//        var uh=$(".uploadImg img").height();
//        var uw=$(".uploadImg img").width();
//        if(uh<=uw){
//            $(".uploadImg img").css("height","100%");
//            $(".uploadImg img").css("width","auto");
//            uh=$(".uploadImg img").height();
//            uw=$(".uploadImg img").width();
//            $(".uploadImg img").css("marginLeft",-uw/2);
//            $(".uploadImg img").css("marginTop",-uh/2);
//        }else{
//            $(".uploadImg img").css("marginLeft",-uw/2);
//            $(".uploadImg img").css("marginTop",-uh/2);
//        }
//    }


