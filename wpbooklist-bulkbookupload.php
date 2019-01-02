<?php
/**
 * WordPress Book List BulkBookUpload Extension
 *
 * @package     WordPress Book List BulkBookUpload Extension
 * @author      Jake Evans
 * @copyright   2018 Jake Evans
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: WPBookList Bulk-Book Upload Extension
 * Plugin URI: https://www.jakerevans.com
 * Description: A Extension for WPBookList that lets users upload tons of books all at once!
 * Version: 1.0.0
 * Author: Jake Evans
 * Text Domain: wpbooklist
 * Author URI: https://www.jakerevans.com
 */

/*
 * SETUP NOTES:
 *
 * Change all filename instances from bulkbookupload to desired plugin name
 *
 * Modify Plugin Name
 *
 * Modify Description
 *
 * Modify Version Number in Block comment and in Constant
 *
 * Find & Replace these 3 strings:
 * bulkbookupload
 * BulkBookUpload
 * BULKBOOKUPLOAD
 *
 *
 * Change the EDD_SL_ITEM_ID_BULKBOOKUPLOAD constant below.
 *
 * Install Gulp & all Plugins listed in gulpfile.js
 *
 *
 *
 *
 */




// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;

/* REQUIRE STATEMENTS */
	require_once 'includes/class-bulkbookupload-general-functions.php';
	require_once 'includes/class-bulkbookupload-ajax-functions.php';
	require_once 'includes/classes/update/class-wpbooklist-bulkbookupload-update.php';
/* END REQUIRE STATEMENTS */

