<?php
/*************************************************
Voting settings for API
www.ask-oracle.com/?voterapi=1&pid=200&username=arpit123
*************************************************/
add_filter('template_include','aheadzen_voter_add_vote_api');
function aheadzen_voter_add_vote_api($template)
{
	if($_GET['voterapi'])
	{
		global $wpdb,$current_user;
		$user_error = $post_error = '';
		$pid = $_GET['pid'];
		$user_id = $current_user->ID;
		if($_GET['uid']){
			$user_id = $_GET['uid'];
			$user_id = $wpdb->get_var("select ID from $wpdb->users where ID=\"$user_id\"");
		}
		if($_GET['username']){
			$username = $_GET['username'];
			$user_id = $wpdb->get_var("select ID from $wpdb->users where user_login=\"$username\"");
		}		
		if(!$user_id){
			$user_error='wronguser';
		}
		if($_GET['type']=='comment'){
			$post_error = '';
		}else{
			if(get_the_title($pid)=='')
			{
				$post_error = 'wrongpost';
			}
		}
		if(!is_numeric($pid))
		{
			$post_error = 'invalidpost';
		}
		
		$arg = array(
			'item_id'=>$pid,
			'item_error'=>$post_error,
			'user_id'=>$user_id,
			'user_error'=>$user_error,
			'type'=>$_GET['type'],
			);
			
		header("Access-Control-Allow-Origin: *");	
		echo VoterPluginClass::aheadzen_get_post_all_vote_details($arg);
		header('Content-Type: application/json; charset=UTF-8', true);
		exit;
	}
	return $template;
}
