<?php

namespace WCGT\WC_Gift_Proceed\Admin;

/**
 * The menu handler class
 */
class Menu {

    /**
     * Initalize the class
     */
    function __construct() {
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
    }

    /**
     * Register admin menu
     *
     * @return void
     */  
    public function admin_menu() {
        $parent_slug = 'WCGT-academy';
        $capability = 'manage_options';
        add_menu_page( __('weDevs WC_Gift_Proceed', 'WCGT-academy'),  __('WC_Gift_Proceed', 'WCGT-academy'), $capability, $parent_slug, [ $this, 'addressbook_page'], 'dashicons-welcome-learn-more' );
        add_submenu_page( $parent_slug, __('Address Book', 'WCGT-academy'), __('Address Book', 'WCGT-academy'), $capability, $parent_slug, [ $this, 'addressbook_page' ] );
        add_submenu_page( $parent_slug, __('Settings', 'WCGT-academy'), __('Settings', 'WCGT-academy'), $capability, 'WCGT-settings', [ $this, 'addressbook_settings' ] );
    }

    /**
     * Initilize address bookpage
     */
    public function addressbook_page() {
        $addressbook = new Addressbook();
        $addressbook->plugin_page();
    }

    /**
     * Render the plugin page
     * 
     * @return void
     */
    public function addressbook_settings() {
        echo 'Hello World';
    }
}