/* CONSTANT DEFINITIONS */

	if ( ! defined('WPBOOKLIST_VERSION_NUM' ) ) {
		define( 'WPBOOKLIST_VERSION_NUM', '6.1.2' );
	}

	// This is the URL our updater / license checker pings. This should be the URL of the site with EDD installed.
	define( 'EDD_SL_STORE_URL_BULKBOOKUPLOAD', 'https://wpbooklist.com' );

	// The id of your product in EDD.
	define( 'EDD_SL_ITEM_ID_BULKBOOKUPLOAD', 725 );

	// This Extension's Version Number.
	define( 'WPBOOKLIST_BULKBOOKUPLOAD_VERSION_NUM', '1.0.0' );

	// Root plugin folder directory.
	define( 'BULKBOOKUPLOAD_ROOT_DIR', plugin_dir_path( __FILE__ ) );

	// Root WordPress Plugin Directory.
	define( 'BULKBOOKUPLOAD_ROOT_WP_PLUGINS_DIR', str_replace( '/wpbooklist-bulkbookupload', '', plugin_dir_path( __FILE__ ) ) );

	// Root WPBL Dir.
	if ( ! defined('BULKBOOKUPLOAD_ROOT_WPBL_DIR' ) ) {
		define( 'BULKBOOKUPLOAD_ROOT_WPBL_DIR', BULKBOOKUPLOAD_ROOT_WP_PLUGINS_DIR . 'wpbooklist/' );
	}

	// Root WPBL Url.
	if ( ! defined('ROOT_WPBL_URL' ) ) {
		define( 'ROOT_WPBL_URL', plugins_url() . '/wpbooklist/' );
	}

	// Root WPBL Dir.
	if ( ! defined( 'ROOT_WPBL_DIR' ) ) {
		define( 'ROOT_WPBL_DIR', BULKBOOKUPLOAD_ROOT_WP_PLUGINS_DIR . 'wpbooklist/' );
	}

	// Root WPBL Classes Dir.
	if ( ! defined('ROOT_WPBL_CLASSES_DIR' ) ) {
		define( 'ROOT_WPBL_CLASSES_DIR', ROOT_WPBL_DIR . 'includes/classes/' );
	}

	// Root WPBL Transients Dir.
	if ( ! defined('ROOT_WPBL_TRANSIENTS_DIR' ) ) {
		define( 'ROOT_WPBL_TRANSIENTS_DIR', ROOT_WPBL_CLASSES_DIR . 'transients/' );
	}

	// Root WPBL Translations Dir.
	if ( ! defined('ROOT_WPBL_TRANSLATIONS_DIR' ) ) {
		define( 'ROOT_WPBL_TRANSLATIONS_DIR', ROOT_WPBL_CLASSES_DIR . 'translations/' );
	}

	// Root WPBL Root Img Icons Dir.
	if ( ! defined('ROOT_WPBL_IMG_ICONS_URL' ) ) {
		define( 'ROOT_WPBL_IMG_ICONS_URL', ROOT_WPBL_URL . 'assets/img/icons/' );
	}

	// Root WPBL Root Utilities Dir.
	if ( ! defined('ROOT_WPBL_UTILITIES_DIR' ) ) {
		define( 'ROOT_WPBL_UTILITIES_DIR', ROOT_WPBL_CLASSES_DIR . 'utilities/' );
	}

	// Root plugin folder URL .
	define( 'BULKBOOKUPLOAD_ROOT_URL', plugins_url() . '/wpbooklist-bulkbookupload/' );

	// Root Classes Directory.
	define( 'BULKBOOKUPLOAD_CLASS_DIR', BULKBOOKUPLOAD_ROOT_DIR . 'includes/classes/' );

	// Root Update Directory.
	define( 'BULKBOOKUPLOAD_UPDATE_DIR', BULKBOOKUPLOAD_CLASS_DIR . 'update/' );

	// Root REST Classes Directory.
	define( 'BULKBOOKUPLOAD_CLASS_REST_DIR', BULKBOOKUPLOAD_ROOT_DIR . 'includes/classes/rest/' );

	// Root Compatability Classes Directory.
	define( 'BULKBOOKUPLOAD_CLASS_COMPAT_DIR', BULKBOOKUPLOAD_ROOT_DIR . 'includes/classes/compat/' );

	// Root Transients Directory.
	define( 'BULKBOOKUPLOAD_CLASS_TRANSIENTS_DIR', BULKBOOKUPLOAD_ROOT_DIR . 'includes/classes/transients/' );

	// Root Image URL.
	define( 'BULKBOOKUPLOAD_ROOT_IMG_URL', BULKBOOKUPLOAD_ROOT_URL . 'assets/img/' );

	// Root Image Icons URL.
	define( 'BULKBOOKUPLOAD_ROOT_IMG_ICONS_URL', BULKBOOKUPLOAD_ROOT_URL . 'assets/img/icons/' );

	// Root CSS URL.
	define( 'BULKBOOKUPLOAD_CSS_URL', BULKBOOKUPLOAD_ROOT_URL . 'assets/css/' );

	// Root JS URL.
	define( 'BULKBOOKUPLOAD_JS_URL', BULKBOOKUPLOAD_ROOT_URL . 'assets/js/' );

	// Root UI directory.
	define( 'BULKBOOKUPLOAD_ROOT_INCLUDES_UI', BULKBOOKUPLOAD_ROOT_DIR . 'includes/ui/' );

	// Root UI Admin directory.
	define( 'BULKBOOKUPLOAD_ROOT_INCLUDES_UI_ADMIN_DIR', BULKBOOKUPLOAD_ROOT_DIR . 'includes/ui/admin/' );

	// Define the Uploads base directory.
	$uploads     = wp_upload_dir();
	$upload_path = $uploads['basedir'];
	define( 'BULKBOOKUPLOAD_UPLOADS_BASE_DIR', $upload_path . '/' );

	// Define the Uploads base URL.
	$upload_url = $uploads['baseurl'];
	define( 'BULKBOOKUPLOAD_UPLOADS_BASE_URL', $upload_url . '/' );

	// Nonces array.
	define( 'BULKBOOKUPLOAD_NONCES_ARRAY',
		wp_json_encode(array(
			'adminnonce1' => 'wpbooklist_bulkbookupload_save_license_key_action_callback',
			'adminnonce2' => 'wpbooklist_bulkbookupload_action_callback',
		))
	);

/* END OF CONSTANT DEFINITIONS */

/* MISC. INCLUSIONS & DEFINITIONS */

	// Loading textdomain.
	load_plugin_textdomain( 'wpbooklist', false, BULKBOOKUPLOAD_ROOT_DIR . 'languages' );

/* END MISC. INCLUSIONS & DEFINITIONS */

/* CLASS INSTANTIATIONS */

	// Call the class found in wpbooklist-functions.php.
	$bulkbookupload_general_functions = new BulkBookUpload_General_Functions();

	// Call the class found in wpbooklist-functions.php.
	$bulkbookupload_ajax_functions = new BulkBookUpload_Ajax_Functions();

	// Include the Update Class.
	$bulkbookupload_update_functions = new WPBookList_BulkBookUpload_Update();


/* END CLASS INSTANTIATIONS */


