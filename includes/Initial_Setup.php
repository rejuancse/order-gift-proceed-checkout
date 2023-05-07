<?php
namespace WCGT\WC_Gift_Proceed;

defined( 'ABSPATH' ) || exit;

if (! class_exists('Initial_Setup')) {

    class Initial_Setup {

        public function __construct() {
            add_action('wp_ajax_install_woocommerce_plugin',     array($this, 'install_woocommerce_plugin'));
            add_action('admin_action_activate_woocommerce_free', array($this, 'activate_woocommerce_free'));
            add_action('woocommerce_after_cart_totals', array($this, 'custom_add_to_cart_redirect'));
            add_action('woocommerce_available_payment_gateways', array($this, 'ogpc_disable_all_but_cod'));
            add_action('woocommerce_checkout_fields', array($this, 'ogpc_override_checkout_fields'));
            add_filter( 'display_post_states', array( $this, 'ogpc_add_display_post_states' ), 10, 2 );
        }
        
        public function custom_add_to_cart_redirect() {
            $btn_text_color = get_option( "wc_settings_tab_btn_text_color", true );
            $btn_border_color = get_option( "wc_settings_tab_btn_border_color", true );
            $btn_bg_color = get_option( "wc_settings_tab_btn_bg_color", true );
            $enable_btn = get_option( "wc_settings_enable_btn", true );
            $order_btn = get_option( "wc_settings_tab_button_text" );
            $buttonName = !empty( $order_btn ) ? $order_btn : __('Order as a Gift', 'ogpc');

            if( isset($enable_btn) && $enable_btn == 'yes' ) {
                echo '<div class="ogpc-order-gift-btn wc-proceed-to-checkout">
                    <a href="'. get_site_url().'/gift-proceed/" class="checkout-button button alt wc-forward wp-element-button">'.$buttonName.'</a>
                </div>';
            } ?>

            <style type="text/css">
                .ogpc-order-gift-btn .checkout-button {
                    color: <?php echo (isset($btn_text_color) && !empty($btn_text_color)) ? esc_html($btn_text_color) : '#7f54b3'; ?>
                }

                .ogpc-order-gift-btn .checkout-button {
                    border-color: <?php echo (isset($btn_border_color) && !empty($btn_border_color)) ? esc_html($btn_border_color) : '#7f54b3'; ?>
                }

                .ogpc-order-gift-btn .checkout-button {
                    background-color: <?php echo (isset($btn_bg_color) && !empty($btn_bg_color)) ? esc_html($btn_bg_color) : '#7f54b3'; ?>
                }
            </style>
        <?php }

        /**
         * Add a post display state for special WP pages in the page list table.
         *
         * @param array   $post_states An array of post display states.
         * @param WP_Post $post        The current post object.
         */
        public function ogpc_add_display_post_states( $post_states, $post ) {
            if ( 'gift-proceed' === $post->post_name && isset( $post->post_name ) ) {
                $post_states['wc_page_for_shop'] = __( 'Gift Checkout Page', 'ogpc' );
            }
            return $post_states;
        }

        /**
         * Do some task during plugin activation
         */
        public function initial_plugin_activation() {
            if(get_option('ogpc_proceed_order')) {
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
                'ogpc_proceed_order' => OGPC_GIFT_VERSION
            );

            foreach ($init_setup_data as $key => $value ) {
                update_option( $key , $value );
            }
        }

        /**
         * Insert menu page
         */
        public function insert_page() {
            $gift_checkout_proceed = array(
                'post_title'    => 'Gift Proceed',
                'post_content'  => '[gift_checkout_proceed]',
                'post_type'     => 'page',
                'post_status'   => 'publish',
            );

            /**
             * Insert the page into the database
             * @Gift Pages Object
             */
            $form_page = wp_insert_post( $gift_checkout_proceed );
            if( !is_wp_error( $form_page ) ) {
                ogpc_function()->update_text( 'gift_page_id', $form_page );
            }
        }

        /**
         * Reset method, the ajax will call that method for Reset Settings
         */
        public function settings_reset() {
            self::update_option();
        }

        /**
         * Deactivation Hook For Gift
         */
        public function initial_plugin_deactivation(){

        }

        public function activation_css() {
            ?>
            <style type="text/css">
                .ogpc-install-notice{
                    padding: 20px;
                }
                .ogpc-install-notice-inner{
                    display: flex;
                    align-items: center;
                }
                .ogpc-install-notice-inner .button{
                    padding: 5px 30px;
                    height: auto;
                    line-height: 20px;
                    text-transform: capitalize;
                }
                .ogpc-install-notice-content{
                    flex-grow: 1;
                    padding-left: 20px;
                    padding-right: 20px;
                }
                .ogpc-install-notice-icon img{
                    width: 64px;
                    border-radius: 4px;
                    display: block;
                }
                .ogpc-install-notice-content h2{
                    margin-top: 0;
                    margin-bottom: 5px;
                }
                .ogpc-install-notice-content p{
                    margin-top: 0;
                    margin-bottom: 0px;
                    padding: 0;
                }
            </style>
            
            <script type="text/javascript">
                jQuery(document).ready(function($){
                    'use strict';
                    $(document).on('click', '.install-ogpc-button', function(e){
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
                                $('.install-ogpc-button').remove();
                                $('#ogpc_install_msg').html(data);
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
            <div class="notice notice-error ogpc-install-notice">
                <div class="ogpc-install-notice-inner">
                    <div class="ogpc-install-notice-icon">
                        <img src="<?php echo OGPC_GIFT_URL.'/assets/src/images/gift-card.png'; ?>" alt="logo" />
                    </div>
                    <div class="ogpc-install-notice-content">
                        <h2><?php _e('Thanks for using WooCommerce Gift Proceed Checkout', 'ogpc'); ?></h2>
                        <?php 
                            printf(
                                '<p>%1$s <a target="_blank" href="%2$s">%3$s</a> %4$s</p>', 
                                __('You must have','ogpc'), 
                                'https://wordpress.org/plugins/woocommerce/', 
                                __('WooCommerce','ogpc'), 
                                __('installed and activated on this website in order to use WooCommerce Gift Proceed Checkout.','ogpc')
                            );
                        ?>
                        <a href="#" target="_blank"><?php _e('Learn more about WooCommerce Gift Proceed Checkout', 'ogpc'); ?></a>
                    </div>
                    <div class="ogpc-install-notice-button">
                        <a  class="button button-primary" href="<?php echo add_query_arg(array('action' => 'activate_woocommerce_free'), admin_url()); ?>"><?php _e('Activate WooCommerce', 'ogpc'); ?></a>
                    </div>
                </div>
            </div>
            <?php
        }
    
        public function free_plugin_not_installed(){
            include( ABSPATH . 'wp-admin/includes/plugin-install.php' );
            $this->activation_css();
            ?>
            <div class="notice notice-error ogpc-install-notice">
                <div class="ogpc-install-notice-inner">
                    <div class="ogpc-install-notice-icon">
                        <img src="<?php echo OGPC_GIFT_URL.'/assets/src/images/gift-card.png'; ?>" alt="logo" />
                    </div>
                    <div class="ogpc-install-notice-content">
                        <h2><?php _e('Thanks for using WooCommerce Gift Proceed Checkout', 'ogpc'); ?></h2>
                        <?php 
                            printf(
                                '<p>%1$s <a target="_blank" href="%2$s">%3$s</a> %4$s</p>', 
                                __('You must have','ogpc'), 
                                'https://wordpress.org/plugins/woocommerce/', 
                                __('WooCommerce','ogpc'), 
                                __('installed and activated on this website in order to use WooCommerce Gift Proceed Checkout.','ogpc')
                            );
                        ?>
                        <a href="#" target="_blank"><?php _e('Learn more about WooCommerce Gift Proceed Checkout', 'ogpc'); ?></a>
                    </div>
                    <div class="ogpc-install-notice-button">
                        <a class="install-ogpc-button button button-primary" data-slug="woocommerce" href="<?php echo add_query_arg(array('action' => 'install_woocommerce_free'), admin_url()); ?>"><?php _e('Install WooCommerce', 'ogpc'); ?></a>
                    </div>
                </div>
                <div id="ogpc_install_msg"></div>
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
                __('Your','ogpc'), 
                'https://wordpress.org/plugins/woocommerce/', 
                __('WooCommerce','ogpc'), 
                __('version is below then 3.0, please update.','ogpc') 
            );
        }

        public function ogpc_disable_all_but_cod( $available_payment_gateways ) {
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

        public function ogpc_override_checkout_fields( $fields ) {
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
    }
}
