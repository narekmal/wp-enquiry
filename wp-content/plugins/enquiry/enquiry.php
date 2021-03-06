<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://narek-dev.com
 * @since             1.0.0
 * @package           Enquiry
 *
 * @wordpress-plugin
 * Plugin Name:       Enquiry
 * Plugin URI:        
 * Description:       Shortcodes to display enquiry form and submitted results
 * Version:           1.0.0
 * Author:            Narek Malkhasyan
 * Author URI:        https://narek-dev.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       enquiry
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Plugin version.
 */
define( 'ENQUIRY_VERSION', '1.0.0' );

/**
 * Plugin base URL.
 */
define( 'ENQUIRY_BASE_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-enquiry-activator.php
 */
function activate_enquiry() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-enquiry-activator.php';
	Enquiry_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-enquiry-deactivator.php
 */
function deactivate_enquiry() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-enquiry-deactivator.php';
	Enquiry_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_enquiry' );
register_deactivation_hook( __FILE__, 'deactivate_enquiry' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-enquiry.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_enquiry() {

	$plugin = new Enquiry();
	$plugin->run();

}
run_enquiry();
