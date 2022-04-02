<?php

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

class Wpfnl_Divi_Theme_Compatibility extends ET_Builder_Plugin_Compat_Base {
	/**
	 * Constructor.
	 *
	 * @since 2.3.4
	 */
	public function __construct() {
		$this->plugin_id = 'wpfunnels/wpfnl.php';
		$this->init_hooks();
	}
	/**
	 * Hook methods to WordPress.
	 *
	 * @since 2.3.4
	 *
	 * @return void
	 */
	public function init_hooks() {
		// Bail if there's no version found.
		if ( ! $this->get_plugin_version() ) {
			return;
		}
		add_action( 'wp', array( $this, 'maybe_disable_theme_builder' ), 9 );
	}
	/**
	 * Disable theme builder for specific Wpfunnels templates that don't use
	 * the normal WordPress partials (get_header(), get_footer()).
	 *
	 * @since 2.3.4
	 */
	public function maybe_disable_theme_builder() {
		$step_post_type = defined( 'WPFNL_STEPS_POST_TYPE' ) ? WPFNL_STEPS_POST_TYPE : 'wpfunnel_steps';

		if ( is_singular( $step_post_type ) ) {
			/**
			 * Filters page templates that should have the Theme Builder disabled.
			 *
			 * @since 2.3.4
			 *
			 * @param string[] $templates
			 */
			$disable_for = apply_filters( 'et_builder_compatibility_cartflows_templates_without_theme_builder', array( 'wpfunnels_boxed', 'wpfunnels_default', 'wpfunnels_fullwidth_without_header_footer' ) );
			$template    = get_post_meta( get_the_ID(), '_wp_page_template', true );

			if ( in_array( $template, $disable_for, true ) ) {
				add_filter( 'et_theme_builder_template_layouts', array( $this, 'disable_theme_builder' ) );
			}
		}
	}
	/**
	 * Disable theme builder for the current request by returning no layouts for it.
	 *
	 * @since 2.3.4
	 *
	 * @param array $layouts
	 *
	 * @return array
	 */

	public function disable_theme_builder( $layouts ) {
		return array();
	}
}
new Wpfnl_Divi_Theme_Compatibility();
