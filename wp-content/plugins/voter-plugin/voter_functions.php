<?php
class VoterPluginClass {

	public function __construct(){

	}

	/*************************************************
	Voter plugin init function
	*************************************************/
	function aheadzen_voter_init()
	{
		load_plugin_textdomain('aheadzen', false, basename( dirname( __FILE__ ) ) . '/languages');
		if ( current_user_can( 'delete_posts' ) )
		{
			add_action('delete_post',array('VoterBpNotifications','aheadzen_delete_post_related_data'),10);
		}
	}

	/*************************************************
	Plugin JS & CSS include
	*************************************************/
	function aheadzen_voter_add_custom_scripts()
	{
		$pid = get_the_ID();
		if(VoterPluginClass::aheadzen_check_voter_page_disabled($pid))return;
		
		wp_register_style('az-voter-ui-css','https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.min.css');
		wp_enqueue_style('az-voter-ui-css');
		
		if(get_option('aheadzen_voter_include_dialog_js')){  }else{
			wp_enqueue_script( 'az-voter-ui-js', 'https://code.jquery.com/ui/1.10.4/jquery-ui.min.js');
			//wp_enqueue_script( 'az-voter-ui-js', 'http://code.jquery.com/ui/1.10.3/jquery-ui.js');
		}
		
		wp_enqueue_script('aheadzen-voter-script', plugins_url('js/voter.js', __FILE__), array('jquery'));
		wp_enqueue_style('', plugins_url('css/voter.css', __FILE__));
	}

	/*************************************************
	Header JS related settings
	*************************************************/
	function aheadzen_header_settings()
	{
		global $current_user;
	?>
		<script>
		var site_url = '<?php echo site_url();?>/';
		var current_user_id = '<?php echo $current_user->ID;?>';
		var current_post_id = '<?php echo get_the_id();?>';
		</script>
		<style>
		ul#wp-admin-bar-bp-notifications-default li{}
		ul#wp-admin-bar-bp-notifications-default li a{padding: 0!important; display: inline !important;height: auto !important;line-height: 0;min-width: 40px;}
		<?php $text_size = get_option('az_button_text_size');
		if(empty($text_size) || $text_size==0)$text_size=10;
		?>

		.aheadzen_vote a.aheadzen_voter_css,.aheadzen_vote span.vote-count-post{font-size:<?php echo $text_size;?>px;}

		</style>
	<?php
		$voting_options = get_option('aheadzen_voter_display_options');

		$voting_page = get_option('aheadzen_voter_display_options_page');
		$voting_post = get_option('aheadzen_voter_display_options_post');
		$voting_product = get_option('aheadzen_voter_display_options_product');
		$voting_posttype = get_option('aheadzen_voter_display_options_custompost');
		$voting_comments = get_option('aheadzen_voter_display_options_comment');
		$voting_activity = get_option('aheadzen_voter_display_options_activity');
		$voting_groups = get_option('aheadzen_voter_display_options_group');
		$voting_profile = get_option('aheadzen_voter_display_options_profile');
		$voting_forum = get_option('aheadzen_voter_display_options_forum');

		$votingsarr = array('thumbs','buttons');
		if(in_array($voting_options,$votingsarr) || in_array($voting_page,$votingsarr) || in_array($voting_post,$votingsarr) || in_array($voting_product,$votingsarr) || in_array($voting_posttype,$votingsarr)	|| in_array($voting_comments,$votingsarr) || in_array($voting_activity,$votingsarr) || in_array($voting_groups,$votingsarr) || in_array($voting_profile,$votingsarr) || in_array($voting_forum,$votingsarr))
		{
			echo '<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">';
		}
	}

	/*************************************************
	Check for user either voted or not,
	return value true  or false
	*************************************************/
	function aheadzen_is_voted($params,$return='action')
	{
		global $post, $bp, $thread_template, $bbP, $forum_id, $wpdb, $table_prefix,$current_user;
		$user_id = $current_user->ID;
		if($_REQUEST['user_id']){$user_id = $_REQUEST['user_id'];}
		if(!$user_id){return false;}

		$item_id = $params['item_id'];
		$component_name = $params['component'];
		$type = $params['type'];
		$secondary_item_id = $params['secondary_item_id'];
		$voteed_action = $wpdb->get_results("SELECT id,action FROM `".$table_prefix."ask_votes` WHERE user_id=\"$user_id\" AND item_id=\"$item_id\" AND component=\"$component_name\" AND type=\"$type\" AND secondary_item_id=\"$secondary_item_id\"");

		if($voteed_action)
		{
			if($return=='action')
			{
				return $voteed_action[0]->action;
			}else{
				return $voteed_action[0];
			}
		}else{
			return false;
		}

	}

	/*************************************************
	Check is the Buddypress Topic
	*************************************************/
	function aheadzen_is_bp_topic()
	{
		global $post, $bp, $thread_template, $bbP, $forum_id, $wpdb, $table_prefix,$current_user;
		if($bp)
		{
			$check_url_for_topic = $bp->unfiltered_uri;
			//echo $type = $check_url_for_topic[0];
			if (in_array("topic", $check_url_for_topic) || in_array("activity", $check_url_for_topic) || in_array("members", $check_url_for_topic) || in_array("groups", $check_url_for_topic))
			{
				return 1;
			}
		}
		return 0;
	}

	/*************************************************
	Check if the voter plugin disable or not
	*************************************************/
	function aheadzen_check_voter_page_disabled($pid='')
	{
		$exclude_pages = get_option('aheadzen_voter_exclude_pages');
		if($exclude_pages)
		{
			$current_template = get_post_meta($pid,'_wp_page_template',true);
			if(in_array($current_template,$exclude_pages))
			{
				return true;
			}
		}elseif(function_exists('is_woocommerce') && (is_cart() || is_checkout() || is_account_page()))
		{
			return true;
		}
		return false;
	}

	/*************************************************
	Check the Buddpress Version for Older version.
	*************************************************/
	function voter_is_old_version()
	{

		if(function_exists('bbp_get_reply_id' ) && function_exists('bbp_get_topic'))return false;

		if(function_exists('bp_get_the_topic_id')) return true;

	}

