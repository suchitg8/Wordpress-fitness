<?php 
global $wpdb;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// $wpdb->show_errors();
// check if main tables exist. If not, build them.
$val=$wpdb->query('select 1 from `fbtc_settings`');
if($val===FALSE){

//////////////////////////////////////////////////////////////////////////////////////////////////
// -- MESSAGES TABLE
//////////////////////////////////////////////////////////////////////////////////////////////////
$fbtc_contact="
CREATE TABLE IF NOT EXISTS `fbtc_messages` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(100) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `name` varchar(500) NOT NULL,
  `email` varchar(500) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `read_on_date` varchar(100) NOT NULL,
  `live` varchar(10) NOT NULL,
  `content` TEXT NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;";
$saveIt=$wpdb->query($fbtc_contact);
if(!$saveIt){echo "<p style='color:#f00;'>Error! Could not create table: fbtc_contact</p>"; $errorMessage="y";}else{echo "<p style='color:#090; font-weight:bold; font-size:18px; '>".$conf_img." Created table: fbtc_contact</p>";}

//////////////////////////////////////////////////////////////////////////////////////////////////
// -- SETTINGS TABLE: all admin settings and options are stored here.
//////////////////////////////////////////////////////////////////////////////////////////////////
$fbtc_settings="
CREATE TABLE IF NOT EXISTS `fbtc_settings` (
  `id` int(30) NOT NULL auto_increment,
  `phone` varchar(500) NOT NULL,
  `path` varchar(500) NOT NULL,
  `admin_email` varchar(200) NOT NULL,
  `cc_email` varchar(200) NOT NULL,
  `bcc_email` varchar(200) NOT NULL,
  `require_name` int(1) NOT NULL,
  `require_email` int(1) NOT NULL,
  `require_email_verify` int(1) NOT NULL,
  `require_phone` int(1) NOT NULL,
  `require_message` int(1) NOT NULL,
  `require_test` int(1) NOT NULL,
  `cc_sender` int(1) NOT NULL,
  `field_name` varchar(100) NOT NULL,
  `field_email` varchar(100) NOT NULL,
  `field_email_verify` varchar(100) NOT NULL,
  `field_phone` varchar(100) NOT NULL,
  `field_message` varchar(100) NOT NULL,
  `field_enter_letters` varchar(100) NOT NULL,
  `field_send_button_text` varchar(100) NOT NULL,
  `error_incomplete` varchar(200) NOT NULL,
  `error_saving` varchar(200) NOT NULL,
  `error_email_failed` varchar(200) NOT NULL,
  `error_invalid_email` varchar(200) NOT NULL,
  `success_saved` varchar(200) NOT NULL,
  `success_email_sent` varchar(200) NOT NULL,
  `success_message_final` TEXT NOT NULL,
  `email_label_name` varchar(100) NOT NULL,
  `email_label_email` varchar(100) NOT NULL,
  `email_label_phone` varchar(100) NOT NULL,
  `email_label_date` varchar(100) NOT NULL,
  `email_label_message` varchar(100) NOT NULL,
  `email_subject` varchar(100) NOT NULL,
  `list_name` int(1) NOT NULL, 
  `list_email` int(1) NOT NULL, 
  `list_phone` int(1) NOT NULL, 
  `list_date` int(1) NOT NULL, 
  `list_read_on_date` int(1) NOT NULL, 
  `list_ip` int(1) NOT NULL, 
  `list_content` int(1) NOT NULL, 
  `send_emails` int(1) NOT NULL, 
  `send_texts` int(1) NOT NULL, 
  `live` int(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;";
$saveIt=$wpdb->query($fbtc_settings);
if(!$saveIt){echo "<p style='color:#f00;'>Error! Could not create table: fbtc_settings</p>"; $errorMessage="y";}else{echo "<p style='color:#090; font-weight:bold; font-size:18px; '>".$conf_img." Created table: fbtc_settings</p>";}

//////////////////////////////////////////////////////////////////////////////////////////////////
// -- Create defaut admin settings
//////////////////////////////////////////////////////////////////////////////////////////////////
if($errorMessage!="y"){
	$insert_new_user="INSERT INTO `fbtc_settings` VALUES(0, '', '', '', '', '', '1', '1', '1', '0', '1', '0', '0', 'Name', 'E-mail', 'Verify+E-mail', 'Phone', 'Message', 'Enter+the+letters', 'Send', 'Complete+the+items+below...', 'Could+Not+Save', 'Could+Not+Send+Message', 'Invalid+E-mail', 'Saved+Successfully%21', 'Message+Sent%21', 'Thank+you+for+contacting+us.+We+will+reply+as+soon+as+possible.', 'Name%3A', 'E-mail%3A', 'Phone%3A', 'Date%3A', 'Message%3A', 'Contact+Form', '1', '1', '0', '1', '0', '0', '0', '1', '1', '1');";
	$saveIt=$wpdb->query($insert_new_user);
	if(!$saveIt){echo "<p style='color:#f00;'>Error! Could not create new user record. Try to re-load this page.</p>"; $errorMessage="y";}else{echo "<p style='color:#090; font-weight:bold; font-size:28px;'>".$conf_img." Created new user successfully!</p>";}

	if($errorMessage!="y"){
		function FBTC_redirect($goto, $wait){
			echo "<script language='javascript'>
			function direct(){
			   window.location='".$goto."';
			   }
			   setTimeout( 'direct();', ".$wait.");
				</script>";
		}

		FBTC_redirect(admin_url()."?page=text-message-contact-form/admin_home.php&v=home&", 1000);
		die();
	}
}
}