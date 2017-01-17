<?php
// Functions to access database
include 'db_connect.php';
header('Content-Type: text/html; charset=utf-8');


/*
 *	Checks if user password matches with real password
 *	returns true if matches, false if doesn't
 */
function password_valid($username, $user_password) {
	$password = get_password($username);

	if($password == $user_password)
		return true;
	else 
		return false;
}

/* 
 * 	Gets password for given username
 * 	returns password or -1 on error
 */
function get_password($username) {
	global $link;
	$query = "SELECT `password` FROM `users` WHERE `username` = '{$username}'";

	if ( ($result = mysqli_query($link, $query)) == false)	// either username doesn't exist, or password doesn't match
		return -1; 
	else {
		$row = mysqli_fetch_array($result);
		$password = $row[0];
		return $password;
	}
}

/* 
 *	Checks if $username exists
 *	Returns -1 on error, true if exists, false if doesn't exist
 */
function user_exists($username) {
	global $link;
	$query = "SELECT username FROM users WHERE username = '{$username}'";

	if ( ($result = mysqli_query($link, $query)) == false ) 
		return -1;
	else {
		$rows = mysqli_num_rows($result);
		if ($rows == 1)
			return true;
		else 
			return false;
	}
}

/*
 * 	Adds user to users table
 *	Returns -1 on error, 0 on success
 */
function add_user($username, $display_name, $password, $email, $date) {
	global $link;
	$query = "	INSERT INTO `users`(`Username`, `Display_name`, `Password`, `Email`, `Date created`) 
				VALUES ('{$username}', '{$display_name}', '{$password}', '{$email}', '{$date}')";

	if ( ($result = mysqli_query($link, $query)) == false ) 
		return -1;
	else
		return 0;
}

/*
 *	Outputs 15 newest users
 */
function new_users() {
	global $link;
	$query = "SELECT username FROM users ORDER BY UID DESC LIMIT 15";
	$result = mysqli_query($link, $query);

	while ($row = mysqli_fetch_array($result)) {
		$html = "<div id = 'new-user-singular'><img src = '../avatars/" . get_user_avatar($row['username']) ."' class = 'single-user' /><p class = 'username'>@".$row['username']."</p></div>";
		echo $html;
	}
}

/*
 *	Outputs 10 most popular users
 */
function popular_users() {
	global $link;
	$query = "SELECT display_name, followers FROM users ORDER BY followers DESC LIMIT 10";
	$result = mysqli_query($link, $query);

	while ($row = mysqli_fetch_array($result)) {
		$html = "<li>{$row['display_name']}<span id = 'popular-user-followers'>{$row['followers']}</span></li>";
		echo $html;
	}
}

/* 
 *	Displays 10 users that username is following on user home page
 */
function following_home($username) {
	global $link;
	$user_ID = get_user_ID($username);
	$query = "SELECT FollowedID FROM relationships WHERE FollowerID = {$user_ID}";
	$result = mysqli_query($link, $query);

	$follower_names = array();
	$i = 0;
	while($row = mysqli_fetch_array($result)) {
		$current_row = array();
		$current_row['name'] = get_username($row['FollowedID']); 
		$current_row['avatar'] = get_user_avatar(get_username($row['FollowedID']));
		array_push($follower_names, $current_row);
		// $follower_names[$i] = get_username($row['FollowedID']);
		$i++;
	}

	echo json_encode($follower_names);
}

/*
 *	Search tweets with search_string
 *	Outputs formatted tweets
 */
function search_tweets($search_string, $current_user) {
	global $link;

	//	query all tweets
	$query = "SELECT TID, `Author ID`, tweet, Date FROM tweets";
	$result = mysqli_query($link, $query);
	
	//	for each tweet that comes back, see if it matches string partially
	while ($row = mysqli_fetch_array($result)) {
		if (strpos($row['tweet'], $search_string) !== false) {
			
			$username = get_username($row['Author ID']);
			$display_name = get_display_name($username);
			$timestamp = $row['Date'];
			$tweet = $row['tweet'];
			$tweet_ID = $row['TID'];
			//	if it does, output it
			tweets_html($current_user, $display_name, $username, $timestamp, $tweet, $tweet_ID);
		}
	}
}

/*
 *	Search users if string is in username
 *	Returns list of matched users (display_name, username) pairs
 */	
function search_users($search_string) {
	global $link;

	//	query all users
	$query = "SELECT username, display_name FROM users";
	$result = mysqli_query($link, $query);
	$matched_users = array();
	$i = 0;
	//	for each user that comes back, see if it matches string partially
	while( $row = mysqli_fetch_array($result) ) {
		if (strpos($row['display_name'], $search_string) !== false) {
			$names = array();
			$names['display_name'] = $row['display_name'];
			$names['username'] = $row['username'];
			$matched_users[$i] = $names;

			output_search_users($row['display_name'], $row['username']);
		}
	}

	return $matched_users;
}

