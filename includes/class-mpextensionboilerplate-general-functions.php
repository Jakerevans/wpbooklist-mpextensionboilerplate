<?php
/**
 * Class MpExtensionBoilerplate_General_Functions - class-mpextensionboilerplate-general-functions.php
 *
 * @author   Jake Evans
 * @category Admin
 * @package  Includes
 * @version  6.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'MpExtensionBoilerplate_General_Functions', false ) ) :
	/**
	 * MpExtensionBoilerplate_General_Functions class. Here we'll do things like enqueue scripts/css, set up menus, etc.
	 */
	class MpExtensionBoilerplate_General_Functions {

		/**
		 * Verifies that the core WPBookList plugin is installed and activated - otherwise, the Extension doesn't load and a message is displayed to the user.
		 */
		public function wpbooklist_mpextensionboilerplate_core_plugin_required() {

			// Require core WPBookList Plugin.
			if ( ! is_plugin_active( 'wpbooklist/wpbooklist.php' ) && current_user_can( 'activate_plugins' ) ) {

				// Stop activation redirect and show error.
				wp_die( 'Whoops! This WPBookList Extension requires the Core WPBookList Plugin to be installed and activated! <br><a target="_blank" href="https://wordpress.org/plugins/wpbooklist/">Download WPBookList Here!</a><br><br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>' );
			}
		}

		/**
		 * Verifies the license for the extension is valid - otherwise, the Extension doesn't load.
		 *
		 * @param  array $plugins List of plugins to activate & load.
		 */
		public function wpbooklist_mpextensionboilerplate_verify_license( $plugins ) {

			global $wpdb;

			// Get license key from plugin options, if it's already been saved. If it has, don't display anything.
			$this->extension_settings = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . 'wpbooklist_mpextensionboilerplate_settings' );

			$unnecessary_plugins[] = 'wpbooklist-mpextensionboilerplate/wpbooklist-mpextensionboilerplate.php';

			// If the License Key just hasn't been entered yet...
			if ( null === $this->extension_settings->license || '' === $this->extension_settings->license ) {

				foreach ( $unnecessary_plugins as $plugin ) {
					$k = array_mpextensionboilerplate( $plugin, $plugins );
					if ( false !== $k ) {
						unset( $plugins[ $k ] );
					}
				}

				return $plugins;

			} else {

				// If a License key has been saved, let's verify it, and if it's not good, don't load the plugin.
				$license_good_flag = true;

				if ( $license_good_flag ) {
					return $plugins;
				} else {

					foreach ( $unnecessary_plugins as $plugin ) {
						$k = array_mpextensionboilerplate( $plugin, $plugins );
						if ( false !== $k ) {
							unset( $plugins[ $k ] );
						}
					}

					return $plugins;

				}
			}
		}

		/**
		 * Adds in the 'Enter License Key' text input and submit button.
		 *
		 * @param  array $links List of existing plugin action links.
		 * @return array List of modified plugin action links.
		 */
		public function wpbooklist_mpextensionboilerplate_pluginspage_license_entry( $links ) {

			global $wpdb;

			require_once ROOT_WPBL_TRANSLATIONS_DIR . 'class-wpbooklist-translations.php';
			$trans = new WPBookList_Translations();

			// Get license key from plugin options, if it's already been saved. If it has, don't display anything.
			$this->extension_settings = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . 'wpbooklist_mpextensionboilerplate_settings' );

			if ( null === $this->extension_settings->license || '' === $this->extension_settings->license ) {
				$value = $trans->trans_613;
			} else {
				$value = $this->extension_settings->license;
			}

			$license_html = '
				<form>
					<input id="wpbooklist-extension-licence-key-plugins-page-input-mpextensionboilerplate" class="wpbooklist-extension-licence-key-plugins-page-input" type="text" placeholder="' . $trans->trans_613 . '" value="' . $value . '"></input>
					<button id="wpbooklist-extension-licence-key-plugins-page-button-mpextensionboilerplate" class="wpbooklist-extension-licence-key-plugins-page-button">' . $trans->trans_614 . '</button>
				</form>';

			array_push( $links, $license_html );

			return $links;

		}

		/**
		 * Displays the 'Enter Your License Key' message at the top of the dashboard if the user hasn't done so already.
		 */
		public function wpbooklist_mpextensionboilerplate_top_dashboard_license_notification() {

			global $wpdb;

			// Get license key from plugin options, if it's already been saved. If it has, don't display anything.
			$this->extension_settings = $wpdb->get_row( 'SELECT * FROM ' . $wpdb->prefix . 'wpbooklist_mpextensionboilerplate_settings' );

			if ( null === $this->extension_settings->license || '' === $this->extension_settings->license ) {

				require_once ROOT_WPBL_TRANSLATIONS_DIR . 'class-wpbooklist-translations.php';
				$trans = new WPBookList_Translations();

				echo '
				<div class="notice notice-success is-dismissible">
					<form class="wpbooklist-extension-licence-key-dashboard-form" id="wpbooklist-extension-licence-key-dashboard-form-mpextensionboilerplate">
						<p class="wpbooklist-extension-licence-key-dashboard-title">' . $trans->trans_615 . '</p>
						<input id="wpbooklist-extension-licence-key-dashboard-input-mpextensionboilerplate" class="wpbooklist-extension-licence-key-dashboard-input" type="text" placeholder="' . $trans->trans_613 . '" value="' . $trans->trans_613 . '"></input>
						<button data-ext="mpextensionboilerplate" id="wpbooklist-extension-licence-key-dashboard-button-mpextensionboilerplate" class="wpbooklist-extension-licence-key-dashboard-button">' . $trans->trans_616 . '</button>
					</form>
				</div>';
			}
		}

		/** Functions that loads up the menu page entry for this Extension.
		 *
		 *  @param array $submenu_array - The array that contains submenu entries to add to.
		 */
		public function wpbooklist_mpextensionboilerplate_submenu( $submenu_array ) {
			$extra_submenu = array(
				'MpExtensionBoilerplate',
			);

			// Combine the two arrays.
			$submenu_array = array_merge( $submenu_array, $extra_submenu );
			return $submenu_array;
		}

		/**
		 *  Here we take the Constant defined in wpbooklist.php that holds the values that all our nonces will be created from, we create the actual nonces using wp_create_nonce, and the we define our new, final nonces Constant, called WPBOOKLIST_FINAL_NONCES_ARRAY.
		 */
		public function wpbooklist_mpextensionboilerplate_create_nonces() {

			$temp_array = array();
			foreach ( json_decode( MPEXTENSIONBOILERPLATE_NONCES_ARRAY ) as $key => $noncetext ) {
				$nonce              = wp_create_nonce( $noncetext );
				$temp_array[ $key ] = $nonce;
			}

			// Defining our final nonce array.
			define( 'MPEXTENSIONBOILERPLATE_FINAL_NONCES_ARRAY', wp_json_encode( $temp_array ) );

		}

		/**
		 *  Runs once upon extension activation and adds it's version number to the 'extensionversions' column in the 'wpbooklist_jre_user_options' table of the core plugin.
		 */
		public function wpbooklist_mpextensionboilerplate_record_extension_version() {
			global $wpdb;
			$existing_string = $wpdb->get_row( 'SELECT * from ' . $wpdb->prefix . 'wpbooklist_jre_user_options' );

			// Check to see if Extension is already registered.
			if ( false !== strpos( $existing_string->extensionversions, 'mpextensionboilerplate' ) ) {
				$split_string = explode( 'mpextensionboilerplate', $existing_string->extensionversions );
				$first_part   = $split_string[0];
				$last_part    = substr( $split_string[1], 5 );
				$new_string   = $first_part . 'mpextensionboilerplate' . WPBOOKLIST_MPEXTENSIONBOILERPLATE_VERSION_NUM . $last_part;
			} else {
				$new_string = $existing_string->extensionversions . 'mpextensionboilerplate' . WPBOOKLIST_MPEXTENSIONBOILERPLATE_VERSION_NUM;
			}

			$data         = array(
				'extensionversions' => $new_string,
			);
			$format       = array( '%s' );
			$where        = array( 'ID' => 1 );
			$where_format = array( '%d' );
			$wpdb->update( $wpdb->prefix . 'wpbooklist_jre_user_options', $data, $where, $format, $where_format );

		}

		/**
		 *  Function to run the compatability code in the Compat class for upgrades/updates, if stored version number doesn't match the defined global in wpbooklist-mpextensionboilerplate.php
		 */
		public function wpbooklist_mpextensionboilerplate_update_upgrade_function() {

			// Get current version #.
			global $wpdb;
			$existing_string = $wpdb->get_row( 'SELECT * from ' . $wpdb->prefix . 'wpbooklist_jre_user_options' );

			// Check to see if Extension is already registered and matches this version.
			if ( false !== strpos( $existing_string->extensionversions, 'mpextensionboilerplate' ) ) {
				$split_string = explode( 'mpextensionboilerplate', $existing_string->extensionversions );
				$version      = substr( $split_string[1], 0, 5 );

				// If version number does not match the current version number found in wpbooklist.php, call the Compat class and run upgrade functions.
				if ( WPBOOKLIST_MPEXTENSIONBOILERPLATE_VERSION_NUM !== $version ) {
					require_once MPEXTENSIONBOILERPLATE_CLASS_COMPAT_DIR . 'class-mpextensionboilerplate-compat-functions.php';
					$compat_class = new MpExtensionBoilerplate_Compat_Functions();
				}
			}
		}

		/**
		 * Adding the admin js file
		 */
		public function wpbooklist_mpextensionboilerplate_admin_js() {

			wp_register_script( 'wpbooklist_mpextensionboilerplate_adminjs', MPEXTENSIONBOILERPLATE_JS_URL . 'wpbooklist_mpextensionboilerplate_admin.min.js', array( 'jquery' ), WPBOOKLIST_VERSION_NUM, true );

			// Next 4-5 lines are required to allow translations of strings that would otherwise live in the wpbooklist-admin-js.js JavaScript File.
			require_once ROOT_WPBL_TRANSLATIONS_DIR . 'class-wpbooklist-translations.php';
			$trans = new WPBookList_Translations();

			// Localize the script with the appropriate translation array from the Translations class.
			$translation_array1 = $trans->trans_strings();

			// Now grab all of our Nonces to pass to the JavaScript for the Ajax functions and merge with the Translations array.
			$final_array_of_php_values = array_merge( $translation_array1, json_decode( MPEXTENSIONBOILERPLATE_FINAL_NONCES_ARRAY, true ) );

			// Adding some other individual values we may need.
			$final_array_of_php_values['MPEXTENSIONBOILERPLATE_ROOT_IMG_ICONS_URL'] = MPEXTENSIONBOILERPLATE_ROOT_IMG_ICONS_URL;
			$final_array_of_php_values['MPEXTENSIONBOILERPLATE_ROOT_IMG_URL']       = MPEXTENSIONBOILERPLATE_ROOT_IMG_URL;
			$final_array_of_php_values['FOR_TAB_HIGHLIGHT']                         = admin_url() . 'admin.php';
			$final_array_of_php_values['SAVED_ATTACHEMENT_ID']                      = get_option( 'media_selector_attachment_id', 0 );

			// Now registering/localizing our JavaScript file, passing all the PHP variables we'll need in our $final_array_of_php_values array, to be accessed from 'wphealthtracker_php_variables' object (like wphealthtracker_php_variables.nameofkey, like any other JavaScript object).
			wp_localize_script( 'wpbooklist_mpextensionboilerplate_adminjs', 'wpbooklistMpExtensionBoilerplatePhpVariables', $final_array_of_php_values );

			wp_enqueue_script( 'wpbooklist_mpextensionboilerplate_adminjs' );

			return $final_array_of_php_values;

		}

		/**
		 * Adding the frontend js file
		 */
		public function wpbooklist_mpextensionboilerplate_frontend_js() {

			wp_register_script( 'wpbooklist_mpextensionboilerplate_frontendjs', MPEXTENSIONBOILERPLATE_JS_URL . 'wpbooklist_mpextensionboilerplate_frontend.min.js', array( 'jquery' ), WPBOOKLIST_MPEXTENSIONBOILERPLATE_VERSION_NUM, true );

			// Next 4-5 lines are required to allow translations of strings that would otherwise live in the wpbooklist-admin-js.js JavaScript File.
			require_once ROOT_WPBL_TRANSLATIONS_DIR . 'class-wpbooklist-translations.php';
			$trans = new WPBookList_Translations();

			// Localize the script with the appropriate translation array from the Translations class.
			$translation_array1 = $trans->trans_strings();

			// Now grab all of our Nonces to pass to the JavaScript for the Ajax functions and merge with the Translations array.
			$final_array_of_php_values = array_merge( $translation_array1, json_decode( MPEXTENSIONBOILERPLATE_FINAL_NONCES_ARRAY, true ) );

			// Adding some other individual values we may need.
			$final_array_of_php_values['MPEXTENSIONBOILERPLATE_ROOT_IMG_ICONS_URL'] = MPEXTENSIONBOILERPLATE_ROOT_IMG_ICONS_URL;
			$final_array_of_php_values['MPEXTENSIONBOILERPLATE_ROOT_IMG_URL']       = MPEXTENSIONBOILERPLATE_ROOT_IMG_URL;

			// Now registering/localizing our JavaScript file, passing all the PHP variables we'll need in our $final_array_of_php_values array, to be accessed from 'wphealthtracker_php_variables' object (like wphealthtracker_php_variables.nameofkey, like any other JavaScript object).
			wp_localize_script( 'wpbooklist_mpextensionboilerplate_frontendjs', 'wpbooklistMpExtensionBoilerplatePhpVariables', $final_array_of_php_values );

			wp_enqueue_script( 'wpbooklist_mpextensionboilerplate_frontendjs' );

			return $final_array_of_php_values;

		}

		/**
		 * Adding the admin css file
		 */
		public function wpbooklist_mpextensionboilerplate_admin_style() {

			wp_register_style( 'wpbooklist_mpextensionboilerplate_adminui', MPEXTENSIONBOILERPLATE_CSS_URL . 'wpbooklist-mpextensionboilerplate-main-admin.css', null, WPBOOKLIST_MPEXTENSIONBOILERPLATE_VERSION_NUM );
			wp_enqueue_style( 'wpbooklist_mpextensionboilerplate_adminui' );

		}

		/**
		 * Adding the frontend css file
		 */
		public function wpbooklist_mpextensionboilerplate_frontend_style() {

			wp_register_style( 'wpbooklist_mpextensionboilerplate_frontendui', MPEXTENSIONBOILERPLATE_CSS_URL . 'wpbooklist-mpextensionboilerplate-main-frontend.css', null, WPBOOKLIST_MPEXTENSIONBOILERPLATE_VERSION_NUM );
			wp_enqueue_style( 'wpbooklist_mpextensionboilerplate_frontendui' );

		}

		/**
		 *  Function to add table names to the global $wpdb.
		 */
		public function wpbooklist_mpextensionboilerplate_register_table_name() {
			global $wpdb;
			$wpdb->wpbooklist_mpextensionboilerplate_settings = "{$wpdb->prefix}wpbooklist_mpextensionboilerplate_settings";
		}

		/**
		 *  Function that calls the Style and Scripts needed for displaying of admin pointer messages.
		 */
		public function wpbooklist_mpextensionboilerplate_admin_pointers_javascript() {
			wp_enqueue_style( 'wp-pointer' );
			wp_enqueue_script( 'wp-pointer' );
			wp_enqueue_script( 'utils' );
		}

		/**
		 *  Runs once upon plugin activation and creates the table that holds info on WPBookList Pages & Posts.
		 */
		public function wpbooklist_mpextensionboilerplate_create_tables() {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			global $wpdb;
			global $charset_collate;

			// Call this manually as we may have missed the init hook.
			$this->wpbooklist_mpextensionboilerplate_register_table_name();

			$sql_create_table1 = "CREATE TABLE {$wpdb->wpbooklist_mpextensionboilerplate_settings}
			(
				ID bigint(190) auto_increment,
				license varchar(255),
				PRIMARY KEY  (ID),
				KEY license (license)
			) $charset_collate; ";

			// If table doesn't exist, create table and add initial data to it.
			$test_name = $wpdb->prefix . 'wpbooklist_mpextensionboilerplate_settings';
			if ( $test_name !== $wpdb->get_var( "SHOW TABLES LIKE '$test_name'" ) ) {
				dbDelta( $sql_create_table1 );
				$table_name = $wpdb->prefix . 'wpbooklist_mpextensionboilerplate_settings';
				$wpdb->insert( $table_name, array( 'ID' => 1, 'license' => 'placeholder', ) );
			}
		}

	}
endif;
