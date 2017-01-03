<?php 
//-- count messages
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$total=FBTC_total("SELECT * FROM fbtc_messages WHERE read_on_date='' AND live='1'");
if($total==1){$messages_word="message";}else{$messages_word="messages";}

//-- if there are unread messages, force the link to open unread, else open the inbox of read messages.
if($total>0){$mailbox="&l=unread&";}else{$mailbox="&l=inbox&";}
?>
<table class='cc800'>
<tr><td style='text-align:left;'>

<table class='cc800' style='margin-top:21px; width:420px;'>
<tr><td style='text-align:center;' colspan='2'>
<span style='font-size:24px;'><img src='<?php echo $fbtc_btns_dir."btn_cellphone32_reg.png";?>' class='btn' />Text Message Contact Form</span>
</td></tr>
<?php 
//==================================================================================================
//-- NEW ACCOUNT -> PROMPT TO SET UP OPTIONS
//==================================================================================================
if($admin_email==""){ 
	echo "<tr><td style='padding-top:7px; text-align:center; width:50%;' colspan='2'>";
	echo "<div class='navBlue' style='text-align:center; font-size:14px; line-height:150%;'>";
	echo "<a href='".$fbtc_admin."&amp;v=options&amp;'>";
	echo "<span style='font-sixe:18px; font-weight:bold;'>Thanks for installing the Text Message Contact Form!</span><br>";
	echo "<span style='font-sixe:14px;'>Click here to start setting up your options.</span>";
	echo "</a></div>";
	echo "</td>";
	echo "</tr>";
}
?>
<tr>
<!-- ==================================================================================================
-- READ 
================================================================================================== -->
<td style='width:50%; text-align:center; border:0px; padding:7px;'>
<?php FBTC_home_btn("Messages", $fbtc_btns_dir."btn_readHome_reg.png", $fbtc_admin."&v=read".$mailbox)?>
</td>

<!-- ==================================================================================================
-- OPTIONS
================================================================================================== -->
<td style='width:50%; text-align:center; border:0px; padding:7px;'>
<?php FBTC_home_btn("Options", $fbtc_btns_dir."btn_optionsHome_reg.png", $fbtc_admin."&v=options&")?>
</td>
</tr>

<!-- ==================================================================================================
-- UNREAD MESSAGES NOTICE
================================================================================================== -->
<tr><td class='nopad' colspan='2'>
<?php if($total>0){
	echo "<div class='navBlue' style='text-align:center;'>";
	echo "<a href='".$fbtc_admin."&v=read&l=unread&'>You have ".$total." unread ".$messages_word."...</a>";
	echo "</div>";
}else{ 
	echo "<div class='gEmpty'>You have no unread messages.</div>";
}
?>
</td></tr>

<!-- footer -->
<tr><td colspan='2' style='padding-top:14px;'><?php FBTC_foot();?></td></tr>
</table>
</td></tr></table>