<?php
/*
Plugin Name: Mingle User Location
Plugin URI: http://www.design.theschires.com
Description: Mingle User Location shows a list of Mingle users in your sidebar or anywhere with included shortcode. Shows user image, name(clickable), location, and website.
Author: Jay Schires
Version: 0.0.1
Author URI: http://icomnow.com
*/ 
class Live_UsersLoc extends WP_Widget {
    /** constructor */
    function Live_UsersLoc() {
        parent::WP_Widget(false, $name = 'UsersLoc');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        ?>
              <?php echo $before_widget; ?>
              
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
     
 <?php

require($_SERVER['DOCUMENT_ROOT'].'/wp-blog-header.php');

global  $mngl_options, $wpdb, $board_post_id, $author_id, $owner_id, $mngl_friend, $mngl_user, $mngl_app_helper, $mngl_board_post, $mngl_app_helper, $mngl_board_comment, $current_user, $mngl_boards_controller, $mngl_friends_controller, $mngl_options;

	require_once ($_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/mingle/classes/models/MnglBoardPost.php');

	require_once ($_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/mingle/classes/models/MnglUser.php');

	require_once ($_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/mingle/classes/helpers/MnglAppHelper.php');
	
	require_once ($_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/mingle/classes/helpers/MnglProfileHelper.php');
	

	require_once($_SERVER['DOCUMENT_ROOT'].'/wp-admin/includes/plugin.php' );

  global $wpdb; // global wordpress database object



  $mngl_board_post =& MnglBoardPost::get_stored_object();
   $user =& MnglUser::get_stored_profile_by_id($username->ID);?>
<style type="text/css">
<!--
.userbio {
	padding-top: 5px;
	padding-bottom: 5px;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
}
.bioline {
	display: inline-block;
	height: auto;
	width: 180px;
	padding-bottom: 12px;
	background-color: #CCF;
	padding-top: 4px;
	border: thin ridge #333;
	margin-bottom: 12px;
	background-color:<?=(get_option('userloc_bottom_color')!=''?get_option('userloc_bottom_color'):"#CCF");?>;
	color:<?=(get_option('userloc_text_color')!=''?get_option('userloc_text_color'):"#000");?>;
	margin-right: 2px;
	margin-left: 2px;
	padding-right: 2px;
	padding-left: 2px;
	float: none;
}
.imag{
	padding-bottom: 6px;
	 	padding-right: 4px; 
}
.ftag {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 12px;
	font-style: italic;
	font-weight: normal;
	color: #333;
	background-color: #CFF;
	text-align: center;
	display: inline-block;
	height: auto;
	width: 180px;
	padding-right: 5px;
	padding-left: 5px;
	border: thin ridge #000;
}
-->
</style>
 
 <div class="userbio">
  <?php $usernames = $wpdb->get_results("SELECT ID, user_nicename, user_url FROM $wpdb->users ORDER BY RAND() DESC LIMIT 5");
	$author = $usernames->ID;
	
	
		foreach ($usernames as $username) {
		$user =& MnglUser::get_stored_profile_by_id($username->ID);
    			echo '<div class="bioline" >&nbsp;' .get_avatar( $username->ID, 48).'<a href="../'.$username->user_nicename.'"> &nbsp;'.$user->first_name." " .$user->last_name ." </a><div>
Location:  ".wptexturize($user->location) ."</div><div>Website:  ".make_clickable($author->url) ."</div></div>";
				
		}
		?></div><center><div class="ftag">Created by <a href="http://icomnow.com/plugin-store/" title="Get More Plugins @ iCom Now!" target="_blank">IcomNow</a></div></center>
              <?php echo $after_widget; ?>
        <?php
    }	

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $title = esc_attr($instance['title']);
        ?>        
<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
        <?php 
    }

} // class Live_UsersLoc
// register Live_UsersLoc
if(is_admin()) {
	add_action('admin_menu','userloc_add_menu');
	add_action('admin_init','userloc_regset');
}
function userloc_regset() {
	register_setting('userloc','userloc_bottom_color');
	register_setting('userloc','userloc_header_color');
	register_setting('userloc','userloc_text_color');
	register_setting('userloc','userloc_force_login');
	register_setting('userloc','userloc_dock_image');
}
function userloc_add_menu() {
	add_menu_page('userlocOptions','Mingle User Location','administrator',__FILE__,'userloc_options',plugins_url('/icon.png',__FILE__));
}
function userloc_options() {
?>
<div class='wrap'>
<h2>Mingle User Location Options</h2>
<form method='post' action='options.php'>
<?php
settings_fields('userloc');
$userloc_force_login_current=get_option('userloc_force_login');
$userloc_bottom_color_current=get_option('userloc_bottom_color');
$userloc_header_color_current=get_option('userloc_header_color');
$userloc_text_color_current=get_option('userloc_text_color');
$userloc_dock_image_current=get_option('userloc_dock_image');
?>
<table class='form-table'>
<tr valign='top'>

</tr>
<tr valign='top'>
<th align="left" scope='row'>User Location Background Color:</th>
<td><input name='userloc_bottom_color' type='text' value='<?=$userloc_bottom_color_current;?>'/></td>
</tr>
<tr valign='top'>
<th align="left" scope='row'>Uer Location Post Box Color:</th>
<td><input name='userloc_header_color' type='text' value='<?=$userloc_header_color_current;?>'/></td>
</tr>
<tr valign='top'>
<th align="left" scope='row'>Uer Location Post Text Color:</th>
<td><input name='userloc_text_color' type='text' value='<?=$userloc_text_color_current;?>'/></td>
</tr>
</table>
<input type='hidden' name='action=' value='update'/>
<input type='hidden' name='options' value='userloc_force_login,userloc_bottom_color,userloc_header_color,userloc_text_color>
<p class='submit'>
<input type='submit' class='button-primary' value='<?php _e('Save Changes') ?>'/>
<div> Use this shortcode to place the Mingle User Location anywhere on your site! <p><strong><em>< ? php Live_UsersLoc_shortcode(); ? ></em></strong></p>
  <p>Visit <a href="http://icomnow.com" target="_blank">iCom</a> for more Mingle fun and customizations!</p>
  <p><img src="http://icomnow.com/wp-content/themes/multi-color/iCom_logo.png" /></p>
  <p style="text-align: center;">Need help on your Wordpress theme or mingle css? We have a few developers-designers on staff to help based on a minimal hourly rate! <a href="http://icomnow.com/contact-us/" title="Need Help? Contact Us" target="_blank">Contact us</a> today and get the help you need!</p>
<p style="text-align: center;">HELP by Donating for even more Mingle Plugins...Every donation is greatly appreciated!</p><div class="desc">
<p style="text-align: center;"><center><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_donations">
<input type="hidden" name="business" value="jschires@me.com">
<input type="hidden" name="lc" value="US">
<input type="hidden" name="item_name" value="iCom Now Mingle Plugin Development">
<input type="hidden" name="item_number" value="MnglPlgDev">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHosted">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form></center></p></div>
  <h4><a href="http://icomnow.com/wp-themes/" target="_blank">Mingle iPhone iTheme</a></h4>
  <p>Turns your Mingle site into an amazing iPhone ready site! Easier to read and post on the iPhone as well as Android devices!</p>
 <h4><a href="http://icomnow.com/plugins/" target="_blank">Mingle Live Status Feed Widget</a></h4>
  <p>See your sitewide status updates and users in your sidebar: Refreshes to see posts and comments as they happen!</p>
  <p>Widget to show the following in your sidebar: Bar for 4 Random or newest users above status updates. Sitewide status updates and comments from all users (5 updates displayed). Now includes live threaded comments for each post. Users image that is clickable. Added View It link thats clickable to orignal post. Now incorporates Private Message count if using Cartpauj PM, refreshes so you always know if you have messages!</p>
  <p>Custom css available for a VERY small fee :)&nbsp;<a href="http://icomnow.com" target="_blank" rel="nofollow">http://icomnow.com</a>&nbsp;Custom iTheme for iPhone Mingle Available at&nbsp;<a href="http://icomnow.com/wp-themes/" target="_blank">Mingle iPhone iTheme</a><a href="http://icomnow.com/mingle-live-status-feed" rel="nofollow"></a>&nbsp;for a very unbelievable price!</p>
  <p>If your looking for custom plugins or widgets, iCom Now can help!<a href="http://icomnow.com/contact-us/" target="_blank"> Let us know</a> what your needing and we will shoot you an awesome price on getting it done for you!</p>

<?php
if($_GET['updated']) { echo "<div style='font-weight:bold;'>Options Saved!</div>\n"; }
?>
</p>
</form>
<?php } ?>
<?php
function Live_UsersLoc_shortcode()
{
    $Live_UsersLoc_class = new Live_UsersLocWidget();
    $Live_UsersLoc_class->widget(array(), 999);
}
add_action('widgets_init', create_function('', 'return register_widget("Live_UsersLoc");')); ?>