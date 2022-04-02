<?php

namespace WPFunnels;


/**
 * Class Rollback
 * @package WPFunnels
 */
class Rollback {

	/**
	 * @var String Package URL
	 * @since 2.3.0
	 */
	protected $package_url;


	/**
	 * @var String WPF versions
	 * @since 2.3.0
	 */
	protected $version;


	/**
	 * @var String Plugin slug
	 *
	 * @since 2.3.0
	 */
	protected $plugin_slug;


	/**
	 * @var String Plugin name
	 *
	 * @since 2.3.0
	 */
	protected $plugin_name;


	/**
	 * Rollback constructor.
	 * @param array $args
	 */
	public function __construct( $args = [] ) {
		foreach ( $args as $key => $value ) {
			$this->{$key} = $value;
		}
	}


	/**
	 * print inline styles
	 */
	private function print_inline_style() {
		?>
		<style>
			.wrap {
				overflow: hidden;
				max-width: 850px;
				margin: auto;
				font-family: Courier, monospace;
			}

			h1 {
				background: #6E42D2;
				text-align: center;
				color: #fff !important;
				padding: 70px !important;
				text-transform: uppercase;
				letter-spacing: 1px;
			}

			h1 img {
				max-width: 300px;
				display: block;
				margin: auto auto 50px;
			}
		</style>
		<?php
	}


	/**
	 * Apply package.
	 *
	 * Change the plugin data when WordPress checks for updates. This method
	 * modifies package data to update the plugin from a specific URL containing
	 * the version package.
	 *
	 * @since 2.3.0
	 * @access protected
	 */
	protected function apply_package() {
		$update_plugins = get_site_transient( 'update_plugins' );
		if ( ! is_object( $update_plugins ) ) {
			$update_plugins = new \stdClass();
		}

		$plugin_info 				= new \stdClass();
		$plugin_info->new_version 	= $this->version;
		$plugin_info->slug 			= $this->plugin_slug;
		$plugin_info->package 		= $this->package_url;
		$plugin_info->url 			= 'http://getwpfunnels.com/';
		$update_plugins->response[ $this->plugin_name ] = $plugin_info;
		set_site_transient( 'update_plugins', $update_plugins );
	}

	/**
	 * Upgrade.
	 *
	 * Run WordPress upgrade to rollback WPF to previous version.
	 *
	 * @since 2.3.0
	 * @access protected
	 */
	protected function upgrade() {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

		$logo_url = WPFNL_URL . 'admin/assets/images/logo-wpfnl.png';

		$upgrader_args = [
			'url' => 'update.php?action=upgrade-plugin&plugin=' . rawurlencode( $this->plugin_name ),
			'plugin' => $this->plugin_name,
			'nonce' => 'upgrade-plugin_' . $this->plugin_name,
			'title' => esc_html__( 'Rollback to WPFunnels Previous Version', 'wpfnl' ),
		];

		$this->print_inline_style();

		$upgrader = new \Plugin_Upgrader( new \Plugin_Upgrader_Skin( $upgrader_args ) );
		$upgrader->upgrade( $this->plugin_name );
	}

	/**
	 * Run.
	 *
	 * Rollback WPFunnels to previous versions.
	 *
	 * @since 1.5.0
	 * @access public
	 */
	public function run() {
		$this->apply_package();
		$this->upgrade();
	}
}