	/*************************************************
	Display up & down link function
	*************************************************/
	function aheadzen_get_voting_link($params)
	{
		if(is_admin()){return '';}
		if(VoterPluginClass::aheadzen_check_voter_page_disabled($params['secondary_item_id']))
		{
			return '';
		}
		global $current_user;
		$votestr = '';
		$the_result = $params['result'];
		unset($params['result']);

		$voting_options = get_option('aheadzen_voter_display_options');
		$buttonStyle = '';
		$post_type_arr = array('page','post','product','custompost','comment','activity','profile','groups','topic','topic-reply');
		if($params['type'] && in_array($params['type'],$post_type_arr)){
			if($params['type']=='topic-reply' || $params['type']=='topic'){
				$voting_options = get_option('aheadzen_voter_display_options_forum');
			}else{
				$voting_options = get_option('aheadzen_voter_display_options_'.$params['type']);
			}
			if($params['type']=='activity')$buttonStyle = ' button bp-primary-action ';
			
		}else{
			global $post;
			$type = $params['type'];
			if($post->post_type && $post->post_type==$type){
				$type = 'custompost';
				$voting_options = get_option('aheadzen_voter_display_options_'.$type);
			}
		}

		if($voting_options=='thumbs'){$votetype = ' thumbs_up_down ';}elseif($voting_options=='buttons'){$votetype = ' button_up_down ';}
		if(strlen($voting_options)<3){$voting_options='likeunlike';}
		$post_id = $params['item_id'];
		$linkurl = VoterPluginClass::aheadzen_get_current_page_url();
		$total_votes_arr = VoterPluginClass::aheadzen_get_total_votes($params);
		$total_votes = $total_votes_arr['total'];
		$is_voted = VoterPluginClass::aheadzen_is_voted($params);
		$class_up = 'vote-up-off';
		$class_down = 'vote-down-off';
		$title_up = $title_down = __('click to vote','aheadzen');

		$user_id = $current_user->ID;
		if($params['user_id']){$user_id = $params['user_id'];}

		if($user_id){
			$params['action']='up';
			$url_up = esc_url(wp_nonce_url(add_query_arg($params,$linkurl),'toggle-vote_' . $post_id));

			$params['action']='down';
			$url_down = esc_url(wp_nonce_url(add_query_arg($params,$linkurl),'toggle-vote_' . $post_id));
		}else{
			$class_up = 'vote-up-off aheadzen_voting_add';
			$class_down = 'vote-down-off aheadzen_voting_add';
			$title_up = $title_down = __('please login to vote','aheadzen');
			$url_up = $url_down = '#';
		}
		$class_up .= $votetype;
		$class_down .= $votetype;
		
		$class_up .= $buttonStyle;
		$class_down .= $buttonStyle;
		
		if($voting_options=='likeunlike'){
			$votestr.= '<div id="aheadzen_voting_'.$params['secondary_item_id'].'_'.$params['item_id'].'_'.$params['component'].'" class="aheadzen_vote like_unlike_vote">';
			if($is_voted!=''){
				if($is_voted=='up')
				{
					$votestr.= '<a id="voter_down" rel="nofollow" title="'.$title_up.'" class="aheadzen_voter_css ' . $class_up . '" href="' . $url_up . '">'.__('Unlike','aheadzen').' <span>('.$total_votes.')</span></a>';
				}else{
					$votestr.= '<a id="voter_up" rel="nofollow" title="'.$title_up.'" class="aheadzen_voter_css ' . $class_up . '" href="' . $url_up . '">'.__('Like','aheadzen').' <span>('.$total_votes.')</span></a>';
				}
			}else{
				$votestr.= '<a id="voter_up" rel="nofollow" title="'.$title_up.'" class="aheadzen_voter_css ' . $class_up . '" href="' . $url_up . '">'.__('Like','aheadzen').' <span>('.$total_votes.')</span></a>';
			}
			$votestr.= '<span class="vote-ajax-preloader" style="display:none;"></span>';
			$votestr.= '</div>';
		}else{
			if($is_voted!=''){
				if($is_voted=='up')
				{
					$class_up = 'vote-up-on';
					$class_down = 'vote-down-off';
					$title_up = __('already voted, click to remove','aheadzen');
					//$url_up = '#';
				}elseif($is_voted=='down')
				{
					$class_up = 'vote-up-off';
					$class_down = 'vote-down-on';
					$title_down = __('already voted','aheadzen');
					//$url_down = '#';
				}
				$class_up .= $votetype;
				$class_down .= $votetype;
			}

			if($voting_options=='helpful')
			{
				$total_up_votes = $total_votes_arr['total_up'];
				$total_count = $total_votes_arr['total_count'];
				$type = $params['type'];
				$srch = array('groups','-','_');
				$replace = array('group',' ',' ');
				$type = str_replace($srch,$replace,$type);

				$votestr.= '<div id="aheadzen_voting_'.$params['secondary_item_id'].'_'.$params['item_id'].'_'.$params['component'].'" class="aheadzen_vote helpful_vote">';
				$votestr.= '<span>'.sprintf(__('Is this %s helpful?','aheadzen'),$type).'<br /><small style="display: block;">'.sprintf(__('%s out of %s said Yes','aheadzen'),$total_up_votes,$total_count).'</small></span>';
				$votestr.= '<a id="voter_up" rel="nofollow" title="'.$title_up.'" class="aheadzen_voter_css ' . $class_up . '" href="' . $url_up . '">'.__('Yes','aheadzen').'</a>';
				$disable_down_voter = get_option('aheadzen_disable_down_voter');
				if($disable_down_voter==1){ }else{
					$votestr.= '<a id="voter_down" rel="nofollow" title="'.$title_down.'" class="aheadzen_voter_css ' . $class_down . '" href="' . $url_down . '">'.__('No','aheadzen').'</a>';
				}
				$votestr.= '<span class="vote-count-post">';
				//$votestr.= $total_votes;
				$votestr.= '</span>';
				$votestr.= '<span class="vote-ajax-preloader" style="display:none;"></span>';
				$votestr.= '</div>';
			}else{
				$inline_css_up = $inline_css_down = $vote_count_css ='';
				$votestr.= '<div id="aheadzen_voting_'.$params['secondary_item_id'].'_'.$params['item_id'].'_'.$params['component'].'" class="aheadzen_vote">';
				$thumbicon1 = $thumbicon2 = '';
				if($voting_options=='customtex'){
					$custom_up_text = stripslashes(get_option('az_custom_up_text'));
					$custom_down_text = stripslashes(get_option('az_custom_down_text'));
					$button_text_color = stripslashes(get_option('az_custom_button_text_color'));
					if(strstr($class_up,'vote-up-on') || strstr($class_up,'vote-up-off')){
						$thumbicon1 = $custom_up_text;
						$class_up .= ' custom_button_up';
						$up_color = get_option('az_custom_up_color');
						if($up_color)
						$inline_css_up = 'style="background-color:'.$up_color.';color:'.$button_text_color.';border:solid 1px '.$up_color.';"';
						$vote_count_css = 'color:'.$up_color.';border:solid 1px '.$up_color.';';
					}
					if(strstr($class_down,'vote-down-on') || strstr($class_down,'vote-down-off')){
						$thumbicon2 = $custom_down_text;
						$class_down .= ' custom_button_down';
						$down_color = get_option('az_custom_down_color');
						if($down_color)
						$inline_css_down = 'style="background-color:'.$down_color.';color:'.$button_text_color.';border:solid 1px '.$down_color.';"';
					}
					if(strstr($class_up,'vote-up-on'))$vote_count_css.='opacity:0.4;';

				}elseif($voting_options=='customhtml'){
					$custom_up_text = stripslashes(get_option('az_custom_up_html'));
					$custom_down_text = stripslashes(get_option('az_custom_down_html'));
					if(strstr($class_up,'vote-up-on') || strstr($class_up,'vote-up-off')){
						$thumbicon1 = $custom_up_text;
						$class_up .= ' custom_html_up';
					}
					if(strstr($class_down,'vote-down-on') || strstr($class_down,'vote-down-off')){
						$thumbicon2 = $custom_down_text;
						$class_down .= ' custom_html_down';
					}
				}elseif($voting_options=='customimage'){
					$custom_up_text = stripslashes(get_option('az_custom_up_image'));
					$custom_down_text = stripslashes(get_option('az_custom_down_image'));
					if(strstr($class_up,'vote-up-on') || strstr($class_up,'vote-up-off')){
						$thumbicon1 = '<img src="'.$custom_up_text.'" alt="">';
						$class_up .= ' custom_image_up';
					}
					if(strstr($class_down,'vote-down-on') || strstr($class_down,'vote-down-off')){
						$thumbicon2 = '<img src="'.$custom_down_text.'" alt="">';
						$class_down .= ' custom_image_down';
					}
				}elseif($voting_options=='thumbs'){
					if(strstr($class_up,'vote-up-on') || strstr($class_up,'vote-up-off')){
						$thumbicon1 = '<i class="fa fa-thumbs-up"></i>';
					}
					if(strstr($class_down,'vote-down-on') || strstr($class_down,'vote-down-off')){
						$thumbicon2 = '<i class="fa fa-thumbs-down"></i>';
					}

				}else if($voting_options=='buttons')
				{
					if(strstr($class_up,'vote-up-on') || strstr($class_up,'vote-up-off')){
						$thumbicon1 = '<i class="fa fa-caret-up"></i>';
					}
					if(strstr($class_down,'vote-down-on') || strstr($class_down,'vote-down-off')){
						$thumbicon2 = '<i class="fa fa-caret-down"></i>';
					}
				}
				if($thumbicon1)
				$votestr.= '<a ' . $inline_css_up . ' id="voter_up" rel="nofollow" title="'.$title_up.'" class="aheadzen_voter_css ' . $class_up . '" href="' . $url_up . '">'.$thumbicon1.'</a>';

				$votestr.= '<span style="'.$vote_count_css.'" class="vote-count-post '.$voting_options.'">';
				$votestr.= $total_votes;
				$votestr.= '</span>';

				$disable_down_voter = get_option('aheadzen_disable_down_voter');
				if($disable_down_voter==1){ }else{
					if($thumbicon2)
					$votestr.= '<a ' . $inline_css_down . ' id="voter_down" rel="nofollow" title="'.$title_down.'" class="aheadzen_voter_css ' . $class_down . '" href="' . $url_down . '">'.$thumbicon2.'</a>';
				}
				$votestr.= '<span class="vote-ajax-preloader" style="display:none;"></span>';
				$votestr.= '</div>';
			}

		}

		if($_REQUEST['rtype']=='json')
		{
			$return_arr = array();
			$return_arr['result'] = $the_result;
			$return_arr['total_votes'] = $total_votes;
			$return_arr['total_up'] = $total_votes_arr['total_up'];
			$return_arr['total_down'] = $total_votes_arr['total_down'];
			$return_arr['url_up'] = $url_up;
			$return_arr['url_down'] = $url_down;
			return $return_arr;
		}else{
			if(get_option('aheadzen_enable_voter')){
				global $wpdb,$table_prefix;
				$component = $params['component'];
				$type = $params['type'];
				$item_id = $params['item_id'];
				$secondary_item_id = $params['secondary_item_id'];
				$users = $wpdb->get_results("select user_id,date_recorded from `".$table_prefix."ask_votes` where component=\"$component\" and type=\"$type\" and item_id=\"$item_id\" and secondary_item_id=\"$secondary_item_id\" order by date_recorded desc limit 20");
				$user_return_arr = array();
				if($users){
					foreach($users as $usersobj)
					{
						$uid = $usersobj->user_id;
						$date_recorded = date('d M,Y',strtotime($usersobj->date_recorded));
						$name = '';
						$name = trim(get_usermeta($uid,'first_name',true).' '.get_usermeta($uid,'last_name',true));
						$name .= '<span>'.$date_recorded.'</span>';
						$user_return_arr[] = $name;
					}
				}
				if($user_return_arr){
					$votestr .= '<div id="view_voter_list_link" onclick="voter_view'.$secondary_item_id.'();">'.__('view voters','aheadzen').'</div>';
					$votestr .= '<ul class="voted_users_list" id="voted_users_list'.$secondary_item_id.'" style="display:none;">';
					$votestr .= '<li>'.implode('</li><li>',$user_return_arr).'</li>';
					$votestr .= '<li><div id="view_voter_list_link" onclick="voter_view'.$secondary_item_id.'();">X close</div></li>';
					$votestr .= '</ul>';
					$votestr .= '<script>function voter_view'.$secondary_item_id.'(){
						jQuery( "#voted_users_list'.$secondary_item_id.'" ).toggle( "slow", function() {
							// Animation complete.
						  });
						}</script>';
				}
			}
			return $votestr;
		}
	}


	/*************************************************
	Get Total votes of particulate item
	*************************************************/
	function aheadzen_get_total_votes($params)
	{
		global $current_user,$wpdb, $table_prefix, $post, $bp, $bbP;
		$total = $total_up = $total_down = 0;
		$component = $params['component'];
		$type = $params['type'];
		$item_id = $params['item_id'];
		$secondary_item_id = $params['secondary_item_id'];

		$total_up = $wpdb->get_var("select count(id) from `".$table_prefix."ask_votes` WHERE action='up' AND item_id=\"$item_id\" AND component=\"$component\" AND type=\"$type\" AND secondary_item_id=\"$secondary_item_id\"");
		$total_down = $wpdb->get_var("select count(id) from `".$table_prefix."ask_votes` WHERE action='down' AND item_id=\"$item_id\" AND component=\"$component\" AND type=\"$type\" AND secondary_item_id=\"$secondary_item_id\"");
		$total = $total_up-$total_down;
		$total_count = $total_up+$total_down;
		return array('total'=>$total,'total_up'=>$total_up,'total_down'=>$total_down,'total_count'=>$total_count);
	}

	/*************************************************
	Insert user vote to Databse
	*************************************************/
	function aheadzen_voter_add_vote($template)
	{
		global $wp_query,$current_user,$wpdb, $table_prefix, $post, $bp, $bbP;
		if($_GET['clear-all']=='notifications' && $current_user->ID)
		{
			aheadzen_delete_user_notifications($current_user->ID);
		}elseif($_GET['ntid'] && $current_user->ID)
		{
			$notification_id = $_GET['ntid'];
			aheadzen_read_user_notifications($notification_id);
		}

		$post_id = get_the_id();
		$user_id = $current_user->ID;
		if($_REQUEST['user_id']){$user_id = $_REQUEST['user_id'];}
		$result = '0';

		if($user_id>0 && isset($_REQUEST) && !empty($_REQUEST))
		{
			if(isset($_REQUEST['component']) && isset($_REQUEST['type']) && isset($_REQUEST['action']) &&  isset($_REQUEST['item_id']) && isset($_REQUEST['secondary_item_id']))
			{
				if($_REQUEST['action']=='up'){$result='1';}elseif($_REQUEST['action']=='down'){$result='-1';}
				$params = array(
					'component' => $_REQUEST['component'],
					'type' => $_REQUEST['type'],
					'item_id' => $_REQUEST['item_id'],
					'secondary_item_id' => $_REQUEST['secondary_item_id']
					);

				$is_voted = VoterPluginClass::aheadzen_is_voted($params,'all');
				if($is_voted){
					$voted_id = $is_voted->id;
					$action = $_REQUEST['action'];
					if(($action=='up' && $is_voted->action=='up') || ($action=='down' && $is_voted->action=='down')){
						$sql = "delete from `".$table_prefix."ask_votes` where id=\"$voted_id\"";
						$wpdb->query($sql);
						$result = '0';
					}else{
						$sql = "update `".$table_prefix."ask_votes` set action=\"$action\" where id=\"$voted_id\"";
						$wpdb->query($sql);
					}
					if($is_voted->action == 'up' && $_REQUEST['secondary_item_id'] && $_REQUEST['type']){
						global $wpdb,$table_prefix;
						$sql = "delete from ".$table_prefix."bp_activity where component='votes' and item_id='".$_REQUEST['secondary_item_id']."' and secondary_item_id='".$_REQUEST['item_id']."' and user_id='".$user_id."'";
						$bool = $wpdb->query($sql);
					}
				}else{
					$sql =  "INSERT INTO `".$table_prefix."ask_votes` (user_id, component, type, action, date_recorded, item_id, secondary_item_id) VALUES ('".$user_id."', '".$_REQUEST['component']."', '".urldecode($_REQUEST['type'])."', '".$_REQUEST['action']."', '".date("Y-m-d h:i:s")."', '".$_REQUEST['item_id']."', '".$_REQUEST['secondary_item_id']."')";
					$wpdb->query($sql);
					VoterBpNotifications::aheadzen_voter_add_vote_bbpress_notification();
				}

				if($_REQUEST['rtype']=='json')
				{
					header("Access-Control-Allow-Origin: *");
					$params['result'] = $result;
					$params['user_id'] = $_REQUEST['user_id'];
					$params['rtype'] = $_REQUEST['rtype'];
					$return_arr = VoterBpNotifications::aheadzen_get_voting_link($params);
					header('Content-Type: application/json; charset=UTF-8', true);
					echo json_encode($return_arr);
					exit;
				}elseif($_REQUEST['rtype']=='ajax'){
					echo VoterBpNotifications::aheadzen_get_voting_link($params);
					exit;
				}
				//exit;
			}
		}
		return $template;
	}


	/*************************************************
	Get user's voting details
	*************************************************/
	function aheadzen_get_user_all_vote_details($params)
	{
		global $current_user,$wpdb, $table_prefix, $post, $bp, $bbP;
		$return_arr = array();
		$user_id = $current_user->ID;
		$component = $params['component'];
		$type = $params['type'];
		$item_id = $params['item_id'];
		$secondary_item_id = $params['secondary_item_id'];
		$sql = "select id,item_id,secondary_item_id,component,type from `".$table_prefix."ask_votes` WHERE user_id=\"$user_id\"";
		$result = $wpdb->get_results($sql);
		if($result)
		{
			foreach($result as $resultobj)
			{
				$return_arr[$resultobj->component][$resultobj->type][$resultobj->secondary_item_id][$resultobj->item_id]=$resultobj->id;
			}
		}
		return $return_arr;
	}


	/*************************************************
	Get post's voting details
	*************************************************/
	function aheadzen_get_post_all_vote_details($arg)
	{
		global $current_user,$wpdb, $table_prefix;
		$post_error = $user_error = '';
		$return_arr = array();
		$comments_arr = array();
		$user_comments_arr = array();
		$post_vote_links = array();
		$item_id = $arg['item_id'];
		$type = $arg['type'];
		$user_id = $arg['user_id'];
		if($arg['user_error']=='wronguser')
		{
			$user_error=__('Wrong User','aheadzen');
		}

		if($arg['item_error']=='wrongpost')
		{
			$post_error=__('Unknown Post','aheadzen');
		}elseif($arg['item_error']=='invalidpost')
		{
			$post_error=__('Invalid Post ID','aheadzen');
		}

		if($item_id && $arg['item_error']=='')
		{
			$subsql = '';
			if($type)
			{
				$subsql = "and type=\"$type\"";
			}else{
				$subsql = "and type!='comment'";
			}

			$item_total_votes = $wpdb->get_var("select count(id) from `".$table_prefix."ask_votes` WHERE secondary_item_id=\"$item_id\" $subsql limit 1");
			$item_total_up_votes = $wpdb->get_var("select count(id) from `".$table_prefix."ask_votes` WHERE  action='up' and secondary_item_id=\"$item_id\" $subsql limit 1");
			$item_total_down_votes = $wpdb->get_var("select count(id) from `".$table_prefix."ask_votes` WHERE  action='down' and secondary_item_id=\"$item_id\" $subsql limit 1");
			$linkurl = get_permalink($item_id);
			$params = array();
			$params['component'] = 'blog';
			$params['type']= get_post_type($item_id);
			$params['item_id'] = 0;
			$params['secondary_item_id'] = $item_id;
			$params['action']='up';
			$params['rtype']='json';
			$params['user_id']=$user_id;
			$url_up = wp_nonce_url(add_query_arg($params,$linkurl),'toggle-vote_' . $item_id);
			$post_vote_links['up'] = $url_up;
			$params['action']='down';
			$url_down = wp_nonce_url(add_query_arg($params,$linkurl),'toggle-vote_' . $item_id);
			$post_vote_links['down'] = $url_down;
			if($user_id && $user_error=='')
			{
				$user_total_votes = $wpdb->get_var("select action from `".$table_prefix."ask_votes` WHERE secondary_item_id=\"$item_id\" and user_id=\"$user_id\" $subsql limit 1");
				if($user_total_votes=='up'){$user_vote='1';}elseif($user_total_votes=='down'){$user_vote='-1';}

				$user_comment_results = $wpdb->get_results("select action,secondary_item_id from `".$table_prefix."ask_votes` WHERE user_id=\"$user_id\" and item_id=\"$item_id\" and type='comment' group by secondary_item_id");
				if($user_comment_results)
				{
					foreach($user_comment_results as $usercommobj)
					{
						$user_vote=0;
						if($usercommobj->action=='up'){$user_vote='1';}elseif($usercommobj->action=='down'){$user_vote='-1';}
						$user_comments_arr[$usercommobj->secondary_item_id]=$user_vote;
					}
				}
			}

			$total_comments = $wpdb->get_var("select count(distinct(secondary_item_id)) from `".$table_prefix."ask_votes` WHERE item_id=\"$item_id\" and type='comment'");
			$comment_results = $wpdb->get_results("select count(id) as total_count,secondary_item_id from `".$table_prefix."ask_votes` WHERE item_id=\"$item_id\" and type='comment' group by secondary_item_id");
			if($comment_results)
			{
				foreach($comment_results as $resultsobj)
				{
					$comment_vote_links = array();
					$params = array();
					$params['component'] = 'blog';
					$params['type']= 'comment';
					$params['item_id'] = $item_id;
					$params['secondary_item_id'] = $resultsobj->secondary_item_id;
					$params['action']='up';
					$params['rtype']='json';
					$params['user_id']=$user_id;
					$url_up = wp_nonce_url(add_query_arg($params,$linkurl),'toggle-vote_' . $item_id);
					$comment_vote_links['up'] = $url_up;
					$params['action']='down';
					$url_down = wp_nonce_url(add_query_arg($params,$linkurl),'toggle-vote_' . $item_id);
					$comment_vote_links['down'] = $url_down;
					$comments_arr[] = array(
						'comment_id' => $resultsobj->secondary_item_id,
						'total_count' => $resultsobj->total_count,
						'current_user_vote' => $user_comments_arr[$resultsobj->secondary_item_id],
						'comment_voter_links' => $comment_vote_links,
						);

				}
			}

		}
		$return_arr['post_id']=$item_id;
		$return_arr['post_error']=$post_error;
		$return_arr['total_votes']=$item_total_votes;
		$return_arr['total_up']=$item_total_up_votes;
		$return_arr['total_down']=$item_total_down_votes;
		$return_arr['post_voter_links']=$post_vote_links;
		$return_arr['current_user_id']=$user_id;
		$return_arr['user_error']=$user_error;
		$return_arr['user_vote']=$user_vote;
		$return_arr['total_voted_comments']=$total_comments;
		$return_arr['comments']=$comments_arr;

		$return_str = json_encode($return_arr);
		return $return_str;
	}

	/*************************************************
	Login form for -- Not login user
	*************************************************/
	function aheadzen_voting_login_dialog()
	{
		global $bp;
		$pid = get_the_ID();
		if(VoterPluginClass::aheadzen_check_voter_page_disabled($pid))
		{
			return $content;
		}

	$redirect_to = VoterPluginClass::aheadzen_get_current_page_url();

	$login_title = get_option('aheadzen_voter_login_title');
	$login_desc = get_option('aheadzen_voter_login_desc');
	$login_link = get_option('aheadzen_voter_login_link');
	$register_link = get_option('aheadzen_voter_register_link');
	$login_frm = get_option('aheadzen_voter_display_login_frm');
	if($login_title==''){$login_title=__('Please Login','aheadzen');}
	if($login_link=='')
	{
		 $login_link = esc_url( site_url( 'wp-login.php', 'login_post' ) );
	}
	?>
	<div id="aheadzen_voting_login" style="background-color:#fff;display:none;" title="<?php echo $login_title;?>">
	<?php echo $login_desc;?>
	<?php if($login_frm){?>
	<form name="loginform" id="loginform" action="<?php echo $login_link; ?>" method="post">
		<p>
			<label for="user_login"><?php _e('Username','aheadzen') ?><br />
			<input type="text" name="log" id="user_login" class="input" value="<?php echo esc_attr($user_login); ?>" size="20" /></label>
		</p>
		<p>
			<label for="user_pass"><?php _e('Password','aheadzen') ?><br />
			<input type="password" name="pwd" id="user_pass" class="input" value="" size="20" /></label>
		</p>

		<p class="forgetmenot"><label for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever" <?php checked( $rememberme ); ?> /> <?php _e('Remember Me','aheadzen'); ?></label></p>
		<p class="submit">
			<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php _e('Log In','aheadzen'); ?>" />
			<?php if($register_link){?>
			<a href="<?php echo $register_link;?>?redirect_to=<?php echo urlencode($redirect_to); ?>" class="aheadzen_button_red"><?php _e('Register','aheadzen'); ?></a>
			<?php }?>

		<input type="hidden" name="redirect_to" value="<?php echo esc_attr($redirect_to); ?>" />
			<?php /*?><input type="hidden" name="testcookie" value="1" /><?php */?>
		</p>

		<?php do_action('login_form');?>
	</form>
	<?php }else{?>
	<?php if($register_link){?>
	<a href="<?php echo $register_link;?>" class="aheadzen_button_blue"><?php _e('Login','aheadzen'); ?></a>
	<?php }?>
	<?php if($register_link){?>
	<a href="<?php echo $register_link;?>?redirect_to=<?php echo urlencode($redirect_to); ?>" class="aheadzen_button_red"><?php _e('Register','aheadzen'); ?></a>
	<?php }?>
	<?php }?>
	</div>
	<?php //if(get_option('aheadzen_voter_include_dialog_js')){  }else{?>
	<?php /*?><script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.min.js" integrity="sha256-oTyWrNiP6Qftu4vs2g0RPCKr3g1a6QTlITNgoebxRc4=" crossorigin="anonymous"></script>
	<?php */?>
	<?php //} ?>
	<?php /*?><link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" /><?php */?>
	<?php
	
	}
	
	
	/*******************************
	Top voted list main function
	****************************/
	function aheadzen_top_voted_list_all($arg)
	{
		$type = $arg['type'];
		$num = $arg['num'];
		$period = $arg['period'];

		if($type=='top_voted_profile')
		{
			$ids_arr = array();
			$data = $this->az_get_author_total_votes($arg);
			if($data){
				foreach($data as $dataObj){
					$ids_arr[] = $dataObj->user_id;
				}
				return $this->get_members_data_html($arg,$ids_arr);
			}

		}elseif($type=='profile' || $type=='groups'){
			$component = 'buddypress';
			$arg['component'] = $component;
			//$arg = array('component'=>$component,'type'=>$type,'num'=>$num,'period'=>$period);
			if($type=='profile'){
				return $this->aheadzen_top_voted_list_members($arg);
			}elseif($type=='groups'){
				return $this->aheadzen_top_voted_list_groups($arg);
			}
		}else
		{

			if($type=='product'){
				$component = 'woocommerce';
			}elseif($type=='topic' || $type=='topic-reply'){
				$component = 'forum';
			}elseif($type=='page' || $type=='post'){
				$component = 'blog';
			}else{
				$component = 'custompost';
			}
			$arg['component'] = $component;
			$arg['type'] = $type;
			$arg['num'] = $num;
			$arg['period'] = $period;
			$voterplugin = new VoterPluginClass();
			return $voterplugin->aheadzen_top_voted_list_default($arg);
		}
	}
	
	function az_display_author_total_votes($args=array()){
		global $current_user;
		$total_votes = 0;
		if(function_exists('bp_displayed_user_id')){
			$user_id = bp_displayed_user_id();
		}else{
			$user_id = get_query_var('author');
		}
		if(!$user_id)return true;
		
		$res = VoterPluginClass::az_get_author_total_votes(array(),$user_id);
		if($res){
			$total_votes = $res[0]->total_votes;
			echo VoterPluginClass::az_author_total_votes_html($total_votes,'author_total_votes_count');
		}
		
	}
	
	function az_author_total_votes_html($total_votes,$css=''){
		return '<div class="author_votes '.$css.'">'.sprintf(__('Author Votes: %s','aheadzen'),$total_votes).'</div>';;
	}
	
	function az_get_author_total_votes($args=array(),$user_id=0)
	{
		global $wpdb,$table_prefix;
		$subsql = VoterPluginClass::az_get_period_vote_subsql($args['period']);
		$limit = (int)$args['num'] ? (int)$args['num'] : 5;
		if($args['post_types']){
			if(is_array($args['post_types'])){
				$post_types = "'".implode("','",$the_post_types)."'";
			}else{
				$post_types = $args['post_types'];
			}
		}else{
			$the_post_types = get_post_types('','names');
			$post_types = "'".implode("','",$the_post_types)."'";
		}
		if(!empty($user_id))
			$subsql2 = "and p.post_author=\"$user_id\"";
		//$sql = "select count(id) as total_votes, user_id from `".$table_prefix."ask_votes` WHERE action='up' AND type in ($post_types) $subsql group by user_id order by total_votes desc limit $limit";
		
		$role = $args['role'];
		if($role){
			$subsql2 .= ' and p.post_author in (select user_id from '.$wpdb->usermeta.' where meta_key like "%capabilities%" and meta_value like "%'.$role.'%") ';
			//$subsql2 .= ' and p.post_author in (select user_id from '.$wpdb->usermeta.' where meta_key="wp_capabilities" and meta_value like \'%"'.$role.'";%\') ';
		}
		$cats = $args['cats'];
		if($cats){
		   $catssubsql = "and secondary_item_id in (select tr.object_id from $wpdb->term_relationships tr join $wpdb->term_taxonomy tt on tt.term_taxonomy_id=tr.term_taxonomy_id where tt.term_id in ($cats))";
		}
		$sql = "SELECT p.post_author as user_id, SUM(v.count) as total_votes FROM $wpdb->posts p JOIN (select secondary_item_id as pid,SUM(IF(action='up', 1, -1)) as count from `".$table_prefix."ask_votes` where component='blog' AND type in ($post_types) $catssubsql $subsql group by secondary_item_id having count > 0) v ON v.pid = p.ID where 1 $subsql2 GROUP BY  user_id order by total_votes desc limit $limit";		
		$res = $wpdb->get_results($sql);
		return $res;
	}

	function az_get_period_vote_subsql($period){
		$subsql = '';
		$period = intval($period);
		if($period>0){
			$mtime = mktime (0,0,0,date('m'),date('d')-$period,date('Y'));
			$start_date = date('Y-m-d',$mtime);
			$end_date = date('Y-m-d');
			$subsql = " and (DATE_FORMAT(date_recorded,'%Y-%m-%d') >= \"$start_date\" AND DATE_FORMAT(date_recorded,'%Y-%m-%d') <= \"$end_date\") ";
		}
		return $subsql;
	}
	/*******************************
	Top voted list default listing function
	****************************/
	function aheadzen_top_voted_list_default($arg)
	{
		global $members_template,$table_prefix, $wpdb;
		$return = '';
		$component = $arg['component'];
		$type = $arg['type'];
		$num = intval($arg['num'])*3;
		$period = intval($arg['period']);
		$cats = $arg['cats'];
		$subsql = '';
		$subsql = $this->az_get_period_vote_subsql($period);
		if($component){
			$voteTypes = array('post','page','pages','topic','topic-reply');
			$componentsql = " and component=\"$component\" ";
			if($component == 'custompost'){
				$componentsql .= " and type=\"$type\"";
			}elseif(in_array($type,$voteTypes)){
				$componentsql .= " and type=\"$type\"";
			}
		}
		if($cats){
			$catssubsql = "and secondary_item_id in (select tr.object_id from $wpdb->term_relationships tr join $wpdb->term_taxonomy tt on tt.term_taxonomy_id=tr.term_taxonomy_id where tt.term_id in ($cats))";
		}
		if(!$arg['display_type']){ $arg['display_type']='topvotedposts'; }
		$isPosts = 0;
		$isUsers = 0;
		if($arg['display_type']=='topvotedposts'){
			$sql = "select secondary_item_id from `".$table_prefix."ask_votes` where 1 $componentsql $catssubsql $subsql group by secondary_item_id order by count(action) desc limit $num";
			$res =  $wpdb->get_col($sql);
			$isPosts = 1;
		}else if($arg['display_type']=='recentposts'){
			$sql = "select secondary_item_id from `".$table_prefix."ask_votes` where 1 $componentsql $catssubsql $subsql order by date_recorded desc limit $num";
			$res =  $wpdb->get_results($sql);
			$isPosts = 1;
		}else if($arg['display_type']=='recentvoters'){
			$sql = "select distinct(user_id) from `".$table_prefix."ask_votes` where 1 $componentsql $catssubsql $subsql order by date_recorded desc limit $num";
			$res =  $wpdb->get_results($sql);
			$isUsers = 1;
		}else if($arg['display_type']=='topvoters'){
			$sql = "select user_id, count(user_id) as usercounts from `".$table_prefix."ask_votes` where 1 $componentsql $catssubsql $subsql group by user_id order by usercounts desc limit $num";
			$res =  $wpdb->get_results($sql);
			$isUsers = 1;
		}
		
		$the_type = $type;
		if($type=='topic-reply')$the_type = 'reply';
		$args = array(
		'numberposts'     => $arg['num'],
		'exclude'         => get_the_ID(),
		'post_type'       => $the_type,
		'post_status'     => 'publish',
		'post__in' 			=> $res,
		'orderby'        => 'post__in');
		$posts = get_posts($args);
		
		if($posts && $isPosts){
			$return .= '<ul class="voter_top_list list_'.$type.' '.$arg['display'].'">';
			foreach($posts  as $resobj){
				$title = $resobj->post_title;
				$link = get_permalink($resobj->ID);
				$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($resobj->ID));
				$return .= $this->az_voter_display_html($title,$link,$thumbnail[0],$arg['display']);
			}			
			/*$return .= '<ul class="voter_top_list list_'.$type.' '.$arg['display'].'">';
			$counter=0;
			foreach($res as $resobj)
			{
				$title = get_the_title($resobj->secondary_item_id);
				$link = get_permalink($resobj->secondary_item_id);

				$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($resobj->secondary_item_id));
				$return .= $this->az_voter_display_html($title,$link,$thumbnail[0],$arg['display']);
				$counter++;
				if($arg['num']<$counter){break;}
			}*/
			$return .= '</ul>';
		}elseif($res && $isUsers){
			$return .= '<ul class="voter_top_list list_'.$type.' '.$arg['display'].'">';
			$counter=0;
			foreach($res as $resobj)
			{
				$user = new BP_Core_User($resobj->user_id);
				if($user->avatar){
					$avatar_thumb = $user->avatar_thumb;
					preg_match_all('/(src)=("[^"]*")/i',$avatar_thumb, $avatar_thumb_result);
					$avatar_thumb_src = str_replace('"','',$avatar_thumb_result[2][0]);
					if($avatar_thumb_src && !strstr($avatar_thumb_src,'http:')){ $avatar_thumb_src = 'http:'.$avatar_thumb_src;}
					if($avatar_thumb){$return .=  '<li><a href="'.$user->user_url.'"><img src="'.$avatar_thumb_src.'" alt="" /><div>'.$user->fullname.'</div></a></li>';}
				}
				$counter++;
				if($arg['num']<$counter){break;}
			}
			$return .= '</ul>';
		}

		return $return;
	}

	/*******************************
	Top voted members list listing function
	****************************/
	function aheadzen_top_voted_list_members($arg)
	{
		global $members_template,$table_prefix, $wpdb;
		$return = '';
		$component = $arg['component'];
		$type = $arg['type'];
		$num = $arg['num'];

		$ids_arr = array();
		$final_arr = array();
		$final_members_arr = array();
		$subsql = $this->az_get_period_vote_subsql($arg['period']);
		$sql = "select secondary_item_id,count(action) as count from `".$table_prefix."ask_votes` where component=\"$component\" and type=\"$type\" $subsql and secondary_item_id!=1 group by secondary_item_id order by count desc limit $num";
		$res =  $wpdb->get_results($sql);
		if($res){
			foreach($res as $resobj){
				$ids_arr[] = $resobj->secondary_item_id;
				$final_arr[$resobj->secondary_item_id] = $resobj->count;
			}
			if($ids_arr){
				$return = $this->get_members_data_html($arg,$ids_arr);
			}
		}
		return $return;
	}

	function get_members_data_html($arg,$ids_arr){
		global $members_template,$table_prefix, $wpdb;
		$return = '';
		$component = $arg['component'];
		$type = $arg['type'];
		$num = $arg['num'];
		$final_arr = array();
		$ids_str = implode(',',$ids_arr);
		$content = '';
		$userDataArr = array();
		if(function_exists('bp_has_members')){ //Check buddypress Members 
			$members_args = array(
				'include'         => $ids_str,
				'per_page'        => $num,
				'max'             => $num
			);
			if(bp_has_members($members_args)) :
				while ( bp_members() ) : bp_the_member();
				$userDataArr[bp_get_member_user_id()] =  $this->az_voter_display_html(bp_get_member_name(),bp_get_member_permalink(),bp_get_member_avatar(),$arg['display']);
				endwhile;
			endif;
		}else{ //check wordpress members
			for($i=0;$i<count($ids_arr);$i++){
				$author = get_userdata($ids_arr[$i]);
				$permalink = get_author_posts_url( $author->ID, $author->user_nicename );
				if($author->first_name && $author->last_name ) {
					$name = "$author->first_name $author->last_name";
				} else {
					$name = $author->display_name;
				}
				$avarar_data = get_avatar_data($author->ID,array('size'=>50));
				$userDataArr[$author->ID] =  $this->az_voter_display_html($name,$permalink,$avarar_data['url'],$arg['display']);				
			}
			
		}
		if($userDataArr){
			for($u=0;$u<count($ids_arr);$u++){
				$final_arr[]=$userDataArr[$ids_arr[$u]];
			}
		}
		if($final_arr){
			$return .= '<ul class="voter_top_list item-list list_'.$type.'" >';
			$return .= implode(' ',$final_arr);
			$return .= '</ul>';
		}
		return $return;
	}

	function az_voter_display_html($title,$permalink,$imageurl='',$type='title'){
		if(!$title)return;
		if(!strstr($imageurl,'<img')){$imageurl = '<img class="avatar user-10-avatar avatar-50 photo" src="'.$imageurl.'" alt="">';}
		if($type=='titleimage'){
			$content = '<li class="display_'.$type.'"><a href="'.$permalink.'"><span class="voter-thumb news-thumb">'.$imageurl.'</span> '.$title.'</a></li>';
		}elseif($type=='image'){
			$content = '<li class="display_'.$type.'"><a href="'.$permalink.'">'.$imageurl.'</a></li>';
		}else{
			$content = '<li class="display_'.$type.'"><a href="'.$permalink.'">'.$title.'</a></li>';
		}
		return $content;
	}
	/*******************************
	Top voted groups list listing function
	****************************/
	function aheadzen_top_voted_list_groups($arg)
	{
		global $members_template,$table_prefix, $wpdb,$bp_prefix;
		$return = '';
		$component = $arg['component'];
		$type = $arg['type'];
		$num = $arg['num'];

		$ids_arr = array();
		$final_arr = array();
		$subsql = $this->az_get_period_vote_subsql($arg['period']);
		$sql = "select g.*,count(v.action) as count from `".$table_prefix."ask_votes` v join ".$table_prefix."bp_groups g on g.id=v.secondary_item_id where g.status='public' and v.component=\"$component\" and v.type=\"$type\" $subsql group by v.secondary_item_id order by count desc limit $num";
		$res =  $wpdb->get_results($sql);
		if($res)
		{
			$return .= '<ul class="voter_top_list list_'.$type.'">';
			foreach($res as $resobj)
			{
				$link = '';
				if(function_exists('bp_get_group_permalink'))
					$link = bp_get_group_permalink( $resobj );
				
				$return .=  '<li><a href="'.$link.'">'.$resobj->name.'</a></li>';
			}
			$return .= '</ul>';

		}
		return $return;
	}

	/*******************************
	Get Current Page URL Function
	****************************/
	function aheadzen_get_current_page_url()
	{
		if (is_ssl()) {$urlStarter='https://';}else{$urlStarter='http://';}		
		$redirect_to = $urlStarter.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		return $redirect_to;
	}
	/**CLASS END**/
}