/* FUNCTIONS FOUND IN CLASS-WPBOOKLIST-GENERAL-FUNCTIONS.PHP THAT APPLY PLUGIN-WIDE */

	// Function that loads up the menu page entry for this Extension.
	//add_filter( 'wpbooklist_add_sub_menu', array( $bulkbookupload_general_functions, 'wpbooklist_bulkbookupload_submenu' ) );

	// Adding the function that will take our BULKBOOKUPLOAD_NONCES_ARRAY Constant from above and create actual nonces to be passed to Javascript functions.
	add_action( 'init', array( $bulkbookupload_general_functions, 'wpbooklist_bulkbookupload_create_nonces' ) );

	// Function to run any code that is needed to modify the plugin between different versions.
	add_action( 'plugins_loaded', array( $bulkbookupload_general_functions, 'wpbooklist_bulkbookupload_update_upgrade_function' ) );

	// Adding the admin js file.
	add_action( 'admin_enqueue_scripts', array( $bulkbookupload_general_functions, 'wpbooklist_bulkbookupload_admin_js' ) );

	// Adding the frontend js file.
	add_action( 'wp_enqueue_scripts', array( $bulkbookupload_general_functions, 'wpbooklist_bulkbookupload_frontend_js' ) );

	// Adding the admin css file for this extension.
	add_action( 'admin_enqueue_scripts', array( $bulkbookupload_general_functions, 'wpbooklist_bulkbookupload_admin_style' ) );

	// Adding the Front-End css file for this extension.
	add_action( 'wp_enqueue_scripts', array( $bulkbookupload_general_functions, 'wpbooklist_bulkbookupload_frontend_style' ) );

	// Function to add table names to the global $wpdb.
	add_action( 'admin_footer', array( $bulkbookupload_general_functions, 'wpbooklist_bulkbookupload_register_table_name' ) );

	// Function to run any code that is needed to modify the plugin between different versions.
	add_action( 'admin_footer', array( $bulkbookupload_general_functions, 'wpbooklist_bulkbookupload_admin_pointers_javascript' ) );

	// Creates tables upon activation.
	register_activation_hook( __FILE__, array( $bulkbookupload_general_functions, 'wpbooklist_bulkbookupload_create_tables' ) );

	// Runs once upon extension activation and adds it's version number to the 'extensionversions' column in the 'wpbooklist_jre_user_options' table of the core plugin.
	register_activation_hook( __FILE__, array( $bulkbookupload_general_functions, 'wpbooklist_bulkbookupload_record_extension_version' ) );

	// And in the darkness bind them.
	add_filter( 'admin_footer', array( $bulkbookupload_general_functions, 'wpbooklist_bulkbookupload_smell_rose' ) );

	// Displays the 'Enter Your License Key' message at the top of the dashboard if the user hasn't done so already.
	add_action( 'admin_notices', array( $bulkbookupload_general_functions, 'wpbooklist_bulkbookupload_top_dashboard_license_notification' ) );

	// Code for adding file that prevents computer sleep during the bulk-upload process
	add_action('admin_enqueue_scripts', array( $bulkbookupload_general_functions, 'wpbooklist_jre_bulkbookupload_sleep_script' ) );


global $wpdb;
$test_name = $wpdb->prefix . 'wpbooklist_bulkbookupload_settings';
if ( $test_name === $wpdb->get_var( "SHOW TABLES LIKE '$test_name'" ) ) {
	$extension_settings = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . 'wpbooklist_bulkbookupload_settings' );
	if ( false !== stripos( $extension_settings->fdks, 'aod' ) ) {
		add_filter( 'wpbooklist_add_tab_books', array( $bulkbookupload_general_functions, 'wpbooklist_bulkbookupload_tab' ) );
	}
}



	// Function that adds in the License Key Submission form on this Extension's entry on the plugins page.
	add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $bulkbookupload_general_functions, 'wpbooklist_bulkbookupload_pluginspage_nonce_entry' ) );

	// Verifies that the core WPBookList plugin is installed and activated - otherwise, the Extension doesn't load and a message is displayed to the user.
	register_activation_hook( __FILE__, array( $bulkbookupload_general_functions, 'wpbooklist_bulkbookupload_core_plugin_required' ) );



/* END OF FUNCTIONS FOUND IN CLASS-WPBOOKLIST-GENERAL-FUNCTIONS.PHP THAT APPLY PLUGIN-WIDE */

/* FUNCTIONS FOUND IN CLASS-WPBOOKLIST-AJAX-FUNCTIONS.PHP THAT APPLY PLUGIN-WIDE */

	// For receiving user feedback upon deactivation & deletion.
	add_action( 'wp_ajax_bulkbookupload_exit_results_action', array( $bulkbookupload_ajax_functions, 'bulkbookupload_exit_results_action_callback' ) );

	// Callback function for handling the saving of the user's License Key.
	add_action( 'wp_ajax_wpbooklist_bulkbookupload_save_license_key_action', array( $bulkbookupload_ajax_functions, 'wpbooklist_bulkbookupload_save_license_key_action_callback' ) );

	add_action( 'wp_ajax_wpbooklist_bulkbookupload_action', array( $bulkbookupload_ajax_functions, 'wpbooklist_bulkbookupload_action_callback' ) );

/* END OF FUNCTIONS FOUND IN CLASS-WPBOOKLIST-AJAX-FUNCTIONS.PHP THAT APPLY PLUGIN-WIDE */












