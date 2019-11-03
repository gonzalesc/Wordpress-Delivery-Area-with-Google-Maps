<?php
/*
Plugin Name: Delivery Area with Google Maps
Plugin URI: https://wordpress.org/plugins/delivery-area-with-google-maps/
Description: This plugin allows you create delivery areas in Google Maps and by a shortcode put it in everywhere
Version: 1.3.1
Author: Lets Go Dev
Author URI: https://www.letsgodev.com
Developer: Alexander
Developer URI: https://vcard.gonzalesc.org
Requires at least: 4.9.0
Tested up to: 5.2.4
Stable tag: 5.2.2
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

define('AREAGM_PLUGIN_DIR' , plugin_dir_path(__FILE__));
define('AREAGM_PLUGIN_URL' , plugin_dir_url(__FILE__));
define('AREAGM_PLUGIN_BASE' , plugin_basename( __FILE__ ));


/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-areamaps.php';


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-areamaps-activator.php
 */
function areagm_activate() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-areamaps-activator.php';
    AreaGM_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-areamaps-deactivator.php
 */
function areagm_deactivate() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-areamaps-deactivator.php';
    AreaGM_Deactivator::deactivate();
}


register_activation_hook( __FILE__, 'areagm_activate' );
register_deactivation_hook( __FILE__, 'areagm_deactivate' );

/**
 * Store the plugin global
 */
global $areagm;

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */

function AreaGM() {
    return AreaGM::instance();
}

$GLOBALS['areagm'] = AreaGM();

?>