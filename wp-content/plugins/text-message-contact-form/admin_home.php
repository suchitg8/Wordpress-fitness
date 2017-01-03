<?php
include(plugin_dir_path( __FILE__ )."_include/fbtc-status.php");

// The admin page to show
$view=$_GET['v'];

// Get the page to show. If it's blank, make it home page
if($view==""){$view='home';}
$fbtc_page=plugin_dir_path( __FILE__ )."_admin/_".$view.".php";
if(file_exists($fbtc_page)){
	include($fbtc_page);
}else{
	echo "<span class='redText'>Sorry, can't find the page you're looking for.</span><br><br>";
	echo "<a href='".$fbtc_admin."'>Go to Admin Page.</a>";
}
?>