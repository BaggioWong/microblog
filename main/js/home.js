var tweet_count = 0; // used to assign unique ID, so reply function doesn't mess up  
// when one reply function is hit, the first tweet's box is wrongly shown because it's the first ID to be matched
var replyID = 10000;	//	make reply ID unique

var hasChosenFile = false;	//	this prevents default action for fake image overlay button (choosing avatar)

// display characters left and returns false if exceeded limit (140 chars)
function characters_left(compose_id, char_count_id) {
	var canTweet = true;

	var maxLimit = 140;
	// get content
	var content = document.getElementById(compose_id);
	// count string length
	var length = content.value.length;

	// if > maxLimit, display limit
	if (length <= maxLimit) {
		var remainingChars = maxLimit - length;
		document.getElementById(char_count_id).innerHTML = remainingChars;
		// document.getElementById("characters-left").innerHTML = "Characters left: " + remainingChars;
	// else display warning message	
	} else {
		var excessChars = maxLimit - length;
		document.getElementById(char_count_id).innerHTML = "<span style = 'color:red'>" + excessChars + "</span>";
		canTweet = false;
	}

	return canTweet;
}

function characters_left_reply(textarea, count) {
	var limit = 140;
	var length = $(textarea).val().length;
	var remaining = limit - length;
	
	
	if (remaining < 0) {
		$(count).html("<span style = 'color:red'>" + remaining + "</span>");
		return false;
	} else {
		$(count).html("<span>" + remaining + "</span>");
		return true;
	}
}

// the clear on click effect
function clear_field(element) {
	var value = document.getElementById(element.id).value;
	if (value == "搜索")
		element.value = "";
	// deepens font color when onfocus
	element.style.color = "gray";
}

// the restore on blur effect
function restore_field(element) {
	var value = document.getElementById(element.id).value;

	if (value == "")
		element.value = "搜索";

	element.style.color = "#cbcbcb";
}

// fake photo button effect
function fake_photo(element_id, event) {
	console.log(element_id, event);

	//	needs implementation
	// if (!hasChosenFile) {
	// 	event.preventDefault();
	// 	document.getElementById(element_id).click();
	// }
	// document.getElementById(element_id).click();
}

//	submit form
function onFileChanged() {
	hasChosenFile = true;
	document.getElementById('change-avatar-form').submit();

	//	after changing file, update it in the avatars
}

/*
 *	Tweet function - displays tweet on front end and adds entry to database
 *
 */
function tweet() {
	// check if characters is okay first
	if (characters_left("compose", "char-count") == false)
		return;
	// increment global tweet counter
	tweet_count++;

	var single_tweet_original = document.getElementById("single-tweet-wrapper");
	var single_tweet = document.getElementById("single-tweet-wrapper").cloneNode(true);
	
	// make id of "single-tweet-wrapper" unique by appending tweet_counter to string
	single_tweet.id += tweet_count;
	
	var childNodes = single_tweet.childNodes;
	
	// add text to tweet
	var single_tweet_content_node = childNodes[1].childNodes[3].childNodes[1].childNodes[1].childNodes[2].childNodes[3];
	var single_tweet_content = document.getElementById("compose").value;
	single_tweet_content_node.innerHTML = single_tweet_content;

	// change tweet time
	var single_tweet_time_node = childNodes[1].childNodes[3].childNodes[1].childNodes[1].childNodes[0].childNodes[7].childNodes[5];
	single_tweet_time_node.innerHTML = getTimeNow();
	
	var all_tweets = document.getElementById("left-column").childNodes;
	document.getElementById("left-column").insertBefore(single_tweet, all_tweets[12]); // all_tweets[12] works before we're adding tweet at the TOP of the pile of tweets, which is always at a fixed position from the left-column wrapper

	// inserts tweet to database
	var tweet = $("#compose").val();
	sendTweet(tweet);
}


/*
 *	Simplified tweet function
 *
 */
function tweet_simple(tweet_content) {
	//	Get the sample node 
	var node 				= 	$("#tweet-ID-0");
	var display_name_node 	= 	$("#tweet-ID-0 span#single-tweet-username");
	var username_node 		= 	$("#tweet-ID-0 span#single-tweet-login-name");
	var time_node 			= 	$("#tweet-ID-0 span#single-tweet-time");
	var tweet_node 			= 	$("#tweet-ID-0 #single-tweet-content");

	//	Change its attributes (display_name, username, time, tweet content)
	var display_name 	= 	$("#personal-profile #profile-meta #display-name").html();
	var username 		= 	$("#personal-profile #profile-meta #username").html();
	var time 			= 	getTimeNow();
	var tweet 			= 	tweet_content;

	display_name_node.html(display_name);
	username_node.html(username);
	time_node.html(time);
	tweet_node.html(tweet);

	//	Turn retweet off
	$("#tweet-ID-0 #retweet").css("display", "none");
	
	//	Clone node
	var cloned_node = node.clone();

	//	Insert after tweets_header
	$(cloned_node.html()).insertAfter("#tweets-header.left-column-module");
}

