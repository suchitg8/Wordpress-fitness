<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//////////////////////////////////////////////////////////////////////////////////////////////////
//=======  Coding and decoding
//////////////////////////////////////////////////////////////////////////////////////////////////
function FBTC_br2nl($string){return preg_replace('/\<br(\s*)?\/?\>/i', " ", $string);} 
if(!function_exists('FBTC_d')){function FBTC_d($DBvar){return stripslashes(urldecode(esc_html($DBvar)));}}
if(!function_exists('FBTC_e')){function FBTC_e($DBvar){return urlencode(nl2br(trim(sanitize_text_field($DBvar))));}}
if(!function_exists('FBTC_dcontent')){function FBTC_dcontent($DBvar){return str_replace("<br />", "", stripslashes(urldecode($DBvar)));}}

function FBTC_total($query){
	global $wpdb;
	$countIt=$wpdb->get_results("$query");
	$total=count($countIt);	
	return $total;
}

function FBTC_result($query){
	global $wpdb;
	$result=$wpdb->get_results("$query", ARRAY_A);
	return $result;
}

function FBTC_read_btn($text, $url, $total, $width){
	if($text=="Unread"){$div_class="navRed";}else{$div_class="navb1";}
	if($text=="Home"){
		echo "<div class='".$div_class."' style='width:".$width."px; float:right; margin-right:0px; text-align:center;'><a href='".$url."' class='fbtc-menu'>".$text."</a></div>";	
	}else if($_GET['l']!=strtolower($text)){
		if($total>0){
			echo "<div class='".$div_class."' style='width:".$width."px; float:right; text-align:center; margin-right:7px;'><a href='".$url."' class='fbtc-menu'>".$text.": ".$total."</a></div>";
		}else{
			echo "<div class='gEmpty' style='width:".$width."px; float:right; text-align:center; font-size:12px; margin-right:7px;'>".$text.": ".$total."</div>";
		}
	}else{
		echo "<div class='read-page-selected' style='width:".$width."px; float:right; text-align:center; font-size:12px; margin-right:7px;'>".$text.": ".$total."</div>";
	}
}

function FBTC_checkbox($text, $check, $var_name, $current_value, $notes){
	if($check==1){$text="<span class='redText'><b>".$text."</span>";}else{$text="<b>".$text."</b>";}
	echo "<tr><td style='width:25px; padding:7px; text-align:right;'>";
	echo "<input type='hidden' id='".$var_name."_hide' name='".$var_name."' value='0'>";
	echo "<input name='".$var_name."' type='checkbox' id='".$var_name."' value='1' ";
	if ($current_value==1) {echo "checked='checked'";}
	echo "/></td>";
	echo "<td class='pad7'>";
	echo "<label for='".$var_name."'>".$text."</label>";
	echo "</td></tr>";
	if($notes!=""){
		echo "<tr><td style='padding:0px 14px 14px 7px;'>&nbsp;</td><td style='padding:0px 14px 14px 7px;'>";
		echo "<span class='smallG'>".$notes."</span>";
		echo "</td></tr>";
	}
}

function FBTC_textfield($text, $check, $var_name, $current_value, $notes){
	echo "<tr><td class='label150'>";
	FBTC_check_text($text, $check);
    echo "</td>";
	echo "<td class='pad7'>";
	echo "<input name='".$var_name."' type='text' class='form_textfield' id='".$var_name."' value='".FBTC_d($current_value)."' maxlength='200'/>";
	echo "</td></tr>";
	if($notes!=""){
		echo "<tr><td style='padding:0px 14px 7px 14px;'>&nbsp;</td><td style='padding:0px 14px 7px 14px;'>";
		echo "<span class='smallG'>".$notes."</span>";
		echo "</td></tr>";		
	}
}

if(!function_exists('FBTC_invalid_email')){function FBTC_invalid_email($errorValidEmail){
	if($errorValidEmail=="y"){
		echo "<tr><td class='label200'>&nbsp;</td><td class='pad7' style='padding-top:0px;'><span class='smallRed'>This is not a valid e-mail address</span></td></tr>";
	}
}}

function FBTC_textarea($text, $check, $var_name, $current_value, $notes){
	echo "<tr><td class='label150'>";
	FBTC_check_text($text, $check);
    echo "</td>";
	echo "<td class='pad7'>";
	echo "<textarea name='".$var_name."' id='".$var_name."' rows='5' class='form_area'>".FBTC_dcontent($current_value)."</textarea>";
	echo "</td></tr>";
	if($notes!=""){
		echo "<tr><td class='pad7'>&nbsp;</td><td style='padding:0px 14px 14px 7px;'>";
		echo "<span class='smallG'>".$notes."</span>";
		echo "</td></tr>";		
	}
}

//==================================================================================================
//-- function takes array and sorts by key
//==================================================================================================
function FBTC_sort_array($array){
	//-- SORT the array
	$sortArray=array(); 
	foreach($array as $ind){
		foreach($ind as $key=>$value){ 
			if(!isset($sortArray[$key])){ 
				$sortArray[$key] = array(); 
			}
			$sortArray[$key][]= $value; 
		} 
	}
	//-- order the list by key, default is name if the key is not found
	$orderby=$_GET['o'];

	if(!array_key_exists($orderby, $sortArray)){$orderby="senders_date";}

	//-- ascending or descending list? default is ascending
	if($_GET['ad']=="a"){
		array_multisort($sortArray[$orderby],SORT_ASC,$array);
	}else{
		array_multisort($sortArray[$orderby],SORT_DESC,$array);
	}
	return $array;
}

function FBTC_detail_message(){
	if($_GET['detail']!=""){
		$message_code=FBTC_e($_GET['detail']);
		$total=FBTC_total("SELECT * FROM fbtc_messages WHERE code='$message_code' LIMIT 1 ");
		if($total>0){
			global $wpdb; global $fbtc_admin; global $round; global $fbtc_btns_dir;

			$new_read_on_date=FBTC_ts();
			$wpdb->update("fbtc_messages", array('read_on_date'=>$new_read_on_date), array("code"=>$message_code, "read_on_date"=>""));

			include(plugin_dir_path( __FILE__ ) . "fbtc-settings.php"); //  load user defined settings
			
			$result=FBTC_result("SELECT * FROM fbtc_messages WHERE code='$message_code' LIMIT 1");
			foreach($result as $row){
				$message_code=$row['code'];
				$senders_ip=$row['ip'];
				$senders_name=FBTC_d($row['name']);
				$senders_email=FBTC_d($row['email']);
				$senders_phone=FBTC_d($row['phone']);
				$senders_date=FBTC_d($row['date']);
				$read_on_date=FBTC_d($row['read_on_date']);
				$senders_content=FBTC_d($row['content']);
				$live=FBTC_d($row['live']);
			}

			if($live==1){
				$op="disable";
				$op_text="Move to Trash";
				$op_img="btn_delete16_reg.png";
			}else{
				$op="enable";
				$op_text="Move to Inbox";
				$op_img="btn_enable16_reg.png";
			}
			FBTC_title($senders_name, "btn_page32_reg.png", $fbtc_admin."&amp;v=read&amp;detail=".$_GET['detail']);
			echo "<table class='cc800'>";
			echo "<tr><td class='b1' style='padding:2px; text-align:center;'>";
			
			echo "<table class='cc100'><tr>";

			//-- back to list
			echo "<td class='nopad' style='width:15%; text-align:left;'>";
			echo "<div class='navb1' style='width:125px; float:left; text-align:center;'><a href='".$fbtc_admin."&amp;v=read&amp;l=".$_GET['l']."&' class='fbtc-menu'>";
			echo "<img src='".$fbtc_btns_dir."btn_list16_reg.png' class='btn'>Back to List</a></div>";
			echo "</td>";

			//-- open email client
			if($senders_email!=""){
				echo "<td class='nopad' style='width:15%; text-align:left;'>";
				echo "<div class='navb1' style='width:125px; float:left; text-align:center;'><a href='mailto:".$senders_email."' class='fbtc-menu'>";
				echo "<img src='".$fbtc_btns_dir."btn_sendmail16_reg.png' class='btn'>Send Reply</a></div>";
				echo "</td>";
			}

			//-- nothing
			echo "<td class='nopad' style='width:53%; text-align:center;'>";
			echo "<span style='color:#fff; font-size:10; font-weight:normal;'>Read: ".FBTC_apt($read_on_date)."&nbsp;</span>";
			echo "</td>";

			//-- delete or inbox
			echo "<td class='nopad' style='width:15%; text-align:right;'>";
			echo "<div class='navb1' style='width:125px; float:left; text-align:center;'>";
			echo "<a href='".$fbtc_admin."&amp;v=read&op=".$op."&l=".$_GET['l']."&wo=".$_GET['detail']."&' class='fbtc-menu'>";
			echo "<img src='".$fbtc_btns_dir.$op_img."' class='btn'>".$op_text."</a></div>";
			echo "</td>";

			//-- print
			echo "<td class='nopad' style='width:2%; text-align:right;'>";
			echo "<div class='navb1' style='width:125px; float:left; text-align:center;'>";
			echo "<a href='#' onClick='window.print();return false' title='Print this Page' class='fbtc-menu'>";
			echo "<img src='".$fbtc_btns_dir."btn_print16_reg.png' class='btn'/>Print</a></div>";
			echo "</td>";

			echo "</tr></table>";
			echo "</td></tr>";

			//-- create a link to open the email client
			if($senders_email!="" && $senders_email!="na"){$senders_email="<a href='mailto:".$senders_email."'>".$senders_email."</a>";}

			echo "<tr><td class='b2' style='padding:14px 14px; '>";
			echo "<table class='cc800'>";
			echo "<tr><td class='label150'>".$email_label_name."</td><td class='pad7'>".$senders_name."</td></tr>";
			echo "<tr><td class='label150'>".$email_label_email."</td><td class='pad7'>".$senders_email."</td></tr>";
			echo "<tr><td class='label150'>".$email_label_phone."</td><td class='pad7'>".$senders_phone."</td></tr>";
			echo "<tr><td class='label150'>".$email_label_date."</td><td class='pad7'>".FBTC_apt($senders_date)."</td></tr>";
			echo "<tr><td class='label150top'>".$email_label_message."</td><td class='pad7'>".$senders_content."</td></tr>";
			echo "</table>";
			echo "</td></tr></table>";
			echo "<br>";
		}
	}
}

