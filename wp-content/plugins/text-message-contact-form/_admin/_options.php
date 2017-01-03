<?php 
//$wpdb->show_errors();
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//=================================================
//======= TEST TEXT
//=================================================
if($_GET['op']=='testtext'){
	$send_to=$phone.$path;
	$saveIt=mail( $send_to, "", "This is a test message from your website... Looks like you're successfully set up for text notices. Have a wonderful day!", null, "-f$admin_email");

	if(!$saveIt){
		FBTC_redBox("Could not send test.",800, 21);
	}else{
		FBTC_greenBox("Sent Text Successfully!", 800,21);
		FBTC_redirect($fbtc_admin."&v=options",3000);
		echo "sent to :".$send_to;
		die();
	}
}

//-- POST settings from form
if ($_SERVER['REQUEST_METHOD']=='POST' && $_GET['v']=='options'){
	$errorMessage="";
	//-- contact vars
	$phone=FBTC_e($_POST['phone']);
	$path=FBTC_e($_POST['path']);
	$admin_email=FBTC_e($_POST['admin_email']);
	$cc_email=FBTC_e($_POST['cc_email']);
	$bcc_email=FBTC_e($_POST['bcc_email']);

	//-- requirement vars
	$require_name=FBTC_e($_POST['require_name']);
	$require_email=FBTC_e($_POST['require_email']);
	$require_email_verify=FBTC_e($_POST['require_email_verify']);
	$require_phone=FBTC_e($_POST['require_phone']);
	$require_message=FBTC_e($_POST['require_message']);
	$require_test=FBTC_e($_POST['require_test']);
	$cc_sender=FBTC_e($_POST['cc_sender']);
	$send_emails=FBTC_e($_POST['send_emails']);
	$send_texts=FBTC_e($_POST['send_texts']);

	//-- field vars
	$field_name=FBTC_e($_POST['field_name']);
	$field_email=FBTC_e($_POST['field_email']);
	$field_email_verify=FBTC_e($_POST['field_email_verify']);
	$field_phone=FBTC_e($_POST['field_phone']);
	$field_message=FBTC_e($_POST['field_message']);
	$field_send_button_text=FBTC_e($_POST['field_send_button_text']);
	$field_enter_letters=FBTC_e($_POST['field_enter_letters']);

	//-- notice & error vars
	$error_incomplete=FBTC_e($_POST['error_incomplete']);
	$error_saving=FBTC_e($_POST['error_saving']);
	$error_email_failed=FBTC_e($_POST['error_email_failed']);
	$error_invalid_email=FBTC_e($_POST['error_invalid_email']);
	$success_saved=FBTC_e($_POST['success_saved']);
	$success_email_sent=FBTC_e($_POST['success_email_sent']);
	$success_message_final=FBTC_e($_POST['success_message_final']);

	//-- email label vars
	$email_label_name=FBTC_e($_POST['email_label_name']);
	$email_label_email=FBTC_e($_POST['email_label_email']);
	$email_label_date=FBTC_e($_POST['email_label_date']);
	$email_label_phone=FBTC_e($_POST['email_label_phone']);
	$email_label_message=FBTC_e($_POST['email_label_message']);
	$email_subject=FBTC_e($_POST['email_subject']);

	//-- colums for messages list
	$list_name=FBTC_e($_POST['list_name']);
	$list_email=FBTC_e($_POST['list_email']);
	$list_phone=FBTC_e($_POST['list_phone']);
	$list_date=FBTC_e($_POST['list_date']);
	$list_read_on_date=FBTC_e($_POST['list_read_on_date']);
	$list_ip=FBTC_e($_POST['list_ip']);
	$list_content=FBTC_e($_POST['list_content']);

	//-- errorchecks
	if($phone!="" && $path==""){$errorMessage="no path"; $errorPath=1;}
	if($path!="" && $phone==""){$errorMessage="no phone"; $errorAdminPhone=1;}
	if($send_emails==1 && $admin_email==""){$errorMessage="no email"; $errorSendEmails=1; $errorAdminEmail=1;}
	if($send_texts==1 && $phone==""){$errorMessage="no phone2"; $errorSendTexts=1; $errorAdminPhone=1;}
	if($send_texts==1 && $path==""){$errorMessage="no path2"; $errorSendTexts=1; $errorPath=1;}

	//-- SAVE IT!
	if($errorMessage==""){
		$saveIt=$wpdb->update("fbtc_settings",
								array(
								"phone"=>$phone,
								"path"=>$path,
								"admin_email"=>$admin_email,
								"cc_email"=>$cc_email,
								"bcc_email"=>$bcc_email,
								"require_name"=>$require_name,
								"require_email"=>$require_email,
								"require_email_verify"=>$require_email_verify,
								"require_phone"=>$require_phone,
								"require_message"=>$require_message,
								"require_test"=>$require_test,
								"cc_sender"=>$cc_sender,
								"send_emails"=>$send_emails,
								"send_texts"=>$send_texts,
								"field_name"=>$field_name,
								"field_email"=>$field_email,
								"field_email_verify"=>$field_email_verify,
								"field_phone"=>$field_phone,
								"field_message"=>$field_message,
								"field_enter_letters"=>$field_enter_letters,
								"field_send_button_text"=>$field_send_button_text,
								"error_incomplete"=>$error_incomplete,
								"error_saving"=>$error_saving,
								"error_email_failed"=>$error_email_failed,
								"error_invalid_email"=>$error_invalid_email,
								"success_saved"=>$success_saved,
								"success_email_sent"=>$success_email_sent,
								"success_message_final"=>$success_message_final,
								'email_label_name'=>$email_label_name,
								'email_label_email'=>$email_label_email,
								'email_label_date'=>$email_label_date,
								'email_label_phone'=>$email_label_phone,
								'email_label_message'=>$email_label_message,
								'email_subject'=>$email_subject,
								'list_name'=>$list_name,
								'list_email'=>$list_email,
								'list_phone'=>$list_phone,
								'list_date'=>$list_date,
								'list_read_on_date'=>$list_read_on_date,
								'list_content'=>$list_content,
								'list_ip'=>$list_ip
								),
								array("live"=>"1")
							);
		//-- succes or failure
		if(!$saveIt){
			FBTC_redBox("Error saving, try again later.", 800, 21);
		}else{
			FBTC_greenBox("Saved Changes to Options!", 800, 21);
			FBTC_redirect($fbtc_admin."&v=options&", 500);
			die();
		}
	}
}
//-- link to get back to top of the page
$fbtc_top="<a href='#fbtc_top' style='float:right; margin-right:7px; color:#ccc; font-size:12px; vertical-align:middle; text-decoration:none;'>^ top</a>";