/* 
 *	Helper function to search_users to output HTML
 *
 */
function output_search_users($display_name, $username) {
	//	format output
	$html = "<div id = 'personal-profile' class = 'user'>
				<div id = 'profile-pic'>
					<img src = './avatars/". get_user_avatar($username) ."' class = 'user-pic-big'/>
				</div>

				<div id = 'profile-meta-small'>
					<p id = 'display-name'>".$display_name."</p>
					<br />
					<p id = 'username'>@<a href = '../user.php?username=".$username."'>".$username."</a>"."</p>
				</div>
			</div>";

	echo $html;
}

/* 
 *	Shows 10 most popular users on user home page
 */
function popular_users_home() {
	global $link;
	$query = "SELECT username FROM users ORDER BY followers DESC LIMIT 10";
	$result = mysqli_query($link, $query);

	while ($row = mysqli_fetch_array($result)) {
		$html = "<div id = 'users-singular'>
					<div id = 'user-pic'>
						<img src = './avatars/". get_user_avatar($row['username']) ."' class = 'user-pic'/>
					</div>

					<div class = 'other-users-username-wrapper'>
						<span class = 'other-users-username' title = '用户名'>@<a href = '../user.php?username={$row['username']}'>{$row['username']}</a></span>
					</div>
				</div>";
		echo $html;
	}
}
/* 
 *	Shows tweets on personal page - tweets of user and from following people
 *	If isPersonal is set true, will show only tweets by that user
 *	else shows tweets from user and users that user is following
 */
function show_tweets($username, $is_personal) {
	global $link;

	//	Current username
	$current_username = $username;

	//	Get user ID
	$user_ID = get_user_ID($username);

	//	Get ID array of following
	$following_IDs = get_following($user_ID);
	$following_IDs_len = count($following_IDs);

	//	Select all tweets from any of user ID or following IDs
	$query = array();

	//	Select all users tweets

	// 	Format query to get list of all users, ordered by date in descending order

	$query_string = "SELECT TID, tweet, `author ID`, date FROM tweets WHERE `Author ID` = {$user_ID}";
	if ($is_personal == false) {
		//	If $is_personal is set to false, then select all users $username is following, else skip
		for ($i = 0; $i < $following_IDs_len; $i++) {
			$query_string .= " OR `Author ID` = {$following_IDs[$i]}";
		}
	}
	$query_string .= " ORDER BY date DESC";
	
	//	execute query
	$result = mysqli_query($link, $query_string);

	//	get query results
	while ( $row = mysqli_fetch_array($result) ) {
		//	fetch arguments
		$username 		= get_username($row['author ID']);
		$display_name 	= get_display_name($username);
		$timestamp 		= $row['date'];
		$tweet 			= $row['tweet'];
		$tweet_ID		= $row['TID'];

		//	display helper functions
		tweets_html($current_username, $display_name, $username, $timestamp, $tweet, $tweet_ID);
	}
}

/* 
 *	Helper function to show single tweet html based on parameters
 */
function tweets_html($current_username, $display_name, $username, $timestamp, $tweet, $tweet_ID) {
	$html = '<div id = "single-tweet-wrapper">
				<div id = "tweet-ID-'.$tweet_ID.'">
					<!-- single tweet module -->
					<div id = "single-tweet" class = "left-column-module">

						<table id = "single-tweet-table">
							<tr>
								<!-- profile pic -->
								<td rowspan = "3" width = "60px" class = "tweet-user-pic">
									<div id = "user-pic" class = "tweet-user-pic">

										<img src = "./avatars/'. get_user_avatar($username) .'" class = "user-pic"/>
									</div>
								</td>

								<!-- tweet meta data -->
								<td class = "single-tweet-meta" id = "single-tweet-meta">
									<span id = "single-tweet-username">'.$display_name.'</span>
									<span id = "single-tweet-login-name">&nbsp;&nbsp;@<a href = "../user.php?username='.$username.'">'.$username.'</a></span>
									<span id = "single-tweet-time">&#183;&nbsp;'.$timestamp.'</span>
								</td>
							</tr>

							<tr>
								<!-- single tweet meta -->
							 	<td class = "single-tweet-meta single-tweet-content" id = "single-tweet-content">
									'.$tweet.'
								</td>
							</tr>

							<tr>
								<!-- tweet meta functions -->
								<td class = "tweet-functions">
									<a><span onclick = "reply(this)" name = "reply">回复</span></a> ';
								if ($current_username != $username) {
									$html .= "&#183;<span id = 'retweet'>转发</span>";
								}
									
								if ($current_username == $username) {
									$html .= '&#183;<a><span onclick = "delete_tweet(this)">删除</span></a>';
								}
								
								$html .= '</td>
							</tr>
						</table>

					</div>
					<!-- tweet reply module -->
					<div id = "tweet-reply" class = "left-column-module">
						<!-- profile pic -->
						<div id = "user-pic" class = "reply-user-pic">
							<img src = "./avatars/'. get_user_avatar($current_username) .'" class = "user-pic"/>
						</div>

						<!-- reply message text area -->
						<textarea id = "reply-area" onkeyup = "characters_left'."('reply-area', 'reply-char-count')".'"></textarea>

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
										<!--<a><span onclick = "reply(this)">回复</span></a> &#183;
										<a>转发</a> &#183;
										<a><span onclick = "delete_tweet(this)">删除</span></a>-->
									</td>
								</tr>
							</table>

						</div>
					</div>
				
				</div>
			</div>';

	echo $html;	
}