function FBTC_col_data($col_name, $url, $var, $col_css){
	if($url!=""){$data="<div class='navListItem'><a href='".$url.">".$var."</a></div>";}
	echo "<td class='".$col_css."'>";
	echo $data;
	echo "</td>";
}

function FBTC_col_tab($text, $img, $url_order, $class, $switch_asc_dsc, $width){
	global $fbtc_btns_dir; global $fbtc_admin;
	$this_page=$fbtc_admin."&v=".$_GET['v']."&l=".$_GET['l'];
	if($_GET['ad']=="d"){
		$arrow="<img src='".$fbtc_btns_dir."btn_downG16_reg.png' style='border:0px; vertical-align:middle; float:right;'>";
	}else{
		$arrow="<img src='".$fbtc_btns_dir."btn_upG16_reg.png' style='border:0px; vertical-align:middle; float:right;'>";
	}

	if($text==""){$img_style=" style='border:0px; vertical-align:middle;' ";}else{$img_style=" class='btn' ";}
	if($img!=""){$img="<img src='".$fbtc_btns_dir.$img."' ".$img_style.">"; $center=" text-align:center; ";}
	echo "<td class='".$class."' style='padding:0px; width:".$width."px; ".$center." font-size:16px;'>";
	echo "<div class='navColumnTop'><a href='".$this_page."&o=".$url_order."".$switch_asc_dsc."'>".$arrow.$img.$text."</a></div>";
	echo "</td>";
}

function FBTC_list_data($var, $include, $url, $title, $css){
	if($include==1){
		echo "<td class='".$css."' style='padding-left:0px;'>";
		echo "<div class='navListItem'><a href='".$url."' title='".$title."'>".$var."</a></div>";
		echo "</td>";
	}
}