//-- if error, display red message box 
//echo "EM: ".$errorMessage;
if($errorMessage!="" && ($errorSendEmails==1 || $errorSendTexts==1)){
	if($errorSendEmails==1){FBTC_redBox("Enter an admin e-mail to send e-mails...", 800, 21);}
	if($errorSendTexts==1){FBTC_redBox("Enter text info to receive messages...", 800, 21);}
}else if($errorMessage!=""){
	FBTC_redBox("Error, correct the items in red below...", 800, 21);
}

FBTC_title("Options & Settings", "btn_options32_reg.png", $fbtc_admin."&amp;v=options&amp;");
?>
<!-- ==================================================================================================
-- MENU AT TOP
================================================================================================== -->
<table class='cc800' style='margin-bottom:7px;'><tr>
<td style='padding-right:3px;'><div class='navb1' style='text-align:center;'><a href='<?php echo $fbtc_admin;?>'>Home</a></div></td>
<td style='padding-right:3px;'><div class='navb1' style='text-align:center;'><a href='#email'>E-mails & Texts</a></div></td>
<td style='padding-right:3px;'><div class='navb1' style='text-align:center;'><a href='#options'>Requirements & Options</a></div></td>
<td style='padding-right:3px;'><div class='navb1' style='text-align:center;'><a href='#labels'>Form Labels</a></div></td>
<td style='padding-right:3px;'><div class='navb1' style='text-align:center;'><a href='#emaillabels'>E-mail Labels</a></div></td>
<td style='padding-right:3px;'><div class='navb1' style='text-align:center;'><a href='#notices'>Alert Notices</a></div></td>
<td style=''><div class='navb1' style='text-align:center;'><a href='#listcolumns'>List Columns</a></div></td>
</tr></table>

