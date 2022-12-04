<?php
/**
 * Plugin Name:       WooCommerce Gift Proceed Checkout
 * Description:       Handle the basics with this plugin.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Rejuan Ahamed
 * Text Domain:       wcgt
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugin class
 */
final class WC_Gift {

    /**
     * Plugin version
     *
     * @var string
     */
    const version = '1.0';

    /**
     * Class construcotr
     */
    private function __construct() {
        $this->define_constants();
        $this->includes_core();
        $this->initial_activation();

        // do_action('wcgt_before_load');
		// $this->run();
		// do_action('wcgt_after_load');

        register_activation_hook( __FILE__, [ $this, 'activate' ] );
        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
    }

    /**
     * Initializes a singleton instance
     *
     * @return \WC_Gift
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'WC_GIFT_VERSION', self::version );
        define( 'WC_GIFT_FILE', __FILE__ );
        define( 'WC_GIFT_URL', plugins_url( '', WC_GIFT_FILE ) );
        define( 'WC_GIFT_ASSETS', WC_GIFT_URL . '/assets' );
        define('WCGT_DIR_URL', plugin_dir_url( WC_GIFT_FILE ));
        define('WCGT_DIR_PATH', plugin_dir_path( WC_GIFT_FILE ));
    }

    public function includes_core() {
		require_once WCGT_DIR_PATH.'includes/Initial_Setup.php';
	}

    //Checking Vendor
	public function run() {
        $initial_setup = new \WCGT\WC_Gift_Proceed\Initial_Setup();
        
        $wcgt_file = WP_PLUGIN_DIR.'/woocommerce/woocommerce.php';

        if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

        } else {
            $wcgt_file = WP_PLUGIN_DIR.'/woocommerce/woocommerce.php';
            if (file_exists($wcgt_file) ) {
                add_action( 'admin_notices', array($initial_setup, 'free_plugin_installed_but_inactive_notice') );
            } elseif ( ! file_exists($wcgt_file) ) {
                add_action( 'admin_notices', array($initial_setup, 'free_plugin_not_installed') );
            }
        }
	}

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {
        if ( is_admin() ) {
            require_once WCGT_DIR_PATH.'includes/Admin.php';
        } else {
            new WCGT\WC_Gift_Proceed\Frontend();
        }
    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installed = get_option( 'wc_gift_proceed_installed' );

        if ( ! $installed ) {
            update_option( 'wc_gift_proceed_installed', time() );
        }

        update_option( 'wc_gift_proceed_version', WC_GIFT_VERSION );
    }

    // Activation & Deactivation Hook
	public function initial_activation() {
		$initial_setup = new \WCGT\WC_Gift_Proceed\Initial_Setup();
		register_activation_hook( WC_GIFT_FILE, array( $initial_setup, 'initial_plugin_activation' ) );
		register_deactivation_hook( WC_GIFT_FILE , array( $initial_setup, 'initial_plugin_deactivation' ) );
	}
}

/**
 * Initializes the main plugin
 *
 * @return \WC_Gift
 */
function WCGT_Gift_Proceed() {
    return WC_Gift::init();
}

if (!function_exists('wcgt_function')) {
    function wcgt_function() {
        require_once WCGT_DIR_PATH . 'includes/Functions.php';
        return new \WCGT\WC_Gift_Proceed\Functions();
    }
}

// kick-off the plugin
WCGT_Gift_Proceed();
