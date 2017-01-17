var login_valid = false;

// validates login - no field can be blank
function login_validate() {
	var username = document.getElementById("username");
	var password = document.getElementById("password");

	if (username.value == "" || username.value == "用户名" || password.value == "" || password.value == "password")
		alert("输入不能为空!");
	else
		login_valid = true;
}

// the clear on click effect
function clear_field(element) {
	var value = document.getElementById(element.id).value;
	if (value == "用户名" || value == "password")
		element.value = "";
	// deepens font color when onfocus
	element.style.color = "gray";
}

// the restore on blur effect
function restore_field(element) {
	var value = document.getElementById(element.id).value;

	if (value == ""){
		if (element.id == "username") {
			element.value = "用户名";
		}
		else if (element.id == "password") {
			element.value = "password";
		}
		element.style.color = "#cbcbcb";
	}
}

// handle submit
$(document).ready(function() {
	// If input valid, post to login.php
	$("#login-button").on('click', function() {
		if (login_valid == true) {
			var postData = {
				username: $("#username").val(),
				password: $("#password").val()
			};

			$.ajax({
				type 		: 	'POST',
				url			:  	'../php/login.php', 
				data 		: 	postData,
				dataType	: 	'JSON',
				success: function(data) {
					if (data.invalid_status == 'invalid')
						alert(data.invalid_string);
					else if (data.invalid_status == 'valid') {
						window.location = 'home.php';
					}
				}
			});
			
		}
	});
});