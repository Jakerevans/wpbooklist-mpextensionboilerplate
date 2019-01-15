<?php
/**
 * WordPress Book List MpExtensionBoilerplate Extension
 *
 * @package     WordPress Book List MpExtensionBoilerplate Extension
 * @author      Jake Evans
 * @copyright   2018 Jake Evans
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: WPBookList MpExtensionBoilerplate Extension
 * Plugin URI: https://www.jakerevans.com
 * Description: A Boilerplate Extension for WPBookList that creates a menu page and has it's own tabs.
 * Version: 1.0.0
 * Author: Jake Evans
 * Text Domain: wpbooklist
 * Author URI: https://www.jakerevans.com
 */

/*
 * SETUP NOTES:
 *
 * Change all filename instances from mpextensionboilerplate to desired plugin name
 *
 * Modify Plugin Name
 *
 * Modify Description
 *
 * Modify Version Number in Block comment and in Constant
 *
 * Find & Replace these 3 strings:
 * mpextensionboilerplate
 * MpExtensionBoilerplate
 * MPEXTENSIONBOILERPLATE
 * repw with something also random - db column that holds license.
 *
 *
 * Change the EDD_SL_ITEM_ID_MPEXTENSIONBOILERPLATE contant below.
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
	require_once 'includes/class-mpextensionboilerplate-general-functions.php';
	require_once 'includes/class-mpextensionboilerplate-ajax-functions.php';
	require_once 'includes/classes/update/class-wpbooklist-mpextensionboilerplate-update.php';
/* END REQUIRE STATEMENTS */

