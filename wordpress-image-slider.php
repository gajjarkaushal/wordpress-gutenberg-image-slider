<?php

/**
 * The plugin main file
 *
 *
 * @link              https://gajjarkaushal.com
 * @since             1.0.0
 * @package           Wordpress_Image_Slider
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Image Slider
 * Plugin URI:        https://gajjarkaushal.com
 * Description:       Create a WordPress Gutenberg image slider block that allows the admin to manage images, captions, and settings directly from the admin panel.
 * Version:           1.0.0
 * Author:            Kaushal Gajjar
 * Author URI:        https://gajjarkaushal.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wis
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use 
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WIS_VERSION', '1.0.0' );
define( 'WIS_PATH', plugin_dir_path( __FILE__ ) );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wis.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wordpress_image_slider() {

	new Wordpress_Image_Slider();

}
run_wordpress_image_slider();