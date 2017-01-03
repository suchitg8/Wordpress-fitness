<?php
/**
 * @package DB Explorer
 * @version .91
 */
/*
Plugin Name: Text Message Contact Form
Plugin URI: http://www.fonebug.com/
Description: Receive text messages and e-mails when contact form is submitted. Fully customizable form fields, labels and requirements.
Version: .91
Author: theArab
Author URI: http://www.fonebug.com/
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('activated_plugin','fbtc_save_error');
function fbtc_save_error(){file_put_contents(plugin_dir_path( __FILE__ ) . '/error.html', ob_get_contents());}

function fbtc_plugin_admin_add_page() {
	add_menu_page('Text Message Contact Form', 'Text Contact', 'manage_options', plugin_dir_path( __FILE__ ) . 'admin_home.php', '', 'dashicons-email-alt', 90);
}
add_action('admin_menu', 'fbtc_plugin_admin_add_page');

function fbtc_my_enqueue($hook) {
  	//-- for the admin page
	if( 'text-message-contact-form/admin_home.php' != $hook )
		return;

  wp_register_style('text-message-contact-form', plugins_url('_include/fbtc-styles.php', __FILE__));
  wp_enqueue_style('text-message-contact-form');
}
add_action('admin_enqueue_scripts', 'fbtc_my_enqueue');

//[text-message-contact-form]
function fonebug_text_shortcode($atts){include_once(plugin_dir_path( __FILE__ ) . "index.php");}
add_shortcode( 'text-message-contact-form', 'fonebug_text_shortcode' );


//////////////////////////////////////////////////////////////////////////////////////////////////
//-- dashboard panel
//////////////////////////////////////////////////////////////////////////////////////////////////
function FBTC_register_dashboard(){
	wp_add_dashboard_widget( 'FBTC_register_dashboard', __("<a href='".admin_url()."?page=text-message-contact-form/admin_home.php&v=home' style='font-size:14px; font-weight:bold; color:#000; text-decoration:none;'>Text Message Contact Form</a>", 'FBTC'), 'FBTC_dashboard' );
}
add_action('wp_dashboard_setup', 'FBTC_register_dashboard', 10 );

function FBTC_dashboard(){
	global $wpdb;
	$fbtc_admin=admin_url()."?page=text-message-contact-form/admin_home.php";
	$fbtc_btns_dir=plugin_dir_url(dirname( __FILE__) )."text-message-contact-form/_btns/";

	//-- count unread
	$countIt=$wpdb->get_results("SELECT * FROM fbtc_messages WHERE read_on_date='' AND live='1'");
	$total_unread=count($countIt);
	if($total_unread==1){$reminder_word="message";}else{$reminder_word="messages";}
	if($total_unread>0){$reminder_color="F00"; $img="btn_ex16_reg.png"; $mailbox="&l=unread&";}else{$reminder_color="090"; $img="btn_enable16_reg.png"; $mailbox="&l=inbox&"; }

	//-- the panel
	echo "<table><tr><td>";
	echo "<a href='".$fbtc_admin."&v=read".$mailbox."&' style='color:#".$reminder_color.";'>";
	echo "<img src='".$fbtc_btns_dir.$img."' style='margin-right:7px; vertical-align:middle;'>";
	echo $total_unread." unread ".$reminder_word;
	echo "</a></td></tr></table>";
}	
?>