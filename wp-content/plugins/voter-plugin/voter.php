<?php
/*
Plugin Name: Voter Plugin
Plugin URI: http://aheadzen.com/
Description: The plugin added votes option for pages, post, custom post types, comments, buddypress activity, groups, member profiles, woocommerce products etc. <br />You can control display option from <a href="options-general.php?page=voter" target="_blank"><b>Plugin Settings >></b></a>
Author: Aheadzen Team  | <a href="options-general.php?page=voter" target="_blank">Manage Plugin Settings</a>
Author URI: http://aheadzen.com/
Text Domain: aheadzen
Domain Path: /language
Version: 3.0.18

Copyright: © 2014-2015 ASK-ORACLE.COM
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/**************************
Get translate your plugin to another language by google tutorial
http://barry.coffeesprout.com/translating-po-files-using-google-translate/
**************************/

global $aheadzen_voter_plugin_version;
$aheadzen_voter_plugin_version = '2.3.6';


include(dirname(__FILE__).'/voter_functions.php');

register_activation_hook(__FILE__, 'aheadzen_voter_install');
//register_deactivation_hook(__FILE__, 'aheadzen_voter_uninstall');

add_action('init', array( 'VoterPluginClass', 'aheadzen_voter_init' ));

add_action('admin_menu',array('VoterAdminClass','aheadzen_voter_admin_menu'));
add_action('admin_enqueue_scripts', array('VoterAdminClass','az_enqueue_color_picker') );
add_action('wp_enqueue_scripts', array('VoterPluginClass','aheadzen_voter_add_custom_scripts'));
add_action('wp_head',array('VoterPluginClass','aheadzen_header_settings'));

add_filter('template_include',array('VoterPluginClass','aheadzen_voter_add_vote'));
add_action('plugins_loaded','aheadzen_bp_is_active' );
function aheadzen_bp_is_active(){
 if(function_exists('bp_is_active')){
  $is_bp = bp_current_action();
  if(!empty($is_bp) || ($_REQUEST['component'] == 'buddypress' && (strpos($_SERVER['REQUEST_URI'],'groups') || strpos($_SERVER['REQUEST_URI'],'members'))))
  add_action('bp_init',array('VoterPluginClass','aheadzen_voter_add_vote'), 99999);
 }
}

add_action('bp_init',array( 'VoterBpNotifications','aheadzen_bp_delete_topic'), 99);

//add_filter('the_content', array('VoterPosts','aheadzen_content_voting_links'),9999); //posts & pages
add_filter('the_content', array('VoterPosts','aheadzen_post_content_voting_links'),9999); //posts & pages
add_filter('woocommerce_single_product_summary', array('VoterPosts','aheadzen_content_voting_links_product'),99); //products

add_filter('get_comment_text', array('VoterPostComments','aheadzen_comment_voting_links'),1,2); //comments
//add_action('bp_before_group_header', array('VoterBpGroups','aheadzen_bp_group_voting_links')); //groups
//add_action('bp_before_member_header', array('VoterBpMembers','aheadzen_bp_member_voting_links')); //member profile

add_action('bp_before_member_header', array('VoterBpMembers','az_display_author_total_votes'),999,2); //member profile
add_filter( 'get_the_author_description',  array('VoterBpMembers','az_display_author_total_votes'), 3, 10);

add_action('bbp_theme_after_reply_content', array('VoterBpTopics','aheadzen_bp_forum_topic_reply_voting_links')); //topic & topic reply
//add_action('bp_activity_entry_content', array('VoterBpActivitys','aheadzen_bp_activity_voting_links'));
add_action('bp_activity_comment_options', array('VoterBpActivitys','aheadzen_bp_activity_voting_links'));

add_action('wp_footer',array('VoterPluginClass','aheadzen_voting_login_dialog'),999);
add_action('wp_footer',array('VoterBpNotifications','aheadzen_update_user_notification'),999);

add_filter( 'bp_notifications_get_registered_components', array('VoterBpNotifications','aheadzen_voter_filter_notifications_get_registered_components'), 10 );
add_filter('bp_notifications_get_notifications_for_user',array('VoterBpNotifications','aheadzen_voter_notification_title_format'),'',3);
add_action( 'bp_setup_globals', array('VoterBpNotifications','aheadzen_voter_setup_globals'),999);
add_filter('bp_core_get_notifications_for_user',array('VoterBpNotifications','askoracle_bp_core_get_notifications_for_user'),99);

add_action('bp_group_header_actions', array('VoterBpGroups','aheadzen_bp_group_voting_links'),999); //groups
add_action('bp_member_header_actions', array('VoterBpMembers','aheadzen_bp_member_voting_links'),999); //member profile
add_action('bp_activity_entry_meta', array('VoterBpActivitys','aheadzen_bp_activity_voting_links'),1);


add_filter('az_bp_group_voting_links_before','az_bp_group_voting_links_before_fun');
function az_bp_group_voting_links_before_fun($str){
	$str.= '<div class="generic-button">';
	return $str;
}

add_filter('az_bp_activity_voting_link_before','az_bp_activity_voting_link_before_fun');
add_filter('az_bp_activity_voting_link_after','az_bp_group_voting_links_after_fun');
function az_bp_activity_voting_link_before_fun($str){
	$str.= '<div style="display: inline-block;">';
	return $str;
}


add_filter('az_bp_group_voting_links_after','az_bp_group_voting_links_after_fun');
function az_bp_group_voting_links_after_fun($str){
	$str.= '</div>';
	return $str;
}

add_filter('az_bp_member_voting_links_before','az_bp_member_voting_links_before_fun');
add_filter('az_bp_member_voting_links_after','az_bp_group_voting_links_after_fun');
function az_bp_member_voting_links_before_fun($str){
	$str.= '<div class="generic-button voter-button voterlink">';
	return $str;
}