// Structure time string
function getTimeNow() {
	var now = new Date();
	var dateString = now.getFullYear() + "-";

	// format month if less than ten
	var month = now.getMonth() + 1;
	if (month < 10)
		dateString += "0" + month + "-";
	else
		dateString += month + "-";

	// format day if less than ten
	var day = now.getDate();
	if (day < 10)
		dateString += "0" + day + " ";
	else
		dateString += day + " ";

	// format hours if less than ten
	var hours = now.getHours();
	if (hours < 10)
		dateString += "0" + hours + ":";
	else
		dateString += hours + ":";

	// format minutes if less than ten
	var minutes = now.getMinutes();	
	if (minutes < 10)
		dateString += "0" + minutes + ":";	
	else
		dateString += minutes + ":";

	// format seconds if less than ten
	var seconds = now.getSeconds();
	if (seconds < 10)
		dateString += "0" + seconds;
	else
		dateString += seconds;

	return dateString;
}

/*
 *	Inserts tweet to database
 */

function sendTweet(tweet) {
	var tweet_text = tweet;

	//	sanitise text
	tweet_text = tweet_text.replace(/'/g, "&#39;");
	tweet_text = tweet_text.replace(/"/g, "&#34;"); 

	var postData = {
		"author" 		: 	$("#username").text().split("@")[1],	// be careful of the @! - @username
		"tweet" 		: 	tweet_text,
		"date" 			: 	getTimeNow()
	};


	$.ajax({
		type 		: 	'POST',
		url			:  	'../php/tweet.php', 
		data 		: 	postData,
		dataType	: 	'JSON',
		complete	: 	function(data) {
			// alert(data);
			console.log(data);
		}, 
		error		: 	function(error) {
			// alert('error');
		}
	});
}


/*
 *	Gets rid of session variable logged_in and redirects back to login page
 *	returns 0 on success, -1 otherwise
 */
function logout() {
 	$.ajax({
 		type 		: 	'POST',
		url			:  	'../php/logout.php', 
		data 		: 	{},
		dataType	: 	'JSON',
		success		: 	function(data) {
			if (data.logout == true)
				window.location = 'login.php';
		}
 	});
}

$(document).ready(function(){
	//	attach logout event handler
	$('#logout').on('click', function(){logout();});

	//	attach reply event handler
	$('div.reply-bar button').on('click', function(){
		reply_tweet($(this));
	});

	//	Initialize fetched times to 0 - fetch only once per page load
	$('.tweet-functions [name="reply"]').attr('fetch', 0);
	
	//	fetch replies for current tweets
	$('.tweet-functions [name="reply"]').on('click', function(){
		display_replies($(this));
	});

	//	attach char count for reply compose area
	$('.left-column-module textarea').on('keyup', function(){
		characters_left_reply(this, '#' + $(this).parent().parent().attr('id') + ' .left-column-module .reply-bar .char-count');
	});

	//	attach retweet handler
	$('span#retweet').on('click', function() {
		retweet($(this));
	});

	//	display users followed by username on load
	displayFollowing();
});

/* 
 *	Display people followed by username on user home page
 *
 */
function displayFollowing() {

	var postData = {
		username: $("#username").text().split("@")[1]
	}

	$.ajax({
		type 		: 	'POST',
		url			:  	'../php/display_following.php', 
		data 		: 	postData,
		dataType	: 	'JSON',
		success		: 	function(data) {
			var length = data.length;
			for (var i = 0; i < length; i++) {
				following_users_singular_html(data[i].name, data[i].avatar, i);
			}
		}
	});
}

/* 	
 *	Output following users html
 *	Returns nothing
 */	
function following_users_singular_html(username, avatar, tempNodeID) {		//	tempNodeID is used to distinguish between clonedNodes

	//	get user singular div
	var following_div 		= $("#following");
	var singular_user_div 	= $("#following div#users-singular:first-of-type");		//	first of type - only ONE element is cloned
	
	//	clone node
	var clonedNode 			= singular_user_div.clone();
	clonedNode.attr('num', tempNodeID);

	//	insert node
	$(clonedNode).appendTo(following_div);
	
	//	change username	
	var num_attr 			= "[num='"+ tempNodeID + "']";
	var username_span		= $("[num='"+ tempNodeID +"'] span");
	var avatar_img			= $("[num='"+ tempNodeID +"'] img");
	// $("[num='"+ tempNodeID +"'] span a").attr('href', ('./user.php?username=' + username));
	username_span.html("@<a href = './user.php?username=" + username + "'>" + username + "</a");
	avatar_img.attr("src", "./avatars/" + avatar);
}

/*
 * 	Switch display of reply area on and off
 *
 */
function reply(tweet) {
	// all the way up till parentNode is the single-tweet node
	// from there to the last next sibling is that tweet's tweet-reply node
	var tweet_reply = tweet.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.nextSibling.nextSibling.nextSibling.nextSibling;

	var tweet_reply_style = window.getComputedStyle(tweet_reply);

	var tweet_reply_hidden = tweet_reply_style.getPropertyValue("display");


	if (tweet_reply_hidden == "none")
		tweet_reply.style.display = "block";
	else if (tweet_reply_hidden != "none")
		tweet_reply.style.display = "none";
}

/*
 *	Display replies for particular tweet (identified by tweet_ID)
 *
 */
function display_replies(reply_button) {
	//	If data has already been fetched, return
	var fetched = $(reply_button).attr('fetch');

	if (parseInt(fetched) != 0)
		return;
	else
		$(reply_button).attr('fetch', (parseInt(fetched) + 1) );

	//	Get tweet ID
	var tweet_ID = $(reply_button).parent().parent().parent().parent().parent().parent().parent().attr("id").split("-")[2];

	var postData = {
		tweet_ID: tweet_ID
	};

	//	Send AJAX request to get replies associated with it
	$.ajax({
		type 		: 	'POST',
		url			:  	'../php/display_tweets.php', 
		data 		: 	postData,
		dataType	: 	'JSON',
		success		: 	function(data) {
			var replies = data.length;
			var authorID;
			var replyID;
			var display_name;
			var username;
			var time;
			var reply;
			var avatar;

			var string = "";
			for (var i = 0; i < replies; i++) {
				replyID 		= 	data[i].RID;
				authorID 		= 	data[i].AuthorID;
				reply 			= 	data[i].Reply;
				time 			= 	data[i].Date;
				display_name 	= 	data[i].DisplayName;
				username 		= 	data[i].Username;
				avatar 			= 	data[i].Avatar;

				single_reply_html(reply_button, replyID, display_name, username, time, reply, avatar);
			}
		}
	});
}

/* 
 *	Helper function for display_replies, handle cloning and displaying reply function
 *
 */
function single_reply_html(reply_button, replyID, display_name, username, time, reply, avatar) {
	//	get reply div
		var unique_tweet 		= $(reply_button).parent().parent().parent().parent().parent().parent().parent();
		var unique_tweet_ID		= unique_tweet.attr("id");
		var whole_reply_div 	= $("#" + unique_tweet.attr("id") + " .single-reply");

		//	clone node - changes made to clone
		var clonedNode = whole_reply_div.clone();
		var clonedNodeOuterHTML = clonedNode.prop('outerHTML');
		clonedNode.attr("id", replyID);

		//	insert cloned node
		$(clonedNode).appendTo(whole_reply_div.parent());

		//	get display_name, username and time div
		var display_name_div 	= $("#" + unique_tweet_ID + " #" + clonedNode.attr("id") + " #single-tweet-username");
		var username_div 		= $("#" + unique_tweet_ID + " #" + clonedNode.attr("id") + " #single-tweet-login-name");
		var time_div 			= $("#" + unique_tweet_ID + " #" + clonedNode.attr("id") + " #single-tweet-time");
		var reply_div			= $("#" + unique_tweet_ID + " #" + clonedNode.attr("id") + " #single-tweet-content");
		var avatar_div 			= $("#" + unique_tweet_ID + " #" + clonedNode.attr("id") + " .tweet-user-pic img");
		var reply_compose_div	= $(reply_button).parent().prev();

		//	change the contents of reply cloned node
		display_name_div.html(display_name);
		username_div.html(username);
		time_div.html(time);
		reply_div.html(reply);
		avatar_div.attr("src", "./avatars/" + avatar);
}

/* 
 *	Replied comment shows beneath tweet, also submits reply to database
 *	returns nothing
 */
function reply_tweet(reply_button) {
	//	if char count < 0, return
	var char_count 			= $(reply_button).next().text();
	char_count 				= parseInt(char_count);

	if (char_count < 0)
		return;
	
	//	display reply beneath reply compose

		//	get reply div
		var unique_tweet_ID		= $(reply_button).parent().parent().parent().attr("id");
		var whole_reply_div 	= $(reply_button).parent().next();

		//	clone node - changes made to clone
		var clonedNode = whole_reply_div.clone();
		var clonedNodeOuterHTML = clonedNode.prop('outerHTML');
		replyID++;
		var newID = clonedNode.attr("id") + "-" + replyID;
		clonedNode.attr("id", newID);

		//	insert cloned node
		$(clonedNode).appendTo(whole_reply_div.parent());

		//	get display_name, username and time div
		var display_name_div 	= $("#" + unique_tweet_ID + " #" + clonedNode.attr("id") + " #single-tweet-username");
		var username_div 		= $("#" + unique_tweet_ID + " #" + clonedNode.attr("id") + " #single-tweet-login-name");
		var time_div 			= $("#" + unique_tweet_ID + " #" + clonedNode.attr("id") + " #single-tweet-time");
		var reply_div			= $("#" + unique_tweet_ID + " #" + clonedNode.attr("id") + " #single-tweet-content");
		var avatar_div			= $("#" + unique_tweet_ID + " #" + clonedNode.attr("id") + " .tweet-user-pic img");
		var reply_compose_div	= $(reply_button).parent().prev();

		//	get values
		var display_name 		= $("#display-name").text();
		var username 			= $("#username").text().split("@")[1];
		var time 				= getTimeNow();
		var reply 				= reply_compose_div.val();
		var avatar 				= $("#profile-pic .user-pic-big").attr("src");

		//	change the contents of reply cloned node
		display_name_div.html(display_name);
		username_div.html(username);
		time_div.html(time);
		reply_div.html(reply);
		avatar_div.attr("src", avatar);
	

	//	post request to insert reply to database
	var postData = {
		author 		: 	$("#username").text().split("@")[1],
		tweet_ID 	: 	$(reply_button).parent().parent().parent().attr("id").split("-")[2],
		reply 		: 	reply,
		date 		: 	getTimeNow()
	};

	$.ajax({
		type 		: 	'POST',
		url			:  	'../php/reply.php', 
		data 		: 	postData,
		dataType	: 	'JSON',
		success		: 	function(data) {}
	});
}

/* 
 *	Retweet = append @tweet author's username, and send to tweets table
 *
 */
function retweet(tweet) {
	//	grab the tweet author, and tweet content
	var author = $($($($($(tweet).parent().parent().parent().children()[0]).children()[1]).children()[1]).children()[0]).html();
	var tweet = $($(tweet).parent().parent().prev().children()[0]).html().trim();

	//	append "转发@"
	var retweet_tweet = "转发@" + author + ": " + tweet;

	//	if over 140 characters in total, can't retweet
	if (retweet_tweet.length > 140) {
		bootbox.alert("超过140个字符!", function(){
			return;
		});
	} else {
		//	enable stylesheet first
		document.getElementById("bootstrap").disabled = false;		// jQuery doesn't work for some reason

		//	show the popup box
		bootbox.dialog({
			message: retweet_tweet,
			title: "确认转发这条微博？",
			buttons: {
				danger: {
					label: "取消",
					className: "btn-danger",
					callback: function() {
						//	disable stylesheet after finish
						document.getElementById("bootstrap").disabled = true;		// jQuery doesn't work for some reason
					}
				},
				//	if they click 确认, then retweet, else do nothing
				success: {
					label: "确认",
					className: "btn-success",
					callback: function() {

						//	insert it into database as with an ordinary tweet
						tweet_simple(retweet_tweet);
						sendTweet(retweet_tweet);

						//	disable stylesheet after finish
						document.getElementById("bootstrap").disabled = true;		// jQuery doesn't work for some reason
					}
				}
			}	
		});		
		
	}
}

/*	
 *	delete tweet front end and remove tweet + replies from db
 *
 */
function delete_tweet(element) {
	var leftColumn = document.getElementById("left-column");

	// parent node is always at fixed distance from node
	leftColumn.removeChild(element.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode);
	//	get tweet_ID
	var tweet_ID = $(element).parent().parent().parent().parent().parent().parent().parent().attr("id").split("-")[2];
	var postData = {
		tweet_ID 	: 	tweet_ID
	};

	//	delete tweet and reply from database
	$.ajax({
		type 		: 	'POST',
		url			:  	'../php/delete.php', 
		data 		: 	postData,
		dataType	: 	'JSON',
		success		: 	function(data) {
		}
	});
}