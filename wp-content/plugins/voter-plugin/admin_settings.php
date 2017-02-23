<?php
class VoterAdminClass {
	
	/*************************************************
	Admin Settings For voter plugin menu function
	*************************************************/
	function aheadzen_voter_admin_menu()
	{
		add_submenu_page('options-general.php', 'VOTER Options', 'VOTER', 'manage_options', 'voter',array('VoterAdminClass','aheadzen_voter_settings_page'));
	}
	
	function az_enqueue_color_picker( $hook_suffix ) {
		// jQuery
		wp_enqueue_script('jquery');
		// This will enqueue the Media Uploader script
		wp_enqueue_media();

		// first check that $hook_suffix is appropriate for your admin page
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'my-script-handle', plugins_url('js/custom-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
	}
	/*************************************************
	Admin Settings For voter plugin
	*************************************************/
	function aheadzen_voter_settings_page()
	{
		global $bp,$post;	
		if($_POST)
		{
			update_option('aheadzen_voter_for_page',$_POST['aheadzen_voter_for_page']);
			update_option('aheadzen_voter_for_post',$_POST['aheadzen_voter_for_post']);
			update_option('aheadzen_voter_for_product',$_POST['aheadzen_voter_for_product']);
			update_option('aheadzen_voter_for_custom_posttype',$_POST['aheadzen_voter_for_custom_posttype']);
			update_option('aheadzen_disable_down_voter',$_POST['aheadzen_disable_down_voter']);
			update_option('aheadzen_enable_voter',$_POST['aheadzen_enable_voter']);
			update_option('aheadzen_voter_for_comments',$_POST['aheadzen_voter_for_comments']);
			update_option('aheadzen_voter_for_activity',$_POST['aheadzen_voter_for_activity']);
			update_option('aheadzen_voter_for_group',$_POST['aheadzen_voter_for_group']);
			update_option('aheadzen_voter_for_profile',$_POST['aheadzen_voter_for_profile']);
			update_option('aheadzen_voter_for_messages',$_POST['aheadzen_voter_for_messages']);
			update_option('aheadzen_voter_for_forum',$_POST['aheadzen_voter_for_forum']);
			update_option('aheadzen_voter_display_options',$_POST['aheadzen_voter_display_options']);
			update_option('aheadzen_voter_login_title',$_POST['aheadzen_voter_login_title']);
			update_option('aheadzen_voter_login_desc',$_POST['aheadzen_voter_login_desc']);
			update_option('aheadzen_voter_login_link',$_POST['aheadzen_voter_login_link']);
			update_option('aheadzen_voter_register_link',$_POST['aheadzen_voter_register_link']);
			update_option('aheadzen_voter_display_login_frm',$_POST['aheadzen_voter_display_login_frm']);
			update_option('aheadzen_voter_include_dialog_js',$_POST['aheadzen_voter_include_dialog_js']);
			update_option('aheadzen_voter_exclude_pages',$_POST['exclude_pages']);
			update_option('aheadzen_voter_disable_activity',$_POST['aheadzen_voter_disable_activity']);
			update_option('aheadzen_voter_disable_notification',$_POST['aheadzen_voter_disable_notification']);
			update_option('aheadzen_voter_disable_email',$_POST['aheadzen_voter_disable_email']);
			
			update_option('aheadzen_voter_display_options_page',$_POST['aheadzen_voter_display_options_page']);
			update_option('aheadzen_voter_display_options_post',$_POST['aheadzen_voter_display_options_post']);
			update_option('aheadzen_voter_display_options_product',$_POST['aheadzen_voter_display_options_product']);
			update_option('aheadzen_voter_display_options_custompost',$_POST['aheadzen_voter_display_options_custompost']);
			update_option('aheadzen_voter_display_options_comment',$_POST['aheadzen_voter_display_options_comment']);
			update_option('aheadzen_voter_display_options_activity',$_POST['aheadzen_voter_display_options_activity']);
			update_option('aheadzen_voter_display_options_groups',$_POST['aheadzen_voter_display_options_groups']);
			update_option('aheadzen_voter_display_options_profile',$_POST['aheadzen_voter_display_options_profile']);
			update_option('aheadzen_voter_display_options_forum',$_POST['aheadzen_voter_display_options_forum']);
			
			update_option('az_custom_up_text',$_POST['az_custom_up_text']);
			update_option('az_custom_down_text',$_POST['az_custom_down_text']);
			update_option('az_custom_down_color',$_POST['az_custom_down_color']);
			update_option('az_custom_up_color',$_POST['az_custom_up_color']);
			update_option('az_custom_button_text_color',$_POST['az_custom_button_text_color']);
			
			update_option('az_custom_up_html',$_POST['az_custom_up_html']);
			update_option('az_custom_down_html',$_POST['az_custom_down_html']);
			update_option('az_custom_up_image',$_POST['az_custom_up_image']);
			update_option('az_custom_down_image',$_POST['az_custom_down_image']);
			if($_POST['az_button_text_size']<=0){$_POST['az_button_text_size']=10;}
			update_option('az_button_text_size',(int)$_POST['az_button_text_size']);
			
			echo '<script>window.location.href="'.admin_url().'options-general.php?page=voter&msg=success";</script>';
			exit;
		}
		
		$exclude_pages = get_option('aheadzen_voter_exclude_pages');
		$templates = get_page_templates( get_post() );
		ksort( $templates );
		?>
		<h2><?php _e('Voter Settings','aheadzen');?></h2>
		<?php
		if($_GET['msg']=='success'){
		echo '<p class="success">'.__('Your settings updated successfully.','aheadzen').'</p>';
		}
		?>
		<style>.success{padding:10px; border:solid 1px green; width:70%; color:green;font-weight:bold;}</style>
		<form method="post" action="<?php echo admin_url();?>options-general.php?page=voter">
			<table class="form-table">
				<tr valign="top">
					<td>
					<label for="aheadzen_voter_display_options">
					<p><?php _e('Voting Display Options','aheadzen');?> ::
					<select class="az_voter_display_options" name="aheadzen_voter_display_options" id="aheadzen_voter_display_options">
					<?php 
					$display_options = get_option('aheadzen_voter_display_options');
					echo voting_display_dl($display_options);
					?>
					</select>
					</p>
					</label>
					</td>
				</tr>
				
				<!-- CUSTOM IMAGE -->
				<?php $up_image = stripslashes(get_option('az_custom_up_image'));?>
				<tr id="az_custom_up_image_tr" valign="top">
					<td>
					<label for="az_custom_up_image">
					<?php _e('Up Button Image URL','aheadzen');?>  <br />
					<input type="text" id="az_custom_up_image" name="az_custom_up_image" value="<?php echo $up_image;?>" />
					<input type="button" name="custom-upload-btn-up" id="custom-upload-btn-up" setvalto="az_custom_up_image" class="button-secondary custom-upload-btn" value="Upload Image">
					<br /><?php if($up_image){echo '<img style="max-width:40px;height:auto;" src="'.$up_image.'" alt="" />';}?>
					<small><?php _e('keep blank to hide.','aheadzen');?> </small>
					</label>
					</td>
				</tr>
				<?php $down_image = stripslashes(get_option('az_custom_down_image'));?>
				<tr id="az_custom_down_image_tr" valign="top">
					<td>
					<label for="az_custom_down_image">
					<?php _e('Down Button Image URL','aheadzen');?> <br />
					<input type="text" id="az_custom_down_image" name="az_custom_down_image" value="<?php echo $down_image;?>" />
					<input type="button" name="custom-upload-btn-up" id="custom-upload-btn-up" setvalto="az_custom_down_image" class="button-secondary custom-upload-btn" value="Upload Image">
					<br /><?php if($down_image){echo '<img style="max-width:40px;height:auto;" src="'.$down_image.'" alt="" />';}?>
					<small><?php _e('keep blank to hide.','aheadzen');?> </small>
					</label>
					</td>
				</tr>
				
				<!-- CUSTOM HTML -->
				<tr id="az_custom_up_html_tr" valign="top">
					<td>
					<label for="az_custom_up_html">
					<?php _e('Up Button HTML','aheadzen');?> <br />
					<textarea id="az_custom_up_html" name="az_custom_up_html"><?php echo stripslashes(get_option('az_custom_up_html'));?></textarea>
					<small><?php _e('keep blank to hide.','aheadzen');?> </small>
					</label>
					</td>
				</tr>
				
				<tr id="az_custom_down_html_tr" valign="top">
					<td>
					<label for="az_custom_down_html">
					<?php _e('Down Button HTML','aheadzen');?> <br />
					<textarea id="az_custom_down_html" name="az_custom_down_html"><?php echo stripslashes(get_option('az_custom_down_html'));?></textarea>
					<small><?php _e('keep blank to hide.','aheadzen');?> </small>
					</label>
					</td>
				</tr>
				
				<!-- CUSTOM TEXT -->
				<tr id="az_custom_up_text_tr" valign="top">
					<td>
					<label for="az_custom_up_text">
					<?php _e('Up Button Text','aheadzen');?> 
					<input type="text" value="<?php echo get_option('az_custom_up_text');?>" id="az_custom_up_text" name="az_custom_up_text" />
					<small><?php _e('keep blank to hide.','aheadzen');?> </small>
					</label>
					</td>
				</tr>
				<tr id="az_custom_up_color_tr" valign="top">
					<td>
					<label for="az_custom_up_color">
					<?php _e('Up Button Color','aheadzen');?> 
					<input type="text" class="az_custom_up_color" value="<?php echo get_option('az_custom_up_color');?>" id="az_custom_up_color" name="az_custom_up_color" />
					</label>
					</td>
				</tr>
				
				<tr id="az_custom_down_text_tr" valign="top">
					<td>
					<label for="az_custom_down_text">
					<?php _e('Down Button Text','aheadzen');?> 
					<input type="text" value="<?php echo get_option('az_custom_down_text');?>" id="az_custom_down_text" name="az_custom_down_text" />
					<small><?php _e('keep blank to hide.','aheadzen');?> </small>
					</label>
					</td>
				</tr>
				<tr id="az_custom_down_color_tr" valign="top">
					<td>
					<label for="az_custom_down_color">
					<?php _e('Down Button Color','aheadzen');?> 
					<input type="text" class="az_custom_down_color" value="<?php echo get_option('az_custom_down_color');?>" id="az_custom_down_color" name="az_custom_down_color" />
					</label>
					</td>
				</tr>
				
				<tr id="az_custom_button_text_color_tr" valign="top">
					<td>
					<label for="az_custom_button_text_color">
					<?php _e('Button Text Color','aheadzen');?> 
					<input type="text" class="az_custom_button_text_color" value="<?php echo get_option('az_custom_button_text_color');?>" id="az_custom_button_text_color" name="az_custom_button_text_color" />
					</label>
					</td>
				</tr>
				
				<tr valign="top">
					<td>
					<label for="az_button_text_size">
					<?php _e('Button Font Size','aheadzen (in pixels)');?> 
					<input style="width:50px;" type="text" class="az_button_text_size" value="<?php echo get_option('az_button_text_size');?>" id="az_button_text_size" name="az_button_text_size" /> px
					</label>
					</td>
				</tr>
				
				<tr valign="top">
					<td>
					<label for="aheadzen_disable_down_voter">
					<input type="checkbox" value="1" id="aheadzen_disable_down_voter" name="aheadzen_disable_down_voter" <?php if(get_option('aheadzen_disable_down_voter')){echo "checked=checked";}?>/>&nbsp;&nbsp;&nbsp;<?php _e('Hide down votes completely?','aheadzen');?>
					</label>
					</td>
				</tr>				
				<tr valign="top">
					<td>
					<label for="aheadzen_enable_voter">
					<input type="checkbox" value="1" id="aheadzen_enable_voter" name="aheadzen_enable_voter" <?php if(get_option('aheadzen_enable_voter')){echo "checked=checked";}?>/>&nbsp;&nbsp;&nbsp;<?php _e('Display Voter List with voter link?','aheadzen');?>
					</label>
					</td>
				</tr>
				<tr valign="top">
					<td>
					<h3><?php _e('Enable On :','aheadzen');?></h3>
					</td>
				</tr>
				<tr valign="top">
					<td>
					<label for="aheadzen_voter_for_page">
					<input type="checkbox" value="1" id="aheadzen_voter_for_page" name="aheadzen_voter_for_page" <?php if(get_option('aheadzen_voter_for_page')){echo "checked=checked";}?>/>&nbsp;&nbsp;&nbsp;<?php _e('Pages','aheadzen');?>
					</label>
					<label for="aheadzen_voter_display_options_page">
					<p><?php _e('Voting Display Options','aheadzen');?> ::
					<select name="aheadzen_voter_display_options_page" id="aheadzen_voter_display_options_page">
					<?php 
					$display_options = get_option('aheadzen_voter_display_options_page');
					echo voting_display_dl($display_options);
					?>
					</select>
					</p>
					</label>
					
					</td>
				</tr>
				<tr valign="top">
					<td>
					<label for="aheadzen_voter_for_post">
					<input type="checkbox" value="1" id="aheadzen_voter_for_post" name="aheadzen_voter_for_post" <?php if(get_option('aheadzen_voter_for_post')){echo "checked=checked";}?>/>&nbsp;&nbsp;&nbsp;<?php _e('Blog Posts','aheadzen');?>
					</label>
					
					<label for="aheadzen_voter_display_options_post">
					<p><?php _e('Voting Display Options','aheadzen');?> ::
					<select name="aheadzen_voter_display_options_post" id="aheadzen_voter_display_options_post">
					<?php 
					$display_options = get_option('aheadzen_voter_display_options_post');
					echo voting_display_dl($display_options);
					?>
					</select>
					</p>
					</label>
					</td>						
				</tr>
				<?php global $woocommerce;
				if($woocommerce){?>
				<tr valign="top">
					<td>
					<label for="aheadzen_voter_for_product">
					<input type="checkbox" value="1" id="aheadzen_voter_for_product" name="aheadzen_voter_for_product" <?php if(get_option('aheadzen_voter_for_product')){echo "checked=checked";}?>/>&nbsp;&nbsp;&nbsp;<?php _e('WooCommerce Products','aheadzen');?>
					</label>
					
					<label for="aheadzen_voter_display_options_product">
					<p><?php _e('Voting Display Options','aheadzen');?> ::
					<select name="aheadzen_voter_display_options_product" id="aheadzen_voter_display_options_product">
					<?php 
					$display_options = get_option('aheadzen_voter_display_options_product');
					echo voting_display_dl($display_options);
					?>
					</select>
					</p>
					</label>
					
					</td>
				</tr>
				<?php }?>
				<tr valign="top">
					<td>
					<label for="aheadzen_voter_for_custom_posttype">
					<input type="checkbox" value="1" id="aheadzen_voter_for_custom_posttype" name="aheadzen_voter_for_custom_posttype" <?php if(get_option('aheadzen_voter_for_custom_posttype')){echo "checked=checked";}?>/>&nbsp;&nbsp;&nbsp;<?php _e('All Custom Post Type','aheadzen');?>
					</label>
					
					<label for="aheadzen_voter_display_options_custompost">
					<p><?php _e('Voting Display Options','aheadzen');?> ::
					<select name="aheadzen_voter_display_options_custompost" id="aheadzen_voter_display_options_custompost">
					<?php 
					$display_options = get_option('aheadzen_voter_display_options_custompost');
					echo voting_display_dl($display_options);
					?>
					</select>
					</p>
					</label>
					
					</td>
				</tr>
				<tr valign="top">
					<td>
					<label for="aheadzen_voter_for_comments">
					<input type="checkbox" value="1" id="aheadzen_voter_for_comments" name="aheadzen_voter_for_comments" <?php if(get_option('aheadzen_voter_for_comments')){echo "checked=checked";}?>/>&nbsp;&nbsp;&nbsp;<?php _e('Comments','aheadzen');?>
					</label>
					
					<label for="aheadzen_voter_display_options_comment">
					<p><?php _e('Voting Display Options','aheadzen');?> ::
					<select name="aheadzen_voter_display_options_comment" id="aheadzen_voter_display_options_comment">
					<?php 
					$display_options = get_option('aheadzen_voter_display_options_comment');
					echo voting_display_dl($display_options);
					?>
					</select>
					</p>
					</label>
					
					</td>
				</tr>
				<?php if (class_exists('BuddyPress')){
				global $bp;
				if(function_exists('bp_is_active') && bp_is_active('activity')){
				?>
				<tr valign="top">
					<td>
					<label for="aheadzen_voter_for_activity">
					<input type="checkbox" value="1" id="aheadzen_voter_for_activity" name="aheadzen_voter_for_activity" <?php if(get_option('aheadzen_voter_for_activity')){echo "checked=checked";}?>/>&nbsp;&nbsp;&nbsp;<?php _e('Buddypress Activity','aheadzen');?>
					</label>
					
					<label for="aheadzen_voter_display_options_activity">
					<p><?php _e('Voting Display Options','aheadzen');?> ::
					<select name="aheadzen_voter_display_options_activity" id="aheadzen_voter_display_options_activity">
					<?php 
					$display_options = get_option('aheadzen_voter_display_options_activity');
					echo voting_display_dl($display_options);
					?>
					</select>
					</p>
					</label>
					
					</td>
				</tr>
				<?php }?>
				<?php if(function_exists('bp_is_active') && bp_is_active('groups')){?>
				<tr valign="top">
					<td>
					<label for="aheadzen_voter_for_group">
					<input type="checkbox" value="1" id="aheadzen_voter_for_group" name="aheadzen_voter_for_group" <?php if(get_option('aheadzen_voter_for_group')){echo "checked=checked";}?>/>&nbsp;&nbsp;&nbsp;<?php _e('Buddypress Groups','aheadzen');?>
					</label>
					
					<label for="aheadzen_voter_display_options_groups">
					<p><?php _e('Voting Display Options','aheadzen');?> ::
					<select name="aheadzen_voter_display_options_groups" id="aheadzen_voter_display_options_groups">
					<?php 
					$display_options = get_option('aheadzen_voter_display_options_groups');
					echo voting_display_dl($display_options);
					?>
					</select>
					</p>
					</label>
					
					</td>
				</tr>
				<?php }?>
				<?php if(function_exists('bp_is_active') && bp_is_active('xprofile')){?>
				<tr valign="top">
					<td>
					<label for="aheadzen_voter_for_profile">
					<input type="checkbox" value="1" id="aheadzen_voter_for_profile" name="aheadzen_voter_for_profile" <?php if(get_option('aheadzen_voter_for_profile')){echo "checked=checked";}?>/>&nbsp;&nbsp;&nbsp;<?php _e('Buddypress Members Profile','aheadzen');?>
					</label>
					
					<label for="aheadzen_voter_display_options_profile">
					<p><?php _e('Voting Display Options','aheadzen');?> ::
					<select name="aheadzen_voter_display_options_profile" id="aheadzen_voter_display_options_profile">
					<?php 
					$display_options = get_option('aheadzen_voter_display_options_profile');
					echo voting_display_dl($display_options);
					?>
					</select>
					</p>
					</label>
					
					</td>
				</tr>
				<?php }?>
				<?php }?>
				<?php 
				if(function_exists('bbPress')){?>
				<tr valign="top">
					<td>
					<label for="aheadzen_voter_for_forum">
					<input type="checkbox" value="1" id="aheadzen_voter_for_forum" name="aheadzen_voter_for_forum" <?php if(get_option('aheadzen_voter_for_forum')){echo "checked=checked";}?>/>&nbsp;&nbsp;&nbsp;<?php _e('bbPress Posts','aheadzen');?>
					</label>
					
					<label for="aheadzen_voter_display_options_forum">
					<p><?php _e('Voting Display Options','aheadzen');?> ::
					<select name="aheadzen_voter_display_options_forum" id="aheadzen_voter_display_options_forum">
					<?php 
					$display_options = get_option('aheadzen_voter_display_options_forum');
					echo voting_display_dl($display_options);
					?>
					</select>
					</p>
					</label>
					
					</td>
				</tr>
				<?php }?>
				<tr valign="top">
					<td>
					<h3><?php _e('Notification Settings','aheadzen');?></h3>
					</td>
				</tr>
				<?php if (class_exists('BuddyPress')){?>
				<?php if(function_exists('bp_is_active') && bp_is_active('activity')){?>
				<tr valign="top">
					<td>
					<label for="aheadzen_voter_disable_activity">
					<input type="checkbox" value="1" id="aheadzen_voter_disable_activity" name="aheadzen_voter_disable_activity" <?php if(get_option('aheadzen_voter_disable_activity')){echo "checked=checked";}?>/>&nbsp;&nbsp;&nbsp;<?php _e('Disable alert by Activity?','aheadzen');?>
					</label>
					</td>
				</tr>
				<?php }?>
				<tr valign="top">
					<td>
					<label for="aheadzen_voter_disable_notification">
					<input type="checkbox" value="1" id="aheadzen_voter_disable_notification" name="aheadzen_voter_disable_notification" <?php if(get_option('aheadzen_voter_disable_notification')){echo "checked=checked";}?>/>&nbsp;&nbsp;&nbsp;<?php _e('Disable alert by Notification?','aheadzen');?>
					</label>
					</td>
				</tr>
				<?php }?>
				<tr valign="top">
					<td>
					<label for="aheadzen_voter_disable_email">
					<input type="checkbox" value="1" id="aheadzen_voter_disable_email" name="aheadzen_voter_disable_email" <?php if(get_option('aheadzen_voter_disable_email')){echo "checked=checked";}?>/>&nbsp;&nbsp;&nbsp;<?php _e('Disable alert by Email?','aheadzen');?>
					</label>
					</td>
				</tr>
				
				<tr valign="top">
					<td>
					<h3><?php _e('Login Popup Settings','aheadzen');?></h3>
					</td>
				</tr>
				<tr valign="top">
					<td>
					<?php
					$login_title = get_option('aheadzen_voter_login_title');
					?>
					<label for="aheadzen_voter_login_title">
					<p><?php _e('Popup Title','aheadzen');?> ::<br />
					<input type="text" id="aheadzen_voter_login_title" name="aheadzen_voter_login_title" value="<?php echo $login_title;?>" />
					</p>
					</label>
					</td>
				</tr>
				<tr valign="top">
					<td>
					<?php 
					$login_desc = get_option('aheadzen_voter_login_desc');
					?>
					<label for="aheadzen_voter_login_desc">
					<p><?php _e('Popup Content','aheadzen');?> ::<br />
					<textarea style="width:80%;" id="aheadzen_voter_login_desc" name="aheadzen_voter_login_desc"><?php echo $login_desc;?></textarea>
					</p>
					</label>
					</td>
				</tr>
				<tr valign="top">
					<td>
					<?php
					$login_link = get_option('aheadzen_voter_login_link');
					?>
					<label for="aheadzen_voter_login_link">
					<p><?php _e('Login URL','aheadzen');?> ::<br />
					<input type="text" id="aheadzen_voter_login_link" name="aheadzen_voter_login_link" value="<?php echo $login_link;?>" />
					<br /><small><?php _e('default:','aheadzen'); echo ' '.wp_login_url();?></small>
					</p>
					</label>
					</td>
				</tr>
				<tr valign="top">
					<td>
					<?php
					$register_link = get_option('aheadzen_voter_register_link');
					?>
					<label for="aheadzen_voter_register_link">
					<p><?php _e('Registration URL','aheadzen');?> ::<br />
					<input type="text" id="aheadzen_voter_register_link" name="aheadzen_voter_register_link" value="<?php echo $register_link;?>" />
					<br /><small><?php _e('default:','aheadzen'); echo ' '.wp_registration_url();?></small>
					</p>
					</label>
					</td>
				</tr>
				<tr valign="top">
					<td>
					<label for="aheadzen_voter_display_login_frm">
					<input type="checkbox" value="1" id="aheadzen_voter_display_login_frm" name="aheadzen_voter_display_login_frm" <?php if(get_option('aheadzen_voter_display_login_frm')){echo "checked=checked";}?>/>&nbsp;&nbsp;&nbsp;<?php _e('Display Login Form?','aheadzen');?>
					<br /> <small><?php _e('By selecting the option it will display the login form with popup.','aheadzen');?></small>
					</label>
					</td>
				</tr>
				<tr valign="top">
					<td>
					<label for="aheadzen_voter_include_dialog_js">
					<input type="checkbox" value="1" id="aheadzen_voter_include_dialog_js" name="aheadzen_voter_include_dialog_js" <?php if(get_option('aheadzen_voter_include_dialog_js')){echo "checked=checked";}?>/>&nbsp;&nbsp;&nbsp;<?php _e('Do you want to disable login box js (jquery-ui.js)?','aheadzen');?>
					<br /> <small><?php _e('The plugin including "http://code.jquery.com/ui/1.10.3/jquery-ui.js" for login dialog box In case your website already included and may creating conflict you can disable/remove it by this option.','aheadzen');?></small>
					</label>
					</td>
				</tr>
				<?php 
				if($templates){?>
				<tr valign="top">
					<td>
					<p><?php _e('Select the page template to disable voter plugin','aheadzen');?> ::</p>
					<ul>
					<?php
					foreach ( array_keys( $templates ) as $template ) {
						$selected = selected( $default, $templates[ $template ], false );
						?>
						<li style="float:left;width:32%;">
						<label>
						<input type="checkbox" value="<?php echo $templates[ $template ];?>" name="exclude_pages[]" <?php if($exclude_pages && in_array($templates[ $template ],$exclude_pages)){echo "checked=checked";}?>/>&nbsp;<?php echo $template;?></li>
						</label>
						<?php
					}
					?>
					</ul>
					<div style="width:100%; clear:both;"></div>
					<br /> <small><?php _e('Please select the template if you want to disable the voter plugin for it.','aheadzen');?></small>
					</td>
				</tr>
				<?php }?>
				<tr valign="top">
					<td>
						<input type="hidden" name="page_options" value="<?php echo $value;?>" />
						<input type="hidden" name="action" value="update" />
						<input type="submit" value="Save settings" class="button-primary"/>
					</td>
				</tr>					
			</table>
		</form>
		<?php
		// Check that the user is allowed to update options  
		if (!current_user_can('manage_options'))
		{
			wp_die('You do not have sufficient permissions to access this page.');
		}
	}
}

function voting_display_dl($selected='')
{
	$return = '';
	$data_arr = array(
		''			=>__('-- Select One --','aheadzen'),
		'likeunlike'=>__('Simple Like/Unlike','aheadzen'),
		'helpful'	=>__('Helpful? Yes/No','aheadzen'),
		'thumbs'	=>__('Thumbs up/down','aheadzen'),
		'buttons'	=>__('Up/Down Button','aheadzen'),
		'customtex'	=>__('Custom Text','aheadzen'),
		'customhtml'	=>__('Custom HTML','aheadzen'),
		'customimage'	=>__('Custom Image','aheadzen'),
		);
		foreach($data_arr as $key=>$val){
			if($selected==$key){$selected_str='selected';}else{$selected_str='';}
			$return .= '<option value="'.$key.'" '.$selected_str.'>'.$val.'</option>';
		}
	return $return;
}

