<?php
/**
 * WPBookList Bulkbookupload Tab Class
 *
 * @author   Jake Evans
 * @category ?????????
 * @package  ?????????
 * @version  1
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'WPBookList_Bulkbookupload_Tab', false ) ) :
/**
 * WPBookList_Bulkbookupload_Tab Class.
 */
class WPBookList_Bulkbookupload_Tab {

    public function __construct() {
        // This extension relies on the admin template file in the core WPBookList plugin.
        require_once(CLASS_DIR.'class-admin-ui-template.php');
        // Instantiate the class
        $this->template = new WPBookList_Admin_UI_Template;

        // Require the file that contains the actual code that will be output within the admin template
        require_once(BULKBOOKUPLOAD_CLASS_DIR.'class-bulkbookupload-form.php');
        $this->form = new WPBookList_Bulkbookupload_Form;

        $this->output_open_admin_container();
        $this->output_tab_content();
        $this->output_close_admin_container();
        $this->output_admin_template_advert();
    }

    private function output_open_admin_container(){
        # The two options that will be used by the admin template in the core WPBookList plugin to set the title and image
        $title = 'Bulk Upload';
        $icon_url = BULKBOOKUPLOAD_ROOT_IMG_ICONS_URL.'server.svg';
        echo $this->template->output_open_admin_container($title, $icon_url);
    }

    private function output_tab_content(){
        echo $this->form->output_bulkbookupload_form();
    }

    private function output_close_admin_container(){
        echo $this->template->output_close_admin_container();
    }

    private function output_admin_template_advert(){
        echo $this->template->output_template_advert();
    }

}

endif;

// Instantiate the class
$am = new WPBookList_Bulkbookupload_Tab;
?>