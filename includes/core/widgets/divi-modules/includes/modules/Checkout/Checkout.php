<?php

namespace WPFunnels\Widgets\DiviModules\Modules;

use ET_Builder_Element;
use ET_Builder_Module;
use ET_Builder_Module_Helper_Woocommerce_Modules;
use WPFunnels\Wpfnl_functions;

class WPFNL_Checkout extends ET_Builder_Module {

    public $slug       = 'wpfnl_checkout';
    public $vb_support = 'on';

    protected $module_credits = array(
        'module_uri' => '',
        'author'     => '',
        'author_uri' => '',
    );
    /**
     * Module properties initialization
     */
	public function init() {

		$this->name = esc_html__( 'WPF Checkout', 'wpfnl' );

        $this->icon_path        =  plugin_dir_path( __FILE__ ) . 'checkout.svg';

        $this->settings_modal_toggles  = array(
            'general'  => array(
                'toggles' => array(
                    'main_content' => esc_html__( 'Layout', 'wpfnl' ),
                    'checkout'     => esc_html__( 'Order Bump', 'wpfnl' ),
                ),
            ),
			'advanced' => array(
				'toggles' => array(
					'layout'      => array(
						'title'    => __( 'Layout','wpfnl' ),
						'priority' => 45,
					),
					'title'       => array(
						'title'    => __( 'Heading', 'wpfnl' ),
						'priority' => 50,
					),
					'field_label' => array(
						'title'    => __( 'Input Field Labels', 'wpfnl' ),
						'priority' => 55,
					),
					'form_field'  => array(
						'title'    => __( 'Input Fields', 'wpfnl' ),
						'priority' => 60,
					),
					'form_notice' => array(
						'title'    => __( 'Form Notice', 'wpfnl' ),
						'priority' => 65,
					),
					'column_label' => array(
						'title'    => __( 'Order Table Column Label', 'wpfnl' ),
						'priority' => 70,
					),
					'body'         => array(
						'title'             => __( 'Order Table Body ', 'wpfnl' ),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'p' => array(
								'name' => 'P',
								'icon' => 'text-left',
							),
							'a' => array(
								'name' => 'A',
								'icon' => 'text-link',
							),
						),
						'priority'          => 75,
					),
					'table'        => array(
						'title'    => __( 'Order Table Section', 'wpfnl' ),
						'priority' => 80,
					),
					'table_row'    => array(
						'title'    => __( 'Order Table Row', 'wpfnl' ),
						'priority' => 85,
					),
					'table_cell'   => array(
						'title'    => __( 'Order Table Cell', 'wpfnl' ),
						'priority' => 90,
					),
					'payment_body'                  => array(
						'title'             => __( 'Payment Section', 'wpfnl' ),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'p' => array(
								'name' => 'P',
								'icon' => 'text-left',
							),
							'a' => array(
								'name' => 'A',
								'icon' => 'text-link',
							),
						),
						'priority'          => 95,
					),

					'radio_button'          => array(
						'title'    => __( 'Payment Radio Buttons', 'wpfnl' ),
						'priority' => 100,
					),
					'selected_radio_button' => array(
						'title'    => __( 'Payment Selected Radio Button', 'wpfnl' ),
						'priority' => 105,
					),
					'coupon_button'          => array(
						'title'    => __( 'Coupon Button', 'wpfnl' ),
						'priority' => 115,
					),
					'coupon_form'                  => array(
						'title'             => __( 'Coupon Form', 'wpfnl' ),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'p' => array(
								'name' => 'P',
								'icon' => 'text-left',
							),
							'a' => array(
								'name' => 'A',
								'icon' => 'text-link',
							),
						),
						'priority'          => 120,
					),

				),
			),

        );
        $this->main_css_element = '%%order_class%%';

		$this->advanced_fields = array(
			'fonts'        => array(
				// Use `title` in place of `header` since `header` needs a workaround in Copy/Paste.
				'title'       => array(
					'label'       => esc_html__( 'Heading', 'wpfnl' ),
					'css'         => array(
						'main' => '%%order_class%% h3',
					),
					'font_size'   => array(
						'default' => '22px',
					),
					'line_height' => array(
						'default' => '1em',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'title',

				),
				'field_label' => array(
					'label'       => __( 'Field Label', 'wpfnl' ),
					'css'         => array(
						'main' => '%%order_class%% form .form-row label',
					),
					'font_size'   => array(
						'default' => '14px',
					),
					'line_height' => array(
						'default' => '2em',
					),
					'toggle_slug' => 'field_label',
				),
				'column_label' => array(
					'label'       => __( 'Column Label', 'wpfnl' ),
					'css'         => array(
						'main' => '%%order_class%% table.shop_table thead th',
					),
					'font_size'   => array(
						'default' => '14px',
					),
					'line_height' => array(
						'default' => '1.5em',
					),
				),
				'body'         => array(
					'label'       => __( 'Body', 'wpfnl' ),
					'css'         => array(

						// Accepts only string and not array. Hence using `implode`.
						'main'        => implode(
							', ',
							array(
								'%%order_class%% td',
								'%%order_class%% tfoot th',
							)
						),

						// Accepts only string and not array. Hence using `implode`.
						'line_height' => implode(
							', ',
							array(
								'%%order_class%% table.shop_table th',
								'%%order_class%% table.shop_table td',
							)
						),
					),
					'font_size'   => array(
						'default' => '14px',
					),
					'line_height' => array(
						'default' => '1.5em',
					),
					'toggle_slug' => 'body',
					'sub_toggle'  => 'p',
				),
				'payment_body' => array(
					'label'       => __( 'Body','wpfnl' ),
					'css'         => array(
						'main'      => implode(
							',',
							array(
								'%%order_class%% .woocommerce-privacy-policy-text',
								'%%order_class%% .woocommerce-privacy-policy-text a',
								'%%order_class%% .wc_payment_method a',
								'%%order_class%% .payment_box label',

								// Order confirmation Page elements.
								'%%order_class%% .woocommerce-order p',
								'%%order_class%% .woocommerce-order .woocommerce-order-overview',
							)
						),
						'important' => array( 'size', 'line-height' ),
					),
					'font_size'   => array(
						'default' => '14px',
					),
					'line_height' => array(
						'default' => '1.7em',
					),
					'toggle_slug' => 'payment_body',
					'sub_toggle'  => 'p',
				),
				'coupon_form' => array(
					'label'       => et_builder_i18n( 'Coupon Form' ),
					'css'         => array(
						'main'      => implode(
							',',
							array(
								'%%order_class%% .wpfnl-checkout .woocommerce form.checkout_coupon > p:not(.form-row)',
							)
						),
						'important' => array( 'size', 'line-height' ),
					),
					'font_size'   => array(
						'default' => '14px',
					),
					'line_height' => array(
						'default' => '1.7em',
					),
					'toggle_slug' => 'coupon_form',
					'sub_toggle'  => 'p',
				),
			),
			'text'         => false,
			'button'         => array(
				'button' => array(
					'label'           =>  __( ' Order Button', 'wpfnl' ),
					'css'             => array(
						'main' => '%%order_class%% #payment #place_order',
					),
					'use_alignment'   => false,
					'border_width'    => array(
						'default' => '2px',
					),
					'box_shadow'      => array(
						'css' => array(
							'main' => '%%order_class%% #payment #place_order',
						),
					),
					'margin_padding'  => array(
						'css' => array(
							'important' => 'all',
						),
					),
					'toggle_priority' => 80,
				),
				'coupon_button' => array(
					'label'           => __( 'Button', 'wpfnl' ),
					'css'             => array(
						'main' => '%%order_class%% .wpfnl-checkout .woocommerce form.checkout_coupon .button',
					),
					'use_alignment'   => false,
					'border_width'    => array(
						'default' => '2px',
					),
					'box_shadow'      => array(
						'css' => array(
							'main' => '%%order_class%% .wpfnl-checkout .woocommerce form.checkout_coupon .button',
						),
					),
					'margin_padding'  => array(
						'css' => array(
							'important' => 'all',
						),
					),
					'toggle_slug' 		=> 'coupon_button',
					'toggle_priority' => 80,
				)
			),
			'link_options' => false,
			'form_field'   => array(
				'form_field'  => array(
					'label'           => __( 'Fields', 'wpfnl' ),
					'css'             => false,
					'box_shadow'      => array(
						'css' => array(
							'main' => implode(
								',',
								array(
									'%%order_class%% .select2-container--default .select2-selection--single',
									'%%order_class%% form .form-row input.input-text',
								)
							),
						),
					),
					'border_styles'   => array(
						'form_field'       => array(
							'label_prefix' => __( 'Fields', 'wpfnl' ),
							'css'          => array(
								'main' => array(
									'border_styles' => implode(
										',',
										array(
											' %%order_class%% .select2-container--default .select2-selection--single',
											' %%order_class%% form .form-row .input-text',
										)
									),
									'border_radii'  => implode(
										',',
										array(
											' %%order_class%% .select2-container--default .select2-selection--single',
											' %%order_class%% form .form-row input.input-text',
										)
									),
								),
							),
							'defaults'     => array(
								'border_radii'  => 'on|0px|0px|0px|0px',
								'border_styles' => array(
									'width' => '0px',
									'style' => 'solid',
								),
							),
						),
						'form_field_focus' => array(
							'label_prefix' => __( 'Fields Focus', 'wpfnl' ),
							'css'          => array(
								'main' => array(
									'border_styles' => implode(
										',',
										array(
											' %%order_class%% .select2-container--default.select2-container--open .select2-selection--single',
											' %%order_class%% form .form-row .input-text:focus',
										)
									),
									'border_radii'  => implode(
										',',
										array(
											' %%order_class%% .select2-container--default.select2-container--open .select2-selection--single',
											' %%order_class%% form .form-row input.input-text:focus',
										)
									),
								),
							),
							'defaults'     => array(
								'border_radii'  => 'on|0px|0px|0px|0px',
								'border_styles' => array(
									'width' => '0px',
									'style' => 'solid',
								),
							),
						),
					),
					'font_field'      => array(
						'css'         => array(
							'main'      => implode(
								',',
								[
									' %%order_class%% .select2-container--default .select2-selection--single',
									' %%order_class%% form .form-row .input-text',
								]
							),

							// Required to override default WooCommerce styles.
							'important' => array( 'line-height', 'size', 'font' ),
						),
						'font_size'   => array(
							'default' => '14px',
						),
						'line_height' => array(
							'default' => '1.7em',
						),
					),
					'margin_padding'  => array(
//						'css' => array(
//							'main'    => '%%order_class%% form .form-row input.input-text, %%order_class%% .select2-container--default .select2-selection--single .select2-selection__rendered',
//							'padding' => '%%order_class%% form .form-row input.input-text, %%order_class%% .select2-container--default .select2-selection--single',
//							'margin'  => '%%order_class%% form .form-row input.input-text, %%order_class%% .select2-container--default .select2-selection--single',
//						),
						'css' => array(
							'main'    => '%%order_class%% form .form-row input.input-text',
							'padding' => '%%order_class%%  form .form-row input.input-text',
							'margin'  => '%%order_class%% form .form-row input.input-text',
							'important' => 'all',
						),
					),
					'width'           => array(),
					'toggle_priority' => 55,
				),
				'form_notice' => array(
					'label'                  => __( 'Form Notice', 'wpfnl' ),
					'css'                    => array(
						'form_text_color'  => '%%order_class%% .woocommerce-error li',
						'background_color' => '%%order_class%% .woocommerce-error',
						'important'        => array( 'background_color' ),
					),
					'background_color'       => array(
						'description' => __( 'Pick a color to fill the module\'s notice.', 'wpfnl' ),
					),
					'text_color'             => array(
						'description' => __( 'Pick a color to be used for the text written inside notice.', 'wpfnl' ),
					),
					'focus_background_color' => false,
					'focus_text_color'       => false,
					'font_field'             => array(
						'css'         => array(
							'main'        => '%%order_class%% .woocommerce-NoticeGroup .woocommerce-error',
							'important'   => array( 'text-shadow', 'size' ),
							'text_shadow' => '%%order_class%% .woocommerce-NoticeGroup .woocommerce-error',
						),
						'font_size'   => array(
							'default' => '18px',
						),
						'line_height' => array(
							'default' => '1.7em',
						),
					),
					'margin_padding'         => array(
						'css'            => array(
							'main'      => '%%order_class%% .woocommerce-error',
							'important' => array( 'custom_padding' ),
						),
						'custom_padding' => array(
							'default' => '15px|15px|15px|15px|false|false',
						),
					),
					'border_styles'          => array(
						'form_notice' => array(
							'label_prefix'      => esc_html__( 'Form Notice', 'wpfnl' ),
							'css'               => array(
								'main'      => array(
									'border_styles' => '%%order_class%% .woocommerce-error',
									'border_radii'  => '%%order_class%% .woocommerce-error',
								),
								'important' => true,
							),
							'defaults'          => array(
								'border_radii'  => 'on|0px|0px|0px|0px',
								'border_styles' => array(
									'width' => '0px',
									'style' => 'solid',
								),
							),
							'use_focus_borders' => false,
						),
					),
					'box_shadow'             => array(
						'css' => array(
							'main'      => '%%order_class%% .woocommerce-error',
							'important' => true,
						),
					),
				),
				'table'      => array(
					'label'                  => __( 'Table', 'wpfnl' ),
					'css'                    => array(
						'main' => '%%order_class%% table.shop_table',
					),
					'background_color'       => array(
						'description' => __( 'Pick a color to fill the module\'s table.', 'wpfnl' ),
					),
					'font_field'             => false,
					'margin_padding'         => array(
						'css'             => array(
							'main'      => '%%order_class%% table.shop_table',
							'important' => array( 'custom_margin' ),
						),
						'depends_on'      => array(
							'collapse_table_gutters_borders',
						),
						'depends_show_if' => 'off',
					),
					'text_color'             => false,
					'focus_background_color' => false,
					'focus_text_color'       => false,
					'border_styles'          => array(
						'table' => array(
							'label_prefix'      => __( 'Table', 'wpfnl' ),
							'css'               => array(
								'main' => array(
									'border_styles' => '%%order_class%% table.shop_table',
									'border_radii'  => '%%order_class%% table.shop_table',
								),
							),
							'use_focus_borders' => false,
							'defaults'          => array(
								'border_radii'  => 'on|5px|5px|5px|5px',
								'border_styles' => array(
									'width' => '1px',
								),
							),
							'depends_on'        => array(
								'collapse_table_gutters_borders',
							),
							'depends_show_if'   => 'off',
						),
					),
					'box_shadow'             => array(
						'css' => array(
							'main' => '%%order_class%% table.shop_table',
						),
					),
				),
				'table_row'  => array(
					'label'                  => __( 'Table Row', 'wpfnl' ),
					'css'                    => array(
						'main' => '%%order_class%% table.shop_table tr',
					),
					'background_color'       => array(
						'description' => __( 'Pick a color to fill the module\'s table row.', 'wpfnl' ),
					),
					'font_field'             => false,
					'margin_padding'         => array(
						'css'         => array(
							'main' => '%%order_class%% table.shop_table tr th, %%order_class%% table.shop_table tr td',
						),
						'use_margin'  => false,
						'use_padding' => false,
					),
					'text_color'             => false,
					'focus_background_color' => false,
					'focus_text_color'       => false,
					'border_styles'          => array(
						'table_row' => array(
							'label_prefix'      => __( 'Table Row', 'wpfnl' ),
							'css'               => array(
								'main'      => array(

									// Accepts only string and not array. Hence using `implode`.
									'border_radii'  => implode(
										', ',
										array(
											'%%order_class%% table.shop_table th',
											'%%order_class%% table.shop_table td',
										)
									),
									'border_styles' => implode(
										', ',
										array(
											'%%order_class%% table.shop_table th',
											'%%order_class%% table.shop_table td',
										)
									),
								),
								'important' => true,
							),
							'use_focus_borders' => false,
							'defaults'          => array(
								'border_radii'  => 'on|0px|0px|0px|0px',
								'border_styles' => array(
									'width' => '1px',
								),
							),
							'depends_on'        => array(
								'collapse_table_gutters_borders',
							),
							'depends_show_if'   => 'on',
							'use_radius'        => false,
						),
					),
					'box_shadow'             => array(
						'css' => array(
							'main' => '%%order_class%% table.shop_table tr',
						),
					),
				),
				'table_cell' => array(
					'label'                  => __( 'Table Cell', 'wpfnl' ),
					'css'                    => array(
						'main' => '%%order_class%% table.shop_table tr th, %%order_class%% table.shop_table tr td',
					),
					'background_color'       => array(
						'description' => __( 'Pick a color to fill the module\'s table cell.', 'wpfnl' ),
					),
					'font_field'             => false,
					'margin_padding'         => array(
						'css'        => array(
							'main' => implode(
								', ',
								array(
									'%%order_class%% table.shop_table tr th',
									'%%order_class%% table.shop_table tr td',
								)
							),
						),
						'use_margin' => false,
					),
					'text_color'             => false,
					'focus_background_color' => false,
					'focus_text_color'       => false,
					'border_styles'          => array(
						'table_cell' => array(
							'label_prefix'      => __( 'Table Cell', 'wpfnl' ),
							'css'               => array(
								'main'      => array(
									'border_styles' => '%%order_class%% table.shop_table tr th,%%order_class%% table.shop_table tr td',
									'border_radii'  => '%%order_class%% table.shop_table tr th, %%order_class%% table.shop_table tr td',
								),
								'important' => array( 'border-color' ),
							),
							'use_focus_borders' => false,
							'defaults'          => array(
								'border_radii'  => 'on|0px|0px|0px|0px',
								'border_styles' => array(
									'width' => '0px',
									'style' => 'solid',
								),
								'composite'     => array(
									'border_top' => array(
										'border_width_top' => '1px',
										'border_style_top' => 'solid',
										'border_color_top' => '#eeeeee',
									),
								),
							),
							'depends_on'        => array(
								'collapse_table_gutters_borders',
							),
							'depends_show_if'   => 'off',
						),
					),
					'box_shadow'             => array(
						'css' => array(
							'main' => '%%order_class%% table.shop_table tr th, %%order_class%% table.shop_table td',
						),
					),
				),

				'radio_button'          => array(
					'label'                  => __( 'Radio Button', 'wpfnl' ),
					'css'                    => array(
						'main'        => '%%order_class%% #payment .wc_payment_method',
						'text_shadow' => '%%order_class%% #payment .wc_payment_method label',
					),
					'background_color'       => array(
						'description' => __( 'Pick a color to fill the module\'s radio buttons.', 'wpfnl' ),
					),
					'text_color'             => array(
						'description' => __( 'Pick a color to be used for the text written next to radio button.', 'wpfnl' ),
					),
					'box_shadow'             => array(
						'css' => array(
							'main' => '%%order_class%% #payment .wc_payment_method',
						),
					),
					'border_styles'          => array(
						'radio_button' => array(
							'label_prefix' => __( 'Radio Button', 'wpfnl' ),
							'css'          => array(
								'main' => array(
									'border_styles' => '%%order_class%% #payment .wc_payment_method',
									'border_radii'  => '%%order_class%% #payment .wc_payment_method',
								),
							),
							'defaults'     => array(
								'border_radii' => 'off|0px|0px|0px|0px',
								'border_style' => array(
									'width' => '0px',
									'style' => 'none',
								),
							),
						),
					),
					'font_field'             => array(
						'css'         => array(
							'main'       => '%%order_class%% #payment .wc_payment_method label',
							'focus'      => '%%order_class%% #payment .input-radio:focus',
							'text_align' => '%%order_class%% #payment ul.payment_methods li',
						),
						'font_size'   => array(
							'default' => '14px',
						),
						'line_height' => array(
							'default' => '1.4em',
						),
					),
					'focus_background_color' => false,
					'focus_text_color'       => false,
					'margin_padding'         => array(
						'css' => array(
							// Different from main css selector for added specificity.
							'margin'  => '%%order_class%% #payment ul.payment_methods li',
							'padding' => '%%order_class%% #payment ul.payment_methods li',
						),
					),
					'width'                  => array(),
				),
				'selected_radio_button' => array(
					'label'                  => __( 'Selected Radio Button', 'wpfnl' ),
					'css'                    => array(
						'main'        => '%%order_class%% #payment .wc_payment_method.et_pb_checked',
						'text_shadow' => '%%order_class%% #payment .wc_payment_method.et_pb_checked label',
					),
					'background_color'       => array(
						'description' => __( 'Pick a color to fill the module\'s selected radio button.', 'wpfnl' ),
					),
					'text_color'             => array(
						'description' => __( 'Pick a color to be used for the text written next to selected radio button.', 'wpfnl' ),
					),
					'box_shadow'             => array(
						'css' => array(
							'main' => '%%order_class%% #payment .wc_payment_method.et_pb_checked',
						),
					),
					'border_styles'          => array(
						'selected_radio_button' => array(
							'label_prefix' => __( 'Selected Radio Button', 'wpfnl' ),
							'css'          => array(
								'main' => array(
									'border_styles' => '%%order_class%% #payment .wc_payment_method.et_pb_checked',
									'border_radii'  => '%%order_class%% #payment .wc_payment_method.et_pb_checked',
								),
							),
							'defaults'     => array(
								'border_radii' => 'off|0px|0px|0px|0px',
								'border_style' => array(
									'width' => '0px',
									'style' => 'none',
								),
							),
						),
					),
					'font_field'             => array(
						'css'         => array(
							'main'       => '%%order_class%% #payment .wc_payment_method.et_pb_checked label',
							'focus'      => '%%order_class%% #payment .wc_payment_method.et_pb_checked .input-radio:focus',
							'text_align' => '%%order_class%% #payment ul.payment_methods li.et_pb_checked',
						),
						'font_size'   => array(
							'default' => '14px',
						),
						'line_height' => array(
							'default' => '1.4em',
						),
					),
					'focus_background_color' => false,
					'focus_text_color'       => false,
					'margin_padding'         => array(
						'css' => array(
							// Different from main css selector for added specificity.
							'margin'  => '%%order_class%% #payment ul.payment_methods li.et_pb_checked',
							'padding' => '%%order_class%% #payment ul.payment_methods li.et_pb_checked',
						),
					),
					'width'                  => array(),
				),
				'tooltip'               => array(
					'label'                  => __( ' Payment Section Tooltip', 'wpfnl' ),
					'css'                    => array(
						'main' => '%%order_class%% #payment div.payment_box',
					),
					'background_color'       => array(
						'description' => __( 'Pick a color to fill the module\'s tooltip.', 'wpfnl' ),
					),
					'font_field'             => array(
						'css'             => array(
							'main' => '%%order_class%% .wc_payment_method p',
						),
						'font_size'       => array(
							'default'        => '',
							'allowed_values' => et_builder_get_acceptable_css_string_values( 'width' ),
							'allow_empty'    => true,
						),
						'line_height'     => array(
							'default' => '1.5em',
						),
						'hide_text_color' => false,
					),
					'margin_padding'         => array(
						'css' => array(
							'main' => '%%order_class%% #payment div.payment_box',
						),
					),
					'text_color'             => false,
					'focus_background_color' => false,
					'focus_text_color'       => false,
					'border_styles'          => array(
						'tooltip' => array(
							'label_prefix' => 'Tooltip',
							'css'          => array(
								'main' => array(
									'border_styles' => '%%order_class%% #payment div.payment_box',
									'border_radii'  => '%%order_class%% #payment div.payment_box',
								),
							),
							'defaults'     => array(
								'border_radii' => 'on|2px|2px|2px|2px',
							),
						),
					),
					'box_shadow'             => array(
						'css' => array(
							'main' => '%%order_class%% #payment div.payment_box',
						),
					),
					'toggle_priority'        => 99,
				),
				'coupon'               => array(
					'label'                  => __( ' Coupon', 'wpfnl' ),
					'css'                    => array(
						'main' => '%%order_class%% .wpfnl-checkout .woocommerce-form-coupon-toggle .woocommerce-info',
					),
					'background_color'       => array(
						'description' => __( 'Pick a color to fill the module\'s coupon.', 'wpfnl' ),
					),
					'font_field'             => array(
						'css'             => array(
							'main' => '%%order_class%% .wpfnl-checkout .woocommerce-form-coupon-toggle .woocommerce-info, .wpfnl-checkout .woocommerce-form-coupon-toggle .woocommerce-info, .wpfnl-checkout .woocommerce-form-coupon-toggle .woocommerce-info a',
						),
						'font_size'       => array(
							'default'        => '',
							'allowed_values' => et_builder_get_acceptable_css_string_values( 'width' ),
							'allow_empty'    => true,
						),
						'line_height'     => array(
							'default' => '1.5em',
						),
						'hide_text_color' => false,
					),

					'margin_padding'         => array(
						'css' => array(
							'main' => '%%order_class%% .wpfnl-checkout .woocommerce-form-coupon-toggle .woocommerce-info',
						),
					),
					'text_color'             => false,
					'focus_background_color' => false,
					'focus_text_color'       => false,
					'border_styles'          => array(
						'coupon' => array(
							'label_prefix' => 'Coupon',
							'css'          => array(
								'main' => array(
									'border_styles' => '%%order_class%% .wpfnl-checkout .woocommerce-form-coupon-toggle .woocommerce-info',
									'border_radii'  => '%%order_class%% .wpfnl-checkout .woocommerce-form-coupon-toggle .woocommerce-info',
								),
							),
						),
					),
					'box_shadow'             => array(
						'css' => array(
							'main' => '%%order_class%% .wpfnl-checkout .woocommerce-form-coupon-toggle .woocommerce-info',
						),
					),
					'toggle_priority'        => 110,
				),

			),
			'margin_padding' => array(
				'use_margin'  => true,
				'use_padding' => true,
				'css' => array(
					'main'  => "%%order_class%% h3",
					'important' => 'all',
				),
				'label_prefix'    => __( 'Heading', 'wpfnl' ),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'title',
			),
			'borders' =>array(
				'title' =>	array(
					'css'             => array(
						'main' => array(
							'border_radii' => "%%order_class%% h3",
							'border_styles' => "%%order_class%% h3",
						)
					),
					'label_prefix'    => __( 'Heading', 'wpfnl' ),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'title',
				)
			),
			'animation' => false,
			'box_shadow' => false,

		);

		$this->custom_css_fields = array(
			'title_text'  => array(
				'label'    => __( 'Heading Text', 'wpfnl' ),
				'selector' => '%%order_class%% h3',
			),
			'field_label' => array(
				'label'    => __( 'Field Label', 'wpfnl' ),
				'selector' => '%%order_class%% form .form-row label',
			),
			'form_field'  => array(
				'label'    => __( 'Fields', 'wpfnl' ),
				'selector' => implode(
					',',
					array(
						'%%order_class%% .select2-container--default .select2-selection--single',
						'%%order_class%% form .form-row .input-text',
					)
				),
			),
			'form_notice' => array(
				'label'    => __( 'Form Notice', 'wpfnl' ),
				'selector' => '%%order_class%% .woocommerce-error',
			),
		);

	}

    /**
     * Module's specific fields
     *
     *
     * The following modules are automatically added regardless being defined or not:
     *   Tabs     | Toggles          | Fields
     *   --------- ------------------ -------------
     *   Content  | Admin Label      | Admin Label
     *   Advanced | CSS ID & Classes | CSS ID
     *   Advanced | CSS ID & Classes | CSS Class
     *   Advanced | Custom CSS       | Before
     *   Advanced | Custom CSS       | Main Element
     *   Advanced | Custom CSS       | After
     *   Advanced | Visibility       | Disable On
     * @return array
     */

    public function get_fields() {
        return array(
            'layout'             => array(
                'label'            => __( 'Layout', 'wpfnl' ),
                'description'      => __( 'Checkout layout', 'wpfnl' ),
                'type'             => 'select',
                'options'          => array(
                    'wpfnl-col-1'       => __( '1 column' ,'wpfnl'),
                    'wpfnl-col-2'       => __( '2 column' ,'wpfnl'),
                    'wpfnl-multistep'   => __( 'Multistep','wpfnl' ),
                ),
                'priority'         => 80,
                'default'          => 'wpfnl-col-2',
                'default_on_front' => 'wpfnl-col-2',
                'toggle_slug'      => 'main_content',
                'sub_toggle'       => 'ul',
                'mobile_options'   => true,
                'computed_affects' => array(
                    '__checkoutForm',
                ),
            ),
            '__checkoutForm'        => array(
                'type'                => 'computed',
                'computed_callback'   => array(
                    'WPFunnels\Widgets\DiviModules\Modules\WPFNL_Checkout',
                    'get_checkout_form',
                ),
                'computed_depends_on' => array(
                    'layout',
                    'order_bump',
                    '__orderBumpEnable',
                    'order_bump_style'
                )
            ),
			'collapse_table_gutters_borders' => ET_Builder_Module_Helper_Woocommerce_Modules::get_field( 'collapse_table_gutters_borders' ),
        );
    }


    /**
     * Computed checkout form
     * @param $props
     * @return string
     */

    public static  function get_checkout_form($props) {

        $step_id 	= isset($_POST['current_page']['id']) ? $_POST['current_page']['id'] : get_the_ID();
		$step_type 	= get_post_type($step_id);

		if( Wpfnl_functions::is_funnel_step_page($step_type) ) {
//			add_filter('woocommerce_locate_template', array('WPFunnels\Widgets\DiviModules\Modules\WPFNL_Checkout', 'wpfunnels_woocommerce_locate_template'), 20, 3);
		}
        do_action( 'wpfunnels/before_checkout_form', $step_id );
        $html   =  '<div class="wpfnl-checkout '.$props['layout'].'" >';
        $html  .= do_shortcode('[woocommerce_checkout]');
        $html  .='</div>';
        return $html;
    }

    /**
     * Get Custom  Woocommerce template
     * @param $template
     * @param $template_name
     * @param $template_path
     * @return mixed|string
     */

    public static function wpfunnels_woocommerce_locate_template($template, $template_name, $template_path)
    {
        global $woocommerce;
        $_template 		= $template;
        $plugin_path 	= WPFNL_DIR . '/woocommerce/templates/';

        if (file_exists($plugin_path . $template_name)) {
            $template = $plugin_path . $template_name;
        }

        if ( ! $template ) {
            $template = $_template;
        }

        return $template;
    }

    /**
     * Render Checkout form
     * @param array $attrs
     * @param null $content
     * @param string $render_slug
     * @return bool|string|null
     */

    public function render( $attrs, $content = null, $render_slug ) {
        $output = self::get_checkout_form( $this->props );
        return $output;
    }


}

new WPFNL_Checkout;