class VoterPosts extends VoterPluginClass {

	function aheadzen_post_content_voting_links($content='')
	{
		global $post, $wp_current_filter;
		if ( empty( $post ) )
                return $content;

        if ( ( is_preview() || is_admin() ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
                return $content;
        }

	    if( function_exists('is_woocommerce')){
                if( is_cart() || is_checkout() || is_ajax() || is_view_order_page() || is_account_page() )
                        return $content;
        }

		// Don't output flair on excerpts
        if ( in_array( 'get_the_excerpt', (array) $wp_current_filter ) ) {
                return $content;
        }

        // Don't allow flair to be added to the_content more than once (prevent infinite loops)
        $done = false;
        foreach ( $wp_current_filter as $filter ) {
                if ( 'the_content' == $filter ) {
                        if ( $done )
                                return $content;
                        else
                                $done = true;
                }
        }


        if ( is_front_page() )
                return $content;

        if ( is_attachment() && in_array( 'the_excerpt', (array) $wp_current_filter ) ) {
                // Many themes run the_excerpt() conditionally on an attachment page, then run the_content().
                // We only want to output the sharing buttons once.  Let's stick with the_content().
                return $content;
        }

        $show = false;
        if ( !is_feed() ) {
                if ( is_singular() )
                        $show = true;
        }

        // Private post?
        $post_status = get_post_status( $post->ID );

        if ( 'private' === $post_status ) {
                $show = false;
        }

        // Allow to be used on P2 ajax requests for latest posts.
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['action'] ) && 'get_latest_posts' == $_REQUEST['action'] )
                $show = true;

