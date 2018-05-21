<?php

/**
 *
 * @link              https://example.com
 * @since             1.0.0
 * @package           Library_Book_Search
 *
 * @wordpress-plugin
 * Plugin Name:       Library Book Search
 * Plugin URI:        https://example.com
 * Description:       This plugin is for library books search.
 * Version:           1.0.0
 * Author:            Hardik Thakkar
 * Author URI:        https://example.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       library-book-search
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * Other Globals.
 */
define( 'BOOK_SEARCH_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'BOOK_SEARCH_PLUGIN_URL', plugins_url( '', __FILE__ ) );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require BOOK_SEARCH_PLUGIN_DIR . 'includes/class-library-book-search.php';

/**
 * Begins execution of the plugin.
 * @since    1.0.0
 */
function run_library_book_search() {

	$plugin = new Library_Book_Search();
	$plugin->run();

}
run_library_book_search();
