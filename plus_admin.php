<?php
/**
 * Plugin Name:       PLUS Wordpress Admin Theme
 * Plugin URI:        http://www.castorstudio.com/plus-admin-wordpress-white-label-admin-theme
 * Description:       PLUS Admin is the most complete and fully powered WordPress White Label Admin Theme. Customizing your admin area has never been so easy, with so many options, easy to use and with the posibility to change everything in seconds.
 * Version:           1.0.2
 * Author:            Castorstudio
 * Author URI:        http://www.castorstudio.com
 * Text Domain:       plus_admin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('PLUS_ADMIN_VERSION', '1.0.2');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plus_admin-activator.php
 */
function cst_activate_plus_admin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plus_admin-activator.php';
	Plus_admin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plus_admin-deactivator.php
 */
function cst_deactivate_plus_admin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plus_admin-deactivator.php';
	Plus_admin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'cst_activate_plus_admin' );
register_deactivation_hook( __FILE__, 'cst_deactivate_plus_admin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-plus_admin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plus_admin() {

	$plugin = new Plus_admin();
	$plugin->run();

}
run_plus_admin();