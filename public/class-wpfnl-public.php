<?php

namespace WPFunnels\Frontend;

use WPFunnels\Compatibility\Wpfnl_Theme_Compatibility;
use WPFunnels\Conditions\Wpfnl_Condition_Checker;
use WPFunnels\Traits\SingletonTrait;
use WPFunnels\Wpfnl_functions;
use WPFunnels\Compatibility;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Wpfnl
 * @subpackage Wpfnl/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wpfnl
 * @subpackage Wpfnl/public
 * @author     RexTheme <support@rextheme.com>
 */
class Wpfnl_Public
{

	use SingletonTrait;

	public $offer_metas;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 * @since    1.0.0
	 */
	public function __construct()
	{


		/** this will trigger when funnel first start */
		add_action( 'wp', array($this, 'init_funnel'), 1 );
		add_action( 'wp', array($this, 'init_wp_actions'), 55 );

		add_action( 'wp_enqueue_scripts', [$this, 'enqueue_styles'] );
		add_action( 'wp_enqueue_scripts', [$this, 'enqueue_scripts'] );

		add_action( 'init' , array($this, 'init_function') );

		add_filter( 'wpfnl_offer_meta', array($this, 'wpfnl_offer_meta'), 10, 1 );

		/**
		 * modify the checkout order received url to next step
		 */
		add_filter( 'woocommerce_get_checkout_order_received_url', [$this, 'redirect_to_funnel_thankyou_page'], 10, 2 );

		/*
		 * This hook will be triggered once the elementor data is saved.
		 * Will be placed this hook on another class in future.
		 */
		add_action( 'elementor/editor/after_save', array($this, 'elementor_data_after_save_action'), 10, 2 );
		add_filter( 'woocommerce_hidden_order_itemmeta', array($this, 'wpfnl_woocommerce_hidden_order_itemmeta'), 10, 1 );

		add_filter( 'woocommerce_order_item_display_meta_key', array($this, 'wpfnl_beautify_item_meta_on_order'), 10, 3 );

        add_filter( 'woocommerce_order_item_display_meta_value', array($this, 'wpfnl_update_order_item_display_meta_value'), 9999, 3 );


		/** trigger ajax if any user abandoned a funnel */
		add_action( 'wp_ajax_nopriv_maybe_abandoned_funnel', array($this, 'maybe_abandoned_funnel'), 10 );
		add_action( 'wp_ajax_maybe_abandoned_funnel', array($this, 'maybe_abandoned_funnel'), 10 );

		add_action( 'wpfunnels/funnel_journey_end', array( $this, 'end_journey' ), 10, 2 );
	}

	/**
	 *
	 */
	public function init_function(){
		$offer_meta = ['_wpfunnels_order_bump'];
		return apply_filters( 'wpfnl_offer_meta', $offer_meta );
	}

	/**
	 * init actions for a funnel
	 *
	 * @since 2.0.2
	 */
	public function init_funnel()
	{
		if (Wpfnl_functions::is_funnel_step_page()) {
			global $post;
			do_action('wpfunnels/wp', $post->ID);
			$this->start_funnel_journey();
		}
	}


	/**
	 * start session data when funnel starts
	 *
	 * @since 2.0.3
	 */
	private function start_funnel_journey()
	{
		Wpfnl_functions::start_journey();
		if (Wpfnl_functions::check_if_this_is_step_type('checkout')) {
			Wpfnl_functions::do_not_cache();
		}
	}


