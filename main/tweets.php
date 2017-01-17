<!DOCTYPE HTML>
<html>
<head>
	<title>【广场、我的、我关注用户的】微博</title>
	<link rel = "stylesheet" type = "text/css" href = "css/home.css">
	<link rel = "stylesheet" type = "text/css" href = "css/results.css">
	<meta charset = "UTF-8">
</head>

<body>
	<div id = "wrapper">
		<!-- header bar -->
		<div id = "header">
			<!-- logo -->
			<div id = "logo-middle">
				<a href = "home.html"><img id = "logo" src = "images/cat-icon.png"  /></a>
			</div>
		</div>
		
		<hr />

		<div id = "main-content">
			<div id = "page-title">微博（<span>37</span>）</div>
			
			<!-- if the search results are tweets --> 
			<div id = "single-tweet-wrapper">
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
									<a><span onclick = "reply(this)">回复</span></a> &#183;
									<a>转发</a> &#183;
									<a><span onclick = "delete_tweet(this)">删除</span></a>
								</td>
							</tr>
						</table>

					</div>

					<!-- tweet reply module -->
					<div id = "tweet-reply" class = "left-column-module">
						<!-- profile pic -->
						<div id = "user-pic" class = "reply-user-pic">
							<img src = "images/male-avatar.jpeg" class = "user-pic"/>
						</div>

						<!-- reply message text area -->
						<textarea id = "reply-area" onkeyup = "characters_left('reply-area', 'reply-char-count')"></textarea>

						<!-- reply message functions -->
						<div id = "reply-bar">
							<button type = "button" id = "reply" class = "send">
								<div class = "tweet-text">发布</div>
							</button>
							<p id = "reply-char-count" class = "char-count">140</p>
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