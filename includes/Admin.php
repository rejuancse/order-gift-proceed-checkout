<?php
/**
 * The admin class
 */

if ( ! class_exists( 'WC_Settings_Gift_Proceed_Page', false ) ) :

	/**
	 * WC_Settings_Gift_Proceed_Page.
	 */
	abstract class WC_Settings_Gift_Proceed_Page {

		/**
		 * Setting page id.
		 *
		 * @var string
		 */
		protected $id = '';

		/**
		 * Setting page label.
		 *
		 * @var string
		 */
		protected $label = '';

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
			add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );
			add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
			add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
		}

		/**
		 * Get settings page ID.
		 *
		 * @since 3.0.0
		 * @return string
		 */
		public function get_id() {
			return $this->id;
		}

		/**
		 * Get settings page label.
		 *
		 * @since 3.0.0
		 * @return string
		 */
		public function get_label() {
			return $this->label;
		}

		/**
		 * Add this page to settings.
		 *
		 * @param array $pages The setings array where we'll add ourselves.
		 *
		 * @return mixed
		 */
		public function add_settings_page( $pages ) {
			$pages[ $this->id ] = $this->label;

			return $pages;
		}

		/**
		 * Get settings array for the default section.
		 *
		 * External settings classes (registered via 'woocommerce_get_settings_pages' filter)
		 * might have redefined this method as "get_settings($section_id='')", thus we need
		 * to use this method internally instead of 'get_settings_for_section' to register settings
		 * and render settings pages.
		 *
		 * *But* we can't just redefine the method as "get_settings($section_id='')" here, since this
		 * will break on PHP 8 if any external setting class have it as 'get_settings()'.
		 *
		 * @return array Settings array, each item being an associative array representing a setting.
		 */
		public function get_settings() {
			$section_id = 0 === func_num_args() ? '' : func_get_arg( 0 );
			return $this->get_settings_for_section( $section_id );
		}

		/**
		 * Get settings array.
		 *
		 * The strategy for getting the settings is as follows:
		 *
		 * - If a method named 'get_settings_for_{section_id}_section' exists in the class
		 *   it will be invoked (for the default '' section, the method name is 'get_settings_for_default_section').
		 *   Derived classes can implement these methods as required.
		 *
		 * - Otherwise, 'get_settings_for_section_core' will be invoked. Derived classes can override it
		 *   as an alternative to implementing 'get_settings_for_{section_id}_section' methods.
		 *
		 * @param string $section_id The id of the section to return settings for, an empty string for the default section.
		 *
		 * @return array Settings array, each item being an associative array representing a setting.
		 */
		final public function get_settings_for_section( $section_id ) {
			if ( '' === $section_id ) {
				$method_name = 'get_settings_for_default_section';
			} else {
				$method_name = "get_settings_for_{$section_id}_section";
			}

			if ( method_exists( $this, $method_name ) ) {
				$settings = $this->$method_name();
			} else {
				$settings = $this->get_settings_for_section_core( $section_id );
			}

			return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $section_id );
		}

		/**
		 * Get the settings for a given section.
		 * This method is invoked from 'get_settings_for_section' when no 'get_settings_for_{current_section}_section'
		 * method exists in the class.
		 *
		 * When overriding, note that the 'woocommerce_get_settings_' filter must NOT be triggered,
		 * as this is already done by 'get_settings_for_section'.
		 *
		 * @param string $section_id The section name to get the settings for.
		 *
		 * @return array Settings array, each item being an associative array representing a setting.
		 */
		protected function get_settings_for_section_core( $section_id ) {
			return array();
		}

		/**
		 * Get all sections for this page, both the own ones and the ones defined via filters.
		 *
		 * @return array
		 */
		public function get_sections() {
			return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
		}

		/**
		 * Output sections.
		 */
		public function output_sections() {
			global $current_section;

			$sections = $this->get_sections();

			if ( empty( $sections ) || 1 === count( $sections ) ) {
				return;
			}

			echo '<ul class="subsubsub">';

			$array_keys = array_keys( $sections );

			foreach ( $sections as $id => $label ) {
				$url       = admin_url( 'admin.php?page=wc-settings&tab=' . $this->id . '&section=' . sanitize_title( $id ) );
				$class     = ( $current_section === $id ? 'current' : '' );
				$separator = ( end( $array_keys ) === $id ? '' : '|' );
				$text      = esc_html( $label );
				echo "<li><a href='$url' class='$class'>$text</a> $separator </li>";
			}

			echo '</ul><br class="clear" />';
		}

		/**
		 * Output the HTML for the settings.
		 */
		public function output() {
			global $current_section;

			$settings = $this->get_settings( $current_section );

			WC_Admin_Settings::output_fields( $settings );
		}

		/**
		 * Save settings and trigger the 'woocommerce_update_options_'.id action.
		 */
		public function save() {
			$this->save_settings_for_current_section();
			$this->do_update_options_action();
		}

		/**
		 * Save settings for current section.
		 */
		protected function save_settings_for_current_section() {
			global $current_section;

			$settings = $this->get_settings( $current_section );
			WC_Admin_Settings::save_fields( $settings );
		}

		/**
		 * Trigger the 'woocommerce_update_options_'.id action.
		 *
		 * @param string $section_id Section to trigger the action for, or null for current section.
		 */
		protected function do_update_options_action( $section_id = null ) {
			global $current_section;

			if ( is_null( $section_id ) ) {
				$section_id = $current_section;
			}

			if ( $section_id ) {
				do_action( 'woocommerce_update_options_' . $this->id . '_' . $section_id );
			}
		}
	}