// show_tweets('baggio');

/* 
 *	Get array of IDs user is following
 *	return array of followedIDs on success, -1 on failure
 */
function get_following($user_ID) {
	global $link;
	$query = "SELECT FollowedID FROM relationships WHERE FollowerID = {$user_ID}";
	$result = mysqli_query($link, $query);

	if ($result == false)
		return -1;

	$data = array();
	while ($row = mysqli_fetch_array($result))
		array_push($data, $row['FollowedID']);

	return $data;
}

/* 
 *	Gets username for ID
 * 	returns username on success, -1 on failure
 */
function get_username($ID) {
	global $link;
	$query = "SELECT username FROM users WHERE UID = {$ID}";
	$result = mysqli_query($link, $query);

	if ($result == false)
		return -1;

	$row = mysqli_fetch_array($result);

	return $row['username'];
}

/* 
 *	Gets display name for user
 * 	returns display_name on success, -1 on failure
 */
function get_display_name($username) {
	global $link;
	$query = "SELECT display_name FROM users WHERE username = '{$username}'";
	$result = mysqli_query($link, $query);

	if ($result == false)
		return -1;

	$row = mysqli_fetch_array($result);

	return $row['display_name'];
}

/*
 *	Get number of followers
 *	returns followers number on success and -1 on failure
 */
function get_number_followers($username) {
	global $link;
	$query = "SELECT followers FROM users WHERE username = '{$username}'";
	$result = mysqli_query($link, $query);

	if ($result == false)
		return -1;

	$row = mysqli_fetch_array($result);

	return $row['followers'];
}

/*
 *	Get number of people $username is following
 *	returns following number on success and -1 on failure
 */
function get_number_following($username) {
	global $link;
	$query = "SELECT following FROM users WHERE username = '{$username}'";
	$result = mysqli_query($link, $query);

	if ($result == false)
		return -1;

	$row = mysqli_fetch_array($result);

	return $row['following'];
}

/* 
 *	Get userID from username
 *	returns userID on success, -1 on failure
 */
function get_user_ID($username) {
	global $link;
	$query = "SELECT UID FROM users WHERE username = '{$username}'";
	$result = mysqli_query($link, $query);

	if ($result == false)
		return -1;

	$row = mysqli_fetch_array($result);

	return $row['UID'];
}

/* 
 *	Get number of tweets for $username
 *	returns tweets number on success, -1 on failure
 */
function get_number_tweets($username) {
	global $link;
	$UID = get_user_ID($username);
	$query = "SELECT * FROM tweets WHERE `Author ID` = {$UID}";
	$result = mysqli_query($link, $query);

	if (result == false)
		return -1;

	$rows = mysqli_num_rows($result);

	return $rows;
}


/* 
 *	Delete a tweet and its associated replies
 *	Returns 0 if successful, -1 if failure
 */
function delete_tweet($tweet_ID) {
	global $link;

	//	delete all replies 
	$query = "DELETE FROM replies WHERE `Tweet ID` = {$tweet_ID}";
	$result = mysqli_query($link, $query);

	if ($result != true)
		return -1;

	//	delete tweet
	$query = "DELETE FROM tweets WHERE TID = {$tweet_ID}";
	$result = mysqli_query($link, $query);

	if ($result == true)
		return 0;
	else
		return -1;
}

/* 
 *	Determine if relationship exists between $follower, $followed
 *	Return true if exists, false otherwise
 */
function relationship_exists($follower, $followed) {
	global $link;

	//	Get follower ID and follwed ID
	$follower_ID = get_user_ID($follower);
	$followed_ID = get_user_ID($followed);

	$query = "SELECT RID FROM relationships WHERE FollowerID = {$follower_ID} AND FollowedID = {$followed_ID}";
	$result = mysqli_query($link, $query);
	$rows = mysqli_num_rows($result);

	//	If entry for relationship exists, return true
	if ($rows == 1)
		return true;
	else 
		return false;
}