        $new_content = '';
        // Code for display here
		$new_content = VoterPosts::aheadzen_content_voting_links($content);
		return $new_content;
	}
	/*************************************************
	Voting link for post,pages,products, etc...
	*************************************************/
	function aheadzen_content_voting_links($content='')
	{
		global $post,$wpdb;

		if(!$post->ID){return $content;}

		$post_type = $post->post_type;

		$component_name = '';
		if($post_type=='page' && get_option('aheadzen_voter_for_page')){
			$component_name = "blog";
		}elseif($post_type=='post' && get_option('aheadzen_voter_for_post')){
			$component_name = "blog";
		}elseif($post_type && get_option('aheadzen_voter_for_custom_posttype') && !in_array($post_type,array('page','post','product'))){
			$component_name = "custompost";
		}

		if($component_name!=''){
			$params = array(
				'component' => $component_name,
				'type' => $post_type,
				'item_id' => 0,
				'secondary_item_id' => $post->ID
				);
			$voting_links = VoterBpNotifications::aheadzen_get_voting_link($params);
		}
		return $content.$voting_links;
	}

	function aheadzen_content_voting_links_product()
	{
		global $post,$wpdb;

		$post_type = $post->post_type;

		$component_name = '';
		if($post_type=='page' && get_option('aheadzen_voter_for_page')){
			$component_name = "blog";
		}elseif($post_type=='post' && get_option('aheadzen_voter_for_post')){
			$component_name = "blog";
		}elseif($post_type=='product' && get_option('aheadzen_voter_for_product')){
			$component_name = "woocommerce";
		}elseif($post_type && get_option('aheadzen_voter_for_custom_posttype') && !in_array($post_type,array('page','post','product'))){
			$component_name = "custompost";
		}

		if($component_name!=''){
			$params = array(
				'component' => $component_name,
				'type' => $post_type,
				'item_id' => 0,
				'secondary_item_id' => $post->ID
				);
			$voting_links = VoterBpNotifications::aheadzen_get_voting_link($params);
		}
		echo $voting_links;
	}
}



