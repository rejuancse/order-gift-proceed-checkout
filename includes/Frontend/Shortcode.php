<?php

namespace WCGT\WC_Gift_Proceed\Frontend;

class Shortcode {
    
    public function __construct() {
        add_shortcode( 'wcgt_gift', [ $this, 'shortcode_callback_func' ] );
    }

    public function shortcode_callback_func( $atts, $content = '' ) {
        return 'This is new shortcode';
    }
}