endif;

class WC_Settings_Gift_Proceed_Plugin extends WC_Settings_Gift_Proceed_Page {

    /**
     * Constructor
     */
    public function __construct() {
        $this->id    = 'settings-gift-proceed';

        add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 50 );
        add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );
        add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
        add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
    }

    /**
     * Add plugin options tab
     *
     * @return array
     */
    public function add_settings_tab( $settings_tabs ) {
        $settings_tabs[$this->id] = __( 'Settings Gift Proceed', 'wcgt' );
        return $settings_tabs;
    }

    /**
     * Get sections
     *
     * @return array
     */
    public function get_sections() {

        $sections = array(
            'section-0'         => __( 'Plugin Options', 'wcgt' ),
            'section-1'         => __( 'Section 1', 'wcgt' ),
            'section 2'         => __( 'Section 2', 'wcgt' ),
        );

        return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
    }

    /**
     * Get sections
     *
     * @return array
     */
    public function get_settings( $section = null ) {

        switch( $section ){

            case 'section-0' :
                $settings = array(
                    'section_title' => array(
                        'name'     => __( 'Main Section Title', 'wcgt' ),
                        'type'     => 'title',
                        'desc'     => '',
                        'id'       => 'wc_settings_tab_demo_title_section-1'
                    ),
                    'title' => array(
                        'name' => __( 'Main Title', 'wcgt' ),
                        'type' => 'text',
                        'desc' => __( 'This is some helper text', 'wcgt' ),
                        'id'   => 'wc_settings_tab_demo_title_section-1'
                    ),
                    'description' => array(
                        'name' => __( 'Main Description', 'wcgt' ),
                        'type' => 'textarea',
                        'desc' => __( 'This is a paragraph describing the setting. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda.', 'wcgt' ),
                        'id'   => 'wc_settings_tab_demo_description_section-1'
                    ),
                    'section_end' => array(
                         'type' => 'sectionend',
                         'id' => 'wc_settings_tab_demo_end-section-1'
                    )
                );
            break;

            case 'section-1':
                $settings = array(
                    'section_title' => array(
                        'name'     => __( 'Section One Title', 'wcgt' ),
                        'type'     => 'title',
                        'desc'     => '',
                        'id'       => 'wc_settings_tab_demo_section_title_section-2'
                    ),
                    'title' => array(
                        'name' => __( 'Section One Title', 'wcgt' ),
                        'type' => 'text',
                        'desc' => __( 'This is some helper text', 'wcgt' ),
                        'id'   => 'wc_settings_tab_demo_title_section-2'
                    ),
                    'description' => array(
                        'name' => __( 'Section One Description', 'wcgt' ),
                        'type' => 'textarea',
                        'desc' => __( 'This is a paragraph describing the setting. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda.', 'wcgt' ),
                        'id'   => 'wc_settings_tab_demo_description_section-2'
                    ),
                    'section_end' => array(
                         'type' => 'sectionend',
                         'id' => 'wc_settings_tab_demo_section_end_section-2'
                    )
                );
            break;

            case 'section-2':
                $settings = array(
                    'section_title' => array(
                        'name'     => __( 'Section Two Title', 'wcgt' ),
                        'type'     => 'title',
                        'desc'     => '',
                        'id'       => 'wc_settings_tab_demo_section_title'
                    ),
                    'title' => array(
                        'name' => __( 'Section Two Title', 'wcgt' ),
                        'type' => 'text',
                        'desc' => __( 'This is some helper text', 'wcgt' ),
                        'id'   => 'wc_settings_tab_demo_title'
                    ),
                    'description' => array(
                        'name' => __( 'Section Two Description', 'wcgt' ),
                        'type' => 'textarea',
                        'desc' => __( 'This is a paragraph describing the setting. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda.', 'wcgt' ),
                        'id'   => 'wc_settings_tab_demo_description'
                    ),
                    'section_end' => array(
                         'type' => 'sectionend',
                         'id' => 'wc_settings_tab_demo_section_end'
                    )
                );
            break;

			default:
				$settings = array(
					'section_title' => array(
						'name'     => __( 'General Settings', 'wcgt' ),
						'type'     => 'title',
						'desc'     => '',
						'id'       => 'wc_settings_tab_demo_section_title'
					),
					'title' => array(
						'name' => __( 'Enable Gift Form', 'wcgt' ),
						'type' => 'checkbox',
						'desc' => __( 'Enable Gift Form', 'wcgt' ),
						'id'   => 'wc_settings_tab_demo_title'
					),
					'section_end' => array(
						'type' => 'sectionend',
						'id' => 'wc_settings_tab_demo_section_default'
					)
				);
        }

        return apply_filters( 'wc_settings_tab_demo_settings', $settings, $section );
    }

    /**
     * Output the settings
     */
    public function output() {
        global $current_section;
        $settings = $this->get_settings( $current_section );
        WC_Admin_Settings::output_fields( $settings );
    }


    /**
     * Save settings
     */
    public function save() {
        global $current_section;
        $settings = $this->get_settings( $current_section );
        WC_Admin_Settings::save_fields( $settings );
    }
}

return new WC_Settings_Gift_Proceed_Plugin();