class VoterPostComments extends VoterPluginClass {

	/*************************************************
	Voting link for Comments
	*************************************************/
	function aheadzen_comment_voting_links($comment_text,$comment)
	{
		if(get_option('aheadzen_voter_for_comments'))
		{
			$comment_id = $comment->comment_ID;
			$comment_post_id = $comment->comment_post_ID;
			$user_id = $comment->user_id;
			$comment_author = $comment->comment_author;
			$comment_author_url = $comment->comment_author_url;
			$post_type = get_post_type($comment_post_id);
			if($post_type == "product"){
				$component_name = "woocommerce";
			}elseif($post_type == "page" || $post_type == "post"){
				$component_name = "blog";
			}else{
				$component_name = "custompost";
			}
			$params = array(
				'component' => $component_name,
				'type' => 'comment',
				'item_id' => $comment_post_id,
				'secondary_item_id' => $comment_id
				);
			$voting_links = VoterBpNotifications::aheadzen_get_voting_link($params);
			$comment_text = $comment_text.$voting_links;
		}
		return $comment_text;
	}
}


class VoterBpGroups extends VoterPluginClass {
	/*************************************************
	Voting link for Buddypress Goup
	*************************************************/
	function aheadzen_bp_group_voting_links()
	{
		global $groups_template,$bp;
		if ( empty( $group ) && get_option('aheadzen_voter_for_group'))
		{
			$group =& $groups_template->group;
			$params = array(
				'component' => 'buddypress',
				'type' => $bp->current_component,
				'item_id' => 0,
				'secondary_item_id' => $group->id
				);
			
			echo apply_filters('az_bp_group_voting_links_before','');
			echo $votestr = VoterBpNotifications::aheadzen_get_voting_link($params);
			echo apply_filters('az_bp_group_voting_links_after','');
		}
	}
}