<form enctype="multipart/form-data" id="form1" name="form1" method="post" action="<?php echo $fbtc_admin;?>&amp;v=options&amp;op=options&amp;" style="margin-top:0px">
<!-- ==================================================================================================
-- EMAIL AND TEXT SETTINGS
================================================================================================== -->
<a name='email' class='SM_anchor'></a>
<table class='cc800' style='margin-bottom:21px;'><tr><td class='b1' style='padding-left:14px;'><?php echo $fbtc_top;?>E-mail & Text Settings</td></tr>
<tr><td class='b2' style='padding:0px;'>
<table class='cc100' style='margin:7px 0px 14px 14px;'>
<tr><td class='pad7' colspan='2'><span class='option_notes'>Use these settings to customize delivery of your text message and e-mail.</span></td></tr>
<?php 
FBTC_textfield("Admin E-mail", $errorAdminEmail, "admin_email", $admin_email, "Enter the e-mail where you would like to receive your messages. This will also be the e-mail used to send copies.");
FBTC_invalid_email($errorValidEmail);
FBTC_checkbox("Send E-mails", $errorSendEmails, "send_emails", $send_emails, "");
FBTC_textfield("Cell Phone", $errorAdminPhone, "phone", $phone, 
"Enter the cellphone number where you would like to receive text messages. <br>NOTE: Do not include spaces or dashes. Use only the numbers. For example: 1235551234");
FBTC_path("path", FBTC_d($path), $errorPath);
FBTC_checkbox("Send Text Messages", $errorSendTexts, "send_texts", $send_texts, "");
FBTC_textfield("CC E-mail", $errorCCEmail, "cc_email", $cc_email, "Enter an e-mail to send carbon copies to.");
FBTC_invalid_email($errorValidCCEmail);
FBTC_textfield("BCC E-mail", $errorBCCEmail, "bcc_email", $bcc_email, "Enter an e-mail to send blind carbon copies to.");
FBTC_invalid_email($errorValidBCCEmail);
FBTC_textfield("E-mail Subject", $errorEmailSubject, "email_subject", $email_subject, "Enter the subject of your contact e-mail.");
//-- send test message
if($phone!="" && $path!=""){?>
<tr><td class='label150'>&nbsp;</td><td style='padding:7px;'><div class='navBlue' style='width:450px; text-align:center;'><a href='<?php echo $fbtc_admin."&v=options&op=testtext&";?>'><img src='<?php echo $fbtc_btns_dir;?>btn_cellphone16_reg.png' class='btn' />Send a Test Text Message to: <br /><?php echo $phone.$path;?></a></div></td></tr>
<?php } ?>
<tr><td class='label150'>&nbsp;</td><td class='pad7'><input type="submit" name="button" id="button" value="Save Changes" /></td></tr>
</table>
</td></tr></table>

<!-- ==================================================================================================
-- REQUIREMENTS & OPTIONS
================================================================================================== -->
<a name='options' class='SM_anchor'></a>
<table class='cc800' style='margin-bottom:21px;'>
<tr><td class='b1' style='padding-left:14px;'><?php echo $fbtc_top;?>Contact Form Requirements & Options</td></tr>
<tr><td class='b2' style='padding:0px;'>
<table class='cc100' style='margin:7px 0px 14px 14px;'>
<tr><td class='pad7' colspan='2'><span class='option_notes'>Select the information you want your sender to provide.</span></td></tr>
<?php 
FBTC_checkbox("Require Sender's Name", $errorName, "require_name", $require_name, "");
FBTC_checkbox("Require Sender's E-mail", $errorEmail, "require_email", $require_email, "");
FBTC_checkbox("Require E-mail Verification", $errorEmailVerify, "require_email_verify", $require_email_verify, "Require sender to type e-mail address a second time for verification.");
FBTC_checkbox("Require Sender's Phone Number", $errorPhone, "require_phone", $require_phone, "");
FBTC_checkbox("Require Sender to Enter a Message", $errorMessage, "require_message", $require_message, "");
FBTC_checkbox("Require Test", $errorRequireTest, "require_test", $require_test, "Require sender to copy letters from a box.");
FBTC_checkbox("CC Sender", $errorCCSender, "cc_sender", $cc_sender, "If the sender has included their e-mail, send a copy of the contact form to them.");
?>
<tr><td class='label150'>&nbsp;</td><td class='pad7'><input type="submit" name="button" id="button" value="Save Changes" /></td></tr>
</table>
</td></tr></table>

<!-- ==================================================================================================
-- FORM LABELS
================================================================================================== -->
<a name='labels' class='SM_anchor'></a>
<table class='cc800' style='margin-bottom:21px;'>
<tr><td class='b1' style='padding-left:14px;'><?php echo $fbtc_top;?>Contact Form Field Labels</td></tr>
<tr><td class='b2' style='padding:0px;'>
<table class='cc100' style='margin:7px 0px 14px 14px;'>
<tr><td class='pad7' colspan='2'><span class='option_notes'>Use these settings to change the label names for your contact form.</span>
</td></tr>
<?php 
FBTC_textfield("Name", $errorFieldName, "field_name", $field_name, "");
FBTC_textfield("E-mail", $errorFieldEmail, "field_email", $field_email, "");
FBTC_textfield("Verify E-mail", $errorFieldVerify, "field_email_verify", $field_email_verify, "");
FBTC_textfield("Phone", $errorFieldPhone, "field_phone", $field_phone, "");
FBTC_textfield("Message", $errorFieldMessage, "field_message", $field_message, "");
FBTC_textfield("Enter Letters", $errorEnterLetters, "field_enter_letters", $field_enter_letters, "");
FBTC_textfield("Send Button", $errorFieldMessage, "field_send_button_text", $field_send_button_text, "");
?>
<tr><td class='label150'>&nbsp;</td><td class='pad7'><input type="submit" name="button" id="button" value="Save Changes" /></td></tr>
</table>
</td></tr></table>

