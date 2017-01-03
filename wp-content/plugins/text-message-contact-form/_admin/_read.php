 <?php 
// $wpdb->show_errors();
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
FBTC_enable_disable("fbtc_messages", $_GET['wo'], $fbtc_admin."&v=read&l=".$_GET['l']."&"); // move msg to trash
FBTC_purge_check(); // purge the trash - check
FBTC_purge_confirm(); // purge confirm

//-- full details of message
FBTC_detail_message();

if($_GET['detail']==""){
	$total_unread=FBTC_total("SELECT * FROM fbtc_messages WHERE live='1' AND read_on_date=''");
	$total_inbox=FBTC_total("SELECT * FROM fbtc_messages WHERE live='1' AND read_on_date!=''");
	$total_trash=FBTC_total("SELECT * FROM fbtc_messages WHERE live!='1'");

	echo "<table class='cc800'><tr><td class='pad7' style='vertical-align:middle; text-align:left;'>";

	//-- navigation buttons
	FBTC_read_btn("Home", $fbtc_admin, 1, 75);
	FBTC_read_btn("Trash", $fbtc_admin."&v=read&l=trash&", $total_trash, 90);
	FBTC_read_btn("Inbox", $fbtc_admin."&v=read&l=inbox&", $total_inbox, 90);
	FBTC_read_btn("Unread", $fbtc_admin."&v=read&l=unread&", $total_unread, 90);
	
	//-- page title
	echo "<a href='".$fbtc_admin."&amp;v=read&amp;l=".$_GET['l']."&' class='header'><img src='".$fbtc_btns_dir."btn_pages32_reg.png' class='btn'>Messages</a>";

	echo "</td></tr></table>";

	//-- list messages
	if($_GET['l']=="unread"){
		FBTC_list_messages("Unread", $fbtc_admin."&v=read&l=".$_GET['l']."&");
	}else if($_GET['l']=="inbox"){
		FBTC_list_messages("Inbox", $fbtc_admin."&v=read&l=".$_GET['l']."&");
	}else if($_GET['l']=="trash"){
		FBTC_list_messages("Trashed", $fbtc_admin."&v=read&l=".$_GET['l']."&");
	}else{
		FBTC_list_messages("Inbox", $fbtc_admin."&v=read&l=".$_GET['l']."&");
	}
}
FBTC_foot();
?>