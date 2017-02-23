<?php
/*************************************************
Install plugin related Database table
*************************************************/
function aheadzen_voter_install()
{
	global $wpdb, $table_prefix;
	
	/**Vote plugin default settings**/
	if(get_option('aheadzen_voter_display_login_frm') || get_option('aheadzen_voter_register_link') || get_option('aheadzen_voter_login_title') || get_option('aheadzen_voter_login_desc'))
	{
	
	}else{
		update_option('aheadzen_voter_for_page',1);
		update_option('aheadzen_voter_for_post',1);
		update_option('aheadzen_voter_for_product',1);
		update_option('aheadzen_voter_for_custom_posttype',1);
		update_option('aheadzen_voter_for_comments',1);
		update_option('aheadzen_voter_for_activity',1);
		update_option('aheadzen_voter_for_group',1);
		update_option('aheadzen_voter_for_profile',1);
		update_option('aheadzen_voter_for_messages',1);
		update_option('aheadzen_voter_for_forum',1);
		update_option('aheadzen_voter_display_options','likeunlike');
		update_option('aheadzen_voter_login_title','Please Login');
		update_option('aheadzen_voter_login_desc','<p>'.__('This site is free and open to everyone, but our registered users get extra privileges like commenting, and voting.','aheadzen').'</p>');
		update_option('aheadzen_voter_login_link',wp_login_url());
		update_option('aheadzen_voter_register_link',wp_registration_url());
		update_option('aheadzen_voter_display_login_frm',1);
	}
	/**Vote plugin Database table**/
	$sql = "CREATE TABLE IF NOT EXISTS `".$table_prefix."ask_votes` (
		  `id` bigint(20) NOT NULL AUTO_INCREMENT,
		  `user_id` int(10) NOT NULL,
		  `component` varchar(40) NOT NULL,
		  `type` varchar(40) NOT NULL,
		  `action` varchar(10) NOT NULL,
		  `item_id` int(10) NOT NULL,
		  `secondary_item_id` int(10) DEFAULT NULL,
		  `date_recorded` datetime NOT NULL,
		  PRIMARY KEY (`id`),
		  UNIQUE KEY `user_id_2` (`user_id`,`component`,`type`,`item_id`,`secondary_item_id`),
		  KEY `date_recorded` (`date_recorded`),
		  KEY `user_id` (`user_id`),
		  KEY `item_id` (`item_id`),
		  KEY `secondary_item_id` (`secondary_item_id`),
		  KEY `component` (`component`),
		  KEY `type` (`type`)
		)";
			
	$wpdb->query($sql);
}


/*************************************************
Uninstall plugin related Database table & Settings
*************************************************/
function aheadzen_voter_uninstall()
{
	global $wpdb, $table_prefix;
	$sql = "DROP TABLE IF EXISTS `".$table_prefix."ask_votes`;";
	$wpdb->query($sql);
}



add_action('bp_notification_settings','az_voter_notification_settings',99);
add_action('bp_core_notification_settings_after_save','az_voter_save_notificaiton_data');
		
function az_voter_notification_settings() {
	if ( !$notify = bp_get_user_meta( bp_displayed_user_id(), 'notification_like_votes', true ) )
		$notify = 'yes';
	
?>
<table class="notification-settings" id="activity-notification-settings">
	<thead>
		<tr>
			<th class="icon">&nbsp;</th>
			<th class="title"><?php _e( 'Like/Votes', 'aheadzen' ) ?></th>
			<th class="yes"><?php _e( 'Yes', 'aheadzen' ) ?></th>
			<th class="no"><?php _e( 'No', 'aheadzen' )?></th>
		</tr>
	</thead>

	<tbody>
		<tr id="notification_emails_all_settings">
			<td>&nbsp;</td>
			<td><?php _e( "A member likes/votes an update or comment you've posted", 'aheadzen' ) ?></td>
			<td class="yes"><input type="radio" name="notifications[notification_like_votes]" id="notification_like_votes_settings_yes" value="yes" <?php checked( $notify, 'yes', true ) ?>/></td>
			<td class="no"><input type="radio" name="notifications[notification_like_votes]" id="notification_like_votes_settings_no" value="no" <?php checked( $notify, 'no', true ) ?>/></td>
		</tr>

		<?php do_action( 'az_all_emails_screen_notification_settings' ) ?>
	</tbody>
</table>
<?php
}

function az_voter_save_notificaiton_data() {
	$user_id = bp_displayed_user_id();
	$settings = array();
	$key_val = $_POST['notifications']['notification_like_votes']=='yes' ? 'yes' : 'no';
	bp_update_user_meta($user_id,'notification_like_votes',$key_val);	
}