class VoterBpMembers extends VoterPluginClass {

	/*************************************************
	Voting link for Buddypress Member profile
	*************************************************/
	function aheadzen_bp_member_voting_links()
	{
		$user_id = bp_displayed_user_id();
		if ($user_id && get_option('aheadzen_voter_for_profile'))
		{
			$userdata = bp_core_get_core_userdata( $user_id );
			global $bp;
			$params = array(
				'component' => 'buddypress',
				'type' => 'profile',
				'item_id' => 0,
				'secondary_item_id' => $user_id
				);
				
			echo apply_filters('az_bp_member_voting_links_before','');
			echo $votestr = VoterBpNotifications::aheadzen_get_voting_link($params);
			echo apply_filters('az_bp_member_voting_links_after','');
		}
	}
}



class VoterBpActivitys extends VoterPluginClass {

	/*************************************************
	Voting link for Buddypress Activity
	*************************************************/
	function aheadzen_bp_activity_voting_links()
	{
		global $activities_template;
		if($activities_template->activity && get_option('aheadzen_voter_for_activity'))
		{
			$activity = bp_activity_current_comment();
			if($activity->id){
				$activity_id = $activity->id;
				$user_id = $activity->user_id;
				$item_id = $activity->secondary_item_id;
			}else{
				$activity_id = $activities_template->activity->id;
				$user_id = $activities_template->activity->user_id;
				$item_id = 0;
			}

			$params = array(
					'component' => 'buddypress',
					'type' => 'activity',
					'item_id' => $item_id,
					'secondary_item_id' => $activity_id
					);
			echo apply_filters('az_bp_activity_voting_link_before','');
			echo $voting_links = VoterBpNotifications::aheadzen_get_voting_link($params);
			echo apply_filters('az_bp_activity_voting_link_after','');
		}
	}

}


