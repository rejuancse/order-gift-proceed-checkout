<?php
namespace WCGT\WC_Gift_Proceed;

defined( 'ABSPATH' ) || exit;

if (! class_exists('Initial_Setup')) {

    class Initial_Setup {

        public function __construct() {
            add_action('wp_ajax_install_woocommerce_plugin',     array($this, 'install_woocommerce_plugin'));
            add_action('admin_action_activate_woocommerce_free', array($this, 'activate_woocommerce_free'));
            add_action('woocommerce_after_cart_totals', array($this, 'custom_add_to_cart_redirect'));
            // add_action('woocommerce_available_payment_gateways', array($this, 'wcgt_disable_all_but_cod'));
            add_action('woocommerce_checkout_fields', array($this, 'wcgt_override_checkout_fields'));
        }
        
        public function custom_add_to_cart_redirect() {
            echo '<button class="wcgt-order-gift-btn checkout-button button alt wc-forward wp-element-button ">
                <a href="'. get_site_url().'/gift-proceed/" class="order-asa-gift">'.__('Order as a Gift', 'wcgt').'</a>
            </button>';
        }

        /**
         * Do some task during plugin activation
         */
        public function initial_plugin_activation() {
            if(get_option('wcgt_proceed_order')) {
                return false;
            }
            self::update_option();
            self::insert_page();
        }

        /**
         * Insert settings option data
         */
        public function update_option() {
            $init_setup_data = array (
                'wcgt_proceed_order' => WC_GIFT_VERSION
            );

            foreach ($init_setup_data as $key => $value ) {
                update_option( $key , $value );
            }
        }

        /**
         * Insert menu page
         */
        public function insert_page() {
            $wcgt_gift = array(
                'post_title'    => 'Gift Proceed',
                'post_content'  => '[wcgt_gift]',
                'post_type'     => 'page',
                'post_status'   => 'publish',
            );

            /**
             * Insert the page into the database
             * @Gift Pages Object
             */
            $form_page = wp_insert_post( $wcgt_gift );
            if( !is_wp_error( $form_page ) ){
                wcgt_function()->update_text( 'gift_page_id', $form_page );
            }
        }

        /**
         * Reset method, the ajax will call that method for Reset Settings
         */
        public function settings_reset() {
            self::update_option();
        }

        /**
         * Deactivation Hook For Crowdfunding
         */
        public function initial_plugin_deactivation(){

        }


        public function activation_css() {
            ?>
            <style type="text/css">
                .wcgt-install-notice{
                    padding: 20px;
                }
                .wcgt-install-notice-inner{
                    display: flex;
                    align-items: center;
                }
                .wcgt-install-notice-inner .button{
                    padding: 5px 30px;
                    height: auto;
                    line-height: 20px;
                    text-transform: capitalize;
                }
                .wcgt-install-notice-content{
                    flex-grow: 1;
                    padding-left: 20px;
                    padding-right: 20px;
                }
                .wcgt-install-notice-icon img{
                    width: 64px;
                    border-radius: 4px;
                    display: block;
                }
                .wcgt-install-notice-content h2{
                    margin-top: 0;
                    margin-bottom: 5px;
                }
                .wcgt-install-notice-content p{
                    margin-top: 0;
                    margin-bottom: 0px;
                    padding: 0;
                }
            </style>
            
            <script type="text/javascript">
                jQuery(document).ready(function($){
                    'use strict';
                    $(document).on('click', '.install-wcgt-button', function(e){
                        e.preventDefault();
                        var $btn = $(this);
                        $.ajax({
                            type: 'POST',
                            url: ajaxurl,
                            data: {install_plugin: 'woocommerce', action: 'install_woocommerce_plugin'},
                            beforeSend: function(){
                                $btn.addClass('updating-message');
                            },
                            success: function (data) {
                                $('.install-wcgt-button').remove();
                                $('#wcgt_install_msg').html(data);
                            },
                            complete: function () {
                                $btn.removeClass('updating-message');
                            }
                        });
                    });
                });
            </script>
            <?php
        }
        
        /**
         * Show notice if there is no woocommerce
         */
        public function free_plugin_installed_but_inactive_notice(){
            $this->activation_css();
            ?>
            <div class="notice notice-error wcgt-install-notice">
                <div class="wcgt-install-notice-inner">
                    <div class="wcgt-install-notice-icon">
                        <img src="<?php echo WC_GIFT_URL.'/assets/src/images/gift-card.png'; ?>" alt="logo" />
                    </div>
                    <div class="wcgt-install-notice-content">
                        <h2><?php _e('Thanks for using WooCommerce Gift Proceed Checkout', 'wcgt'); ?></h2>
                        <?php 
                            printf(
                                '<p>%1$s <a target="_blank" href="%2$s">%3$s</a> %4$s</p>', 
                                __('You must have','wcgt'), 
                                'https://wordpress.org/plugins/woocommerce/', 
                                __('WooCommerce','wcgt'), 
                                __('installed and activated on this website in order to use WooCommerce Gift Proceed Checkout.','wcgt')
                            );
                        ?>
                        <a href="#" target="_blank"><?php _e('Learn more about WooCommerce Gift Proceed Checkout', 'wcgt'); ?></a>
                    </div>
                    <div class="wcgt-install-notice-button">
                        <a  class="button button-primary" href="<?php echo add_query_arg(array('action' => 'activate_woocommerce_free'), admin_url()); ?>"><?php _e('Activate WooCommerce', 'wcgt'); ?></a>
                    </div>
                </div>
            </div>
            <?php
        }
    
        public function free_plugin_not_installed(){
            include( ABSPATH . 'wp-admin/includes/plugin-install.php' );
            $this->activation_css();
            ?>
            <div class="notice notice-error wcgt-install-notice">
                <div class="wcgt-install-notice-inner">
                    <div class="wcgt-install-notice-icon">
                        <img src="<?php echo WC_GIFT_URL.'/assets/src/images/gift-card.png'; ?>" alt="logo" />
                    </div>
                    <div class="wcgt-install-notice-content">
                        <h2><?php _e('Thanks for using WooCommerce Gift Proceed Checkout', 'wcgt'); ?></h2>
                        <?php 
                            printf(
                                '<p>%1$s <a target="_blank" href="%2$s">%3$s</a> %4$s</p>', 
                                __('You must have','wcgt'), 
                                'https://wordpress.org/plugins/woocommerce/', 
                                __('WooCommerce','wcgt'), 
                                __('installed and activated on this website in order to use WooCommerce Gift Proceed Checkout.','wcgt')
                            );
                        ?>
                        <a href="#" target="_blank"><?php _e('Learn more about WooCommerce Gift Proceed Checkout', 'wcgt'); ?></a>
                    </div>
                    <div class="wcgt-install-notice-button">
                        <a class="install-wcgt-button button button-primary" data-slug="woocommerce" href="<?php echo add_query_arg(array('action' => 'install_woocommerce_free'), admin_url()); ?>"><?php _e('Install WooCommerce', 'wcgt'); ?></a>
                    </div>
                </div>
                <div id="wcgt_install_msg"></div>
            </div>
            <?php
        }

        public function activate_woocommerce_free() {
            activate_plugin('woocommerce/woocommerce.php' );
            wp_redirect(admin_url('admin.php?page=wc-settings&tab=settings-gift-proceed'));
		    exit();
        }

        public function install_woocommerce_plugin(){
            include(ABSPATH . 'wp-admin/includes/plugin-install.php');
            include(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
    
            if ( ! class_exists('Plugin_Upgrader')){
                include(ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php');
            }
            if ( ! class_exists('Plugin_Installer_Skin')) {
                include( ABSPATH . 'wp-admin/includes/class-plugin-installer-skin.php' );
            }
    
            $plugin = 'woocommerce';
    
            $api = plugins_api( 'plugin_information', array(
                'slug'      => $plugin,
                'fields'    => array(
                    'short_description' => false,
                    'sections'          => false,
                    'requires'          => false,
                    'last_updated'      => false,
                    'compatibility'     => false,
                ),
            ) );
    
            if ( is_wp_error( $api ) ) {
                wp_die( $api );
            }
    
            $title = sprintf( __('Installing Plugin: %s'), $api->name . ' ' . $api->version );
            $nonce = 'install-plugin_' . $plugin;
            $url = 'update.php?action=install-plugin&plugin=' . urlencode( $plugin );
    
            $upgrader = new \Plugin_Upgrader( new \Plugin_Installer_Skin( compact('title', 'url', 'nonce', 'plugin', 'api') ) );
            $upgrader->install($api->download_link);
            die();
        }
        
        public static function wc_low_version(){
            printf(
                '<div class="notice notice-error is-dismissible"><p>%1$s <a target="_blank" href="%2$s">%3$s</a> %4$s</p></div>', 
                __('Your','wcgt'), 
                'https://wordpress.org/plugins/woocommerce/', 
                __('WooCommerce','wcgt'), 
                __('version is below then 3.0, please update.','wcgt') 
            );
        }

        public function wcgt_disable_all_but_cod( $available_payment_gateways ) {
            global $wp;
            $current_url = home_url( '/gift-proceed/' );
            $currentpageURL = home_url( $wp->request ).'/';
            $user = wp_get_current_user();
            $allowed_roles = array('customer','subscriber', 'admin');
            if (!array_intersect($allowed_roles, $user->roles )) {
                if( $current_url == $currentpageURL) {
                    if (isset($available_payment_gateways['cod'])) {
                        unset($available_payment_gateways['cod']);
                    } 
                }   
            }
            return $available_payment_gateways;
        }

        public function wcgt_override_checkout_fields( $fields ) {
            global $wp;
            $current_url = home_url( '/gift-proceed/' );
            $currentpageURL = home_url( $wp->request ).'/';

            if( $current_url == $currentpageURL) {
                unset($fields['billing']['billing_company']);
                unset($fields['billing']['billing_address_2']);
                unset($fields['billing']['billing_postcode']);
                unset($fields['order']['order_comments']);
            }
            return $fields;
        }
        

        
        // Remove some fields from billing form
        // Our hooked in function - $fields is passed via the filter!
        // Get all the fields - https://docs.woothemes.com/document/tutorial-customising-checkout-fields-using-actions-and-filters/
        function custom_override_checkout_fields_ek( $fields ) {
            unset($fields['billing']['shipping_first_name']);
            // unset($fields['billing']['billing_address_1']);
            // unset($fields['billing']['billing_postcode']);
            // unset($fields['billing']['billing_state']);

            return $fields;
        }
    }
}
