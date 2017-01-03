<?php
global $wpdb;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//-- main colors used
$b1_color="252d32";
$b1_highlight="e7574d";
//$b1_highlight="cf4";
$b2_color="e1e2de";
$b3_color="65393f";
$b1_text_highlight="fff";

//-- get all admin settings from database
$fbtc_settings_result=$wpdb->get_results("SELECT * FROM fbtc_settings", ARRAY_A);
foreach($fbtc_settings_result as $row){
	$phone=FBTC_d($row['phone']);
	$path=FBTC_d($row['path']);
	$admin_email=FBTC_d($row['admin_email']);
	$cc_email=FBTC_d($row['cc_email']);
	$bcc_email=FBTC_d($row['bcc_email']);

	$require_name=FBTC_d($row['require_name']);
	$require_email=FBTC_d($row['require_email']);
	$require_email_verify=FBTC_d($row['require_email_verify']);
	$require_phone=FBTC_d($row['require_phone']);
	$require_message=FBTC_d($row['require_message']);
	$require_test=FBTC_d($row['require_test']);
	$cc_sender=FBTC_d($row['cc_sender']);
	$send_emails=FBTC_d($row['send_emails']);
	$send_texts=FBTC_d($row['send_texts']);

	$field_name=FBTC_d($row['field_name']);
	$field_email=FBTC_d($row['field_email']);
	$field_email_verify=FBTC_d($row['field_email_verify']);
	$field_phone=FBTC_d($row['field_phone']);
	$field_message=FBTC_d($row['field_message']);
	$field_enter_letters=FBTC_d($row['field_enter_letters']);
	$field_send_button_text=FBTC_d($row['field_send_button_text']);

	$error_incomplete=FBTC_d($row['error_incomplete']);
	$error_saving=FBTC_d($row['error_saving']);
	$error_email_failed=FBTC_d($row['error_email_failed']);
	$error_invalid_email=FBTC_d($row['error_invalid_email']);
	$success_saved=FBTC_d($row['success_saved']);
	$success_email_sent=FBTC_d($row['success_email_sent']);
	$success_message_final=FBTC_d($row['success_message_final']);

	$email_label_name=FBTC_d($row['email_label_name']);
	$email_label_email=FBTC_d($row['email_label_email']);
	$email_label_date=FBTC_d($row['email_label_date']);
	$email_label_phone=FBTC_d($row['email_label_phone']);
	$email_label_message=FBTC_d($row['email_label_message']);
	$email_subject=FBTC_d($row['email_subject']);

	$list_name=FBTC_d($row['list_name']);
	$list_email=FBTC_d($row['list_email']);
	$list_phone=FBTC_d($row['list_phone']);
	$list_date=FBTC_d($row['list_date']);
	$list_read_on_date=FBTC_d($row['list_read_on_date']);
	$list_content=FBTC_d($row['list_content']);
	$list_ip=FBTC_d($row['list_ip']);
}?>