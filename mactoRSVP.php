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

// Require needed files
require_once( WP_PLUGIN_DIR . '/mactoRSVP/includes/facebook/php-sdk/src/facebook.php' ); 	// facebook-php-sdk
require_once( WP_PLUGIN_DIR . '/mactoRSVP/includes/class-mactoRSVP-abstract.php' ); 		// mactoRSVP abstract class
require_once( WP_PLUGIN_DIR . '/mactoRSVP/includes/class-mactoRSVP-admin.php' );			// mactoRSVP admin class
require_once( WP_PLUGIN_DIR . '/mactoRSVP/includes/class-mactoRSVP-util.php' ); 			// mactoRSVP utilities class
require_once( WP_PLUGIN_DIR . '/mactoRSVP/includes/class-mactoRSVP.php' ); 					// mactoRSVP main class


