<?php
/**
 * Plugin Name: Easy Digital Downloads - MailPoet (formerly Wysija)
 * Plugin URL: http://easydigitaldownloads.com/extensions/wysija
 * Description: Add customers to your newsletter lists in MailPoet
 * Version: 1.4.1
 * Author: Sandhills Development, LLC
 * Author URI: https://sandhillsdev.com/
 * Contributors: mordauk
 * Text Domain: edd_wysija
 * Domain Path: languages
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/* PHP Hack to Get Plugin Headers in the .POT File */
	$edd_wysija_plugin_header_translate = array(
		__( 'Easy Digital Downloads - MailPoet', 'edd_wysija' ),
    	__( 'Include a MailPoet signup option with your Easy Digital Downloads checkout', 'edd_wysija' ),
    	__( 'Pippin Williamson', 'edd_wysija' ),
    	__( 'http://easydigitaldownloads.com/extension/wysija/', 'edd_wysija' ),
    );

define( 'EDD_MAILPOET_PATH', dirname( __FILE__ ) );

/*
|--------------------------------------------------------------------------
| LICENSING / UPDATES
|--------------------------------------------------------------------------
*/
if( class_exists( 'EDD_License' ) && is_admin() ) {
	$eddw_license = new EDD_License( __FILE__, 'Wysija', '1.4.1', 'EDD Team', 'edd_wysija_license_key' );
}

if( ! class_exists( 'EDD_Newsletter' ) ) {
	include( dirname( __FILE__ ) . '/includes/class-edd-newsletter.php' );
}

if( ! class_exists( 'EDD_MailPoet' ) ) {
	include( dirname( __FILE__ ) . '/includes/class-edd-mailpoet.php' );
}

function edd_mailpoet_load() {

	$edd_mp = new EDD_MailPoet( 'mailpoet', 'MailPoet' );
}
add_action( 'plugins_loaded', 'edd_mailpoet_load' );


/*
|--------------------------------------------------------------------------
| INTERNATIONALIZATION
|--------------------------------------------------------------------------
*/

function edd_wysija_textdomain() {

	// Set filter for plugin's languages directory
	$edd_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$edd_lang_dir = apply_filters( 'edd_wysija_languages_directory', $edd_lang_dir );

	// Load the translations
	load_plugin_textdomain( 'edd_wysija', false, $edd_lang_dir );
}
add_action( 'plugins_loaded', 'edd_wysija_textdomain' );