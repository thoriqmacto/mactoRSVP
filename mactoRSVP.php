<?php
/*
Plugin Name: mactoRSVP
Plugin URI: http://macto.us/mactoRSVP
Description: Easily integrate events via Facebook Event Graph API.
Version: 1.0.0
Author: macto
Author URI: http://macto.us
License: GPLv3

*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Define GLOBAL CONSTANT
global $wpdb;
define('TB_EVENT', $wpdb->prefix . 'mactoRSVP_events');
define('TB_GUEST', $wpdb->prefix . 'mactoRSVP_guests');

// Load Facebook php-sdk
session_start();
require_once( WP_PLUGIN_DIR . '/mactoRSVP/includes/facebook/php-sdk/src/facebook.php' ); 	// facebook php-sdk

// Load MactoRSVP_Abstract class
require_once( WP_PLUGIN_DIR . '/mactoRSVP/includes/class-mactoRSVP-abstract.php' ); 		// MactoRSVP_Abstract class

// Load MactoRSVP_Event class
require_once( WP_PLUGIN_DIR . '/mactoRSVP/includes/class-mactoRSVP-event.php' );	 		// MactoRSVP_Event class

// Load MactoRSVP_Guest class
require_once( WP_PLUGIN_DIR . '/mactoRSVP/includes/class-mactoRSVP-guest.php' );	 		// MactoRSVP_Guest class

// Load MactoRSVP class
require_once( WP_PLUGIN_DIR . '/mactoRSVP/includes/class-mactoRSVP.php' );					// MactoRSVP class

// Load MactoRSVP_Admin class
if ( is_admin() ) {
	require_once( WP_PLUGIN_DIR . '/mactoRSVP/includes/class-mactoRSVP-admin.php' );		// MactoRSVP_Admin class

	// Instantiate MactoRSVP_Admin singleton
	MactoRSVP_Admin::get_object();	
}

if ( ! function_exists( 'mactoRSVP' ) ) :
/**
 * Displays baic mactoRSVP instance
 *
 * @see MactoRSVP::display() for $args parameters
 * @since 1.0.0
 *
 * @param string|array $args Template tag arguments
 */
function mactoRSVP_display( $event_id, $args = '' ) {
	echo MactoRSVP::get_object()->display( $event_id, wp_parse_args( $args ) );
}
endif;

// mactoRSVP hook function
function insert_table(){
	MactoRSVP_Event::create_tb_event();		
	MactoRSVP_Guest::create_tb_guest();		
}

function drop_table(){
	global $wpdb;
	$wpdb->query("DROP TABLE IF EXISTS " . TB_EVENT);
	$wpdb->query("DROP TABLE IF EXISTS " . TB_GUEST);
}

if( MactoRSVP::plugin_status == "development" ){
	register_activation_hook( __FILE__, 'insert_table' );
	register_deactivation_hook( __FILE__, 'drop_table' );
}

?>
