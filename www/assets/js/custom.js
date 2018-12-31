$(document).ready(function(){
	$("#login_link").attr("href", "#login_modal").attr("data-toggle", "modal");	
});

function login(){
	username = $("#username").val();
	password = $("#password").val();
	if($('#remember_me').attr('checked')){
		remember_me = "yes";
	} else {
		remember_me = "no";
	}
	$.ajax({
		type: "POST",
		url: "data.php",
		data: "page=login&action=login&username="+username+"&password="+password+"&remember_me="+remember_me,
		success: function(html){
			if(html == "false"){
				$("#username").addClass("error");
				$("#password").addClass("error");
				$("#login_btn").html("Login");
				update_login_msg();
			} else {
				window.location.replace(html);
			}
		},
		beforeSend: function(){
			$("#login_btn").html("Working...");
		}
	});
}

function update_login_msg(){
	$.ajax({
		type: "POST",
		url: "data.php",
		data: "page=login&action=update_msg",
		success: function(html){
			if(html){
				$("#message").html(html);
			}
		}
	});
}

function valid_email(email) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(email);
};