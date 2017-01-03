<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if(!function_exists('FBTC_d')){function FBTC_d($DBvar){return stripslashes(urldecode($DBvar));}}
$fbtc_admin=admin_url()."?page=text-message-contact-form/admin_home.php";
$fbtc_btns_dir=plugin_dir_url(dirname( __FILE__) )."/_btns/";
include(plugin_dir_path( __FILE__ ) . "fbtc-build-db.php");
include(plugin_dir_path( __FILE__ ) . "fbtc-settings.php"); //  load user defined settings
include(plugin_dir_path( __FILE__ ) . "fbtc-functions.php"); // load functions
include(plugin_dir_path( __FILE__ ) . "fbtc-styles.php");
?>
<div id='body' align='center'>
<a name="fbtc_top" class='SM-anchor'>top of page</a>