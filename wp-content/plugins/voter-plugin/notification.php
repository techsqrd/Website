<?php
class VoterBpNotifications extends VoterPluginClass {
	/*******************************
	Read all notifications on one click
	****************************/
	function aheadzen_read_user_notifications($notification_id)
	{
		global $bp, $wpdb,$table_prefix;
		$wpdb->query("update ".$table_prefix."bp_notifications set is_new='0' where id=\"$notification_id\"");
	}
	
	/*******************************
	Delete all notifications on one click
	****************************/
	function aheadzen_delete_user_notifications($user_id)
	{
		global $bp, $wpdb,$table_prefix;
		$wpdb->query("delete from ".$table_prefix."bp_notifications where user_id=\"$user_id\"");
	}
	
	/*******************************
	Read all user post notifications on while visit the page
	****************************/
	function aheadzen_read_user_post_notifications($pid,$uid,$type)
	{
		global $wpdb,$table_prefix;
		
		if($type == 'activity')
		{
			$subsql = " and component_action like \"activity_%\"";
		}elseif($type == 'topic-reply')
		{
			$subsql = " and secondary_item_id=\"$pid\"";
		}elseif($type == 'post')
		{
			$subsql = " and (item_id=\"$pid\") OR (secondary_item_id=\"$pid\" and component_action like \"comment_%\")";
		}else
		{
			$subsql = " and item_id=\"$pid\"";
		}
		$sql = "update ".$table_prefix."bp_notifications set is_new='0' where user_id=\"$uid\" $subsql";
		$wpdb->query($sql);
	}
	
	/*******************************
	Update User Notification function
	****************************/
	function aheadzen_update_user_notification()
	{
		global $activities_template,$groups_template,$bp,$current_user,$post;
		$post_type = $post->post_type;
		$user_id = $current_user->ID;
		
		if(VoterPluginClass::voter_is_old_version())
		{
			$reply_id = bp_get_the_topic_id();
		}elseif(function_exists('bbp_get_reply_id'))
		{
			$reply_id = bbp_get_reply_id();
		}
		 
		if($reply_id) 
		{
			VoterBpNotifications::aheadzen_read_user_post_notifications($reply_id,$user_id,'topic-reply');
		}elseif($groups_template->group->group_id)
		{
			VoterBpNotifications::aheadzen_read_user_post_notifications($groups_template->group->group_id,$user_id,'group');
		}elseif($activities_template->activities)
		{
			$activity_id = '0';
			VoterBpNotifications::aheadzen_read_user_post_notifications($activity_id,$user_id,'activity');
		}elseif (function_exists('bp_displayed_user_id') && bp_displayed_user_id())
		{
			$display_user_id = bp_displayed_user_id();
			VoterBpNotifications::aheadzen_read_user_post_notifications($display_user_id,$user_id,'profile');
		}elseif($post_type||$post_type=='page'||$post_type=='post'||$post_type=='product')
		{
			VoterBpNotifications::aheadzen_read_user_post_notifications($post->ID,$user_id,'post');
		}
	}

	/*******************************
	Delete Post data
	****************************/
	function aheadzen_delete_post_related_data($pid)
	{
		global $wpdb,$table_prefix;
		$voter_table = $table_prefix.'ask_votes';
		$notifications_table = $table_prefix.'bp_notifications';
		$activity_table = $table_prefix.'bp_activity';
		$wpdb->query("DELETE FROM $voter_table WHERE item_id=\"$pid\" or secondary_item_id=\"$pid\"");
		$wpdb->query("DELETE FROM $notifications_table WHERE item_id=\"$pid\"");
		$wpdb->query("DELETE FROM $activity_table WHERE component='votes' and item_id=\"$pid\"");
	}
	
	/*******************************
	Delete Topics
	****************************/
	function aheadzen_bp_delete_topic()
	{
		add_action('bbp_delete_topic',array($this,'aheadzen_delete_post_related_data'));
	}
	