class VoterBpTopics extends VoterPluginClass {
	/*************************************************
	Voting link for Forum Topic & Reply
	*************************************************/
	function aheadzen_bp_forum_topic_reply_voting_links()
	{
		if(!get_option('aheadzen_voter_for_forum'))return false;

			if(VoterPluginClass::voter_is_old_version())
			{
				$reply_id = bp_get_the_topic_post_id();
				if($reply_id)
				{
					global $topic_template;
					$reply_id = bp_get_the_topic_post_id();
					$reply_content= $topic_template->post;
					$reply_content->post_parent=$reply_content->topic_id;

				}elseif(bp_get_the_topic_id() && function_exists('bp_forums_get_post')){
					$reply_id = bp_get_the_topic_id();
					$reply_content= bp_forums_get_post( $reply_id );
				}
			}elseif(function_exists('bbp_get_reply'))
			{
				$reply_id = bbp_get_reply_id();
				$reply_content = bbp_get_reply($reply_id);
			}
			if($reply_content)
			{
				$params = array(
					'component' => 'forum',
					'type' => 'topic-reply',
					'item_id' => $reply_content->post_parent,
					'secondary_item_id' => $reply_id
					);
				echo apply_filters('az_bp_forum_reply_voting_links_before','');
				echo $votestr = VoterBpNotifications::aheadzen_get_voting_link($params);
				echo apply_filters('az_bp_forum_reply_voting_links_after','');
			}else{
				if(function_exists('bbp_get_topic'))
				{
					$topic_details = bbp_get_topic($reply_id);
					$params = array(
						'component' => 'forum',
						'type' => 'topic',
						'item_id' => $topic_details->post_parent,
						'secondary_item_id' => $topic_details->ID
						);
					echo apply_filters('az_bp_forum_topic_voting_links_before','');
					echo $votestr = VoterBpNotifications::aheadzen_get_voting_link($params);
					echo apply_filters('az_bp_forum_topic_voting_links_after','');
				}
			}

	}
}

include_once('voter_db_settings.php');
include_once('admin_settings.php');
include_once('notification.php');
include_once('voter_api.php');
include_once('voter_widgets_shorcodes.php');

new VoterPluginClass();
