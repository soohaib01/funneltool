<?php
/**
 * Optin shortcode class
 */
namespace WPFunnels\Shortcodes;


use ElementorPro\Modules\Forms\Module;
use ElementorPro\Plugin;

class WC_Shortcode_Optin {

	/**
	 * Attributes
	 *
	 * @var array
	 */
	protected $attributes = array();


	/**
	 * WC_Shortcode_Optin constructor.
	 * @param array $attributes
	 */
	public function __construct( $attributes = array() ) {
		$this->attributes = $this->parse_attributes( $attributes );
	}


	/**
	 * Get shortcode attributes.
	 *
	 * @since  3.2.0
	 * @return array
	 */
	public function get_attributes() {
		return $this->attributes;
	}


	/**
	 * parse attributes
	 *
	 * @param $attributes
	 * @return array
	 */
	protected function parse_attributes( $attributes ) {
		$attributes = shortcode_atts(
			array(
				'first_name' => false,
				'last_name' => false,
				'phone' => false,
				'acceptance_checkbox' => false,
				'notification_text' => '',
				'post_action' => '',
				'redirect_url' => '',
				'admin_email' => wp_get_current_user()->user_email,
				'admin_email_subject' => 'Opt-in form submission',
				'btn_class' => '',
			),
			$attributes
		);
		return $attributes;
	}


	/**
	 * get wrapper classes
	 *
	 * @return array
	 */
	protected function get_wrapper_classes() {
		$classes = array( 'wpfnl', 'wpfnl-optin-form-wrapper', 'wpfnl-shortcode-optin-form-wrapper');
		return $classes;
	}


	/**
	 * content of optin form
	 *
	 * @return string
	 */
	public function get_content() {
		$classes = $this->get_wrapper_classes();
		ob_start();
		do_action( 'wpfunnels/before_optin_form' );
		require_once WPFNL_DIR.'/includes/core/shortcodes/templates/optin/form.php';
		do_action( 'wpfunnels/after_optin_form' );
		return '<div class="' . esc_attr( implode( ' ', $classes ) ) . '">' . ob_get_clean() . '</div>';
	}


	
}