/* CONSTANT DEFINITIONS */

	if ( ! defined('WPBOOKLIST_VERSION_NUM' ) ) {
		define( 'WPBOOKLIST_VERSION_NUM', '6.1.2' );
	}

	// This is the URL our updater / license checker pings. This should be the URL of the site with EDD installed.
	define( 'EDD_SL_STORE_URL_MPEXTENSIONBOILERPLATE', 'https://wpbooklist.com' );

	// The id of your product in EDD.
	define( 'EDD_SL_ITEM_ID_MPEXTENSIONBOILERPLATE', 46 );

	// This Extension's Version Number.
	define( 'WPBOOKLIST_MPEXTENSIONBOILERPLATE_VERSION_NUM', '1.0.0' );

	// Root plugin folder directory.
	define( 'MPEXTENSIONBOILERPLATE_ROOT_DIR', plugin_dir_path( __FILE__ ) );

	// Root WordPress Plugin Directory. The If is for taking into account the update process - a temp folder gets created when updating, which temporarily replaces the 'wpbooklist-bulkbookupload' folder.
	if ( false !== stripos( plugin_dir_path( __FILE__ ) , '/wpbooklist-mpextensionboilerplate' ) ) { 
		define( 'MPEXTENSIONBOILERPLATE_ROOT_WP_PLUGINS_DIR', str_replace( '/wpbooklist-mpextensionboilerplate', '', plugin_dir_path( __FILE__ ) ) );
	} else {
		$temp = explode( 'plugins/', plugin_dir_path( __FILE__ ) );
		define( 'MPEXTENSIONBOILERPLATE_ROOT_WP_PLUGINS_DIR', $temp[0] . 'plugins/' );
	}

	// Root WPBL Dir.
	if ( ! defined('MPEXTENSIONBOILERPLATE_ROOT_WPBL_DIR' ) ) {
		define( 'MPEXTENSIONBOILERPLATE_ROOT_WPBL_DIR', MPEXTENSIONBOILERPLATE_ROOT_WP_PLUGINS_DIR . 'wpbooklist/' );
	}

		// Root WPBL Dir.
	if ( ! defined( 'ROOT_WPBL_DIR' ) ) {
		define( 'ROOT_WPBL_DIR', MPEXTENSIONBOILERPLATE_ROOT_WP_PLUGINS_DIR . 'wpbooklist/' );
	}

	// Root WPBL Url.
	if ( ! defined('ROOT_WPBL_URL' ) ) {
		define( 'ROOT_WPBL_URL', plugins_url() . '/wpbooklist/' );
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
	define( 'MPEXTENSIONBOILERPLATE_ROOT_URL', plugins_url() . '/wpbooklist-mpextensionboilerplate/' );

	// Root Classes Directory.
	define( 'MPEXTENSIONBOILERPLATE_CLASS_DIR', MPEXTENSIONBOILERPLATE_ROOT_DIR . 'includes/classes/' );

	// Root Update Directory.
	define( 'MPEXTENSIONBOILERPLATE_UPDATE_DIR', MPEXTENSIONBOILERPLATE_CLASS_DIR . 'update/' );

	// Root REST Classes Directory.
	define( 'MPEXTENSIONBOILERPLATE_CLASS_REST_DIR', MPEXTENSIONBOILERPLATE_ROOT_DIR . 'includes/classes/rest/' );

	// Root Compatability Classes Directory.
	define( 'MPEXTENSIONBOILERPLATE_CLASS_COMPAT_DIR', MPEXTENSIONBOILERPLATE_ROOT_DIR . 'includes/classes/compat/' );

	// Root Transients Directory.
	define( 'MPEXTENSIONBOILERPLATE_CLASS_TRANSIENTS_DIR', MPEXTENSIONBOILERPLATE_ROOT_DIR . 'includes/classes/transients/' );

	// Root Image URL.
	define( 'MPEXTENSIONBOILERPLATE_ROOT_IMG_URL', MPEXTENSIONBOILERPLATE_ROOT_URL . 'assets/img/' );

	// Root Image Icons URL.
	define( 'MPEXTENSIONBOILERPLATE_ROOT_IMG_ICONS_URL', MPEXTENSIONBOILERPLATE_ROOT_URL . 'assets/img/icons/' );

	// Root CSS URL.
	define( 'MPEXTENSIONBOILERPLATE_CSS_URL', MPEXTENSIONBOILERPLATE_ROOT_URL . 'assets/css/' );

	// Root JS URL.
	define( 'MPEXTENSIONBOILERPLATE_JS_URL', MPEXTENSIONBOILERPLATE_ROOT_URL . 'assets/js/' );

	// Root UI directory.
	define( 'MPEXTENSIONBOILERPLATE_ROOT_INCLUDES_UI', MPEXTENSIONBOILERPLATE_ROOT_DIR . 'includes/ui/' );

	// Root UI Admin directory.
	define( 'MPEXTENSIONBOILERPLATE_ROOT_INCLUDES_UI_ADMIN_DIR', MPEXTENSIONBOILERPLATE_ROOT_DIR . 'includes/ui/admin/' );

	// Define the Uploads base directory.
	$uploads     = wp_upload_dir();
	$upload_path = $uploads['basedir'];
	define( 'MPEXTENSIONBOILERPLATE_UPLOADS_BASE_DIR', $upload_path . '/' );

	// Define the Uploads base URL.
	$upload_url = $uploads['baseurl'];
	define( 'MPEXTENSIONBOILERPLATE_UPLOADS_BASE_URL', $upload_url . '/' );

	// Nonces array.
	define( 'MPEXTENSIONBOILERPLATE_NONCES_ARRAY',
		wp_json_encode(array(
			'adminnonce1' => 'wpbooklist_mpextensionboilerplate_save_license_key_action_callback',
		))
	);

/* END OF CONSTANT DEFINITIONS */

/* MISC. INCLUSIONS & DEFINITIONS */

	// Loading textdomain.
	load_plugin_textdomain( 'wpbooklist', false, MPEXTENSIONBOILERPLATE_ROOT_DIR . 'languages' );

/* END MISC. INCLUSIONS & DEFINITIONS */

/* CLASS INSTANTIATIONS */

	// Call the class found in wpbooklist-functions.php.
	$mpextensionboilerplate_general_functions = new MpExtensionBoilerplate_General_Functions();

	// Call the class found in wpbooklist-functions.php.
	$mpextensionboilerplate_ajax_functions = new MpExtensionBoilerplate_Ajax_Functions();

	// Include the Update Class.
	$mpextensionboilerplate_update_functions = new WPBookList_MpExtensionBoilerplate_Update();


/* END CLASS INSTANTIATIONS */


/* FUNCTIONS FOUND IN CLASS-WPBOOKLIST-GENERAL-FUNCTIONS.PHP THAT APPLY PLUGIN-WIDE */

	// Function that adds in the License Key Submission form on this Extension's entry on the plugins page.
	add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $mpextensionboilerplate_general_functions, 'wpbooklist_mpextensionboilerplate_pluginspage_nonce_entry' ) );

	// Function that loads up the menu page entry for this Extension.
	add_filter( 'wpbooklist_add_sub_menu', array( $mpextensionboilerplate_general_functions, 'wpbooklist_mpextensionboilerplate_submenu' ) );

	// Adding the function that will take our MPEXTENSIONBOILERPLATE_NONCES_ARRAY Constant from above and create actual nonces to be passed to Javascript functions.
	add_action( 'init', array( $mpextensionboilerplate_general_functions, 'wpbooklist_mpextensionboilerplate_create_nonces' ) );

	// Function to run any code that is needed to modify the plugin between different versions.
	add_action( 'plugins_loaded', array( $mpextensionboilerplate_general_functions, 'wpbooklist_mpextensionboilerplate_update_upgrade_function' ) );

	// Adding the admin js file.
	add_action( 'admin_enqueue_scripts', array( $mpextensionboilerplate_general_functions, 'wpbooklist_mpextensionboilerplate_admin_js' ) );

	// Adding the frontend js file.
	add_action( 'wp_enqueue_scripts', array( $mpextensionboilerplate_general_functions, 'wpbooklist_mpextensionboilerplate_frontend_js' ) );

	// Adding the admin css file for this extension.
	add_action( 'admin_enqueue_scripts', array( $mpextensionboilerplate_general_functions, 'wpbooklist_mpextensionboilerplate_admin_style' ) );

	// Adding the Front-End css file for this extension.
	add_action( 'wp_enqueue_scripts', array( $mpextensionboilerplate_general_functions, 'wpbooklist_mpextensionboilerplate_frontend_style' ) );

	// Function to add table names to the global $wpdb.
	add_action( 'admin_footer', array( $mpextensionboilerplate_general_functions, 'wpbooklist_mpextensionboilerplate_register_table_name' ) );

	// Function taht adds in any possible admin pointers
	add_action( 'admin_footer', array( $mpextensionboilerplate_general_functions, 'wpbooklist_mpextensionboilerplate_admin_pointers_javascript' ) );

	// Creates tables upon activation.
	register_activation_hook( __FILE__, array( $mpextensionboilerplate_general_functions, 'wpbooklist_mpextensionboilerplate_create_tables' ) );

	// Runs once upon extension activation and adds it's version number to the 'extensionversions' column in the 'wpbooklist_jre_user_options' table of the core plugin.
	register_activation_hook( __FILE__, array( $mpextensionboilerplate_general_functions, 'wpbooklist_mpextensionboilerplate_record_extension_version' ) );

	// And in the darkness bind them.
	add_filter( 'admin_footer', array( $mpextensionboilerplate_general_functions, 'wpbooklist_mpextensionboilerplate_smell_rose' ) );

	// Displays the 'Enter Your License Key' message at the top of the dashboard if the user hasn't done so already.
	add_action( 'admin_notices', array( $mpextensionboilerplate_general_functions, 'wpbooklist_mpextensionboilerplate_top_dashboard_license_notification' ) );

	/*
		global $wpdb;
		$test_name = $wpdb->prefix . 'wpbooklist_mpextensionboilerplate_settings';
		if ( $test_name === $wpdb->get_var( "SHOW TABLES LIKE '$test_name'" ) ) {
			$extension_settings = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . 'wpbooklist_mpextensionboilerplate_settings' );
			if ( false !== stripos( $extension_settings->repw, 'aod' ) ) {
				add_filter( 'wpbooklist_add_tab_settings', array( $mpextensionboilerplate_general_functions, 'wpbooklist_affiliate_tab' ) );
			}
		}

	*/

	// Function that adds in the License Key Submission form on this Extension's entry on the plugins page.
	add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $mpextensionboilerplate_general_functions, 'wpbooklist_mpextensionboilerplate_pluginspage_nonce_entry' ) );

	// Verifies that the core WPBookList plugin is installed and activated - otherwise, the Extension doesn't load and a message is displayed to the user.
	register_activation_hook( __FILE__, array( $mpextensionboilerplate_general_functions, 'wpbooklist_mpextensionboilerplate_core_plugin_required' ) );



/* END OF FUNCTIONS FOUND IN CLASS-WPBOOKLIST-GENERAL-FUNCTIONS.PHP THAT APPLY PLUGIN-WIDE */

/* FUNCTIONS FOUND IN CLASS-WPBOOKLIST-AJAX-FUNCTIONS.PHP THAT APPLY PLUGIN-WIDE */

	// For receiving user feedback upon deactivation & deletion.
	add_action( 'wp_ajax_mpextensionboilerplate_exit_results_action', array( $mpextensionboilerplate_ajax_functions, 'mpextensionboilerplate_exit_results_action_callback' ) );

	// Callback function for handling the saving of the user's License Key.
	add_action( 'wp_ajax_wpbooklist_mpextensionboilerplate_save_license_key_action', array( $mpextensionboilerplate_ajax_functions, 'wpbooklist_mpextensionboilerplate_save_license_key_action_callback' ) );

/* END OF FUNCTIONS FOUND IN CLASS-WPBOOKLIST-AJAX-FUNCTIONS.PHP THAT APPLY PLUGIN-WIDE */






















