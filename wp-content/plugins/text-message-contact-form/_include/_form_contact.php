<?php
// $wpdb->show_errors();
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ($_SERVER['REQUEST_METHOD']=='POST'){
	$check=$_POST['check'];	// users input
	$word=$_POST['word']; // generated word
	$errorMessage="";
	$senders_name=sanitize_text_field($_POST['senders_name']);
	$senders_email=sanitize_email($_POST['senders_email']);
	$senders_email2=sanitize_email($_POST['senders_email2']);
	$senders_content=sanitize_text_field($_POST['senders_content']);
	$senders_phone=sanitize_text_field($_POST['senders_phone']);
	$senders_ip=sanitize_text_field($_SERVER['REMOTE_ADDR']);
	$showdate=FBTC_apt(sanitize_text_field(FBTC_ts()));
	$senders_date=sanitize_text_field(FBTC_ts());

	//--checks
	if($require_test==1 && $check==""){$errorMessage="check"; $errorCapture=1;}else if($check!="" && $check!=$word){$errorMessage='check';$errorCapture=1;} // capture
	if($require_test==1 && $check!=$word){$errorMessage="check"; $errorCapture=1;}else if($check!="" && $check!=$word){$errorMessage='check';$errorCapture=1;} // capture
	if($require_email_verify==1 && ($senders_email2!=$senders_email || $senders_email2=="")){$errorMessage="verify email"; $errorConfirm=1;}
	if($require_email==1 && is_email($senders_email)==false){$errorMessage="email not vaild"; $errorValid=1; $errorEmail=1;}

	if($require_email==1 && $senders_email==""){$errorMessage="email"; $errorEmail=1;}
	if($require_name==1 && $senders_name==""){$errorMessage="name"; $errorName=1;}
	if($require_phone==1 && $senders_phone==""){$errorMessage="phone"; $errorPhone=1;}
	if($require_message==1 && $senders_content==""){$errorMessage="message"; $errorContent=1;}

	if($errorMessage=="" && $re_submitted==""){
		//-- convert null varibles to na
		if($senders_name==""){$senders_name="na";}
		if($senders_email==""){$senders_email="na";}
		if($senders_email2==""){$senders_email2="na";}
		if($senders_content==""){$senders_content="na";}
		if($senders_phone==""){$senders_phone="na";}
		if($senders_ip==""){$senders_ip="na";}

		//-- email body
		$bodyData="<table width='100%'>
		<tr><td class='label150'>".$email_label_name."</td><td class='pad7'>".$senders_name."</td></tr>
		<tr><td class='label150'>".$email_label_email."</td><td class='pad7'>".$senders_email."</td></tr>
		<tr><td class='label150'>".$email_label_date."</td><td class='pad7'>".$showdate."</td></tr>
		<tr><td class='label150'>".$email_label_phone."</td><td class='pad7'>".$senders_phone."</td></tr>
		<tr><td class='label150'>".$email_label_message."</td><td class='pad7'>".$senders_content."</td></tr>
		<tr><td colspan='2' style='border-top:1px dotted #666;'>&nbsp;</td></tr>
		</table>";

		//-- send the email
		$send_email=FBTC_emailIt($to, $senders_email, $bodyData);

		if($send_email==false){
			FBTC_error($error_email_failed);
		}else{
			//-- encode before saving
			$senders_name=FBTC_e($senders_name);
			$senders_email=FBTC_e($senders_email);
			$senders_email2=FBTC_e($senders_email2);
			$senders_content=FBTC_e($senders_content);
			$senders_phone=FBTC_e($senders_phone);
			$senders_ip=FBTC_e($senders_ip);

			//-- save in database
			$code=FBTC_code();
			$saveIt=$wpdb->insert("fbtc_messages", 
						array(
						"code"=>$code, 
						"ip"=>$senders_ip, 
						"name"=>$senders_name, 
						"email"=>$senders_email, 
						"phone"=>$senders_phone, 
						"date"=>$senders_date, 
						"read_on_date"=>"",
						"content"=>$senders_content, 
						"live"=>'1' // last field - no comma dumbass!!!
						)
					);
			if(!$saveIt){
				FBTC_error($error_saving);
			}else{
				//-- if it's saved successfully AND send texts is yes - then send the text message
				if($send_texts==1){
					$send_to=$phone.$path;
					$text_msg_content=FBTC_d($senders_content);
					$text_msg_content=str_replace("<br />", "", $text_msg_content);
					$final_text_msg_content=FBTC_d($senders_email).": ".$text_msg_content;
					$final_text_msg_content=FBTC_d(FBTC_char_lim($final_text_msg_content, 140, " [more...]"));

					$saveIt=mail($send_to, "", $final_text_msg_content, null, "-f$admin_email");
					if(!$saveIt){
						//-- dont do anything if text msg fails.
					}else{
						FBTC_success($success_email_sent);
						echo "<div style='text-align:left;'>".$success_message_final."</div>"; //-- custom success message here
						$yay_success=1;
					}
				//-- texting is turned off, just display success
				}else{
					FBTC_success($success_email_sent);
					echo "<div style='text-align:left;'>".$success_message_final."</div>"; //-- custom success message here
					$yay_success=1;
				}
			}
		}
	}
}

