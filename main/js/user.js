$(document).ready(function(){
	//	display 解除关注 button or not
	display_button();

	//	attach follow button event handler
	$("#follow-button").on('click', function(){
		follow_unfollow();
	});

});

/*
 *	Display 解除关注 button or not
 *	If $user is visiting own page, hide button
 */
function display_button() {
	var logged_in_username 	= $("#hidden-username").text();
	var page_username 		= $("#username").text().split("@")[1];

	if (logged_in_username == page_username)
		$("#follow-button").css("display", "none");	
}


/*
 *	If button text is 解除关注, delete relationship
 *	Else if button text is 关注, add relationship
 */

 function follow_unfollow() {
 	var button_text = 	$("#follow-button").text().trim();
 	var action 		= 	"";

 	//	if 解除关注, delete relationship
 	if (button_text == '解除关注')
 		action = 'delete';
 	//	else if 关注, add relationship
 	else if (button_text == '关注')
 		action = 'add';

 	var postData = {
 		action			: 	action,
 		follower_name	: 	$("#hidden-username").text(),
 		followed_name	: 	$("#username").text().split("@")[1]
 	};
 	
 	$.ajax({
 		type 		: 	'POST',
		url			:  	'../php/follow.php', 
		data 		: 	postData,
		dataType	: 	'JSON',
		success		: 	function(data) {
			// alert(data.status + " " + data.action + " " + data.follower_name + " " + data.followed_name + " " + data.follower_ID + " " + data.followed_ID);
			if (data.status == "success") {
				if (button_text == '解除关注')
					$("#follow-button").html("关注");
				else if (button_text == '关注')
					$("#follow-button").html("解除关注");

				$("#tweets-meta-followers-count").html(data.followers);
			}
		}
 	});
 }