/*
 *	Delete relationship between $follower_ID and $followed_ID
 *	Return true on success and false on failure
 */
function delete_relationship($follower_ID, $followed_ID) {
	global $link;

	$query = "DELETE FROM relationships WHERE `FollowerID` = {$follower_ID} AND `FollowedID` = {$followed_ID}";
	$result = mysqli_query($link, $query);
	
	tally();

	if ($result == true)
		return true;
	else
		return false;
}

/* 
 *	Adds relationship between $follower_ID and $followed_ID
 *	Return true on success and false on failure
 */
function add_relationship($follower_ID, $followed_ID) {
	global $link;

	$query = "INSERT INTO relationships (`FollowerID`, `FollowedID`) VALUES ({$follower_ID}, {$followed_ID})";
	$result = mysqli_query($link, $query);

	tally();

	if ($result == true)
		return true;
	else
		return false;
}

/* 
 *	Re tallies all the followers and following figures for all users
 *	Use after deleting or inserting relationship
 */
function tally () {
	global $link;
	$query = "SELECT * FROM users";
	$result = mysqli_query($link, $query);
	$users = mysqli_num_rows($result);

	// Tally following and fill in users table
	for ($i = 1; $i <= $users; $i++) {
		$query = "SELECT * FROM relationships WHERE FollowerID = {$i}";
		$result = mysqli_query($link, $query);

		$rows = mysqli_num_rows($result);
		
		$query = "UPDATE users SET following = {$rows} WHERE UID = {$i}";
		mysqli_query($link, $query);
	}


	// Tally followers and fill in users table
	for ($i = 1; $i <= $users; $i++) {
		$query = "SELECT * FROM relationships WHERE FollowedID = {$i}";
		$result = mysqli_query($link, $query);

		$rows = mysqli_num_rows($result);

		$query = "UPDATE users SET followers = {$rows} WHERE UID = {$i}";
		mysqli_query($link, $query);
	}
}

/*
 *	Get filename for $username
 *	Returns -1 on failure, 0 on success
 */
function get_user_avatar($username) {
	global $link;
	$query = "SELECT Avatar FROM users WHERE Username = '{$username}'";
	$result = mysqli_query($link, $query);

	if ($result == false)
		return -1;
	else {
		$row = mysqli_fetch_array($result);

		if ($row['Avatar'] == "")
			return 'default.png';
		else
			return $row['Avatar'];
	}
}

// function update() {
// 	global $link;
// 	$query = "SELECT username FROM users";
// 	$result = mysqli_query($link, $query);
// 	// $users = array();
// 	while ($row = mysqli_fetch_array($result)) {
// 		// array_push($users, $row['username']);
// 		$query_2 = "UPDATE users SET Avatar = '{$row['username']}.png' WHERE username = '{$row['username']}'";
// 		mysqli_query($link, $query_2);
// 	}
// }

/*
 *	Save filename of avatar for $username
 */
function insert_avatar_filename($username, $filename) {
	global $link;
	$query = "UPDATE users SET Avatar = '{$filename}' WHERE Username = '{$username}'";
	mysqli_query($link, $query);
}

/*
 *	Insert tweet into database
 *	returns -1 on failure, 0 on success
 */
function insert_tweet($author, $tweet, $date) {
	global $link;
	$author_ID = get_user_ID($author);
	$query = 	"INSERT INTO tweets (`Author ID`, `Tweet`, `Date`) 
				VALUES ('{$author_ID}', '{$tweet}', '{$date}')";
	$result = mysqli_query($link, $query);
	// echo var_dump($author_ID, $tweet, $date, $query, $result);

	// if ($result == false)
	// 	return -1;
	// else
	// 	return 0;
}

/* 
 *	Insert reply into database
 *	returns -1 on failure, 0 on success
 */
function insert_reply($author, $tweet_ID, $reply, $date) {
	global $link;
	$author_ID = get_user_ID($author);
	$query = "INSERT INTO replies (`Author ID`, `Tweet ID`, Reply, Date) 
				VALUES ('{$author_ID}', '{$tweet_ID}', '{$reply}', '{$date}')";

	$result = mysqli_query($link, $query);

	if ($result == false)
		return -1;
	else 
		return 0;
}

/* 
 *	Redirect in javascript 
 *	Because header(Location: ) must be placed first - convenient workaround
 */
function redirect($url) {echo "<script>window.location = '{$url}';</script>";}

/*
 *	Alert in javascript
 * 	
 */
function alert($message) {echo "<script>alert('{$message}');</script>";}

?>