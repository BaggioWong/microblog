<!-- microblog signup page -->
<!DOCTYPE html>
<html>
<head>
	<title>Sign Up</title>
	<link rel = "stylesheet" href = "css/signup.css" />
	<meta charset="UTF-8">
	<script src="js/jquery-1.11.1.js"></script>
	<script language = "javascript" type = "text/javascript" src = "js/signup.js"></script>
</head>

<body>
	<!-- wrapper -->
	<div id = "wrapper">

		<!-- header -->
		<div id = "header">
			<a href = "login.php"><img src = "images/cat-icon.png" id = "logo"/></a>
			<hr class = "join-us-separator"/>
		</div>

		<!-- main content -->
		<div id = "main-content">
			<span id = "join-us">加入喵喵微博!</span>			

			<!-- sign up form -->
			<table>
				<!-- nickname / full name -->
				<tr>
					<td colspan = "2" class = "form-attribute">登录名</td>
				</tr>

				<tr>
					<td colspan = "2" class = "form-field" ><input type = "text" id = "full-name" name = "full-name" onkeyup = "checkFullName();checkFullNameExists();" /></td>
					<td class = "form-validation"  id = "full-name-validation"></td>
				</tr>
				
				<!-- email -->
				<tr>
					<td colspan = "2" class = "form-attribute" >电邮地址</td>
				</tr>
					
				<tr>
					<td colspan = "2" class = "form-field" ><input type = "text" id = "email" onkeyup = "checkMail()"/></td>
					<td class = "form-validation"  id = "email-validation"></td>
				</tr>
				
				<!-- password -->
				<tr>
					<td colspan = "2" class = "form-attribute" >填写密码</td>
				</tr>

				<tr>
					<td colspan = "2" class = "form-field" ><input type = "password" id = "password" onkeyup = "checkPass()" /></td>
					<td class = "form-validation"  id = "password-validation"></td>
				</tr>
					
				<!-- enter password again -->
				<tr>
					<td colspan = "2" class = "form-attribute" >重写密码</td>
				</tr>
					
				<tr>
					<td colspan = "2" class = "form-field" ><input type = "password" id = "password-repeat" onkeyup = "checkRepeatPass()" /></td>
					<td class = "form-validation"  id = "password-repeat-validation"></td>
				</tr>
					
				<!-- account-name -->
				<tr>
					<td colspan = "2" class = "form-attribute" >选择昵称</td>
				</tr>
				
				<tr>
					<td colspan = "2" class = "form-field" ><input type = "text" id = "username" onkeyup = "checkUsername()"/></td>
					<td class = "form-validation"  id = "username-validation"></td>
				</tr>
				
				<!-- 服务条款 -->
				<tr>
					<td colspan = "2">
						请仔细阅读以下的服务协议。
						<iframe src = "terms.html" id = "terms"> </iframe>
					</td>
				</tr>

				<tr>
					<td width = "5px" class = "terms-checkbox">
						<input type = "checkbox" id = "agree" />
					</td>
					<td width = "175px" class = "terms"><span id = "agree-text" onclick = "checkAgree()">我同意微博公司的使用协议。</span></td>
				</tr>	
				
				<!-- 创建户口 -->
				<tr>
					<td colspan = "2" class = "form-attribute submit" >
						<button type = "button" id = "submit" onclick = "checkAllAndRegister()">创建户口</button> 
					</td>
				</tr>
			</table>

		</div>
	</div>
</body>
</html>