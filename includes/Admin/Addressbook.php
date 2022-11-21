<?php

namespace WCGT\WC_Gift_Proceed\Admin;

/**
 * Addressbook handler class
 */
class Addressbook {

    public function plugin_page() {
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';

        switch ($action) {
            default:
                $template = __DIR__ . '/views/address-list.php';
                break;
        }

        if ( file_exists( $template ) ) {
            include $template;
        }
    }
}