	/*************************************************
	Insert voter to buddypress activity & notifications
	*************************************************/
	function aheadzen_voter_add_vote_bbpress_notification()
	{
		global $wpdb,$post, $bp, $current_user,$wp_query;	
		$item_id = (int)$_REQUEST['item_id'];
		$secondary_item_id = (int)$_REQUEST['secondary_item_id'];	
		$action = $_REQUEST['action'];
		$type = $_REQUEST['type'];
		$check_url_for_topic = $bp->unfiltered_uri;		
		
		if($bp && $_REQUEST['action'] == 'up' && $secondary_item_id && $type && function_exists('bp_loggedin_user_id') && function_exists('bp_core_get_userlink'))
		{
			$user_id = bp_loggedin_user_id();
			if($_REQUEST['user_id']){$user_id = $_REQUEST['user_id'];}
			
			$topic_id = $secondary_item_id;
			$action_str = $type.'-+'.$user_id.'-+vote-+' .$action;
			$userlink = bp_core_get_userlink( $user_id );
			
			if($type=='topic-reply'){
				if(VoterPluginClass::voter_is_old_version())
				{		
					$topic = bp_forums_get_topic_details( $item_id );
					$topic_details= bp_forums_get_post( $secondary_item_id ); //reply	
					$topic_details->post_author = $topic_details->poster_id;				
					$topic_details->post_title = $topic->topic_title;
				}else{
					$topic = bbp_get_topic( $item_id );
					$topic_details = bbp_get_reply($secondary_item_id);
					$topic_details->post_title = $topic->post_title;
				}
			}elseif($type=='topic' && function_exists('bbp_get_topic')){
				$topic_details = bbp_get_topic( $secondary_item_id );
			}			
		
			if($_REQUEST['type']=='comment' && function_exists('bp_core_get_userlink'))
			{
				$post = get_post($item_id);
				$post_title =$post->post_title;
				$post_type = get_post_type($item_id);
				$comment_detail = get_comment( $secondary_item_id );
				if($comment_detail->user_id){
					$post_author = $comment_detail->user_id;
				}else{
					$post_author = $post->post_author;
				}
				$poster_link = bp_core_get_userlink( $post->post_author );	
				$topic_link = '<a href="' . get_permalink($post->ID) .'#comment-'.$secondary_item_id.'">' . $post->post_title . '</a>';
				$action_content = sprintf( __( "%s likes comment on %s's %s %s", 'aheadzen' ), $userlink, $poster_link,$post_type, $topic_link );
			}elseif($_REQUEST['type']=='profile' || $_REQUEST['type']=='groups' || $_REQUEST['type']=='activity')
			{
				$post_title = $topic_link = $wp_query->post->post_title;			
				if($_REQUEST['type']=='profile')
				{
					$topic_link = '<a href="'.$bp->displayed_user->domain.'/">'.$bp->displayed_user->fullname.'</a>';
					$post_title = $bp->displayed_user->fullname;
					$post_author = $bp->displayed_user->id;
				}elseif($_REQUEST['type']=='groups')
				{
					$topic_link = '<a href="' . bp_get_root_domain() . '/' . 'groups/' . $bp->groups->current_group->slug . '/">' . $bp->groups->current_group->name . '</a>';
					$post_title = $bp->groups->current_group->name;
					$post_author = $bp->groups->current_group->creator_id;
				}
				$action_content = sprintf( __( "%s likes %s %s", 'aheadzen' ), $userlink, $_REQUEST['type'], $topic_link );
			}elseif(($_REQUEST['type']=='topic' || $_REQUEST['type']=='topic-reply') && $topic_details)
			{
				$post_title = $topic_details->post_title;
				$post_author = $topic_details->post_author;
				if(VoterPluginClass::voter_is_old_version())
				{	
					$group = groups_get_group( array( 'group_id' => $topic->object_id ) );
					$topic_link = trailingslashit( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/' ).'/forum/topic/' . $topic->topic_slug . '/';
					$topic_post= bp_forums_get_post( $item_id );
				}else{
					if($_REQUEST['type']=='topic-reply'){
						$topic_link = bbp_get_topic_permalink( $item_id ).'#post-'.$secondary_item_id;
					}else{
						$topic_link = bbp_get_topic_permalink( $secondary_item_id );
					}
					$post_author = bbp_get_reply_author( $secondary_item_id );
				}	
				
				$topic_link = '<a href="' . $topic_link .'">' . $post_title . '</a>';
				if($_REQUEST['type']=='topic-reply'){$typestr = 'topic reply';}else{$typestr = 'topic';}
				$action_content = sprintf( __( "%s likes %s %s", 'aheadzen' ), $userlink, $typestr, $topic_link );
			}else{
				$post = get_post($secondary_item_id);
				$post_author = $post->post_author;
				$post_title = $post->post_title;
				$post_type = get_post_type($secondary_item_id);
				$topic_link = '<a href="' . get_permalink($post->ID) .'">' . $post->post_title . '</a>';
				$action_content = sprintf( __( "%s likes %s %s", 'aheadzen' ), $userlink, $_REQUEST['type'], $topic_link );
			}
			
			if(!get_option('aheadzen_voter_disable_activity')){
				/**Send Activity start**/
				$groupStatus = '';
				if($_REQUEST['type']=='groups'){
					$groupId = $secondary_item_id;
					$aGroup = groups_get_group( array( 'group_id' => $groupId ) );
					$groupStatus = $aGroup->status;
				}
				if(function_exists('bp_activity_add')){
					$arg_arr = array(
								'user_id'   => $post_author,
								'action'    => $action_content,
								'component' => 'votes',
								'item_id'           => $topic_id, 
								'secondary_item_id' => $_REQUEST['item_id'],
								'type'      => 'forums'
							);
					if($groupStatus!='public'){
						$arg_arr['hide_sitewide'] = 1;
					}
					$activity_id = bp_activity_add($arg_arr);
				}
				/**Send Activity end**/
				
				if($_REQUEST['type']=='activity')
				{
					//don't send notification for activity
					global $wpdb;
					$table_name =  $bp->activity->table_name;
					$post_author = $wpdb->get_var("select user_id from $table_name where id=\"$topic_id\"");
				}
			}
			if($post_author){
				if(!get_option('aheadzen_voter_disable_email')){
					/**Send Email start**/
					$arg = array(
						'secondary_item_id' => $_REQUEST['secondary_item_id'],
						'item_id' 		=> $_REQUEST['item_id'],
						'post_author' 	=> $post_author,
						'type' 			=> $_REQUEST['type'],
						'action' 		=> $_REQUEST['action'],
						'user_id' 		=> $user_id,
						'like_msg' 		=> $action_content,
						'title_msg' 	=> $post_title,
						);
					VoterBpNotifications::aheadzen_send_author_notification($arg);
					/**Send Email end**/
				}
				
				if(!get_option('aheadzen_voter_disable_notification') && function_exists('bp_notifications_add_notification')){
					/**Send Notification start**/					
					bp_notifications_add_notification($_REQUEST['secondary_item_id'], $post_author, 'votes', $action_str,$_REQUEST['item_id']);
					/**Send Notification end**/
				}
			}			
			return true;			
		}
		return false;
	}

	/*************************************************
	Register Buddpress voter component
	*************************************************/
	function aheadzen_voter_filter_notifications_get_registered_components( $component_names = array() )
	{
		 // Force $component_names to be an array
		 if ( ! is_array( $component_names ) ) {$component_names = array();}

		 // Add 'votes' component to registered components array
		 array_push( $component_names, 'votes' );

		 // Return component's with 'votes' appended
		 return $component_names;
	}


	/*************************************************
	Set voter buddypress componant Global
	*************************************************/
	function aheadzen_voter_setup_globals()
	{
		global $bp;
		//for ask oracle.com
		$bp->votes = new BP_Component;
		$bp->votes->notification_callback = 'VoterBpNotifications::aheadzen_voter_notification_title_format';
		$bp->active_components['votes'] = '1';

	}
	
	/*************************************************
	Get user's voting details
	*************************************************/
	function aheadzen_voter_notification_title_format( $component_action, $item_id, $secondary_item_id ) {
	   
	   global $bp,$wp_query;
	   $component_action_arr = explode('_',$component_action);
	   if($component_action_arr && is_numeric($component_action_arr[1])){ }else{$component_action_arr = explode('-+',$component_action);}
	   $component_action_type = $component_action_arr[0];
	   $poster_user_id = (int)$component_action_arr[1];
	   $notifications = $bp->notifications->query_loop->notifications;
		$notification = '';
		$user_id = $poster_user_id;
		$post_id = $secondary_item_id;
		
		$display_name = bp_core_get_user_displayname( $user_id );
		$url = bp_core_get_user_domain( $user_id );
		$voter_link = $display_name;
		$archarr = array('-','_');
		$rplarr = array(' ',' ');
		if($component_action_type=='topic' || $component_action_type=='topic-reply')
		{
			if(VoterPluginClass::voter_is_old_version())
			{			
				$topic_details = bp_forums_get_topic_details( $post_id );
			}else{
				$topic_details = bbp_get_topic( $post_id );
			}
		}
		
		if($component_action_type=='comment')
		{      
			$post = get_post($secondary_item_id);
			$topic_url = get_permalink($secondary_item_id);
			$title = get_the_title($secondary_item_id);
			$topic_link = '<a href="' . $topic_url . '">' . $title . '</a>';
			$component_action_type = str_replace($archarr,$rplarr,$component_action_arr[0]);
			$notification = sprintf(__('%s likes your %s on  %s','aheadzen'),$voter_link,$component_action_type,$topic_link);
		}elseif($component_action_type=='profile' || $component_action_type=='groups'  || $component_action_type=='activity')
		{
			$topic_link = '';
			if($component_action_type=='groups' && function_exists('groups_get_group'))
			{
				$post = groups_get_group( array( 'group_id' => $item_id ) );
				$group_name = $post->name;
				$group_slug = $post->slug;
				$topic_link = '<a href="' . bp_get_root_domain() . '/' . 'groups/' . $group_slug . '/">' . $group_name . '</a>';
			}
			$component_action_arr = explode('_',$component_action);
			if($component_action_arr && is_numeric($component_action_arr[1])){ }else{$component_action_arr = explode('-+',$component_action);}
			$component_action_type = str_replace($archarr,$rplarr,$component_action_arr[0]);
			if($component_action_type=='activity'){
				$notification = sprintf(__('%s likes your update.','aheadzen'),$voter_link,$component_action_type,$topic_link);
			}elseif($component_action_type=='groups'){
				$notification = sprintf(__('%s likes your group %s','aheadzen'),$voter_link,$topic_link);
			}else{
				$notification = sprintf(__('%s likes your %s','aheadzen'),$voter_link,$component_action_type);
			}
		}elseif(($component_action_type=='topic' || $component_action_type=='topic-reply') && $topic_details)
		{
			$post_title = $topic_details->post_title;
			$post_author = $topic_details->post_author;
			if(VoterPluginClass::voter_is_old_version() && function_exists('groups_get_group'))
			{ 
				$post_title = $topic_details->topic_title;
				$group = groups_get_group( array( 'group_id' => $topic_details->object_id ) );
				$topic_link = trailingslashit( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/' ).'/forum/topic/' . $topic_details->topic_slug . '/';
				$topic_post= bp_forums_get_post( $post_id );
				$post_author = $topic_post->poster_id;
			}elseif(function_exists('bbp_get_topic_permalink') && function_exists('bbp_get_reply_author')){
				$topic_link = bbp_get_topic_permalink( $post_id );
				$post_author = bbp_get_reply_author( $post_id );
			} 
			if($topic_link && $post_title){
				$topic_link = '<a href="' . $topic_link .'">' . $post_title . '</a>';
				$component_action_type = str_replace($archarr,$rplarr,$component_action_arr[0]);
				$notification = sprintf(__('%s likes your %s on  %s','aheadzen'),$voter_link,$component_action_type,$topic_link);
			}
			
		}else{
			$post = get_post($item_id);
			$topic_url = get_permalink($item_id);
			$title = get_the_title($item_id);
			$topic_link = '<a href="' . $topic_url . '">' . $title . '</a>';
			$component_action_arr = explode('_',$component_action);
			if($component_action_arr && is_numeric($component_action_arr[1])){ }else{$component_action_arr = explode('-+',$component_action);}
			$component_action_type = str_replace($archarr,$rplarr,$component_action_arr[0]);
			$notification = sprintf(__('%s likes your %s on  %s','aheadzen'),$voter_link,$component_action_type,$topic_link);
		}
		return $notification;

	}

	/*************************************************
	Email alert send while voting Up
	*************************************************/
	function aheadzen_send_author_notification($arg)
	{
		global $bp;
		$from_name =  get_option('blogname');
		$from_email = get_option('admin_email');
		
		$to = $arg['post_author'];
		$to_display_name = bp_core_get_user_displayname($to);
		$user_data = get_userdata($to);
		$to_email = $user_data->data->user_email;
		
		$secondary_item_id = $arg['secondary_item_id'];
		$item_id = $arg['item_id'];
		$type = $arg['type'];
		$user_id = $arg['user_id'];
		$like_msg = $arg['like_msg'];
		$title_msg = $arg['title_msg'];
		
		if(bp_get_user_meta($user_id,'notification_like_votes',true)=='yes') return true;
		
		$user_id_link = bp_core_get_userlink( $user_id );
		$user_display_name = bp_core_get_user_displayname($user_id);
		$component_action_type = $type;
		$voter_link = bp_core_get_userlink( $user_id );
		$subject = '';
		
		$notification = str_replace($arg['type'],'your '.$arg['type'],$like_msg);
		
		//list card new_avatar profile
		if($component_action_type=='activity'){
			$activityId = $arg['secondary_item_id'];			
			$activityData = bp_activity_get(array('in'=> $activityId));
			$activityData = $activityData['activities'][0];
			if($activityData->type=='activity_link'){
				$subject = sprintf(__('%s likes link you shared','aheadzen'),$user_display_name);
			}elseif($activityData->type=='activity_photo' || $activityData->type=='new_avatar'){
				$subject = sprintf(__('%s likes your profile photo','aheadzen'),$user_display_name);
			}elseif($activityData->type=='activityshare'){
				$subject = sprintf(__('%s likes your shared link','aheadzen'),$user_display_name);
			}else{
				$subject = sprintf(__('%s likes your updates','aheadzen'),$user_display_name);
			}
		}else{
			$subject = sprintf(__('%s likes your %s on  %s','aheadzen'),$user_display_name,$component_action_type,$title_msg);
		}
		$notification_link = $bp->bp_nav['notifications']['link'];
		$settingNotificationLink = $bp->bp_nav['settings']['link'].'notifications/';
		$settings_link = '<a href="'.$settingNotificationLink.'"> member settings</a>';
		$message =  $subject.'<br /><br />To view all of your pending notifications: <a href="'.$notification_link.'">Click the link</a> <br /><br />Click to view '.$voter_link.'\'s profile.';
		$message .= '<br /><br />'.sprintf( __( 'To disable these notifications please log in and go to: %s', 'aheadzen' ), $settings_link );
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type: text/html; charset=".get_bloginfo('charset')."" . "\r\n";
		$headers .= "From: $from_name <$from_email>" . "\r\n";
		$message = apply_filters('az_voter_mail_content',$message,$subject,$settingNotificationLink,$user_data->data);
		$subject = apply_filters('az_voter_mail_subject',$subject,$settings_link);
		//echo "to : $to_email<br />, SUBJECT: $subject<br />, Message: $message<br />,Header : $headers";exit;
		wp_mail($to_email, $subject, $message, $headers);
	}
	
	function askoracle_bp_core_get_notifications_for_user($renderable){
		$theReturnObj = array();
		if($renderable){
			foreach($renderable as $renderableObj){
				if($renderableObj->component_name=='votes')
				{
					$theurl = '';
					$component_action_arr = explode('_',$renderableObj->component_action);
					if($component_action_arr && count($component_action_arr)>1){ }else{$component_action_arr = explode('-+',$renderableObj->component_action);}
					$component_action_type = $component_action_arr[0];
					$poster_user_id = (int)$component_action_arr[1];
					
					$user_id = $poster_user_id;
					$post_id = $renderableObj->secondary_item_id;
					$item_id = $renderableObj->item_id;
					
					if($component_action_type=='topic' || $component_action_type=='topic-reply'){
						if(class_exists('VoterPluginClass') && VoterPluginClass::voter_is_old_version())
						{			
							$topic_details = bp_forums_get_topic_details( $post_id );
						}else{
							$topic_details = bbp_get_topic( $post_id );
						}
					}
					
					if($component_action_type=='comment'){
						$theurl = get_permalink($secondary_item_id);
					}elseif($component_action_type=='activity'){
						
					}elseif($component_action_type=='groups' && function_exists('groups_get_group')){
						$post = groups_get_group( array( 'group_id' => $item_id ) );
						$group_name = $post->name;
						$group_slug = $post->slug;
						$theurl = bp_get_root_domain() . '/' . 'groups/' . $group_slug . '/';				
					}elseif(($component_action_type=='topic' || $component_action_type=='topic-reply') && $topic_details)
					{
						if(class_exists('VoterPluginClass') && VoterPluginClass::voter_is_old_version() && function_exists('groups_get_group'))
						{ 
							$post_title = $topic_details->topic_title;
							$group = groups_get_group( array( 'group_id' => $topic_details->object_id ) );
							$theurl = trailingslashit( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/' ).'/forum/topic/' . $topic_details->topic_slug . '/';					
						}elseif(function_exists('bbp_get_topic_permalink') && function_exists('bbp_get_reply_author')){
							$theurl = bbp_get_topic_permalink( $post_id );
						} 
					}else{
						$theurl = get_permalink($item_id);
					}
					if($theurl)$renderableObj->href = $theurl;
				}		
				$theReturnObj[] = $renderableObj;
			}
		}
		return $theReturnObj;	
	}
}