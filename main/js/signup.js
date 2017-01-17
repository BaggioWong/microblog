// check mail field
function checkMail() {
	var mailValue = document.getElementById("email").value;
	 var mailMessage = document.getElementById("email-validation");

	 var regex = new RegExp("\\s*^[a-zA-Z0-9\._-]{1,}[@]{1}[a-zA-Z0-9.-]{1,}[.]{1}[a-zA-Z]{2,4}\\s*$");

	 var valid = 0;

	 if (mailValue.length == 0)
	 	mailMessage.innerHTML = "<span style = 'color:gray;'>全名不能为空!</span>";
	 else if (regex.test(mailValue)) {
	 	mailMessage.innerHTML = "<span style = 'color:green;'>电邮OK!</span>";
	 	valid = 1;
	 } else
	 	mailMessage.innerHTML = "<span style = 'color:gray;'>输入错误，请重新输入。</span>";

	 return valid;
}

// check pass field
function checkPass(){
	var passValue = document.getElementById("password").value;
	var passMessage = document.getElementById("password-validation");

	var length = passValue.length;

	var valid = 0;
	// allowed special characters !@#$%^&*()-_+=~`?:;"'<>,.\|/{}[
	var allDigits = new RegExp("[\\s]{0}^[\\d]{6,}$[\\s]{0}");
	var allLetters = new RegExp("[\\s]{0}^[a-zA-Z]{6,}$[\\s]{0}");
	var regex = new RegExp("[\\s]{0}^[\\w~`!@#$%^&\*()-=+?:;\"\'<>,\.\/|\\\{\}\[]{6,}$[\\s]{0}"); 

	if (length == 0)
		passMessage.innerHTML = "<span style = 'color:gray;'>密码不能为空!</span>";
	else if (length < 6)
		passMessage.innerHTML = "<span style = 'color:gray;'>密码错误! 密码长度至少为6。</span>";
	else if (allDigits.test(passValue))
		passMessage.innerHTML = "<span style = 'color:gray;'>密码不能全为数字!</span>";
	else if (allLetters.test(passValue))
		passMessage.innerHTML = "<span style = 'color:gray;'>密码不能全为字母!</span>";
	else if (regex.test(passValue)) {
		passMessage.innerHTML = "<span style = 'color:green;'>密码正确!</span>";
		valid = 1;
	}
	else 
		passMessage.innerHTML = "<span style = 'color:gray;'>密码错误! 密码必为字母、数字或者字符。<br />合法字符：!@#$%^&*()-_+=~`?:;<>,.{}[&quot;&#39;&#92;|/</span>";
	


	// if (length == 0)
	// 	passMessage.innerHTML = "<span style = 'color:gray;'>密码不能为空!</span>";
	// else if (length < 4 || length > 20)
	// 	passMessage.innerHTML = "<span style = 'color:gray;'>密码长度为4-20个字符。</span>";
	// else if (length <= 7) {
	// 	passMessage.innerHTML = "<span style = 'color:green;'>密码强度： <span style = 'color: #F0D000; font-weight: bold;'>弱</span></span>";
	// 	valid = 1;
	// }
	// else if (length <= 12) {
	// 	passMessage.innerHTML = "<span style = 'color:green;'>密码强度： <span style = 'color: orange; font-weight: bold;'>中</span></span>";
	// 	valid = 1;
	// }
	// else {
	// 	passMessage.innerHTML = "<span style = 'color:green;'>密码强度：: <span style = 'color: green; font-weight: bold;'>强</span></span>";
	// 	valid = 1;
	// }

	return valid;
}

// check pass field again
function checkRepeatPass(){
	var passValue = document.getElementById("password-repeat").value;
	var passMessage = document.getElementById("password-repeat-validation");
	var originalPassValue = document.getElementById("password").value;

	var length = passValue.length;
	var originalLength = originalPassValue.length;

	var valid = 0;

	if (originalLength == 0)
		passMessage.innerHTML = "<span style = 'color:gray;'>你没有填入第一个密码!</span>";
	else if (checkPass() != 1)
		passMessage.innerHTML = "<span style = 'color:gray;'>第一个密码不正确!</span>";
	else if (length == 0)
		passMessage.innerHTML = "<span style = 'color:gray;'>密码不能为空!</span>";
	else if (passValue.localeCompare(originalPassValue) != 0)
		passMessage.innerHTML = "<span style = 'color:gray;'>两个密码不匹配!</span>";
	else {
		passMessage.innerHTML = "<span style = 'color:green;'>密码匹配!</span>";
		valid = 1;
	}
	
	return valid;
}