	public function init_wp_actions() {
		if (Wpfnl_functions::is_funnel_step_page('')) {
			/** enqueue public styles */
			add_action( 'wp_enqueue_scripts', array($this, 'enqueue_styles') );
			add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts') );
			add_action( 'wp_enqueue_scripts', array($this, 'remove_theme_styles') );
			add_filter( 'woocommerce_enqueue_styles', array( $this, 'enqueue_wc_styles' ), 9999 );

			/* Load WC templates from wpfunnels plugin */
			add_filter('woocommerce_locate_template', array($this, 'wpfunnels_woocommerce_locate_template'), 20, 3);
		}
	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
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

		if(Wpfnl_functions::is_funnel_step_page()) {
			wp_enqueue_style('wpfnl-public', plugin_dir_url(__FILE__) . 'assets/css/wpfnl-public.css', [], WPFNL_VERSION, 'all');
//			$this->load_googlefonts();
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
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
		if(Wpfnl_functions::is_funnel_step_page()) {

			$compatibility = Wpfnl_Theme_Compatibility::getInstance();

			wp_enqueue_script('wpfnl-public', plugin_dir_url(__FILE__) . 'assets/js/wpfnl-public.js', ['jquery'], WPFNL_VERSION, false);
			wp_localize_script('wpfnl-public', 'wpfnl_obj', [
					'ajaxurl' 				=> admin_url('admin-ajax.php'),
					'is_builder_preview'	=> $compatibility->is_builder_preview(),
					'funnel_id'				=> get_post_meta( get_the_ID(), '_funnel_id', true ),
					'step_id' 				=> get_the_ID(),
					'ajax_nonce' 			=> wp_create_nonce('wpfnl'),
					'abandoned_ajax_nonce' 	=> wp_create_nonce('abandoned_ajax_nonce'),
					'optin_form_nonce' 		=> wp_create_nonce('optin_form_nonce'),
					'is_user_logged_in' 	=> is_user_logged_in(),
					'is_login_reminder' 	=> get_option( 'woocommerce_enable_checkout_login_reminder' ),
				]
			);
		}
	}


	/**
	 * this functions are from qubely.
	 */
	private function load_googlefonts() {
		global $blocks;
		$contains_wpfnl_blocks = false;
		$block_fonts = [];
		$load_google_fonts ='yes';

		if ($load_google_fonts == 'yes') {
			$blocks=$this->parse_all_blocks();
			$contains_wpfnl_blocks = $this->has_wpfnl_blocks($blocks);

			if ($contains_wpfnl_blocks) {
				$block_fonts = $this->gather_block_fonts($blocks, $block_fonts);
				$global_settings = get_option($this->option_keyword);
				$global_settings = $global_settings == false ? json_decode('{}') : json_decode($global_settings);
				$global_settings = json_decode(json_encode($global_settings), true);
				$gfonts = '';
				$all_global_fonts = array();
				if (isset($global_settings['presets']) && isset($global_settings['presets'][$global_settings['activePreset']]) && isset($global_settings['presets'][$global_settings['activePreset']]['typography'])) {
					$all_global_fonts = $this->colsFromArray(array_column($global_settings['presets'][$global_settings['activePreset']]['typography'], 'value'), ['family', 'weight']);
				}
				if (count($all_global_fonts) > 0) {
					$global_fonts = array_column($all_global_fonts, 'family');

					$all_fonts = array_unique(array_merge($global_fonts, $block_fonts));

					if (!empty($all_fonts)) {
						$system = array(
							'Arial',
							'Tahoma',
							'Verdana',
							'Helvetica',
							'Times New Roman',
							'Trebuchet MS',
							'Georgia',
						);

						$gfonts_attr = ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';

						foreach ($all_fonts as $font) {
							if (!in_array($font, $system, true) && !empty($font)) {
								$gfonts .= str_replace(' ', '+', trim($font)) . $gfonts_attr . '|';
							}
						}
					}

					if (!empty($gfonts)) {
						$query_args = array(
							'family' => $gfonts,
						);

						wp_register_style(
							'qubely-google-fonts',
							add_query_arg($query_args, '//fonts.googleapis.com/css'),
							array(),
							QUBELY_VERSION
						);
						wp_enqueue_style('qubely-google-fonts');
					}
				}
			}
		}
	}


	/**
	 * parse all blocks
	 *
	 * @return array[]
	 */
	public function parse_all_blocks(){
		$blocks;
		if (is_single() || is_page() || is_404()) {
			global $post;
			if (is_object($post) && property_exists($post, 'post_content')) {
				$blocks = parse_blocks($post->post_content);
			}
		} elseif (is_archive() || is_home() || is_search()) {
			global $wp_query;
			foreach ($wp_query as $post) {
				if (is_object($post) && property_exists($post, 'post_content')) {
					$blocks = parse_blocks($post->post_content);
				}
			}
		}
		return $blocks;
	}


	/**
	 * check whether the contents has wpfnl blocks or not
	 *
	 * @param $blocks
	 * @return bool
	 */
	public function has_wpfnl_blocks($blocks){
		$is_wpfnl_block = false;
		foreach ($blocks as $key => $block) {
			if (strpos($block['blockName'], 'wpfnl') !== false) {
				$is_wpfnl_block = true;
			}
			if (isset($block['innerBlocks']) && gettype($block['innerBlocks']) == 'array' && count($block['innerBlocks']) > 0) {
				$is_wpfnl_block = $this->has_qubely_blocks($block['innerBlocks']);
			}
		}
		return $is_wpfnl_block;
	}


	/**
	 * gather block google fonts
	 *
	 * @param $blocks
	 * @param $block_fonts
	 * @return array
	 */
	public function gather_block_fonts($blocks,$block_fonts){
		$google_fonts = $block_fonts;
		foreach ($blocks as $key => $block) {
			if (strpos($block['blockName'], 'wpfnl') !== false) {
				foreach ($block['attrs'] as $key =>  $att) {
					if (gettype($att) == 'array' && isset($att['openTypography']) && isset($att['family'])) {
						if (isset($block['attrs'][$key]['activeSource'])) {
							if ($block['attrs'][$key]['activeSource'] == 'custom') {
								array_push($google_fonts,$block['attrs'][$key]['family']);
							}
						} else {
							array_push($google_fonts,$block['attrs'][$key]['family']);
						}
					}
				}
			}
			if(isset($block['innerBlocks']) && gettype($block['innerBlocks']) == 'array' && count($block['innerBlocks'])>0){
				$child_fonts=$this->gather_block_fonts($block['innerBlocks'],$google_fonts);
				if(count($child_fonts)>0){
					$google_fonts=	array_merge($google_fonts,$child_fonts);
				}
			}
		}
		return array_unique($google_fonts);
	}


	/**
	 * @param array $array
	 * @param $keys
	 * @return array
	 */
	public function colsFromArray(array $array, $keys) {
		if (!is_array($keys)) $keys = [$keys];
		return array_map(function ($el) use ($keys) {
			$o = [];
			foreach($keys as $key){
				//  if(isset($el[$key]))$o[$key] = $el[$key]; //you can do it this way if you don't want to set a default for missing keys.
				$o[$key] = isset($el[$key])?$el[$key]:false;
			}
			return $o;
		}, $array);
	}


	/**
	 * remove theme style
	 *
	 * @return bool
	 */
	public function remove_theme_styles() {
		if( Wpfnl_functions::is_funnel_step_page() ) {

			if ( Wpfnl_Theme_Compatibility::getInstance()->is_compatible_theme_enabled() ) {
				return;
			}

			$wp_styles  = wp_styles();
			$themes_uri = get_theme_root_uri();

			$dequeue_theme_style = apply_filters('wpfunnels/dequeue_theme_style', false);

			if($dequeue_theme_style) {
				foreach ( $wp_styles->registered as $wp_style ) {
					if ( strpos( $wp_style->src, $themes_uri ) !== false ) {
						wp_deregister_style( $wp_style->handle );
						wp_dequeue_style( $wp_style->handle );
						do_action('wpfunnels/enqueue_custom_scripts');
					}
				}
			}
		}
	}


	/**
	 * enqueue woocommerce styles
	 *
	 * @return array
	 */
	public function enqueue_wc_styles() {
		$wc_styles = array(
			'woocommerce-layout'      => array(
				'src'     => plugins_url( 'assets/css/woocommerce-layout.css', WC_PLUGIN_FILE ),
				'deps'    => '',
				'version' => WC_VERSION,
				'media'   => 'all',
				'has_rtl' => true,
			),
			'woocommerce-smallscreen' => array(
				'src'     => plugins_url( 'assets/css/woocommerce-smallscreen.css', WC_PLUGIN_FILE ),
				'deps'    => 'woocommerce-layout',
				'version' => WC_VERSION,
				'media'   => 'only screen and (max-width: ' . apply_filters( 'woocommerce_style_smallscreen_breakpoint', '768px' ) . ')',
				'has_rtl' => true,
			),
			'woocommerce-general'     => array(
				'src'     => plugins_url( 'assets/css/woocommerce.css', WC_PLUGIN_FILE ),
				'deps'    => '',
				'version' => WC_VERSION,
				'media'   => 'all',
				'has_rtl' => true,
			),
		);

		return $wc_styles;
	}


	/**
	 * save custom meta fields for order bump while saving
	 * checkout widget
	 *
	 * @param $post_id
	 * @param $editor_data
	 *
	 * @since 2.0.0
	 */
	public function elementor_data_after_save_action($post_id, $editor_data)
	{
		if (Wpfnl_functions::check_if_this_is_step_type_by_id($post_id, 'checkout')) {
			foreach ($editor_data as $key => $inner_element) {
				$checkout_widget = Wpfnl_functions::recursive_multidimensional_ob_array_search_by_value('wpfnl-checkout', $inner_element['elements']);
				if ($checkout_widget) {
					$widget_settings = $checkout_widget['settings'];

					$order_bump_status = get_post_meta($post_id, 'order-bump', true);
					$order_bump_settings = get_post_meta($post_id, 'order-bump-settings', true);
					$order_bump_enabled = 'no';
					$default_order_bump_data = [
						'isEnabled' => 'no',
						'selectedStyle' => 'style1',
						'position' => 'after-order',
						'product' => '',
						'quantity' => '1',
						'price' => '',
						'salePrice' => '',
						'htmlPrice' => '',
						'productImage' => '',
						'highLightText' => 'Special one time offer',
						'checkBoxLabel' => 'Grab this offer with one click!',
						'productDescriptionText' => 'Get this scratch proof 6D Tempered Glass Screen Protector for your iPhone. Keep your phone safe and sound just like a new one. ',
						'discountOption' => 'original',
						'discountValue' => '',
						'couponName' => '',
						'obNextStep' => 'default',
						'productName' => '',
						'isReplace' => 'no',
					];

					if ($order_bump_status) {
						$order_bump_enabled = $order_bump_status;
					}

					if ($order_bump_settings) {
						$order_bump_data = $order_bump_settings;
					} else {
						$order_bump_data = $default_order_bump_data;
					}
					$order_bump_data['isEnabled'] = $this->set_order_bump_data('isEnabled', 'order_bump', $widget_settings, $order_bump_data, 'yes');
					$order_bump_data['selectedStyle'] = $this->set_order_bump_data('selectedStyle', 'order_bump_layout', $widget_settings, $order_bump_data, 'style1');
					$order_bump_data['position'] = $this->set_order_bump_data('position', 'order_bump_position', $widget_settings, $order_bump_data, 'after-order');
					$order_bump_data['checkBoxLabel'] = $this->set_order_bump_data('checkBoxLabel', 'order_bump_checkbox_label', $widget_settings, $order_bump_data, 'Grab this offer with one click!');
					$order_bump_data['highLightText'] = $this->set_order_bump_data('highLightText', 'order_bump_product_detail_header', $widget_settings, $order_bump_data, 'Special one time offer');
					$order_bump_data['productDescriptionText'] = $this->set_order_bump_data('productDescriptionText', 'order_bump_product_detail', $widget_settings, $order_bump_data, 'Get this scratch proof 6D Tempered Glass Screen Protector for your iPhone. Keep your phone safe and sound just like a new one.');
					$ob_image = $this->set_order_bump_data('productImage', 'order_bump_image', $widget_settings, $order_bump_data);
					$order_bump_data['productImage'] = array(
						'id' => isset($ob_image['id']) ? $ob_image['id'] : 0,
						'url' => isset($ob_image['url']) ? $ob_image['url'] : '',
					);

					update_post_meta($post_id, 'order-bump-settings', $order_bump_data);
					update_post_meta($post_id, 'order-bump', $order_bump_data['isEnabled']);
				}
			}
		}
	}


	/**
	 * redirect to next step
	 *
	 * @param $order_received_url
	 * @param $order
	 * @return string
	 *
	 * @since 1.0.0
	 */
	public function redirect_to_funnel_thankyou_page( $order_received_url, $order ) {

		if (Wpfnl_functions::check_if_funnel_order( $order )) {
			$order_key 			= $order->get_order_key();
			$order_id 			= $order->get_id();
			$current_page_id 	= get_post_meta($order_id, '_wpfunnels_checkout_id', true);
			$funnel_id 			= get_post_meta($order_id, '_wpfunnels_funnel_id', true);
			$link 				= '#';
			$next_step 			= Wpfnl_functions::get_next_step( $funnel_id, $current_page_id );
			if ( !$funnel_id ) {
				return $link;
			}

			if ($next_step) {
				if ( 'conditional' !== $next_step['step_type'] ) {
					$next_step_id = $next_step['step_id'];

					$custom_url = Wpfnl_functions::custom_url_for_thankyou_page( $next_step_id );
					if( $custom_url ){
						return $custom_url;
					}

					$query_args = array(
						'wpfnl-order' 	=> $order_id,
						'wpfnl-key' 	=> $order_key,
					);
					$next_step_url = $this->get_thankyou_step_url( $funnel_id, $next_step_id, $order );
					if( $next_step_url ) {
						return add_query_arg( $query_args, $next_step_url );
					}
					return $order_received_url;
				} else {
					$condition				= Wpfnl_Condition_Checker::getInstance();
					$condition_identifier	= strval( $next_step['step_id'] );
					$condition_matched		= $condition->check_condition( $funnel_id, $order, $condition_identifier, $current_page_id );
					$next_node 				= Wpfnl_functions::get_next_step( $funnel_id, $condition_identifier, $condition_matched );

					$custom_url = Wpfnl_functions::custom_url_for_thankyou_page( $next_node['step_id'] );
					if( $custom_url ){
						return $custom_url;
					}

					$query_args = array(
						'wpfnl-order' => $order_id,
						'wpfnl-key' => $order_key,
					);
					$next_step_url = $this->get_thankyou_step_url( $funnel_id, $next_node['step_id'], $order );
					if( $next_step_url ) {
						return add_query_arg( $query_args, $next_step_url );
					}
					return $order_received_url;
				}
			}
		}
		return $order_received_url;
	}


	/**
	 * get thankyou page url
	 *
	 * @param $funnel_id
	 * @param $step_id
	 * @param $order_received_url
	 * @return string
	 */
	private function get_thankyou_step_url( $funnel_id, $step_id, $order ) {
		/**
		 * If pro plugin isn't installed, there is no need to redirect user to the
		 * offer page. So for the plugin we redirect the user to the funnel thankyou page
		 * forcefully. We have also placed a hook for next step url, which will be modified
		 * on the pro plugin
		 */

		$next_step_url 		= get_permalink( $step_id );
		$thankyou_step_url  = '';
		if ( Wpfnl_functions::check_if_this_is_step_type_by_id( $step_id, 'thankyou' ) ) {
			return apply_filters( 'wpfunnels/funnel_thankyou_page_url', $next_step_url, $order );
		}
		else {
			$thankyou_page_id = Wpfnl_functions::get_thankyou_page_id( $funnel_id );
			if( $thankyou_page_id ) {
				$thankyou_step_url = get_permalink( $thankyou_page_id );
			}
		}
		return apply_filters( 'wpfunnels/next_step_url', $thankyou_step_url, $next_step_url, $order );
	}


	/**
	 * @param $settings_key
	 * @param $key
	 * @param $widget_settings
	 * @param string $default_value
	 * @return mixed|string
	 *
	 * @since 2.0.0
	 */
	private function set_order_bump_data($settings_key, $key, $widget_settings, $order_bump_data, $default_value = '')
	{
		$value = $default_value;
		if (isset($widget_settings[$key])) {
			$value = $widget_settings[$key];
		} else {
			$value = $order_bump_data[$settings_key];
		}
		return $value;
	}


	/**
	 * locate wc templates from plugin folders
	 *
	 * @param $template
	 * @param $template_name
	 * @param $template_path
	 * @return string
	 *
	 * @since 2.0.3
	 */
	public function wpfunnels_woocommerce_locate_template($template, $template_name, $template_path)
	{
		global $woocommerce;
		$_template 		= $template;
		$plugin_path 	= WPFNL_DIR . 'woocommerce/templates/';

		if (file_exists($plugin_path . $template_name)) {
			$template = $plugin_path . $template_name;
		}

		if ( ! $template ) {
			$template = $_template;
		}
		return $template;
	}


	/**
	 * wpfnl_offer_meta
	 *
	 * Return offer meta
	 */
	public function wpfnl_offer_meta($offer_meta){
		$this->offer_metas = $offer_meta;
		return $offer_meta;
	}

    /**
     * Hidden order item meta
     *
     * @param $meta
     *
     * @return $meta
     */
    public function wpfnl_woocommerce_hidden_order_itemmeta($meta) {
        $meta = ['_wpfunnels_step_id','_wpfnl_upsell','_wpfnl_downsell','_wpfnl_step_id','_wpfunnels_offer_txn_id','_reduced_stock','_wpfunnels_offer_refunded'];
        return $meta;
    }

	/**
     * beautify item meta on order
     *
     * @param $display_key, $meta, $item
     *
     * @return $display_key
     */
    public function wpfnl_beautify_item_meta_on_order( $display_key, $meta, $item ){
		$offer_meta = '_wpfunnels_order_bump';
		if( is_admin() && $item->get_type() === 'line_item' && ( $meta->key === $offer_meta)) {
            $display_key = __("Offer Type", "woocommerce" );
        }
		return $display_key;
    }

    /**
     * Display customize meta value
     *
     * @param $display_key, $meta, $item
     *
     * @return $meta
     */
    public function wpfnl_update_order_item_display_meta_value($display_key, $meta, $item){
		if( isset($item['order_id']) &&  $item['order_id'] ){
			$order = wc_get_order( $item['order_id'] );
			if ( Wpfnl_functions::check_if_funnel_order($order) ) {
				if( is_admin() && $item->get_type() === 'line_item' && ( $meta->key === '_wpfunnels_order_bump')) {
					$meta = __("Order Bump", "woocommerce" );
					return $meta;
				}elseif( is_admin() && $item->get_type() === 'line_item' && ( $meta->key === '_wpfunnels_upsell')) {
					$meta = __("Upsell", "woocommerce" );
					return $meta;
				}elseif( is_admin() && $item->get_type() === 'line_item' && ( $meta->key === '_wpfunnels_downsell')) {
					$meta = __("Downsell", "woocommerce" );
					return $meta;
				}elseif( is_admin() && $item->get_type() === 'shipping' && $meta->key === 'Items') {
					$meta = $item->get_meta('Items');
					return $meta;
				}else{
					$display_value = $meta->value;
					return $display_value;
				}
			}else{
				$display_value = $meta->value;
				return $display_value;

			}
		}else{
			$display_value = $meta->value;
			return $display_value;
		}

    }


	/**
	 * may be user abandoned funnel
	 */
    public function maybe_abandoned_funnel() {
		check_ajax_referer( 'abandoned_ajax_nonce', 'security' );
		$step_id 					= $_POST['step_id'];
		$funnel_id 					= $_POST['funnel_id'];
		$cookie_name        		= 'wpfunnels_automation_data';
		$cookie             		= isset( $_COOKIE[$cookie_name] ) ? json_decode( wp_unslash( $_COOKIE[$cookie_name] ), true ) : array();
		$cookie['funnel_status']   	= 'abandoned';
		$current_user = wp_get_current_user();
		if( !isset($_SESSION) )  {
			session_start();
		}
		if ( isset( $_SESSION ) || $_SESSION ) {
			if( isset($_SESSION['wpfnl_orders_'.get_current_user_id().'_'.$funnel_id]) ){
				unset($_SESSION['wpfnl_orders_'.get_current_user_id().'_'.$funnel_id]);
			}
		}

		do_action( 'wpfunnels/maybe_user_abandoned_funnel', $step_id, $funnel_id, $cookie );
		die();
	}


	/**
	 * Redirect Thankyou page
	 *
	 * @param mixed $step_id
	 * @param mixed $funnel_id
	 *
	 * @return [type]
	 */
	public function end_journey( $step_id, $funnel_id ){

		if( !Wpfnl_Theme_Compatibility::getInstance()->is_elementor_preview() ){
			Wpfnl_functions::custom_url_for_thankyou_page( $step_id );
		}

	}

}
