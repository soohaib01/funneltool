<?php

namespace WPFunnels\Admin;

use WPFunnels\Wpfnl_functions;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    WPFunnels
 * @subpackage WPFunnels/Admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpfnl
 * @subpackage Wpfnl/admin
 * @author     RexTheme <support@rextheme.com>
 */
class Wpfnl_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;


	private $dependency_plugins;


	/**
	 * page hooks
	 *
	 * @var array
	 */
	private $page_hooks = [
		'toplevel_page_wp_funnels',
		'Hacker-Lab_page_wp_funnel_settings',
		'Hacker-Lab_page_edit_funnel',
		'Hacker-Lab_page_create_funnel',
		'Hacker-Lab_page_wpfnl_settings',
		'Hacker-Lab_page_wpf-license',
	];

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 * @since    1.0.0
	 */
	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action( 'admin_enqueue_scripts', [$this, 'enqueue_styles'] );
		add_action( 'admin_enqueue_scripts', [$this, 'enqueue_scripts'] );
		add_filter( 'plugin_action_links_' . WPFNL_BASE, [$this, 'add_funnel_action_links'] );

		if( is_admin() ) {
			add_filter( 'wp_dropdown_pages', array( $this, 'show_funnel_steps_on_reading' ) );
		}
	}

	/**
	 * Funnel action links
	 *
	 * @since    1.0.0
	 */
	public function add_funnel_action_links($actions)
	{
		$documentation = array(
			'<a href="https://getwpfunnels.com/docs/wpfunnels-wordpress-funnel-builder/" target="_blank" >Documentation</a>',
		);
		$actions = array_merge($actions, $documentation);
		return $actions;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles($hook)
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpfnl_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpfnl_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if (in_array($hook, $this->page_hooks)) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style($this->plugin_name . '-jquery-ui', plugin_dir_url(__FILE__) . 'assets/css/jquery-ui.min.css', [], $this->version, 'all');
			wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'assets/css/wpfnl-admin.css', [], $this->version, 'all');
			do_action('wpfunnels_after_styles_loaded');
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook)
	{
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpfnl_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpfnl_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( in_array($hook, $this->page_hooks )) {
			$is_wc_installed = 'no';
			$is_elementor_installed = 'no';
			if (is_plugin_active('woocommerce/woocommerce.php')) {
				$is_wc_installed = 'yes';
			}
			if (is_plugin_active('elementor/elementor.php')) {
				$is_elementor_installed = 'yes';
			}

			wp_enqueue_script($this->plugin_name . '-select2', plugin_dir_url(__FILE__) . 'assets/js/select2.min.js', ['jquery'], $this->version, true);
			$products = array();
			if (isset($_GET['step_id'])) {
				$step_id = sanitize_text_field($_GET['step_id']);
				if ($step_id) {
					if (Wpfnl_functions::check_if_this_is_step_type_by_id($step_id, 'checkout')) {
						$products = get_post_meta($step_id, '_wpfnl_checkout_products', true);
						if (empty($products)) {
							$products = [];
						}
					}
				}
			}

			$funnel_id 		= '';
			$funnel_title 	= '';
			$step_id 		= '';
			if (isset($_GET['id'])) {
				$funnel_id 		= $_GET['id'];
				$funnel_title 	= html_entity_decode(get_the_title($funnel_id));
				if (isset($_GET['step_id'])) {
					$step_id = filter_input(INPUT_GET, 'step_id', FILTER_VALIDATE_INT);
				}
			}

			/** get funnel preview link */
			$steps 					= get_post_meta( $funnel_id, '_steps_order', true );
			$funnel_preview_link 	= '#';
			$response['success'] = false;
			if ($steps) {
				if ( isset($steps[0]) && $steps[0]['id'] ) {
					$funnel_preview_link = get_post_permalink($steps[0]['id']);
				}
			}


			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script('wp-color-picker');
			wp_enqueue_media();
			wp_enqueue_script( $this->plugin_name, plugin_dir_url(__FILE__) . 'assets/js/wpfnl-admin.js', ['jquery', 'jquery-ui-sortable'], $this->version, true );
			wp_enqueue_script( $this->plugin_name . '-funnel-window', plugin_dir_url(__FILE__) . 'assets/dist/js/funnel-components.min.js', ['jquery', 'wp-i18n', 'wp-util', 'updates', 'wp-color-picker'], $this->version, true );
			wp_enqueue_script( $this->plugin_name . '-backbone-marionette', plugin_dir_url(__FILE__) . 'assets/lib/backbone/backbone.marionette.min.js', ['backbone'], $this->version, true );
			wp_enqueue_script( $this->plugin_name . '-backbone-radio', plugin_dir_url(__FILE__) . 'assets/lib/backbone/backbone.radio.min.js', ['backbone'], $this->version, true );

			$general_settings 	= Wpfnl_functions::get_general_settings();
			$builder 			= $general_settings['builder'];

			/**
			 * this code snippet will check if pro addons is activated or not. if not activated
			 * total number of funnels will be maximum 3, otherwise customer can add as more funnels
			 * as they want
			 */
			$is_pro_active = apply_filters( 'wpfunnels/is_pro_license_activated', false );
			$count_funnels = wp_count_posts('wpfunnels')->publish + wp_count_posts('wpfunnels')->draft;
			$total_allowed_funnels = 3;
			if ($is_pro_active) {
				$total_allowed_funnels = -1;
			}

			$currency_symbol = '$';
			if ( function_exists('get_woocommerce_currency_symbol') ) {
				$currency_symbol = get_woocommerce_currency_symbol();
			}


			wp_localize_script( $this->plugin_name, 'WPFunnelVars', array(
				'ajaxurl' 					=> admin_url( 'admin-ajax.php' ),
				'rest_api_url' 				=> get_rest_url(),
				'security' 					=> wp_create_nonce('wpfnl-admin'),
				'admin_url' 				=> admin_url(),
				'edit_funnel_url' 			=> admin_url('admin.php?page=edit_funnel'),
				'is_wc_installed' 			=> $is_wc_installed,
				'is_elementor_installed'	=> $is_elementor_installed,
				'products' 					=> $products,
				'funnel_id' 				=> $funnel_id,
				'funnel_title' 				=> $funnel_title,
				'funnel_preview_link' 		=> $funnel_preview_link,
				'site_url'	 				=> site_url(),
				'image_path' 				=> WPFNL_URL . 'admin/assets/images',
				'nonce' 					=> wp_create_nonce('wp_rest'),
				'isNewFunnel' 				=> \Wpfnl_Activator::is_new_install() ? true : false,
				'isProActivated' 			=> $is_pro_active,
				'totalFunnels' 				=> $count_funnels,
				'totalAllowedFunnels' 		=> $total_allowed_funnels,
				'builder' 					=> $builder,
				'dependencyPlugins' 		=> Wpfnl_functions::get_dependency_plugins_status(),
				'isAnyPluginMissing' 		=> Wpfnl_functions::is_any_plugin_missing(),
				'isGlobalFunnelActivated' 	=> Wpfnl_functions::is_global_funnel_activated(),
				'isGlobalFunnel' 			=> Wpfnl_functions::is_global_funnel( $funnel_id ),
				'paymentMethod' 			=> $this->get_payment_method(),
				'shippingMethod' 			=> $this->get_shipping_method(),
			));

			wp_localize_script( $this->plugin_name, 'template_library_object',
				array(
					'ajaxurl' 				=> esc_url_raw(admin_url('admin-ajax.php')),
					'rest_api_url' 			=> esc_url_raw(get_rest_url()),
					'dashboard_url' 		=> esc_url_raw(admin_url('admin.php?page=' . WPFNL_MAIN_PAGE_SLUG)),
					'settings_url' 			=> esc_url_raw(admin_url('admin.php?page=settings')),
					'home_url' 				=> esc_url_raw(home_url()),
					'funnel_id' 			=> $funnel_id,
					'is_pro' 				=> Wpfnl_functions::is_pro_license_activated(),
					'is_webhook'   			=> Wpfnl_functions::is_webhook_activated(),
					'is_webhook_licensed'   => Wpfnl_functions::is_webhook_license_activated(),
					'pro_url' 				=> add_query_arg('wpfnl-dashboard', '1', 'https://rextheme.com/wpfunnels'),
					'nonce' 				=> wp_create_nonce('wp_rest'),
					'image_path' 			=> WPFNL_URL . 'admin/assets/images',
				)
			);

			wp_localize_script( $this->plugin_name, 'CheckoutStep',
				array(
					'ajaxurl' 			=> esc_url_raw(admin_url('admin-ajax.php')),
					'rest_api_url' 		=> esc_url_raw(get_rest_url()),
					'wc_currency' 		=> $currency_symbol,
					'nonce' 			=> wp_create_nonce('wp_rest'),
					'security' 			=> wp_create_nonce('wpfnl-admin'),
					'image_path' 		=> WPFNL_URL . 'admin/assets/images',
					'tooltipIcon' 		=> WPFNL_URL . 'admin/partials/icons/question-tooltip-icon.php',
					'imageUploadIcon' 	=> WPFNL_URL . 'admin/partials/icons/image-upload-icon.php',
					'step_id' 			=> $step_id,
					'back' => add_query_arg(
						array(
							'page' => WPFNL_EDIT_FUNNEL_SLUG,
							'id' => filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT),
							'step_id' => $step_id,
						),
						admin_url('admin.php')
					)
				)
			);

			wp_localize_script( $this->plugin_name, 'wpfnl_addons_vars',
				array(
					'addons' => Wpfnl_functions::get_supported_addons()
				)
			);

			/**
			 * localize scripts for funnel window
			 * @var  $localize
			 */

			$notices = $this->get_builder_notice();

			$localize = apply_filters( 'wpfunnels/funnel_window_admin_localize', array() );
			wp_localize_script( $this->plugin_name . '-funnel-window', 'wpfunnels_funnel_localize', $localize);

			do_action( 'wpfunnels_after_scripts_loaded' );
		}
	}

	/**
	 * define w2cloud routes
	 * @return array
	 */
	public function get_wpfnl_routes()
	{
		$routes = array(
			array(
				'path' => '/',
				'name' => 'home',
				'component' => 'Home'
			)
		);
		return apply_filters('wpfnl_routes', $routes);
	}

	/**
	 * Get all active Payment Method
	 * @return array
	 */

	public function get_payment_method(){
		$gateways = WC()->payment_gateways->get_available_payment_gateways();
		$enabled_gateways = [];

		if( $gateways ) {
			foreach( $gateways as $key =>  $gateway ) {
				if( $gateway->enabled == 'yes' ) {
					$enabled_gateways[$key] = $gateway->method_title;
				}
			}
		}
		return $enabled_gateways;
	}

	/**
	 * get all shipping methods
	 * @return array
	 */
	public function get_shipping_method(){
		$available_shipping = WC()->shipping()->get_shipping_methods();
		$enabled_shipping = [];
		if( $available_shipping ) {
			foreach( $available_shipping as $key =>  $shipping ) {
				$enabled_shipping[$key] = $shipping->method_title;
			}
		}
		return $enabled_shipping;
	}


	/**
	 * @param $output
	 * @return mixed
	 * @source https://github.com/wpscholar-wp-plugins/mpress-custom-front-page/blob/8872fc4bea0419d9802b5459ee3de19b5cc2dc40/mpress-custom-front-page.php#L70
	 */
	public function show_funnel_steps_on_reading( $output ) {
		global $pagenow;
		if ( ( 'options-reading.php' === $pagenow || 'customize.php' === $pagenow ) && preg_match( '#page_on_front#', $output ) ) {
			$output = $this->show_steps_options( $output );
		}
		return $output;
	}


	/**
	 * show steps pages on reading
	 *
	 * @param $output
	 * @return string
	 */
	private function show_steps_options( $output ) {
		$options 	= '';

		$steps = get_posts(
			array(
				'post_type'      => WPFNL_STEPS_POST_TYPE,
				'posts_per_page' => - 1,
				'numberposts'    => 100,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'post_status'    => 'publish',
				'meta_query'  => array(
					'relation' => 'OR',
					array(
						'key'   => '_step_type',
						'value' => 'landing',
					),
					array(
						'key'   => '_step_type',
						'value' => 'checkout',
					)
				),
			)
		);

		if( $steps && is_array( $steps ) ) {
			$front_page_id 	= get_option( 'page_on_front' );
			foreach ( $steps as $step ) {
				$selected      	= selected( $front_page_id, $step->ID, false );
				$post_type_obj 	= get_post_type_object( $step->post_type );
				$options 		.= "<option value=\"{$step->ID}\"{$selected}>{$step->post_title} (WPFunnels {$post_type_obj->labels->singular_name})</option>";
			}
			$options 	.= '</select>';
			$output 	= str_replace( '</select>', $options, $output );
		}
		return $output;
	}


	/**
	 * get notice for builder
	 *
	 * @return array
	 */
	public function get_builder_notice() {
		$builder = Wpfnl_functions::get_builder_type();
		$notices = array();
		if( 'divi-builder' === $builder ) {
			$notices['notices'][] = array();
		}
		return $notices;
	}
}