function FBTC_list_messages($func_status, $this_page){
	global $fbtc_btns_dir; global $round;
	include(plugin_dir_path( __FILE__ ) . "fbtc-settings.php"); //  load user defined settings
	//-- set some variables based $func_status variable
	if($func_status=="Unread"){
		$DBstatus=" WHERE live='1' AND read_on_date='' ";
		$banner_img="<img src='".$fbtc_btns_dir."btn_ex16_reg.png' class='btn'>";
		$msg_location="Unread";
		$msg_color="F00";
	}else if($func_status=="Inbox"){
		$DBstatus=" WHERE live='1' AND read_on_date!='' ";
		$msg_location="Inbox";
		$banner_img="<img src='".$fbtc_btns_dir."btn_enable16_reg.png' class='btn'>";
		$msg_color="000";
	}else{
		$DBstatus=" WHERE live!='1' ";
		$banner_img="<img src='".$fbtc_btns_dir."btn_delete16_reg.png' class='btn'>";
		$msg_location="Trash";
		$msg_color="000";
	}

	//-- get the total # of messages base on $DBstatus variable set above
	$total=FBTC_total("SELECT * FROM fbtc_messages $DBstatus ");

	//-- if there's more that 0 then start to create a list
	if($total>0){
		//-- determine the order of the list
		$order=$_GET['o'];
		if($order=="senders_name"){
			$order_text="name";
		}else if($order=="senders_date"){
			$order_text="date";
		}else if($order=="senders_email"){
			$order_text="e-mail";
		}else{
			$order_text="date";
		}
		//-- ascending or descending ?
		if($_GET['ad']=="d"){
			$switch_asc_dsc="&ad=a&";
			$asc_dsc_text="in ascending order";
		}else{
			$switch_asc_dsc="&ad=d&";
			$asc_dsc_text="in descending order";
		}

		//-- description area 
		if($total==1){$msg_word="Message";}else{$msg_word="Messages";}
		echo "<table class='cc800' style='margin-bottom:7px;'><tr><td class='pad7'>";
		echo "<span style='color:#".$msg_color."; font-size:18px;'><b>".$banner_img.$msg_location.": </b>".$total."</span>";
		echo "<span style='color:#888;'> - listed by ".$order_text." </span>";
		echo "</td></tr></table>";

		//-- create colum headers from options
		echo "<table class='cc800'><tr>";
		$cols_array=array();
		if($list_name==1){$individual=array("col_name"=>"Name", "col_order"=>"senders_name"); array_push($cols_array, $individual);}
		if($list_email==1){$individual=array("col_name"=>"E-mail", "col_order"=>"senders_email"); array_push($cols_array, $individual);}
		if($list_phone==1){$individual=array("col_name"=>"Phone", "col_order"=>"senders_phone"); array_push($cols_array, $individual);}
		if($list_date==1){$individual=array("col_name"=>"Date", "col_order"=>"senders_date"); array_push($cols_array, $individual);}
		if($list_read_on_date==1){$individual=array("col_name"=>"Read", "col_order"=>"read_on_date"); array_push($cols_array, $individual);}
		if($list_ip==1){$individual=array("col_name"=>"IP", "col_order"=>"senders_ip"); array_push($cols_array, $individual);}
		if($list_content==1){$individual=array("col_name"=>"Content", "col_order"=>"senders_content"); array_push($cols_array, $individual);}
		$total=count($cols_array);
		for($x=0; $x<$total; $x++){
			if($x==0){$col_css="tab-left";}else{$col_css="tab";}
			$this_ar=$cols_array[$x];
			$col_name=$this_ar['col_name'];
			$col_order=$this_ar['col_order'];
			FBTC_col_tab($col_name, "", $col_order, $col_css, $switch_asc_dsc, 250);
		}
		echo "<td colspan='2' width='50px' class='tab-right'></td>"; // actions column
		echo "</tr>";

		//-- populate array from database
		$list_array=array();
		$result=FBTC_result("SELECT * FROM fbtc_messages $DBstatus");
		foreach($result as $row){
			$message_code=$row['code'];
			$senders_ip=$row['ip'];
			$senders_name=FBTC_d($row['name']);
			$senders_email=FBTC_d($row['email']);
			$senders_phone=FBTC_d($row['phone']);
			$senders_date=FBTC_d($row['date']);
			$read_on_date=FBTC_d($row['read_on_date']);
			$senders_content=FBTC_char_lim(FBTC_d($row['content']), 35);

			$individual=array(
				"code"=>$message_code, 
				"senders_ip"=>$senders_ip, 
				"senders_name"=>$senders_name, 
				"senders_email"=>$senders_email, 
				"senders_phone"=>$senders_phone, 
				"senders_date"=>$senders_date, 
				"read_on_date"=>$read_on_date, 
				"senders_content"=>$senders_content 
				);
			array_push($list_array, $individual);
		}

		//-- sort the array
		if($list_array!=""){$list_array=FBTC_sort_array($list_array);}

		//-- list the data
		$total=count($list_array);
		for($x=0; $x<$total; $x++){
			$this_ar=$list_array[$x];
			$code=$this_ar['code'];
			$ip=$this_ar['senders_ip'];
			$name=$this_ar['senders_name'];
			$email=$this_ar['senders_email'];
			$phone=$this_ar['senders_phone'];
			$date=$this_ar['senders_date'];
			$read_on_date=$this_ar['read_on_date'];
			$content=$this_ar['senders_content'];

			if($read_on_date==""){$name="<b>".$name."</b>";}

			$stagger=FBTC_stagger($stagger);

			//-- data
			FBTC_list_data($name, $list_name, $this_page."&detail=".$code."&", "Open", "list_left");
			FBTC_list_data($email, $list_email, "mailto:".$email, "Reply", "list_center");
			FBTC_list_data($phone, $list_phone, $this_page."&detail=".$code."&", "title", "list_center");
			FBTC_list_data(FBTC_apt_abv($date), $list_date, $this_page."&detail=".$code."&", "title", "list_center");
			FBTC_list_data(FBTC_apt_abv($read_on_date), $list_read_on_date, $this_page."&detail=".$code."&", "title", "list_center");
			FBTC_list_data($ip, $list_ip, $this_page."&detail=".$code."&", "title", "list_center");
			FBTC_list_data($content, $list_content, $this_page."&detail=".$code."&", "title", "list_center");

			//======= ACTIONS
			//-- move to trash
			if($func_status=="Inbox" || $func_status=="Unread"){
				echo "<td class='list_right' style='text-align:center; padding:0px;'>";
				echo "<div class='navListAction'><a href='".$this_page."&op=disable&wo=".$code."&' title='Move to Trash'>";
				echo "<img src='".$fbtc_btns_dir."btn_delete16_reg.png' style='border:0px'>";
				echo "</a></div></td>";
			//-- move to inbox
			}else{
				echo "<td class='list_right' style='text-align:center; padding:0px;'>";
				echo "<div class='navListAction'><a href='".$this_page."&op=enable&wo=".$code."&' title='Move to Inbox'>";
				echo "<img src='".$fbtc_btns_dir."btn_enable16_reg.png' style='border:0px'>";
				echo "</a></td>";
			}
			echo "</tr>";
		}
		echo "<tr><td class='list_bottom' colspan='7'>&nbsp;</td></tr>";
		echo "</table>";

		//-- purge - only after the trash list
		if($func_status=="Trashed"){
			echo "<div class='navPurge' style='width:250px; text-align:center; margin-bottom:14px;'><a href='".$this_page."&op=purge&'>";
			echo "<img src='".$fbtc_btns_dir."btn_purge16_reg.png' class='btn'>Purge All Trashed Messages";
			echo "</a></div>";
		}
	//-- theres nothing to show
	}else{
		echo "<div style='background-color:#e9e9e9; padding:7px; color:#666; text-align:center; margin-top:14px; margin-bottom:14px; width:800px;".$round."'>You have no ".strtolower($func_status)." messages.</div>";
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////////
//-- Check text
//////////////////////////////////////////////////////////////////////////////////////////////////
if(!function_exists('FBTC_check_text')){function FBTC_check_text($text, $check){if($check==1){echo "<span class='redText'>".$text."</span>";}else{echo "<b>".$text."</b>";}}}

function FBTC_home_btn($text, $img, $url){
	echo "<div class='navMenuHome'>";
	echo "<a href='".$url."' style='font-size:21px;'>";
	echo "<img src='".$img."' style='border:0px; margin-bottom:7px;' />";
	echo "<br />";
	echo $text;
	echo "</a></div>";
}

//==================================================================================================
// -- enable disable
//==================================================================================================
function FBTC_enable_disable($table, $code, $redirect_link){
	global $wpdb; global $fbtc_admin;
	if($_GET['op']!="" && ($_GET['op']=="disable" || $_GET['op']=="enable")){
		$code=FBTC_e($code);
		$op=$_GET['op'];
		if($op=="disable"){
			$the_action=FBTC_e(0);
			$display_op="Moved to Trash";
		}else if($op=="enable"){
			$the_action=FBTC_e(1);
			$display_op="Moved to Inbox";
		}
		if($op=="disable" || $op=='enable'){
			$saveIt=$wpdb->update($table, array('live'=>$the_action), array('code'=>$code));
			if(!$saveIt){
				FBTC_redBox("Error, could not save changes.", 800, 21);
			}else{
				FBTC_greenBox($display_op." Successfully!", 800, 21);
				FBTC_redirect($redirect_link, 500);
				die();
			}
		}
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////////
//-- limit the number of characters in a string
//////////////////////////////////////////////////////////////////////////////////////////////////
if(!function_exists('FBTC_char_lim')){function FBTC_char_lim($str, $n, $end_char = '&#8230;'){
	if (strlen($str) < $n){return $str;}
	$str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));
	if (strlen($str) <= $n){return $str;}
	$out = "";
	foreach (explode(' ', trim($str)) as $val){
		$out .= $val.' ';
		if (strlen($out) >= $n){
			$out = trim($out);
			return (strlen($out) == strlen($str)) ? $out : $out.$end_char;
		}       
	}
}}

//////////////////////////////////////////////////////////////////////////////////////////////////
//-- date functions
//////////////////////////////////////////////////////////////////////////////////////////////////
if(!function_exists('FBTC_ts')){function FBTC_ts(){$date=strtotime(date("Y-m-d H:i.s")); return $date;}}
if(!function_exists('FBTC_timestamp')){function FBTC_timestamp($timeDateVar){return strtotime($timeDateVar);}}
if(!function_exists('FBTC_timestring')){function FBTC_timestring($timeStringVar){return date("Y-m-d-H-i-s", $timeStringVar);}}
if(!function_exists('FBTC_apt')){function FBTC_apt($APTtimestamp){if($APTtimestamp!=""){return date("l, F d, Y, g:i a", $APTtimestamp);}}}
if(!function_exists('FBTC_apt_abv')){function FBTC_apt_abv($APTtimestamp){if($APTtimestamp!=""){return date("D, M d, Y, g:i a", $APTtimestamp);}}}
if(!function_exists('FBTC_aptShort')){function FBTC_aptShort($APTtimestamp){if($APTtimestamp!=""){return date("M d, Y, g:i a", $APTtimestamp);}}}
if(!function_exists('FBTC_dateText')){function FBTC_dateText($dateTimestamp){return date("l, F d, Y", $dateTimestamp);}}
if(!function_exists('FBTC_dateTextShort')){function FBTC_dateTextShort($dateTimestamp){if($dateTimestamp!=""){return date("M d, Y", $dateTimestamp);}}}
if(!function_exists('FBTC_timeText')){function FBTC_timeText($timeVar){return date("g:i a", $timeVar);}}
if(!function_exists('FBTC_timeText_noampm')){function FBTC_timeText_noampm($timeVar){return date("g:i", $timeVar);}}
if(!function_exists('FBTC_timeText')){function FBTC_timeHour($timeVar){return date("g", $timeVar);}}
if(!function_exists('FBTC_birthdayText')){function FBTC_birthdayText($dateTimestamp){if($dateTimestamp!=""){return date("F d, Y", $dateTimestamp);}}}
if(!function_exists('FBTC_dateNum')){function FBTC_dateNum($dateTimestamp){if($dateTimestamp!=""){return date("m/d/Y", $dateTimestamp);}}}
if(!function_exists('FBTC_dateNumTime')){function FBTC_dateNumTime($dateTimestamp){if($dateTimestamp!=""){return date("m/d/Y h:i a", $dateTimestamp);}}}

//////////////////////////////////////////////////////////////////////////////////////////////////
//-- gets ip address
//////////////////////////////////////////////////////////////////////////////////////////////////
if(!function_exists('FBTC_ip')){function FBTC_ip(){
	$ip=$_SERVER['REMOTE_ADDR']; 
	if($ip==""){$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];}
	if($ip==""){$ip="No IP";}
	return $ip;
}}

//////////////////////////////////////////////////////////////////////////////////////////////////
//======= Stagger the TR tag
//////////////////////////////////////////////////////////////////////////////////////////////////
if(!function_exists('FBTC_stagger')){function FBTC_stagger($stagger){
	global $b2_color;
	if($stagger==""){echo "<tr style='background-color:#".$b2_color.";'>"; $stagger=1;}else{echo "<tr class='stagger'>"; $stagger="";}
	return $stagger;
}}


//////////////////////////////////////////////////////////////////////////////////////////////////
//-- title to go at top of pages
//////////////////////////////////////////////////////////////////////////////////////////////////
if(!function_exists('FBTC_title')){function FBTC_title($text, $img, $link){
	$FBTC_btns_dir=plugin_dir_url(dirname( __FILE__) )."/_btns/";
	if($link!=""){
		echo "<table class='cc800'><tr><td class='pad7' style='vertical-align:middle; text-align:left;'><a href='".$link."' class='header'><img src='".$FBTC_btns_dir.$img."' class='btn'>".$text."</a></td></tr></table>";
	}else{
		echo "<table class='cc800'><tr><td class='pad7' style='vertical-align:middle; text-align:left;'><span class='header'><img src='".$FBTC_btns_dir.$img."'  class='btn'>".$text."</span></td></tr></table>";
	}
}}



if(!function_exists('FBTC_redBox')){function FBTC_redBox($msg, $width,$redBoxFontSize){	
	if(wp_is_mobile() || $width=="100%"){$width="100%";}else{$width=$width."px";}
	echo "<table style='width:".$width."; margin:0px; border:0px; max-width:800px; border-collapse:separate;'><tr><td class='redBox' style='text-align:center; -moz-border-radius:7px !important; -webkit-border-radius:7px !important; border-radius:7px !important; overflow:hidden !important;'><span class='redText' style='font-size:".$redBoxFontSize."px;'>".$msg."</span></td></tr></table>";
}}


if(!function_exists('FBTC_grayBox')){function FBTC_grayBox($msg, $width,$redBoxFontSize){	
	if(wp_is_mobile() || $width=="100%"){$width="100%";}else{$width=$width."px";}
	echo "<table style='width:".$width."; margin:0px; border:0px; max-width:800px; border-collapse:separate;'><tr><td class='grayBox' style='text-align:center; -moz-border-radius:7px !important; -webkit-border-radius:7px !important; border-radius:7px !important; overflow:hidden !important;'><span style='font-size:".$redBoxFontSize."px;'>".$msg."</span></td></tr></table>";
}}

if(!function_exists('FBTC_greenBox')){function FBTC_greenBox($msg, $width, $font_size){
	if(wp_is_mobile() || $width=="100%"){$width="100%";}else{$width=$width."px";}
	echo "<table style='width:".$width."; margin:0px; border:0px; max-width:800px; border-collapse:separate;'><tr><td class='greenBox' style='text-align:center; -moz-border-radius:7px !important; -webkit-border-radius:7px !important; border-radius:7px !important; overflow:hidden !important;'><span class='greenText' style='font-size:".$font_size."px;'>".$msg."</span></td></tr></table>";
}}

if(!function_exists('FBTC_blueBox')){function FBTC_blueBox($msg, $width, $fontSize){
	if(wp_is_mobile() || $width=="100%"){$width="100%";}else{$width=$width."px";}
	echo "<table style='width:".$width."; margin:0px; border:0px; max-width:800px; padding:0px; border-collapse:separate;'><tr><td class='blueBox' ><span style='font-size:".$fontSize."px; font-weight:bold; color:#06F;'>".$msg."</span></td></tr></table>";
}}

if(!function_exists('FBTC_orangeBox')){function FBTC_orangeBox($msg, $width, $fontSize){	
	if(wp_is_mobile() || $width=="100%"){$width="100%";}else{$width=$width."px";}
	echo "<table style='width:".$width."; margin:0px; border:0px; max-width:800px; border-collapse:separate;'><tr><td class='orangeBox' align='center'><span style='font-size:".$fontSize."px'>".$msg."</span></td></tr></table>";
}}

//////////////////////////////////////////////////////////////////////////////////////////////////
//======= REDIRECT 
//////////////////////////////////////////////////////////////////////////////////////////////////
if(!function_exists('FBTC_redirect')){function FBTC_redirect($goto, $wait){
	echo "<script language='javascript'>
			function direct(){
			   window.location='".$goto."';
			   }
			   setTimeout( 'direct();', ".$wait.");
				</script>";
}}

//////////////////////////////////////////////////////////////////////////////////////////////////
//-- Create a generic code
//////////////////////////////////////////////////////////////////////////////////////////////////
if(!function_exists('FBTC_code')){function FBTC_code(){
	$codedate=date('Ymd');
	$len=10;
	$base='BCDFGHJKLMNPRSTVWXYZ';
	$max=strlen($base)-1;
	$code='';
	mt_srand((double)microtime()*1000000);
	while (strlen($code)<$len+1)
		$code.=$base{mt_rand(0,$max)
	};
	$DBcode=$codedate.$code;
	return $DBcode;
}}

if(!function_exists('FBTC_error')){function FBTC_error($text){
	echo "<div style='text-align:left; margin-bottom:14px;'><span class='redText' style='font-size:21px;'>".$text."</span></div>";
}}

if(!function_exists('FBTC_success')){function FBTC_success($text){
	echo "<div style='text-align:left; margin-bottom:14px;'><span class='greenText' style='font-size:21px;'>".$text."</span></div>";
}}

//////////////////////////////////////////////////////////////////////////////////////////////////
//-- Take the passed variables and use to send all emails
//////////////////////////////////////////////////////////////////////////////////////////////////
if(!function_exists('FBTC_emailIt')){function FBTC_emailIt($to, $from, $bodyData){
	global $b1_color; global $b2_color;
	include(plugin_dir_path( __FILE__ ) . "fbtc-settings.php");
$startData="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
	<HTML xmlns=\"http://www.w3.org/1999/xhtml\">
	<head>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
	<style type='text/css'>
	.bBox{ background-color: #D0F1FB; text-align:left; padding:7;}
	.blkBox{ background-color: #000; text-align:left; padding:5;}
	.dgBox{ background-color: #333; text-align:left; padding:5;}
	.header{font-family:Verdana; font-size:28px; color:#000; font-weight:normal;}
	.gBox{ background-color: #EFEFEF; text-align:left; padding:7;}
	.greenText {font-family:Verdana; font-size:14px; color:#009900; font-weight:bold;}
	.redText {font-family:Verdana; font-size:14px; color:#F00; font-weight:bold;}	
	.smallG{font-family:Verdana; font-size:10px; color:#666;}
	.smallText{font-family:Verdana; font-size:10px; color:#000;}
	.subhead{font-family:Verdana; font-size:18px; color:#000; font-style:italic;}
	.whiteBold{font-family:Verdana; font-size:14px; color:#FFF; font-weight:bold; }
	img.btn {border:none; margin-right:7px; vertical-align:middle;}
	td.b1{-moz-border-radius-topleft:7px; -webkit-border-top-left-radius:7px; border-top-left-radius:7px; -moz-border-radius-topright:7px; -webkit-border-top-right-radius:7px;
       border-top-right-radius:7px;	overflow:hidden; background-color:#".$b1_color."; padding:7px 7px 7px 14px; color:#fff; font-weight:bold; font-size:18px; text-align:left; border:none; }
	td.b2{background-color:#".$b2_color."; padding:10px; border:1px solid #".$b1_color."; text-align:left; -moz-border-radius-bottomleft:7px; -webkit-border-bottom-left-radius:7px;
       border-bottom-left-radius:7px; -moz-border-radius-bottomright:7px; -webkit-border-bottom-right-radius:7px; border-bottom-right-radius:7px; overflow:hidden;}
	td.label150{padding:7px; text-align:right; font-weight:bold; vertical-align:middle; width:150px;}
	td.label200{padding:7px; text-align:right; font-weight:bold; vertical-align:middle; width:200px; font-size:14px;}
	td.nopad{padding-top:0px; padding-right:0px; padding-bottom:0px; padding-left:0px;}
	td.pad7{padding:7px;}
	td.pad14{padding:14px;}
	td.greenBanner{background-color:#090; text-align:left; padding:7px; }
	td.redBanner{background-color:#C00; text-align:left; padding:7px;}
	table.cc100{border-collapse:separate; border:none; padding:0px; border-spacing:0px; width:100%;}
	table.cc800{border-collapse:separate; border:none; padding:0px; border-spacing:0px; width:800px;}
	body,td,th {font-family:Verdana; font-size:14px; color:#000;}
	body {background-color:#FFF margin-top:0px; margin-right:0px; margin-bottom:0px; margin-left:0px;}
	a:link {color: #000;text-decoration: none; font-weight: bold;}
	a:visited {text-decoration: none;color: #366; font-weight: bold;}
	a:hover {text-decoration: none;color: #0CC; font-weight: bold;}
	a:active {text-decoration: none;color: #366; font-weight: bold;}
	.navGreen a:link	{display: block;	background-color: #090; text-decoration: none; padding:7px; color:#fff;}
	.navGreen a:visited	{display: block;	background-color: #090; text-decoration: none; padding:7px; color:#fff;}
	.navGreen a:hover	{display: block;	background-color: #0C0;	text-decoration: none; padding:7px; color:#fff;}
	.navGreen a:active	{display: block;	background-color: #0C0;	text-decoration: none; padding:7px; color:#fff;}
	.navRed a:link		{height: 100%; display: block;	background-color: #900;	text-decoration: none; padding:7px; color:#fff;}
	.navRed a:visited	{height: 100%; display: block;	background-color: #900; text-decoration: none; padding:7px; color:#fff;}
	.navRed a:hover		{height: 100%; display: block;	background-color: #F03;	text-decoration: none; padding:7px; color:#fff;}
	.navRed a:active	{height: 100%; display: block;	background-color: #F03;	text-decoration: none; padding:7px; color:#fff;}
	.navBlue a:link		{height: 100%; display: block;	background-color: #09F;	text-decoration: none; padding:7px; color:#fff;}
	.navBlue a:visited	{height: 100%; display: block;	background-color: #09F; text-decoration: none; padding:7px; color:#fff;}
	.navBlue a:hover	{height: 100%; display: block;	background-color: #0CF;	text-decoration: none; padding:7px; color:#fff;}
	.navBlue a:active	{height: 100%; display: block;	background-color: #0CF;	text-decoration: none; padding:7px; color:#fff;}
	</style>
	</head>
	<BODY>
	<LEFT>";

	$endData="
	<table class='cc800'>
	<tr><td class='pad7'><span class='smallText'>Do not reply to this e-mail. This e-mail address does not receive incoming mail.</span></td></tr>
	</table>
	</LEFT></BODY></HTML>";

	$finalData=$startData.$bodyData.$endData;

	$worpress_site_name=get_bloginfo();

	//-- send a copy to original sender
	if($cc_sender==1 && $from!=""){
		$sendto="$from";
		$headers = "From: $wordpress_site_name <$admin_email> \r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\n";
		$headers .= "MIME-Version: 1.0\n";
		$success=mail($sendto, $email_subject, $finalData, $headers, "-f$admin_email");
		if(!$success){$errorMessage='sending_client';}
	}

	//-- send a copy to admins
	if($admin_email!="" && $send_emails==1){
		$sendto="$admin_email";
		$headers = "From: Contact Form <$from> \r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\n";
		$headers .= "MIME-Version: 1.0\n";
		if($cc_email!=""){$headers.= "CC: ".$cc_email."\n";}
		if($bcc_email!=""){$headers.= "BCC: ".$bcc_email."\n";}
		$success=mail($sendto, $email_subject, $finalData, $headers, "-f$admin_email");
		if(!$success){$errorMessage='sending_admin';}
	}

	if($errorMessage==""){return true;}else{return false;}
}}

//////////////////////////////////////////////////////////////////////////////////////////////////
//-- purge_check
//////////////////////////////////////////////////////////////////////////////////////////////////
if(!function_exists('FBTC_purge_check')){function FBTC_purge_check(){
	if($_GET['op']=='purge'){
		global $fbtc_admin; global $wpdb; global $fbtc_btns_dir;
		$totalPast=FBTC_total("SELECT * FROM fbtc_messages WHERE live!='1'");
		if($totalPast==1){$apt_word="message";}else{$apt_word="messages";}
		FBTC_title("Purge Trashed Messages", "btn_purge32_reg.png", "");
		?>
		<form id="form2" name="form2" method="post" action="<?php echo $fbtc_admin."&amp;v=".$_GET['v']."&amp;op=purge_confirm&amp;l=".$_GET['l']."&amp;";?>" style='margin:0px;'>
		<table style='width:800px;'>
		<tr><td class='pad7' colspan='2' style='width:66.66%;'>
		<?php FBTC_redBox("<span style='font-size:21px;'>You are about to purge ".$totalPast." ".$apt_word.".</span><br><br>This action can NOT be undone.", "100%", 16);?>
        </td>
        <td style='width:33.33%;'>&nbsp;</td></tr>

        <tr>
        <td class='pad7' style='width:33.33%; text-align:center;'>
        <div class='navb1' style='text-align:center;'>
        <a href='<?php echo $fbtc_admin."&amp;v=read&l=".$_GET['l'];?>'>
        <img src='<?php echo $fbtc_btns_dir;?>btn_cancel16_reg.png' class='btn' />Cancel - Don't Purge
        </a></div></td>

        <td style='padding:7px width:33.33%; text-align:right;'>
        <input type="submit" name="purge" id="purge" value="Purge All Trashed Messages" />
        </td>
        </tr></table>
        </form>
		<?php 
		die();
	}
}}

//////////////////////////////////////////////////////////////////////////////////////////////////
//======= purge_confirm
//////////////////////////////////////////////////////////////////////////////////////////////////
if(!function_exists('FBTC_purge_confirm')){function FBTC_purge_confirm(){
	if ($_SERVER['REQUEST_METHOD']=='POST' && $_GET['op']=='purge_confirm'){
		global $fbtc_admin; global $wpdb;
		$saveIt=$wpdb->query("DELETE FROM fbtc_messages WHERE live!='1'");
		if(!$saveIt){
			FBTC_redBox("Could not purge. Try again later.", "100%", 21);
		}else{
			FBTC_greenBox("Purged Successfully!", "100%", 21);
			FBTC_redirect($fbtc_admin."&v=read&l=inbox&", 500);
			die();
		}
	}
}}

function FBTC_select_value($show_text, $current_value, $this_value){?>
		<option value='<?php echo $this_value;?>' <?php if($current_value==$this_value){ ?> selected='selected' <?php } ?> ><?php echo $show_text;?> </option>
<?php 
}

function FBTC_path($var_name, $current_value, $errorPath){
	echo "<tr><td class='label150'>";
	FBTC_check_text("Carrier -- Path:", $errorPath);
	echo "</td>";
	echo "<td class='pad7'>";
	echo "<select name='".$var_name."' id='".$var_name."' class='form_select'>";
	echo "<option value=''>Select Carrier...</option>";
	FBTC_select_value("AT&T -- @txt.att.net", $current_value, "@txt.att.net");
	FBTC_select_value("Sprint -- @messaging.sprintpcs.com", $current_value, "@messaging.sprintpcs.com");
	FBTC_select_value("T-Mobile -- @tmomail.net", $current_value, "@tmomail.net");
	FBTC_select_value("Verizon -- @vtext.com", $current_value, "@vtext.com");
	FBTC_select_value("3 River Wireless -- @sms.3rivers.net", $current_value, "@sms.3rivers.net"); 
	FBTC_select_value("7-11 Speakout (USA GSM) -- @cingularme.com", $current_value, "@cingularme.com"); 
	FBTC_select_value("ACS Wireless -- @paging.acswireless.com", $current_value, "@paging.acswireless.com"); 
	FBTC_select_value("Advantage Communications -- @advantagepaging.com", $current_value, "@advantagepaging.com"); 
	FBTC_select_value("Airtel (Karnataka, India) -- @airtelkk.com", $current_value, "@airtelkk.com"); 
	FBTC_select_value("Airtel Wireless (Montana, USA) -- @sms.airtelmontana.com", $current_value, "@sms.airtelmontana.com"); 
	FBTC_select_value("Airtouch Pagers -- @airtouch.net", $current_value, "@airtouch.net"); 
	FBTC_select_value("Airtouch Pagers -- @airtouchpaging.com", $current_value, "@airtouchpaging.com"); 
	FBTC_select_value("Airtouch Pagers -- @alphapage.airtouch.com", $current_value, "@alphapage.airtouch.com"); 
	FBTC_select_value("Airtouch Pagers -- @myairmail.com", $current_value, "@myairmail.com"); 
	FBTC_select_value("Alaska Communications Systems -- @msg.acsalaska.com", $current_value, "@msg.acsalaska.com"); 
	FBTC_select_value("Alltel -- @message.alltel.com", $current_value, "@message.alltel.com"); 
	FBTC_select_value("Alltel PCS -- @message.alltel.com", $current_value, "@message.alltel.com"); 
	FBTC_select_value("AlphaNow -- @alphanow.net", $current_value, "@alphanow.net"); 
	FBTC_select_value("AlphNow -- @alphanow.net", $current_value, "@alphanow.net"); 
	FBTC_select_value("American Messaging -- @page.americanmessaging.net", $current_value, "@page.americanmessaging.net"); 
	FBTC_select_value("American Messaging (SBC/Ameritech) -- @page.americanmessaging.net", $current_value, "@page.americanmessaging.net"); 
	FBTC_select_value("Ameritech Clearpath -- @clearpath.acswireless.com", $current_value, "@clearpath.acswireless.com"); 
	FBTC_select_value("Ameritech Paging -- @paging.acswireless.com", $current_value, "@paging.acswireless.com"); 
	FBTC_select_value("Ameritech Paging (see also American Messaging) -- @pageapi.com", $current_value, "@pageapi.com"); 
	FBTC_select_value("Ameritech Paging (see also American Messaging) -- @mms.att.net", $current_value, "@mms.att.net"); 
	FBTC_select_value("Andhra Pradesh Airtel -- @airtelap.com", $current_value, "@airtelap.com"); 
	FBTC_select_value("Aql -- @text.aql.com", $current_value, "@text.aql.com"); 
	FBTC_select_value("Arch Pagers (PageNet) -- @archwireless.net", $current_value, "@archwireless.net"); 
	FBTC_select_value("Arch Pagers (PageNet) -- @epage.arch.com", $current_value, "@epage.arch.com"); 
	FBTC_select_value("AT&T -- @mobile.att.net", $current_value, "@mobile.att.net"); 
	FBTC_select_value("AT&T -- @txt.att.net", $current_value, "@txt.att.net"); 
	FBTC_select_value("AT&T Enterprise Paging -- @page.att.net", $current_value, "@page.att.net"); 
	FBTC_select_value("AT&T PCS -- @mobile.att.net", $current_value, "@mobile.att.net"); 
	FBTC_select_value("AT&T Pocketnet PCS -- @dpcs.mobile.att.net", $current_value, "@dpcs.mobile.att.net"); 
	FBTC_select_value("BeeLine GSM -- @sms.beemail.ru", $current_value, "@sms.beemail.ru"); 
	FBTC_select_value("Beepwear -- @beepwear.net", $current_value, "@beepwear.net"); 
	FBTC_select_value("Bell Atlantic -- @message.bam.com", $current_value, "@message.bam.com"); 
	FBTC_select_value("Bell Canada -- @bellmobility.ca", $current_value, "@bellmobility.ca"); 
	FBTC_select_value("Bell Canada -- @txt.bellmobility.ca", $current_value, "@txt.bellmobility.ca"); 
	FBTC_select_value("Bell Mobility -- @txt.bellmobility.ca", $current_value, "@txt.bellmobility.ca"); 
	FBTC_select_value("Bell Mobility & Solo Mobile (Canada) -- @txt.bell.ca", $current_value, "@txt.bell.ca"); 
	FBTC_select_value("Bell Mobility (Canada) -- @txt.bell.ca", $current_value, "@txt.bell.ca"); 
	FBTC_select_value("Bell South -- @bellsouth.cl", $current_value, "@bellsouth.cl"); 
	FBTC_select_value(" Bell South -- @blsdcs.net", $current_value, "@blsdcs.net"); 
	FBTC_select_value("Bell South -- @sms.bellsouth.com", $current_value, "@sms.bellsouth.com"); 
	FBTC_select_value("Bell South -- @wireless.bellsouth.com", $current_value, "@wireless.bellsouth.com"); 
	FBTC_select_value("Bell South (Blackberry) -- @bellsouthtips.com", $current_value, "@bellsouthtips.com"); 
	FBTC_select_value("Bell South Mobility -- @blsdcs.net", $current_value, "@blsdcs.net"); 
	FBTC_select_value("BigRedGiant Mobile Solutions -- @tachyonsms.co.uk", $current_value, "@tachyonsms.co.uk"); 
	FBTC_select_value("Blue Sky Frog -- @blueskyfrog.com", $current_value, "@blueskyfrog.com"); 
	FBTC_select_value("Bluegrass Cellular -- @sms.bluecell.com", $current_value, "@sms.bluecell.com"); 
	FBTC_select_value("Boost -- @myboostmobile.com", $current_value, "@myboostmobile.com"); 
	FBTC_select_value("Boost Mobile -- @myboostmobile.com", $current_value, "@myboostmobile.com"); 
	FBTC_select_value("BPL Mobile -- @bplmobile.com", $current_value, "@bplmobile.com"); 
	FBTC_select_value("BPL Mobile (Mumbai, India) -- @bplmobile.com", $current_value, "@bplmobile.com"); 
	FBTC_select_value("Carolina Mobile Communications -- @cmcpaging.com", $current_value, "@cmcpaging.com"); 
	FBTC_select_value("Carolina West Wireless -- @cwwsms.com", $current_value, "@cwwsms.com"); 
	FBTC_select_value("Cellular One -- @cell1.textmsg.com", $current_value, "@cell1.textmsg.com"); 
	FBTC_select_value("Cellular One -- @cellularone.textmsg.com", $current_value, "@cellularone.textmsg.com"); 
	FBTC_select_value("Cellular One -- @cellularone.txtmsg.com", $current_value, "@cellularone.txtmsg.com"); 
	FBTC_select_value("Cellular One -- @message.cellone-sf.com", $current_value, "@message.cellone-sf.com"); 
	FBTC_select_value("Cellular One -- @mobile.celloneusa.com", $current_value, "@mobile.celloneusa.com"); 
	FBTC_select_value("Cellular One -- @sbcemail.com", $current_value, "@sbcemail.com"); 
	FBTC_select_value("Cellular One (Dobson) -- @mobile.celloneusa.com", $current_value, "@mobile.celloneusa.com"); 
	FBTC_select_value("Cellular One (East Coast) -- @phone.cellone.net", $current_value, "@phone.cellone.net"); 
	FBTC_select_value("Cellular One (South West) -- @swmsg.com", $current_value, "@swmsg.com"); 
	FBTC_select_value("Cellular One (West) -- @mycellone.com", $current_value, "@mycellone.com"); 
	FBTC_select_value("Cellular One East Coast -- @phone.cellone.net", $current_value, "@phone.cellone.net"); 
	FBTC_select_value("Cellular One PCS -- @paging.cellone-sf.com", $current_value, "@paging.cellone-sf.com"); 
	FBTC_select_value("Cellular One South West -- @swmsg.com", $current_value, "@swmsg.com"); 
	FBTC_select_value("Cellular One West -- @mycellone.com", $current_value, "@mycellone.com"); 
	FBTC_select_value("Cellular South -- @csouth1.com", $current_value, "@csouth1.com"); 
	FBTC_select_value("Centennial Wireless -- @cwemail.com", $current_value, "@cwemail.com"); 
	FBTC_select_value("Centennial Wireless -- @cwemail.com", $current_value, "@cwemail.com"); 
	FBTC_select_value("Central Vermont Communications -- @cvcpaging.com", $current_value, "@cvcpaging.com"); 
	FBTC_select_value("CenturyTel -- @messaging.centurytel.net", $current_value, "@messaging.centurytel.net"); 
	FBTC_select_value("Chennai RPG Cellular -- @rpgmail.net", $current_value, "@rpgmail.net"); 
	FBTC_select_value("Chennai Skycell / Airtel -- @airtelchennai.com", $current_value, "@airtelchennai.com"); 
	FBTC_select_value("Cincinnati Bell -- @gocbw.com", $current_value, "@gocbw.com"); 
	FBTC_select_value("Cincinnati Bell Wireless -- @gocbw.com", $current_value, "@gocbw.com"); 
	FBTC_select_value("Cingular -- @cingularme.com", $current_value, "@cingularme.com"); 
	FBTC_select_value("Cingular -- @mms.cingularme.com", $current_value, "@mms.cingularme.com"); 
	FBTC_select_value("Cingular -- @mycingular.com", $current_value, "@mycingular.com"); 
	FBTC_select_value("Cingular -- @mycingular.net", $current_value, "@mycingular.net"); 
	FBTC_select_value("Cingular -- @page.cingular.com", $current_value, "@page.cingular.com"); 
	FBTC_select_value("Cingular (GoPhone prepaid) -- @cingularme.com (SMS)", $current_value, "@cingularme.com (SMS)"); 
	FBTC_select_value("Cingular (Now AT&T) -- @txt.att.net", $current_value, "@txt.att.net"); 
	FBTC_select_value("Cingular (Postpaid) -- @cingularme.com", $current_value, "@cingularme.com"); 
	FBTC_select_value("Cingular Wireless -- @mobile.mycingular.com", $current_value, "@mobile.mycingular.com"); 
	FBTC_select_value("Cingular Wireless -- @mobile.mycingular.net", $current_value, "@mobile.mycingular.net"); 
	FBTC_select_value("Cingular Wireless -- @mycingular.textmsg.com", $current_value, "@mycingular.textmsg.com"); 
	FBTC_select_value("Claro (Brasil) -- @clarotorpedo.com.br", $current_value, "@clarotorpedo.com.br"); 
	FBTC_select_value("Claro (Nicaragua) -- @ideasclaro-ca.com", $current_value, "@ideasclaro-ca.com"); 
	FBTC_select_value("Clearnet -- @msg.clearnet.com", $current_value, "@msg.clearnet.com"); 
	FBTC_select_value("Comcast -- @comcastpcs.textmsg.com", $current_value, "@comcastpcs.textmsg.com"); 
	FBTC_select_value("Comcel -- @comcel.com.co", $current_value, "@comcel.com.co"); 
	FBTC_select_value("Communication Specialist Companies -- @pager.comspeco.com", $current_value, "@pager.comspeco.com"); 
	FBTC_select_value("Communication Specialists -- @pageme.comspeco.net", $current_value, "@pageme.comspeco.net"); 
	FBTC_select_value("Communication Specialists -- @pageme.comspeco.net", $current_value, "@pageme.comspeco.net"); 
	FBTC_select_value("Comviq -- @sms.comviq.se", $current_value, "@sms.comviq.se"); 
	FBTC_select_value("Cook Paging -- @cookmail.com", $current_value, "@cookmail.com"); 
	FBTC_select_value("Corr Wireless Communications -- @corrwireless.net", $current_value, "@corrwireless.net"); 
	FBTC_select_value("Cricket -- @sms.mycricket.com (SMS)", $current_value, "@sms.mycricket.com (SMS)"); 
	FBTC_select_value("Cricket Wireless -- @sms.mycricket.com", $current_value, "@sms.mycricket.com"); 
	FBTC_select_value("CTI -- @sms.ctimovil.com.ar", $current_value, "@sms.ctimovil.com.ar"); 
	FBTC_select_value("Delhi Aritel -- @airtelmail.com", $current_value, "@airtelmail.com"); 
	FBTC_select_value("Delhi Hutch -- @delhi.hutch.co.in", $current_value, "@delhi.hutch.co.in"); 
	FBTC_select_value("Digi-Page / Page Kansas -- @page.hit.net", $current_value, "@page.hit.net"); 
	FBTC_select_value("Dobson -- @mobile.dobson.net", $current_value, "@mobile.dobson.net"); 
	FBTC_select_value("Dobson Cellular Systems -- @mobile.dobson.net", $current_value, "@mobile.dobson.net"); 
	// FBTC_select_value("Dobson-Alex Wireless / Dobson-Cellular One -- @mobile.cellularone.com", $current_value, "@mobile.cellularone.com"); 
	FBTC_select_value("DT T-Mobile -- @t-mobile-sms.de", $current_value, "@t-mobile-sms.de"); 
	FBTC_select_value("Dutchtone / Orange-NL -- @sms.orange.nl", $current_value, "@sms.orange.nl"); 
	FBTC_select_value("Edge Wireless -- @sms.edgewireless.com", $current_value, "@sms.edgewireless.com"); 
	FBTC_select_value("EMT -- @sms.emt.ee", $current_value, "@sms.emt.ee"); 
	FBTC_select_value("Emtel (Mauritius) -- @emtelworld.net", $current_value, "@emtelworld.net"); 
	FBTC_select_value("Escotel -- @escotelmobile.com", $current_value, "@escotelmobile.com"); 
	FBTC_select_value("Fido -- @fido.ca", $current_value, "@fido.ca"); 
	FBTC_select_value("Fido (Canada) -- @fido.ca", $current_value, "@fido.ca"); 
	FBTC_select_value("Gabriel Wireless -- @epage.gabrielwireless.com", $current_value, "@epage.gabrielwireless.com"); 
	FBTC_select_value("Galaxy Corporation -- @sendabeep.net", $current_value, "@sendabeep.net"); 
	FBTC_select_value("GCS Paging -- @webpager.us", $current_value, "@webpager.us"); 
	FBTC_select_value("General Communications Inc. -- @msg.gci.net", $current_value, "@msg.gci.net"); 
	FBTC_select_value("German T-Mobile -- @t-mobile-sms.de", $current_value, "@t-mobile-sms.de"); 
	FBTC_select_value("Globalstar (satellite) -- @msg.globalstarusa.com", $current_value, "@msg.globalstarusa.com"); 
	FBTC_select_value("Goa BPLMobil -- @bplmobile.com", $current_value, "@bplmobile.com"); 
	FBTC_select_value("Golden Telecom -- @sms.goldentele.com", $current_value, "@sms.goldentele.com"); 
	FBTC_select_value("GrayLink / Porta-Phone -- @epage.porta-phone.com", $current_value, "@epage.porta-phone.com"); 
	FBTC_select_value("GTE -- @airmessage.net", $current_value, "@airmessage.net"); 
	FBTC_select_value("GTE -- @gte.pagegate.net", $current_value, "@gte.pagegate.net"); 
	FBTC_select_value("GTE -- @messagealert.com", $current_value, "@messagealert.com"); 
	FBTC_select_value("Gujarat Celforce -- @celforce.com", $current_value, "@celforce.com"); 
	FBTC_select_value("Helio -- @messaging.sprintpcs.com", $current_value, "@messaging.sprintpcs.com"); 
	FBTC_select_value("Helio -- @messaging.sprintpcs.com", $current_value, "@messaging.sprintpcs.com"); 
	FBTC_select_value("Houston Cellular -- @text.houstoncellular.net", $current_value, "@text.houstoncellular.net"); 
	FBTC_select_value("i wireless -- .iws@iwspcs.net", $current_value, ".iws@iwspcs.net"); 
	FBTC_select_value("Idea Cellular -- @ideacellular.net", $current_value, "@ideacellular.net"); 
	FBTC_select_value("Illinois Valley Cellular -- @ivctext.com", $current_value, "@ivctext.com"); 
	FBTC_select_value("Illinois Valley Cellular -- @ivctext.com", $current_value, "@ivctext.com"); 
	FBTC_select_value("Indiana Paging Co -- @inlandlink.com", $current_value, "@inlandlink.com"); 
	FBTC_select_value("Infopage Systems -- @page.infopagesystems.com", $current_value, "@page.infopagesystems.com"); 
	FBTC_select_value("Infopage Systems -- @page.infopagesystems.com", $current_value, "@page.infopagesystems.com"); 
	FBTC_select_value("Inland Cellular Telephone -- @inlandlink.com", $current_value, "@inlandlink.com"); 
	FBTC_select_value("Iridium (satellite) -- @msg.iridium.com", $current_value, "@msg.iridium.com"); 
	FBTC_select_value("Iusacell -- @rek2.com.mx", $current_value, "@rek2.com.mx"); 
	FBTC_select_value("JSM Tele-Page -- @jsmtel.com", $current_value, "@jsmtel.com"); 
	FBTC_select_value("JSM Tele-Page -- @jsmtel.com", $current_value, "@jsmtel.com"); 
	FBTC_select_value("Kerala Escotel -- @escotelmobile.com", $current_value, "@escotelmobile.com"); 
	FBTC_select_value("Kolkata Airtel -- @airtelkol.com", $current_value, "@airtelkol.com"); 
	FBTC_select_value("Koodo Mobile (Canada) -- @msg.koodomobile.com", $current_value, "@msg.koodomobile.com"); 
	FBTC_select_value("Kyivstar -- @smsmail.lmt.lv", $current_value, "@smsmail.lmt.lv"); 
	FBTC_select_value("Lauttamus Communication -- @e-page.net", $current_value, "@e-page.net"); 
	FBTC_select_value("LMT -- @smsmail.lmt.lv", $current_value, "@smsmail.lmt.lv"); 
	FBTC_select_value("LMT (Latvia) -- @sms.lmt.lv", $current_value, "@sms.lmt.lv"); 
	FBTC_select_value("Maharashtra BPL Mobile -- @bplmobile.com", $current_value, "@bplmobile.com"); 
	FBTC_select_value("Maharashtra Idea Cellular -- @ideacellular.net", $current_value, "@ideacellular.net"); 
	FBTC_select_value("Manitoba Telecom Systems -- @text.mtsmobility.com", $current_value, "@text.mtsmobility.com"); 
	FBTC_select_value("MCI -- @pagemci.com", $current_value, "@pagemci.com"); 
	FBTC_select_value("MCI Phone -- @mci.com", $current_value, "@mci.com"); 
	FBTC_select_value("Mero Mobile (Nepal) -- @sms.spicenepal.com", $current_value, "@sms.spicenepal.com"); 
	FBTC_select_value("Meteor -- @mymeteor.ie", $current_value, "@mymeteor.ie"); 
	FBTC_select_value("Meteor -- @sms.mymeteor.ie", $current_value, "@sms.mymeteor.ie"); 
	FBTC_select_value("Meteor (Ireland) -- @sms.mymeteor.ie", $current_value, "@sms.mymeteor.ie"); 
	FBTC_select_value("Metro PCS -- @metropcs.sms.us", $current_value, "@metropcs.sms.us"); 
	FBTC_select_value("Metro PCS -- @mymetropcs.com", $current_value, "@mymetropcs.com"); 
	FBTC_select_value("Metro PCS -- @mymetropcs.com, metropcs.sms.us", $current_value, "@mymetropcs.com, metropcs.sms.us"); 
	FBTC_select_value("Metrocall 2-way -- @my2way.com", $current_value, "@my2way.com"); 
	FBTC_select_value("MetroPCS -- @mymetropcs.com", $current_value, "@mymetropcs.com"); 
	FBTC_select_value("Microcell -- @fido.ca", $current_value, "@fido.ca"); 
	FBTC_select_value("Midwest Wireless -- @clearlydigital.com", $current_value, "@clearlydigital.com"); 
	FBTC_select_value("MiWorld -- @m1.com.sg", $current_value, "@m1.com.sg"); 
	FBTC_select_value("Mobilcomm -- @mobilecomm.net", $current_value, "@mobilecomm.net"); 
	FBTC_select_value("Mobilecom PA -- @page.mobilcom.net", $current_value, "@page.mobilcom.net"); 
	FBTC_select_value("Mobilecomm -- @mobilecomm.net", $current_value, "@mobilecomm.net"); 
	FBTC_select_value("Mobileone -- @m1.com.sg", $current_value, "@m1.com.sg"); 
	FBTC_select_value("Mobilfone -- @page.mobilfone.com", $current_value, "@page.mobilfone.com"); 
	FBTC_select_value("Mobility Bermuda -- @ml.bm", $current_value, "@ml.bm"); 
	FBTC_select_value("MobiPCS (Hawaii only) -- @mobipcs.net", $current_value, "@mobipcs.net"); 
	FBTC_select_value("Mobistar Belgium -- @mobistar.be", $current_value, "@mobistar.be"); 
	FBTC_select_value("Mobitel (Sri Lanka) -- @sms.mobitel.lk", $current_value, "@sms.mobitel.lk"); 
	FBTC_select_value("Mobitel Tanzania -- @sms.co.tz", $current_value, "@sms.co.tz"); 
	FBTC_select_value("Mobtel Srbija -- @mobtel.co.yu", $current_value, "@mobtel.co.yu"); 
	FBTC_select_value("Morris Wireless -- @beepone.net", $current_value, "@beepone.net"); 
	FBTC_select_value("Motient -- @isp.com", $current_value, "@isp.com"); 
	FBTC_select_value("Movicom (Argentina) -- @sms.movistar.net.ar", $current_value, "@sms.movistar.net.ar"); 
	FBTC_select_value("Movistar -- @correo.movistar.net", $current_value, "@correo.movistar.net"); 
	FBTC_select_value("Movistar (Colombia) -- @movistar.com.co", $current_value, "@movistar.com.co"); 
	FBTC_select_value("MTN (South Africa) -- @sms.co.za", $current_value, "@sms.co.za"); 
	FBTC_select_value("MTS -- @text.mtsmobility.com", $current_value, "@text.mtsmobility.com"); 
	FBTC_select_value("MTS (Canada) -- @text.mtsmobility.com", $current_value, "@text.mtsmobility.com"); 
	FBTC_select_value("Mumbai BPL Mobile -- @bplmobile.com", $current_value, "@bplmobile.com"); 
	FBTC_select_value("Mumbai Orange -- @orangemail.co.in", $current_value, "@orangemail.co.in"); 
	FBTC_select_value("NBTel -- @wirefree.informe.ca", $current_value, "@wirefree.informe.ca"); 
	FBTC_select_value("Netcom -- @sms.netcom.no", $current_value, "@sms.netcom.no"); 
	FBTC_select_value("Nextel -- @messaging.nextel.com", $current_value, "@messaging.nextel.com"); 
	FBTC_select_value("Nextel -- @nextel.com.br", $current_value, "@nextel.com.br"); 
	FBTC_select_value("Nextel -- @page.nextel.com", $current_value, "@page.nextel.com"); 
	// FBTC_select_value("Nextel (Argentina) -- @TwoWay.11number@nextel.net.ar", $current_value, "@TwoWay.11number@nextel.net.ar"); 
	FBTC_select_value("Nextel (United States) -- @messaging.nextel.com", $current_value, "@messaging.nextel.com"); 
	FBTC_select_value("Northeast Paging -- @pager.ucom.com", $current_value, "@pager.ucom.com"); 
	FBTC_select_value("NPI Wireless -- @npiwireless.com", $current_value, "@npiwireless.com"); 
	FBTC_select_value("Ntelos -- @pcs.ntelos.com", $current_value, "@pcs.ntelos.com"); 
	FBTC_select_value("O2 -- @o2.co.uk", $current_value, "@o2.co.uk"); 
	FBTC_select_value("O2 -- @o2imail.co.uk", $current_value, "@o2imail.co.uk"); 
	FBTC_select_value("O2 (M-mail) -- @mmail.co.uk", $current_value, "@mmail.co.uk"); 
	FBTC_select_value("Omnipoint -- @omnipoint.com", $current_value, "@omnipoint.com"); 
	FBTC_select_value("Omnipoint -- @omnipointpcs.com", $current_value, "@omnipointpcs.com"); 
	FBTC_select_value("One Connect Austria -- @onemail.at", $current_value, "@onemail.at"); 
	FBTC_select_value("OnlineBeep -- @onlinebeep.net", $current_value, "@onlinebeep.net"); 
	FBTC_select_value("Optus Mobile -- @optusmobile.com.au", $current_value, "@optusmobile.com.au"); 
	FBTC_select_value("Orange -- @orange.net", $current_value, "@orange.net"); 
	FBTC_select_value("Orange - NL / Dutchtone -- @sms.orange.nl", $current_value, "@sms.orange.nl"); 
	FBTC_select_value("Orange Mumbai -- @orangemail.co.in", $current_value, "@orangemail.co.in"); 
	FBTC_select_value("Orange NL / Dutchtone -- @sms.orange.nl", $current_value, "@sms.orange.nl"); 
	FBTC_select_value("Orange Polska (Poland) -- @orange.pl", $current_value, "@orange.pl"); 
	FBTC_select_value("Oskar -- @mujoskar.cz", $current_value, "@mujoskar.cz"); 
	FBTC_select_value("P&T Luxembourg -- @sms.luxgsm.lu", $current_value, "@sms.luxgsm.lu"); 
	FBTC_select_value("Pacific Bell -- @pacbellpcs.net", $current_value, "@pacbellpcs.net"); 
	FBTC_select_value("PageMart -- @pagemart.net", $current_value, "@pagemart.net"); 
	FBTC_select_value("PageMart Advanced /2way -- @airmessage.net", $current_value, "@airmessage.net"); 
	FBTC_select_value("PageMart Canada -- @pmcl.net", $current_value, "@pmcl.net"); 
	FBTC_select_value("PageNet Canada -- @e.pagenet.ca", $current_value, "@e.pagenet.ca"); 
	FBTC_select_value("PageNet Canada -- @pagegate.pagenet.ca", $current_value, "@pagegate.pagenet.ca"); 
	FBTC_select_value("PageOne NorthWest -- @page1nw.com", $current_value, "@page1nw.com"); 
	FBTC_select_value("PCS One -- @pcsone.net", $current_value, "@pcsone.net"); 
	FBTC_select_value("Personal (Argentina) -- @alertas.personal.com.ar", $current_value, "@alertas.personal.com.ar"); 
	//FBTC_select_value("Personal Communication -- sms@pcom.ru (number in subject line)", $current_value, "sms@pcom.ru (number in subject line)"); 
	//FBTC_select_value("Personal Communication -- sms@pcom.ru (put the number in the subject line)", $current_value, "sms@pcom.ru (put the number in the subject line)"); 
	FBTC_select_value("Pioneer / Enid Cellular -- @msg.pioneerenidcellular.com", $current_value, "@msg.pioneerenidcellular.com"); 
	FBTC_select_value("Plus GSM (Poland) -- @text.plusgsm.pl", $current_value, "@text.plusgsm.pl"); 
	FBTC_select_value("PlusGSM -- @text.plusgsm.pl", $current_value, "@text.plusgsm.pl"); 
	FBTC_select_value("Pondicherry BPL Mobile -- @bplmobile.com", $current_value, "@bplmobile.com"); 
	FBTC_select_value("Powertel -- @voicestream.net", $current_value, "@voicestream.net"); 
	FBTC_select_value("Presidents Choice (Canada) -- @txt.bell.ca", $current_value, "@txt.bell.ca"); 
	FBTC_select_value("Presidents Choice -- @txt.bell.ca", $current_value, "@txt.bell.ca"); 
	FBTC_select_value("Price Communications -- @mobilecell1se.com", $current_value, "@mobilecell1se.com"); 
	FBTC_select_value("Primeco -- @email.uscc.net", $current_value, "@email.uscc.net"); 
	FBTC_select_value("Primtel -- @sms.primtel.ru", $current_value, "@sms.primtel.ru"); 
	FBTC_select_value("ProPage -- @page.propage.net", $current_value, "@page.propage.net"); 
	FBTC_select_value("Public Service Cellular -- @sms.pscel.com", $current_value, "@sms.pscel.com"); 
	FBTC_select_value("Qualcomm -- @pager.qualcomm.com", $current_value, "@pager.qualcomm.com"); 
	FBTC_select_value("Qwest -- @qwestmp.com", $current_value, "@qwestmp.com"); 
	FBTC_select_value("Qwest -- @qwestmp.com", $current_value, "@qwestmp.com"); 
	FBTC_select_value("RAM Page -- @ram-page.com", $current_value, "@ram-page.com"); 
	FBTC_select_value("Rogers -- @pcs.rogers.com", $current_value, "@pcs.rogers.com"); 
	FBTC_select_value("Rogers (Canada) -- @pcs.rogers.com", $current_value, "@pcs.rogers.com"); 
	FBTC_select_value("Rogers AT&T Wireless -- @pcs.rogers.com", $current_value, "@pcs.rogers.com"); 
	FBTC_select_value("Rogers Canada -- @pcs.rogers.com", $current_value, "@pcs.rogers.com"); 
	FBTC_select_value("Safaricom -- @safaricomsms.com", $current_value, "@safaricomsms.com"); 
	FBTC_select_value("Sasktel (Canada) -- @sms.sasktel.com", $current_value, "@sms.sasktel.com"); 
	FBTC_select_value("Satelindo GSM -- @satelindogsm.com", $current_value, "@satelindogsm.com"); 
	FBTC_select_value("Satellink -- .pageme@satellink.net", $current_value, ".pageme@satellink.net"); 
	FBTC_select_value("Satellink -- @satellink.net", $current_value, "@satellink.net"); 
	FBTC_select_value("SBC Ameritech Paging -- @paging.acswireless.com", $current_value, "@paging.acswireless.com"); 
	// FBTC_select_value("SBC Ameritech Paging (see also American Messaging) -- @paging.acswireless.com", $current_value, "@paging.acswireless.com"); 
	FBTC_select_value("SCS-900 -- @scs-900.ru", $current_value, "@scs-900.ru"); 
	FBTC_select_value("SCS-900 -- @scs-900.ru", $current_value, "@scs-900.ru"); 
	FBTC_select_value("Setar Mobile email (Aruba) -- @mas.aw", $current_value, "@mas.aw"); 
	FBTC_select_value("SFR France -- @sfr.fr", $current_value, "@sfr.fr"); 
	FBTC_select_value("Simple Freedom -- @text.simplefreedom.net", $current_value, "@text.simplefreedom.net"); 
	FBTC_select_value("Skytel Pagers -- @skytel.com", $current_value, "@skytel.com"); 
	FBTC_select_value("Skytel Pagers -- @email.skytel.com", $current_value, "@email.skytel.com"); 
	FBTC_select_value("SL Interactive (Australia) -- @slinteractive.com.au", $current_value, "@slinteractive.com.au"); 
	FBTC_select_value("Smart Telecom -- @mysmart.mymobile.ph", $current_value, "@mysmart.mymobile.ph"); 
	FBTC_select_value("Solo Mobile -- @txt.bell.ca", $current_value, "@txt.bell.ca"); 
	FBTC_select_value("Southern LINC -- @page.southernlinc.com", $current_value, "@page.southernlinc.com"); 
	FBTC_select_value("Southwestern Bell -- @email.swbw.com", $current_value, "@email.swbw.com"); 
	FBTC_select_value("Sprint -- @cingularme.com", $current_value, "@cingularme.com"); 
	FBTC_select_value("Sprint -- @messaging.sprintpcs.com", $current_value, "@messaging.sprintpcs.com"); 
	FBTC_select_value("Sprint -- @sprintpaging.com", $current_value, "@sprintpaging.com"); 
	FBTC_select_value("Sprint PCS -- @messaging.sprintpcs.com", $current_value, "@messaging.sprintpcs.com"); 
	FBTC_select_value("ST Paging -- @pin@page.stpaging.com", $current_value, "@pin@page.stpaging.com"); 
	FBTC_select_value("Sumcom -- @tms.suncom.com", $current_value, "@tms.suncom.com"); 
	FBTC_select_value("Suncom -- @tms.suncom.com", $current_value, "@tms.suncom.com"); 
	FBTC_select_value("SunCom -- @suncom1.com", $current_value, "@suncom1.com"); 
	FBTC_select_value("Suncom -- @tms.suncom.com", $current_value, "@tms.suncom.com"); 
	FBTC_select_value("Sunrise Mobile -- @freesurf.ch", $current_value, "@freesurf.ch"); 
	FBTC_select_value("Sunrise Mobile -- @mysunrise.ch", $current_value, "@mysunrise.ch"); 
	FBTC_select_value("Sunrise Mobile -- @swmsg.com", $current_value, "@swmsg.com"); 
	FBTC_select_value("Surewest Communicaitons -- @mobile.surewest.com", $current_value, "@mobile.surewest.com"); 
	FBTC_select_value("Surewest Communications -- @freesurf.ch", $current_value, "@freesurf.ch"); 
	FBTC_select_value("Swisscom -- @bluewin.ch", $current_value, "@bluewin.ch"); 
	FBTC_select_value("Tamil Nadu BPL Mobile -- @bplmobile.com", $current_value, "@bplmobile.com"); 
	FBTC_select_value("Tele2 Latvia -- @sms.tele2.lv", $current_value, "@sms.tele2.lv"); 
	FBTC_select_value("Telefonica Movistar -- @movistar.net", $current_value, "@movistar.net"); 
	FBTC_select_value("Telenor -- @mobilpost.no", $current_value, "@mobilpost.no"); 
	FBTC_select_value("Teletouch -- @pageme.teletouch.com", $current_value, "@pageme.teletouch.com"); 
	FBTC_select_value("Telia Denmark -- @gsm1800.telia.dk", $current_value, "@gsm1800.telia.dk"); 
	FBTC_select_value("Telus -- @msg.telus.com", $current_value, "@msg.telus.com"); 
	FBTC_select_value("Telus Mobility (Canada) -- @msg.telus.com", $current_value, "@msg.telus.com"); 
	FBTC_select_value("The Indiana Paging Co -- @pager.tdspager.com", $current_value, "@pager.tdspager.com"); 
	FBTC_select_value("Thumb Cellular -- @sms.thumbcellular.com", $current_value, "@sms.thumbcellular.com"); 
	FBTC_select_value("Tigo (Formerly Ola) -- @sms.tigo.com.co", $current_value, "@sms.tigo.com.co"); 
	FBTC_select_value("TIM -- @timnet.com", $current_value, "@timnet.com"); 
	FBTC_select_value("T-Mobile -- @tmomail.net", $current_value, "@tmomail.net"); 
	FBTC_select_value("T-Mobile -- @voicestream.net", $current_value, "@voicestream.net"); 
	FBTC_select_value("T-Mobile (Austria) -- @sms.t-mobile.at", $current_value, "@sms.t-mobile.at"); 
	FBTC_select_value("T-Mobile (UK) -- @t-mobile.uk.net", $current_value, "@t-mobile.uk.net"); 
	FBTC_select_value("T-Mobile Austria -- @sms.t-mobile.at", $current_value, "@sms.t-mobile.at"); 
	FBTC_select_value("T-Mobile Germany -- @t-d1-sms.de", $current_value, "@t-d1-sms.de"); 
	FBTC_select_value("T-Mobile UK -- @t-mobile.uk.net", $current_value, "@t-mobile.uk.net"); 
	FBTC_select_value("Tracfone -- @txt.att.net", $current_value, "@txt.att.net"); 
	FBTC_select_value("Tracfone (prepaid) -- @mmst5.tracfone.com", $current_value, "@mmst5.tracfone.com"); 
	FBTC_select_value("Triton -- @tms.suncom.com", $current_value, "@tms.suncom.com"); 
	FBTC_select_value("TSR Wireless -- @alphame.com", $current_value, "@alphame.com"); 
	FBTC_select_value("TSR Wireless -- @beep.com", $current_value, "@beep.com"); 
	FBTC_select_value("U.S. Cellular -- @email.uscc.net", $current_value, "@email.uscc.net"); 
	FBTC_select_value("UCOM -- @pager.ucom.com", $current_value, "@pager.ucom.com"); 
	FBTC_select_value("UMC -- @sms.umc.com.ua", $current_value, "@sms.umc.com.ua"); 
	FBTC_select_value("Unicel -- @utext.com", $current_value, "@utext.com"); 
	FBTC_select_value("Unicel -- @utext.com", $current_value, "@utext.com"); 
	FBTC_select_value("Uraltel -- @sms.uraltel.ru", $current_value, "@sms.uraltel.ru"); 
	FBTC_select_value("US Cellular -- @email.uscc.net", $current_value, "@email.uscc.net"); 
	FBTC_select_value("US Cellular -- @smtp.uscc.net", $current_value, "@smtp.uscc.net"); 
	FBTC_select_value("US Cellular -- @uscc.textmsg.com", $current_value, "@uscc.textmsg.com"); 
	FBTC_select_value("US West -- @uswestdatamail.com", $current_value, "@uswestdatamail.com"); 
	FBTC_select_value("Uttar Pradesh Escotel -- @escotelmobile.com", $current_value, "@escotelmobile.com"); 
	FBTC_select_value("Verizon -- @vtext.com", $current_value, "@vtext.com"); 
	FBTC_select_value("Verizon Pagers -- @myairmail.com", $current_value, "@myairmail.com"); 
	FBTC_select_value("Verizon PCS -- @myvzw.com", $current_value, "@myvzw.com"); 
	FBTC_select_value("Verizon PCS -- @vtext.com", $current_value, "@vtext.com"); 
	FBTC_select_value("Vessotel -- @pager.irkutsk.ru", $current_value, "@pager.irkutsk.ru"); 
	FBTC_select_value("Virgin Mobile -- @vmobl.com", $current_value, "@vmobl.com"); 
	FBTC_select_value("Virgin Mobile -- @vxtras.com", $current_value, "@vxtras.com"); 
	FBTC_select_value("Virgin Mobile (Canada) -- @vmobile.ca", $current_value, "@vmobile.ca"); 
	FBTC_select_value("Virgin Mobile Canada -- @vmobile.ca", $current_value, "@vmobile.ca"); 
	FBTC_select_value("Vodacom (South Africa) -- @voda.co.za", $current_value, "@voda.co.za"); 
	FBTC_select_value("Vodafone (Italy) -- @sms.vodafone.it", $current_value, "@sms.vodafone.it"); 
	FBTC_select_value("Vodafone Italy -- @sms.vodafone.it", $current_value, "@sms.vodafone.it"); 
	FBTC_select_value("Vodafone Japan -- @c.vodafone.ne.jp", $current_value, "@c.vodafone.ne.jp"); 
	FBTC_select_value("Vodafone Japan -- @h.vodafone.ne.jp", $current_value, "@h.vodafone.ne.jp"); 
	FBTC_select_value("Vodafone Japan -- @t.vodafone.ne.jp", $current_value, "@t.vodafone.ne.jp"); 
	FBTC_select_value("Vodafone UK -- @vodafone.net", $current_value, "@vodafone.net"); 
	FBTC_select_value("VoiceStream -- @voicestream.net", $current_value, "@voicestream.net"); 
	FBTC_select_value("VoiceStream / T-Mobile -- @voicestream.net", $current_value, "@voicestream.net"); 
	FBTC_select_value("WebLink Wiereless -- @airmessage.net", $current_value, "@airmessage.net"); 
	FBTC_select_value("WebLink Wiereless -- @pagemart.net", $current_value, "@pagemart.net"); 
	FBTC_select_value("WebLink Wireless -- @airmessage.net", $current_value, "@airmessage.net"); 
	FBTC_select_value("WebLink Wireless -- @pagemart.net", $current_value, "@pagemart.net"); 
	FBTC_select_value("West Central Wireless -- @sms.wcc.net", $current_value, "@sms.wcc.net"); 
	FBTC_select_value("Western Wireless -- @cellularonewest.com", $current_value, "@cellularonewest.com"); 
	FBTC_select_value("Wyndtell -- @wyndtell.com", $current_value, "@wyndtell.com"); 
	FBTC_select_value("YCC -- @sms.ycc.ru", $current_value, "@sms.ycc.ru");
	echo "</select>";
	echo "</td>";
	echo "</tr>";
	echo "<tr><td style='padding:0px 14px 7px 14px;'>&nbsp;</td><td style='padding:0px 14px 7px 14px;'>";
	echo "<span class='smallG'>Select your cellphone carrier path from the list.</span>";
	echo "</td></tr>";		

//	echo "<td class='label200'>&nbsp;</td>";
//	echo "<td class='pad7'>";
//	echo " <span class='smallText'>You may need to try different paths, if there is more than one selection for your carrier.<br />";
//	echo " Click Save Text Message Settings below and then click Send Test Message.<br />
//  A sample message will be sent based on your selections.<br />
//  With good service, this message should be delivered in a few seconds.<br />
//  If you do not receive the message, you may need to try a different path.<br />
//  If you find that you are unable to receive text messages, please send us an e-mail.<br />
//	</span></td></tr>";
}

//////////////////////////////////////////////////////////////////////////////////////////////////
//-- plugin footer
//////////////////////////////////////////////////////////////////////////////////////////////////
if(!function_exists('FBTC_foot')){function FBTC_foot(){ ?>
	</div>
	<table class='cc100' style='width:100%;'><tr><td style='text-align:center;'>
    <span class='smallG' style='font-weight:normal;'>Fonebug Text Contact Form version .91 © Copyright Fonebug</span></td></tr></table>
	<?php 
}}