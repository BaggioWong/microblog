<!-- microblog homepage -->
<?php 
	include 'php/db_functions.php';
	session_start(); 

	//	If not logged in, redirect to login
	if (isset($_SESSION['logged_in']) == false) {
		echo alert('It seems like you have not logged in yet. Please login first.');
		echo redirect('login.php');
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>喵喵微博</title>
	<script src = "js/jquery-1.11.1.js"></script>
	<!-- CSS dependencies -->
    <link rel="stylesheet" type="text/css" href="./js/bootstrap-3.1.1-dist/css/bootstrap.min.css" disabled = "true" id = "bootstrap">
	<link rel = "stylesheet" type = "text/css" href = "css/home.css">

    <!-- JS dependencies -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="./js/bootstrap-3.1.1-dist/js/bootstrap.min.js"></script>
 
    <!-- bootbox code -->
    <script src="./js/bootbox.min.js"></script>
	<script src = "js/home.js"></script>
	<meta charset="UTF-8">
</head>

<body>

	<!-- wrapper -->
	<div id = "wrapper">
		<!-- header -->
		<div id = "header">

			<!-- logo -->
			<div id = "logo-wrapper">
				<a href = "home.php"><img id = "logo" src = "images/cat-icon.png"  /></a>
			</div>

			<!-- functions bar: nav menu and search box -->
			<div id = "functions-bar">
				<!-- navigation menu -->
				<div id = "nav-menu">
					<ul id = "navigation">
						<li><a href = "#">首页</a>&nbsp;&nbsp;&nbsp;&nbsp;</li>
						<li><a href = "#">关于</a>&nbsp;&nbsp;&nbsp;&nbsp;</li>
						<li><a id = "logout">注销</a></li>
					</ul>
				</div>

				<!-- search box -->
				<form id = "search-form" action = "results.php" method = "POST">
					<select class = "search-type" name = "search_type">
						<option value = "用户">用户</option>
						<option value = "微博">微博</option>
					</select>
					<input type = "text" id = "search" value = "搜索" onclick = "clear_field(this)" onblur = "restore_field(this)" name = "search_string" />
				</form>
			</div>

		</div>

		<!-- main content -->
		<div id = "main-content">
			
			<!-- left column: microblog -->
			<div id = "left-column">

				<!-- compose message wrapper -->
				<div id = "textarea" class = "left-column-module">

					<!-- compose text area -->
					<textarea id = "compose" onkeyup = "characters_left('compose', 'char-count')"></textarea>

					<!-- tweet functions - upload photo, characters left, tweet button -->
					<div id = "tweet-bar">
						<!-- fake file input -->
						<input type = "file" id = "tweet-photo-input"/>
						<button type = "button" id = "tweet-photo" onclick = "fake_photo('tweet-photo-input')"></button>

						<button type = "button" id = "tweet" class = "send" onclick = "tweet()">
							<div class = "tweet-text">发布</div>
						</button>
						
						<p id = "char-count" class = "char-count">140</p>
					</div>

				</div>

				<!-- module separator -->
				<div class = "left-column-module">
					<hr class = "module-separator" />
				</div>

				<!-- tweets header -->
				<div id = "tweets-header" class = "left-column-module">
					<p class = "tweets-header">微博</p>
				</div>

				<!-- single tweet wrapper - wraps tweet module and tweet reply together -->

				<!-- get latest tweets from user and following users -->

				<?php show_tweets($_SESSION['username'], false); ?>
				<div id = "single-tweet-wrapper">
					<div id = "tweet-ID-0">
					<!-- single tweet module -->
					<div id = "single-tweet" class = "left-column-module">

						<table id = "single-tweet-table">
							<tr>
								<!-- profile pic -->
								<td rowspan = "3" width = "60px" class = "tweet-user-pic">
									<div id = "user-pic" class = "tweet-user-pic">
										<img src = "images/male-avatar.jpeg" class = "user-pic"/>
									</div>
								</td>

								<!-- tweet meta data -->
								<td class = "single-tweet-meta" id = "single-tweet-meta">
									<span id = "single-tweet-username">昵称</span>
									<span id = "single-tweet-login-name">&nbsp;&nbsp;@用户名</span>
									<span id = "single-tweet-time">&#183;&nbsp;时间</span>
								</td>
							</tr>

							<tr>
								<!-- single tweet meta -->
							 	<td class = "single-tweet-meta single-tweet-content" id = "single-tweet-content">
									微博内容：这里试一下随机的内容。要让字段换一下行看看浏览器里面如何显示。那么三行字会怎么样显示？想试试看。这里应该足够四暗示三行字的字数了。
								</td>
							</tr>

							<tr>
								<!-- tweet meta functions -->
								<td class = "tweet-functions">
									<a><span onclick = "reply(this)" name = 'reply'>回复</span></a> &#183;
									<a><span id = "retweet">转发</span></a> <!--&#183;-->
									<a><span onclick = "delete_tweet(this)">删除</span></a>
								</td>
							</tr>
						</table>

					</div>

					<?php  
						
					?>

					<!-- tweet reply module -->
					<div id = "tweet-reply" class = "left-column-module">
						<!-- profile pic -->
						<div id = "user-pic" class = "reply-user-pic">
							<img src = "images/male-avatar.jpeg" class = "user-pic"/>
						</div>

						<!-- reply message text area -->
						<textarea id = "reply-area" onkeyup = "characters_left('reply-area', 'reply-char-count')"></textarea>

						<!-- reply message functions -->
						<div id = "reply-bar" class = "reply-bar">
							<button type = "button" id = "reply" class = "send">
								<div class = "tweet-text">发布</div>
							</button>
							<p id = "reply-char-count" class = "char-count">140</p>
						</div>

						<!-- single reply module -->
						<div id = "single-reply" class = "left-column-module single-reply">

							<table id = "single-tweet-table">
								<tr>
									<!-- profile pic -->
									<td rowspan = "3" width = "60px" class = "tweet-user-pic">
										<div id = "user-pic" class = "tweet-user-pic">
											<img src = <?php echo "./avatars/" . get_user_avatar($_SESSION['username']); ?> class = "user-pic"/>
										</div>
									</td>

									<!-- tweet meta data -->
									<td class = "single-tweet-meta" id = "single-tweet-meta">
										<span id = "single-tweet-username">昵称</span>
										<span id = "single-tweet-login-name">&nbsp;&nbsp;@用户名</span>
										<span id = "single-tweet-time">&#183;&nbsp;时间</span>
									</td>
								</tr>

								<tr>
									<!-- single tweet meta -->
								 	<td class = "single-tweet-meta single-tweet-content" id = "single-tweet-content">
										微博内容：这里试一下随机的内容。要让字段换一下行看看浏览器里面如何显示。那么三行字会怎么样显示？想试试看。这里应该足够四暗示三行字的字数了。
									</td>
								</tr>

								<tr>
									<!-- tweet meta functions -->
									<td class = "tweet-functions">
										<!-- <a><span onclick = "reply(this)">回复</span></a> &#183;
										<a>转发</a> &#183;
										<a><span onclick = "delete_tweet(this)">删除</span></a> -->
									</td>
								</tr>
							</table>

						</div>
					</div>


					
				</div>
				</div>
			</div>

			<!-- right column: profile display -->
			<div id = "right-column">
				<!-- personal profile module -->
				<div id = "personal-profile" class = "right-column-module">
					<div id = "profile-pic">
						<img src = <?php echo "./avatars/" . get_user_avatar($_SESSION['username']); ?> class = "user-pic-big"/> <!--"images/male-avatar.jpeg"-->
					</div>

					<div id = "profile-meta">
						<p id = "display-name">
							<?php echo get_display_name($_SESSION['username']);?>
						</p>
						<br />
						<p id = "username">@<?php echo $_SESSION['username'];?></p>
						<table id = "tweets-meta">
							<tr>
								<td id = "tweets-meta-tweets" class = "tweets-meta-header">微博</td>
								<td id = "tweets-meta-following" class = "tweets-meta-header">关注</td>
								<td id = "tweets-meta-followers" class = "tweets-meta-header">粉丝</td>
							</tr>

							<tr>
								<td id = "tweets-meta-tweets-count" class = "tweets-meta-data">
									<?php echo get_number_tweets($_SESSION['username']); ?>
								</td>
								<td id = "tweets-meta-following-count" class = "tweets-meta-data">
									<?php echo get_number_following($_SESSION['username']);?>
								</td>
								<td id = "tweets-meta-followers-count" class = "tweets-meta-data">
									<?php echo get_number_followers($_SESSION['username']);?>
								</td>
							</tr>
						</table>
					</div>
				</div>

				<!-- module separator -->
				<div class = "right-column-module">
					<hr class = "module-separator"/>
				</div>

				<!-- change avatar module -->
				<div id = "change-avatar" class = "right-column-module">
					<form action = "./php/upload_avatar.php" method = "POST" enctype = "multipart/form-data" id = "change-avatar-form">
						<div class = "change-avatar-text">
							改变头像
						</div>		
						<input type = "hidden" name = "username" value = <?php echo '"'.$_SESSION['username'].'"' ?> />
						<!-- hidden button trick -->
						<input type = "file" name = "file" id = "change-avatar-input" onchange = "onFileChanged()" />
						<!-- <input type = "submit" name = "submit" value = "Submit"> -->
						<button type = "submit" class = "change-avatar" onclick = "fake_photo('change-avatar-input', event)"></button>
					</form>
				</div>

				<!-- module separator -->
				<div class = "right-column-module">
					<hr class = "module-separator"/>
				</div>

				<!-- followers module -->
				<div id = "following" class = "right-column-module">
					<p class = "module-header">关注</p>

					
					<div id = "users-singular">
						<div id = "user-pic">
							<img src = "images/male-avatar.jpeg" class = "user-pic"/>
						</div>

						<div class = "other-users-username-wrapper">
							<span class = "other-users-username" title = "用户名">@用户名</span>
						</div>
					</div>	
				</div>

				<!-- popular users module -->
				<div class = "right-column-module">
					<hr class = "module-separator"/>
				</div>

				<div id = "popular-user" class = "right-column-module">
					<p class = "module-header">人気用户</p>
					<?php popular_users_home(); ?>
					<div id = "users-singular">
						<div id = "user-pic">
							<img src = "images/male-avatar.jpeg" class = "user-pic"/>
						</div>

						<div class = "other-users-username-wrapper">
							<span class = "other-users-username" title = "用户名">@用户名</span>
						</div>
					</div>

				</div>
			</div>
		</div>

		<!-- footer  -->
		<div id = "footer">
			<p>&copy;2014 喵喵微博</p>
		</div>
	</div>
</body>
</html>