<?php

namespace WCGT\WC_Gift_Proceed\Frontend;

class Shortcode {
    
    public function __construct() {
        add_shortcode( 'wcgt_gift', [ $this, 'shortcode_callback_func' ] );
    }

    public function shortcode_callback_func( $atts, $content = '' ) { 
        $checkout = WC()->checkout();

        ob_start();
        do_action( 'woocommerce_before_checkout_form', $checkout );

        // If checkout registration is disabled and not logged in, the user cannot checkout.
        if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
            echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'wcgt' ) ) );
            return;
        } ?>
        
        <div class="wcgt-form">
            <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
                <?php if ( $checkout->get_checkout_fields() ) : ?>
                    <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
            
                    <!-- COL2 -->
                    <div class="col2-set" id="customer_details">
                        <div class="col-1">
                            <div class="woocommerce-billing-fields">
                                <h3><?php esc_html_e( 'Gift From', 'wcgt' ); ?></h3>
                                <?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>
                                <div class="woocommerce-billing-fields__field-wrapper">
                                    <?php
                                        $fields = $checkout->get_checkout_fields( 'billing' );

                                        foreach ( $fields as $key => $field ) {
                                            woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
                                        }
                                    ?>
                                </div>
                                <?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
                            </div>

                            <?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
                                <div class="woocommerce-account-fields">
                                    <?php if ( ! $checkout->is_registration_required() ) : ?>
                                        <p class="form-row form-row-wide create-account">
                                            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                                                <input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1" /> <span><?php esc_html_e( 'Create an account?', 'wcgt' ); ?></span>
                                            </label>
                                        </p>
                                    <?php endif; ?>

                                    <?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

                                    <?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>
                                        <div class="create-account">
                                            <?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
                                                <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
                                            <?php endforeach; ?>
                                            <div class="clear"></div>
                                        </div>
                                    <?php endif; ?>

                                    <?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <!-- End Col2 -->

                        <!-- #ship-to-different-address: Gift To -->
                        <div class="col-2">
                            <h3 id="ship-to-different-address">
                                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                                    <input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 1 ), 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" />
                                </label>
                            </h3>

                            <div class="woocommerce-shipping-fields">
                                <?php if ( true === WC()->cart->needs_shipping_address() ) : ?>
                                    <h3><?php esc_html_e( 'Gift To', 'wcgt' ); ?></h3>

                                    <div class="shipping_address">
                                        <?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>
                                        <div class="woocommerce-shipping-fields__field-wrapper">
                                            <?php
                                                $fields = $checkout->get_checkout_fields( 'shipping' );
                                                foreach ( $fields as $key => $field ) {
                                                    woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
                                                }
                                            ?>
                                        </div>
                                        <?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="woocommerce-additional-fields">
                                <?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>
                                <?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>
                                    <?php if ( ! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only() ) : ?>
                                        <h3><?php esc_html_e( 'Additional information', 'wcgt' ); ?></h3>
                                    <?php endif; ?>

                                    <div class="woocommerce-additional-fields__field-wrapper">
                                        <?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
                                            <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
                            </div>
                        </div>
                    </div>
            
                    <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
                <?php endif; ?>
                
                <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
                    <h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'wcgt' ); ?></h3>
                    <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
                    <div id="order_review" class="woocommerce-checkout-review-order">
                        <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                    </div>
                <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
            </form>
        </div>
        
        <?php
        do_action( 'woocommerce_after_checkout_form', $checkout );
        $output = ob_get_contents();
        ob_end_clean();
        wp_reset_postdata();
        return $output;
    }
}
