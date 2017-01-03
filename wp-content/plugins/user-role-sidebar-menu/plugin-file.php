<?php 
/**
* Plugin Name: User Role Base Sidebar Menu
* Plugin URI: http://phpmake.wordpress.com/2014/02/13/user-role-sidebar-menu-plugin/
* Description: Manage custom Sidebar menus based on logged-in user and you can assign different custom menus for different user roles on WordPress pages. 
* Version: 0.0.2
* Author: Achyuth401
* Author URI: http://phpmake.wordpress.com/
* License: GPLv2 or later
*/
/*
Copyright 2014  Achyuth Kumar 401  (email: b.achyuthkumar@gmail.com)

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
	
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
	
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
require_once 'role-base-sidebar-menu.php';

/**
 * rbsm == Role Base Sidebar Menu
 * Plugin JS files register and enqueue into wordpress
 * @since 0.0.2
 * */
function rbsm_plugin_scripts() {
	wp_register_script( 'role-base-sidebar-js', plugins_url( '/js/plugin-js.js', __FILE__ ), array( 'jquery' ), '20140217', true );
	wp_enqueue_script( 'role-base-sidebar-js' );
	wp_register_style( 'role-base-sidebar-css', plugins_url('/css/plugin-css.css', __FILE__) );
	wp_enqueue_style( 'role-base-sidebar-css' );

}// End Plugin_scripts

/**
 * Enqueues JS on dashboard and front end 
 **/
add_action( 'wp_enqueue_scripts', 'rbsm_plugin_scripts' );
add_action( 'admin_enqueue_scripts', 'rbsm_plugin_scripts' );
?>