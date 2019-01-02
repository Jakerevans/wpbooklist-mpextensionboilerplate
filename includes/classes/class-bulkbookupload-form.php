<?php
/**
 * WPBookList WPBookList_Bulkbookupload_Form Tab Class
 *
 * @author   Jake Evans
 * @category ??????
 * @package  ??????
 * @version  1
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'WPBookList_Bulkbookupload_Form', false ) ) :
/**
 * WPBookList_Bulkbookupload_Form Class.
 */
class WPBookList_Bulkbookupload_Form {

	public static function output_bulkbookupload_form(){

		// Perform check for previously-saved Amazon Authorization
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpbooklist_jre_user_options';
		$opt_results = $wpdb->get_row("SELECT * FROM $table_name");

		$table_name = $wpdb->prefix . 'wpbooklist_jre_list_dynamic_db_names';
		$db_row = $wpdb->get_results("SELECT * FROM $table_name");

		$string1 = '<div id="wpbooklist-bulkbookupload-container">
			<p>To Bulk-Upload books via ISBN Number, simply select a library from the drop-down below, place the ISBN numbers in the text block (seperated by commas), and click the<span class="wpbooklist-color-orange-italic"> \'Add Books\'</span> button. Optionally, you can have <span class="wpbooklist-color-orange-italic">WPBookList</span> automatically create a Page or Post (or both!) for each book by checking the <span class="wpbooklist-color-orange-italic">\'Create a Page\'</span> or <span class="wpbooklist-color-orange-italic">\'Create a Post\'</span> checkboxes below.<br/><br/><span ';

				if($opt_results->amazonauth == 'true'){ 
					$string2 = 'style="display:none;"';
				} else {
					$string2 = '';
				}

		$string3 = ' >You must check the box below to authorize <span class="wpbooklist-color-orange-italic">WPBookList</span> to gather data from Amazon - otherwise, data will be gathered from various other sources, such as Google Books, Apple iBooks, and OpenLibrary. WPBookList uses it\'s own Amazon Product Advertising API keys to gather book data, but if you happen to have your own API keys, you can use those instead by adding them on the <a href="'.menu_page_url( 'WPBookList-Options-settings', false ).'&tab=api">Amazon Settings</a> page.</span></p>
      		<form id="wpbooklist-bulkbookupload-form" method="post" action="">
	          	<div id="wpbooklist-authorize-amazon-container">
	    			<table>';

	    			if($opt_results->amazonauth == 'true'){ 
						$string4 = '<tr style="display:none;"">
	    					<td><p id="auth-amazon-question-label">Authorize Amazon Usage?</p></td>
	    				</tr>
	    				<tr style="display:none;"">
	    					<td>
	    						<input checked type="checkbox" name="authorize-amazon-yes" />
	    						<label for="authorize-amazon-yes">Yes</label>
	    						<input type="checkbox" name="authorize-amazon-no" />
	    						<label for="authorize-amazon-no">No</label>
	    					</td>
	    				</tr>';
					} else {
						$string4 = '<tr>
	    					<td><p id="auth-amazon-question-label">Authorize Amazon Usage?</p></td>
	    				</tr>
	    				<tr>
	    					<td>
	    						<input type="checkbox" name="authorize-amazon-yes" />
	    						<label for="authorize-amazon-yes">Yes</label>
	    						<input type="checkbox" name="authorize-amazon-no" />
	    						<label for="authorize-amazon-no">No</label>
	    					</td>
	    				</tr>';
					}

					$string5 = '</table>
		    		</div>
		    		<div id="wpbooklist-bulkbookupload-select-library-label" for="wpbooklist-bulkbookupload-select-library">Select a Library to Add These Books To:</div>
		    		<select class="wpbooklist-bulkbookupload-select-default" id="wpbooklist-bulkbookupload-select-library">
		    			<option value="'.$wpdb->prefix.'wpbooklist_jre_saved_book_log">Default Library</option> ';

		    		$string6 = '';
		    		foreach($db_row as $db){
						if(($db->user_table_name != "") || ($db->user_table_name != null)){
							$string6 = $string6.'<option value="'.$wpdb->prefix.'wpbooklist_jre_'.$db->user_table_name.'">'.ucfirst($db->user_table_name).'</option>';
						}
					}

					$string7 = '    
	          		</select>
	          		<div id="wpbooklist-bulk-upload-page-post-container">
		    			<table>
		    				<tr>
		    					<td><p id="use-page-post-question-label">Create a Page and/or Post For Each Book?</p></td>
		    				</tr>
		    				<tr>
		    					<td>
		    						<input type="checkbox" name="bulk-upload-create-post" />
		    						<label for="bulk-upload-create-post">Create Posts</label>
		    						<input type="checkbox" name="bulk-upload-create-page" />
		    						<label for="bulk-upload-create-page">Create Pages</label>
		    					</td>
		    				</tr>';

		    				// Check to see if Storefront extension is active
							include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
							if(is_plugin_active('wpbooklist-storefront/wpbooklist-storefront.php')){

								$string7 = $string7.'<tr>
			    					<td>
			    						<input type="checkbox" name="bulk-upload-create-woo" />
			    						<label for="bulk-upload-create-post">Create WooCommerce Products?</label>
			    					</td>
		    					</tr>';
		    				}
	$string7 = $string7.'</table>
		    		</div>
		    		<div id="wpbooklist-bulkbookupload-textarea-submit-div">
		    		<textarea id="wpbooklist-bulkbookupload-textarea" placeholder="9780761345086,9780761169764,9780761162345,9780761169086,9780545790352,9780553106633..."></textarea>
		    		<div id="wpbooklist-bulkbookupload-status-div"></div>
		    		<div class="wpbooklist-spinner" id="wpbooklist-spinner-bulkbookupload"></div>
		    		<div id="wpbooklist-bulkbookupload-div-for-hiding-scroll">
		    			<div id="wpbooklist-bulkbookupload-title-response"></div>
		    		</div>
		    		<button id="wpbooklist-bulkbookupload-button">Add Books</button>



		    		</div>';

		    		$string8 = '</form></div>';

		return $string1.$string2.$string3.$string4.$string5.$string6.$string7.$string8;
	}
}

endif;