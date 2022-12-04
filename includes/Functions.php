<?php

namespace WCGT\WC_Gift_Proceed;

use WP_Query;

defined('ABSPATH') || exit;

class Functions {

    public function update_text($option_name = '', $option_value = null) {
        if (!empty($option_value)) {
            update_option($option_name, $option_value);
        }
    }
}