<!-- ==================================================================================================
-- EMAIL LABELS 
================================================================================================== -->
<a name='emaillabels' class='SM_anchor'></a>
<table class='cc800' style='margin-bottom:21px;'>
<tr><td class='b1' style='padding-left:14px;'><?php echo $fbtc_top;?>E-mail Field Labels</td></tr>
<tr><td class='b2' style='padding:0px;'>
<table class='cc100' style='margin:7px 0px 14px 14px;'>
<tr><td class='pad7' colspan='2'><span class='option_notes'>Use these settings to change the label names for your contact e-mail.</span>
</td></tr>
<?php 
FBTC_textfield("Name", $errorEmailName, "email_label_name", $email_label_name, "");
FBTC_textfield("E-mail", $errorEmailEmail, "email_label_email", $email_label_email, "");
FBTC_textfield("Phone", $errorEmailPhone, "email_label_phone", $email_label_phone, "");
FBTC_textfield("Date", $errorEmailDate, "email_label_date", $email_label_date, "");
FBTC_textfield("Message", $errorEmailMessage, "email_label_message", $email_label_message, "");
?>
<tr><td class='label150'>&nbsp;</td><td class='pad7'><input type="submit" name="button" id="button" value="Save Changes" /></td></tr>
</table>
</td></tr></table>

<!-- ==================================================================================================
-- NOTICES
================================================================================================== -->
<a name='notices' class='SM_anchor'></a>
<table class='cc800' style='margin-bottom:21px;'>
<tr><td class='b1' style='padding-left:14px;'><?php echo $fbtc_top;?>Success & Error Notices</td></tr>
<tr><td class='b2' style='padding:0px;'>
<table class='cc100' style='margin:7px 0px 14px 14px;'>
<tr><td class='pad7' colspan='2'><span class='option_notes'>Use these settings to change success or error notification text.</span></td></tr>
<?php 
FBTC_textfield("Save Success", $errorSuccessSaved, "success_saved", $success_saved, "");
FBTC_textfield("Send Success", $errorSuccessSent, "success_email_sent", $success_email_sent, "");
FBTC_textarea("Success Message", $errorSuccessMessage, "success_message_final", $success_message_final, "");
FBTC_textfield("Incomplete", $errorErrorIncomplete, "error_incomplete", $error_incomplete, "");
FBTC_textfield("Error Saving", $errorErrorSaving, "error_saving", $error_saving, "");
FBTC_textfield("Error E-mailing", $errorErrorEmailing, "error_email_failed", $error_email_failed, "");
FBTC_textfield("Invalid E-mail", $errorErrorValidEmail, "error_invalid_email", $error_invalid_email, "");
?>
<tr><td class='label150'>&nbsp;</td><td class='pad7'><input type="submit" name="button" id="button" value="Save Changes" /></td></tr>
</table>
</td></tr></table>

<!-- ==================================================================================================
-- MESSAGE LIST COLUMNS
================================================================================================== -->
<a name='listcolumns' class='SM_anchor'></a>
<table class='cc800' style='margin-bottom:21px;'>
<tr><td class='b1' style='padding-left:14px;'><?php echo $fbtc_top;?>Message List Columns</td></tr>
<tr><td class='b2' style='padding:0px;'>
<table class='cc100' style='margin:7px 0px 14px 14px;'>
<tr><td class='pad7' colspan='2'><span class='option_notes'>Check the columns you want to display on your messages list.</span></td></tr>
<?php 
FBTC_checkbox("Sender's Name", $errorListName, "list_name", $list_name, "");
FBTC_checkbox("Sender's E-mail", $errorListEmail, "list_email", $list_email, "");
FBTC_checkbox("Sender's Phone", $errorListPhone, "list_phone", $list_phone, "");
FBTC_checkbox("Date Sent", $errorListDate, "list_date", $list_date, "");
FBTC_checkbox("Date Read", $errorListRead, "list_read_on_date", $list_read_on_date, "");
FBTC_checkbox("Content Brief", $errorListBrief, "list_content", $list_content, "");
FBTC_checkbox("Sender's IP Address", $errorListIP, "list_ip", $list_ip, "");
?>
<tr><td class='label150'>&nbsp;</td><td class='pad7'><input type="submit" name="button" id="button" value="Save Changes" /></td></tr>
</table>
</td></tr></table>
</form>
<?php FBTC_foot();?>