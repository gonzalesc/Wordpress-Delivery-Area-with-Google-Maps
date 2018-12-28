<?php
/*
Plugin Name: Delivery Area with Google Maps
Plugin URI: http://wordpress.org/extend/plugins/wp-delivery-area-with-google-maps/
Description: This plugin allows you create delivery areas in Google Maps and by a shortcode put it in everywhere
Version: 1.2.0
Author: LetsGoDev
Author URI: https://www.letsgodev.com
Developer: Alexander
Developer URI: https://www.letsgodev.com
Requires at least: 4.0.1
Tested up to: 5.0.2
Stable tag: 5.0.2
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

define('AREAMAPS_PLUGIN_DIR' , plugin_dir_path(__FILE__));
define('AREAMAPS_PLUGIN_URL' , plugin_dir_url(__FILE__));
define('AREAMAPS_PLUGIN_BASE' , plugin_basename( __FILE__ ));



/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-areamaps.php';


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-areamaps-activator.php
 */
function areamaps_activate() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-areamaps-activator.php';
    AreaMaps_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-areamaps-deactivator.php
 */
function areamaps_deactivate() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-areamaps-deactivator.php';
    AreaMaps_Deactivator::deactivate();
}


register_activation_hook( __FILE__, 'areamaps_activate' );
register_deactivation_hook( __FILE__, 'areamaps_deactivate' );

/**
 * Store the plugin global
 */
global $areamaps;

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */

function AreaMaps() {
    return AreaMaps::instance();
}

$GLOBALS['areamaps'] = AreaMaps();

?>