// -- check if the form was sent, block form from showing after success
// echo "Error: ".$errorMessage;

if($yay_success!=1){

echo "<form id='fbtc_contact_form' name='fbtc_contact_form' method='post' action=''>";
echo "<div style='text-align:left;'>";
if($errorMessage!=""){ echo "<span class='redText' style='font-size:21px;'>".$error_incomplete."<br><br></span>";}

//////////////////////////////////////////////////////////////////////////////////////////////////
//------- NAME
//////////////////////////////////////////////////////////////////////////////////////////////////
if($require_name==1){ 
	FBTC_check_text($field_name, $errorName); 
	echo "<br />";
	echo "<input name='senders_name' type='text' value='".$senders_name."' class='form_textfield' style='width:400px;'/>";
	echo "<br /><br />";
} 

//////////////////////////////////////////////////////////////////////////////////////////////////
//------- EMAIL
//////////////////////////////////////////////////////////////////////////////////////////////////
if($require_email==1){ 
	FBTC_check_text($field_email, $errorEmail); 
	echo "<br />";
	echo "<input name='senders_email' type='text' value='".$senders_email."' class='form_textfield' />";
	echo "<br />";
	if($errorValid==1){echo "<span style='color:#f00; margin-top:7px; font-size:12px;' >".$error_invalid_email."</span><br /><br />";
	}else{echo "<br />";} 
}

//////////////////////////////////////////////////////////////////////////////////////////////////
//------- VERIFY
//////////////////////////////////////////////////////////////////////////////////////////////////
if($require_email_verify==1){ 
	FBTC_check_text($field_email_verify, $errorConfirm);
	echo "<br />";
	echo "<input name='senders_email2' type='text' value='".$senders_email2."' class='form_textfield' />";
	echo "<br /><br />";
} ?>

<?php 
//////////////////////////////////////////////////////////////////////////////////////////////////
//------- PHONE
//////////////////////////////////////////////////////////////////////////////////////////////////
if($require_phone==1){ 
	FBTC_check_text($field_phone, $errorPhone);
	echo "<br />";
	echo "<input name='senders_phone' type='text' value='".$senders_phone."' class='form_textfield'/>";
	echo "<br /><br />";
} 

//////////////////////////////////////////////////////////////////////////////////////////////////
//------- MESSAGE
//////////////////////////////////////////////////////////////////////////////////////////////////
if($require_message==1){ 
	FBTC_check_text($field_message, $errorContent);
	echo "<br />";
	echo "<textarea name='senders_content' rows='5' class='form_area'>".$senders_content."</textarea>";
	echo "<br /><br />";
}

//////////////////////////////////////////////////////////////////////////////////////////////////
//------- TEST CHECK
//////////////////////////////////////////////////////////////////////////////////////////////////
if($require_test==1){
$word="";
$wordlen=rand(5,7);
?>
<table width='80%'><tr>
<!-- LEFT SIDE GENERATE LETTERS -->
<td style="border:2px solid #666; background-color:#fff; background-image:url('<?php echo plugin_dir_url(dirname( __FILE__) );?>_cap_lets/cap_back.png'); vertical-align:top; <?php echo $round;?>">
<table width='100%'><tr>
<?php
//------- RANDOMIZE LETTERS
for($x=1; $x<$wordlen; $x++){
	$len = 1;
	$base='bcdfghjklmnpqrstvwxz';
	$max=strlen($base)-1;
	$letter='';
	mt_srand((double)microtime()*1000000);
	while (strlen($letter)<$len)$letter.=$base{mt_rand(0,$max)};
	echo "<td style='width:1%; border:0px;'><img src='".plugin_dir_url(dirname( __FILE__) )."/_cap_lets/".$letter.".png' style='border:0px;'></td>";
	$word=$word.$letter;
}
?>
</tr></table>
</td>

<!-- RIGHT SIDE -->
<td style='padding-left:14px; padding-top:7px;'>
<?php FBTC_check_text($field_enter_letters, $errorCapture)?>
<input name='word' type='hidden' id='word' value='<?php echo $word; ?>' />
<p><input name='check' type='text' id='check' size='14' maxlength='10' class='form_textfield_cap'/></p>
</td></tr></table>
<?php } ?>

<input type="submit" name="button" id="" value="<?php echo $field_send_button_text;?>"/>
</div>
</form>
<?php FBTC_foot(); 
} // -- end success check.
?>