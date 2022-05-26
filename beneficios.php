<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://genosha.com.ar
 * @since             1.0.0
 * @package           Beneficios
 *
 * @wordpress-plugin
 * Plugin Name:       Beneficios
 * Plugin URI:        https://genosha.com.ar
 * Description:       Esta es la descripción corta del plugin. Le podemos agregar algo mas.
 * Version:           1.3.2
 * Author:            Juan Iriart
 * Author URI:        https://genosha.com.ar
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       beneficios
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
define( 'BENEFICIOS_VERSION', '1.3.2' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-beneficios-activator.php
 */
function activate_beneficios() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-beneficios-activator.php';
	Beneficios_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-beneficios-deactivator.php
 */
function deactivate_beneficios() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-beneficios-deactivator.php';
	Beneficios_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_beneficios' );
register_deactivation_hook( __FILE__, 'deactivate_beneficios' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-beneficios.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_beneficios() {

	$plugin = new Beneficios();
	$plugin->run();

}
run_beneficios();