// check fullname
function checkFullName() {
	var fullNameValue = document.getElementById('full-name').value;
	var fullNameMessage = document.getElementById('full-name-validation');

	var length = fullNameValue.length;
	var regex = new RegExp("[\\s]{0}^[\\w]{2,14}$[\\s]{0}");

	var valid = 0;

	if (length == 0) 
		fullNameMessage.innerHTML = "<span style = 'color:gray;'>登录名不能为空!</span>";
	else if (length < 2 || length > 14) 
		fullNameMessage.innerHTML = "<span style = 'color:gray;'>登录名长度为2-14个字符。</span>";
	else if (regex.test(fullNameValue)) {
		fullNameMessage.innerHTML = "<span style = 'color:green;'>登录名正确！</span>";
		return 1;	// valid = 1 doesn't work
	}
	else
		fullNameMessage.innerHTML = "<span style = 'color:gray;'>登录名必须为字母、数字和下方划线。</span>";

	return valid;
}

// check if full name exists (AJAX)
function checkFullNameExists() {
	var noExist = 1;
	var postData = {
		full_name: $('#full-name').val()
	};

	// if user doesn't exist, show appropriate message
	$.ajax({
		type 		: 	'POST',
		url			:  	'../php/user_exists.php', 
		data 		: 	postData,
		dataType	: 	'JSON',
		success: function(data) {
			if (data.exists == true) {
				$('#full-name-validation').html('<span style = "color:gray;">Username taken! Please choose another one</span>');
				noExist = 0;
				return noExist;	// jQuery scope issues? 
			}
		}
	});

	return noExist;
}

// check username
function checkUsername(){
	var usernameValue = document.getElementById('username').value;
	var usernameMessage = document.getElementById('username-validation');

	var length = usernameValue.length;
	
	var valid = 0;

	if (length == 0) 
		usernameMessage.innerHTML = "<span style = 'color:gray;'>昵称不能为空!</span>";
	else if (length < 2 || length > 12)
		usernameMessage.innerHTML = "<span style = 'color:gray;'>昵称长度为2-12个字符。</span>";
	else {
		usernameMessage.innerHTML = "<span style = 'color:green;'>昵称OK!</span>";
		valid = 1;
	}

	return valid;
}

// check all and/or register account
// returns true if registration successful, false otherwise
function checkAllAndRegister(){
	var valid1 = checkFullName() && checkFullNameExists();		// if full name passed
	var valid2 = checkMail();
	var valid3 = checkPass();
	var valid4 = checkRepeatPass();
	var valid5 = checkUsername();
	var valid6 = document.getElementById('agree').checked;

	if (valid1 == 1 && valid2 == 1 && valid3 == 1 && valid4 == 1 && valid5 == 1 && valid6 == true) {
		register_account();
		return true; //alert("所有输入正确!");
	}
	else 
		return false; // alert("有些信息输入不正确。请重新填写。");
}

// registers account
function register_account() {
	var postData = {
		full_name	: 	$("#full-name").val(),
		email 		: 	$("#email").val(),
		password 	: 	$("#password").val(),
		username 	: 	$("#username").val()
	};

	$.ajax({
		type 		: 	'POST',
		url			:  	'../php/register.php', 
		data 		: 	postData,
		dataType	: 	'JSON',
		success 	: 	function(data) {
			if (data.result == -1)
				alert('Registration unsuccessful!');
			else if (data.result == 0) {				// redirect to page
				alert('Registration successful!');
				window.location = '../home.php';
			}
		}
	});
}

// allow selection of checkbox by clicking text
function checkAgree() {
	// var agreeText = document.getElementById('agree-text');
	var agreeCheckbox = document.getElementById('agree');

	if (agreeCheckbox.checked == true)
		agreeCheckbox.checked = false;
	else 
		agreeCheckbox.checked = true;
}