<!-- microblog login page -->
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel = "stylesheet" href = "css/login.css" />
	<script src = "js/jquery-1.11.1.js"></script>
	<script language = "javascript" type = "text/javascript" src = "js/login.js"></script>
	<meta charset = "utf-8" />
</head>

<body>
	<!-- wrapper -->
	<div id = "wrapper">

		<!-- banner spanning the top -->
		<div id = "banner">
			<img src = "images/header-banner.png" style = "float: left; height: 150px; margin-left: 30px; margin-top: 30px"/>
		</div>

		<!-- content wrapper -->
		<div id = "content">
			<!-- left column -->
			<div id = "left-column">
				<div id = "new-users">
					<p class = "label">新用户</p>

					<div id = "new-users-wrapper">

						<?php
							include 'php/db_functions.php';
							
							// Output 15 newest users
							new_users();
						?>
						<!-- <div id = "new-user-singular">
							<img src = "images/male-avatar.jpeg" class = "single-user" />
							<p class = "username">@用户名</p>
						</div> -->
					</div>
				</div>

				<div id = "popular-users">
					<p class = "label">人気用户</p>
					
					<ol>
						<?php
							// Output 10 most popular users
							popular_users();
						?>
						<!-- <li>微博王子<span id = "popular-user-followers">12957</span></li>
						<li>aaron粉丝<span id = "popular-user-followers">2780</span></li>
						<li>健康小贴士<span id = "popular-user-followers">2061</span></li>
						<li>奇葩党<span id = "popular-user-followers">1980</span></li>
						<li>z_先生<span id = "popular-user-followers">882</span></li>
						<li>复旦管院<span id = "popular-user-followers">243</span></li>
						<li>吐槽女王<span id = "popular-user-followers">179</span></li>
						<li>临时抱佛脚<span id = "popular-user-followers">110</span></li>
						<li>困死了<span id = "popular-user-followers">80</span></li>
						<li>我的天啊<span id = "popular-user-followers">30</span></li> -->
					</ol>
				</div>
			</div>

			<div id = "right-column">
				<div class = "right-column-module">
					<a href = "signup.php"><button id = "register-button" type = "button">立即开通</button></a>
				</div>
					<table id = "login">
						<tr>
							<td colspan = "2"><input type = "text" value = "用户名" id = "username" onclick = "clear_field(this)" onblur = "restore_field(this)" name = 'username'/></td>
						</tr>

						<tr>
							<td colspan = "2"><input type = "password" value = "password" id = "password" onclick = "clear_field(this)" onblur = "restore_field(this)" name = 'password'/></td>
						</tr>

						<tr>
							<td colspan = "1" width = "10px">
								<!-- <input type = "checkbox" value = "Remember me" id = "remember"/> -->
							</td>
							<td colspan = "1">
								<div class = "remember-me-text"> 
								<!-- <span>保持登录状态</span> -->
								</div>
							</td>
						</tr>
						<tr>
							<td colspan = "2" class = "login">
								<button type = "submit" id = "login-button" onclick = "login_validate()">登录</button>
							</td>
						</tr>

						<tr>
							<td colspan = "2"><hr class = "module-separator"/></td>
						</tr>

						<tr>
							<td colspan = "2">
							<div class = "signup">没有账户？ <a href = "signup.php">注册</a></div></td>
							<!-- <td><input type = "button" value = "Sign up" id = "signup-button" /></td> -->
						</tr>

					</table>
			</div>
		</div>

		<div id = "footer">
			<p class = "copyright">&copy;2014&nbsp;&nbsp;喵喵微博</p>
		</div>
	</div>
</body>

</html>