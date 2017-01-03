<?php
/**
 * @package Mega Messages
 * @version 1.0.0
 */
/*
Plugin Name: Light Messages
Plugin URI: https://wordpress.org/plugins/light-messages/
Description: Light Message plugin allowing you to welcome your users with customized messages from image to text and other various elements.
Author: Eliran Givoni
Version: 1.0
Author URI: http://eliran.givoni.com/
*/

if ( ! function_exists( 'add_action' ) ) {
   echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
   exit;
}

define( 'MEGA_MESSAGES_VERSION', '1.0.0' );
define( 'MEGA_MESSAGES_VERSION_MINIMUM_VERSION', '3.2' );
define( 'MEGA_MESSAGES_VERSION_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'MEGA_MESSAGES_VERSION_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( MEGA_MESSAGES_VERSION_PLUGIN_DIR . 'class.light-messages.php' );

add_action( 'posts_selection', array( 'mega_messages', 'whosPage' ), 1 );
do_action("posts_selection");

if ( is_admin() ) {
    require_once( MEGA_MESSAGES_VERSION_PLUGIN_DIR . 'class.light-messages-admin.php' );
    add_action( 'init', array( 'mega_messages_admin', 'init' ) );
}

add_action( "session_destroy", array( "mega_messages", "destroy_session" ) );