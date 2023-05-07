<?php
/**
 * Plugin Name:       Order Gift Proceed Checkout
 * Description:       Order Gift Proceed Checkout is easily manage gift order in woocommerce platform. In this plugin you can easily handle order as a gift.
 * Version:           1.0.2
 * Requires at least: 5.9
 * Requires PHP:      7.2
 * Author:            Rejuan Ahamed
 * Text Domain:       ogpc
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
    const version = '1.0.2';

    /**
     * Class construcotr
     */
    private function __construct() {
        $this->define_constants();
        $this->includes_core();
        $this->initial_activation();

        do_action('ogpc_before_load');
		$this->run();
		do_action('ogpc_after_load');

        register_activation_hook( __FILE__, [ $this, 'activate' ] );
        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
        add_action('wp_enqueue_scripts', [ $this, 'frontend_script' ]); //Add frontend js and css
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
        define( 'OGPC_GIFT_VERSION', self::version );
        define( 'OGPC_GIFT_FILE', __FILE__ );
        define( 'OGPC_GIFT_URL', plugins_url( '', OGPC_GIFT_FILE ) );
        define( 'OGPC_GIFT_ASSETS', OGPC_GIFT_URL . '/assets' );
        define( 'OGPC_DIR_URL', plugin_dir_url( OGPC_GIFT_FILE ));
        define( 'OGPC_DIR_PATH', plugin_dir_path( OGPC_GIFT_FILE ));
    }

    public function includes_core() {
		require_once OGPC_DIR_PATH.'includes/Initial_Setup.php';
	}

    //Checking Vendor
	public function run() {
        if ( is_admin() ) {
            $initial_setup = new \WCGT\WC_Gift_Proceed\Initial_Setup();
            
            $ogpc_file = WP_PLUGIN_DIR.'/woocommerce/woocommerce.php';

            if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

            } else {
                $ogpc_file = WP_PLUGIN_DIR.'/woocommerce/woocommerce.php';
                if (file_exists($ogpc_file) ) {
                    add_action( 'admin_notices', array($initial_setup, 'free_plugin_installed_but_inactive_notice') );
                } elseif ( ! file_exists($ogpc_file) ) {
                    add_action( 'admin_notices', array($initial_setup, 'free_plugin_not_installed') );
                }
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
            require_once OGPC_DIR_PATH.'includes/Admin.php';
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

        update_option( 'wc_gift_proceed_version', OGPC_GIFT_VERSION );
    }

    // Activation & Deactivation Hook
	public function initial_activation() {
		$initial_setup = new \WCGT\WC_Gift_Proceed\Initial_Setup();
		register_activation_hook( OGPC_GIFT_FILE, array( $initial_setup, 'initial_plugin_activation' ) );
		register_deactivation_hook( OGPC_GIFT_FILE , array( $initial_setup, 'initial_plugin_deactivation' ) );
	}

    /**
     * Registering necessary js and css
     * @ Frontend
     */
    public function frontend_script(){
        wp_enqueue_style( 'ogpc-css-front', OGPC_DIR_URL .'assets/build/css/main.css', false, OGPC_GIFT_VERSION );
         
        #JS
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'wp-ogpc-front', OGPC_DIR_URL .'assets/build/js/main.js', array('jquery'), OGPC_GIFT_VERSION, true);
        wp_enqueue_media(); 
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

if (!function_exists('ogpc_function')) {
    function ogpc_function() {
        require_once OGPC_DIR_PATH . 'includes/Functions.php';
        return new \WCGT\WC_Gift_Proceed\Functions();
    }
}

// kick-off the plugin
WCGT_Gift_Proceed();
