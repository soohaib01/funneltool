<?php

namespace WPFunnels\Widgets\Elementor;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes\Color as Scheme_Color;
use WPFunnels\Data_Store\Wpfnl_Steps_Store_Data;

if (! defined('ABSPATH')) {
	exit;
} // Exit if accessed directly

/**
 * Optin form widget
 *
 * @since 1.0.0
 */
class OptinForm extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name()
	{
		return 'wpfnl-optin-form';
	}


	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title()
	{
		return __('Optin Form', 'wpfnl');
	}


	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon()
	{
		return 'eicon-form-horizontal';
	}


	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories()
	{
		return [ 'wp-funnel' ];
	}


	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends()
	{
		return [ 'optin-form' ];
	}


	/**
	 * Get button sizes.
	 *
	 * Retrieve an array of button sizes.
	 *
	 * @return array An array containing button sizes.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_button_sizes()
	{
		return [
			'xs' => __('Extra Small', 'wpfnl'),
			'sm' => __('Small', 'wpfnl'),
			'md' => __('Medium', 'wpfnl'),
			'lg' => __('Large', 'wpfnl'),
			'xl' => __('Extra Large', 'wpfnl'),
		];
	}


	/**
	 * Register the widget controls.
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls()
	{
		$this->register_form_layout_controls();
		$this->register_form_field_controls();
		$this->register_form_button_controls();
		$this->register_action_after_submit_controls();

		//-------style tab--------
		$this->register_form_style_controls();
		$this->register_input_fields_style_controls();
		$this->register_button_style_controls();

	}


	/**
     * Register Form Layout Controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_form_layout_controls(){
		$this->start_controls_section(
			'wpfnl_optin_form_layout_controls', array(
				'label' => __('Form Layout', 'wpfnl'),
			)
		);

		$this->add_control(
			'optin_form_layout',
			[
				'label' => __( 'Form Style', 'wpfnl' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'Default Style', 'wpfnl' ),
					'form-style1'  => __('Form Style-1', 'wpfnl'),
					'form-style2' => __('Form Style-2', 'wpfnl'),
					'form-style3' => __('Form Style-3', 'wpfnl'),
					'form-style4' => __('Form Style-4', 'wpfnl'),
				],
			]
		);

//		$this->add_control(
//			'optin_form_layout',
//			[
//				'label' => esc_html__( 'Structure', 'wpfnl' ),
//				'type' => 'optin_styles',
//				'default' => 'form-style1',
//				'render_type' => 'none',
//				'style_transfer' => false,
//			]
//		);

		$this->end_controls_section();
	}


	/**
     * Register Form Controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_form_field_controls(){
		$this->start_controls_section(
			'wpfnl_optin_form_field_controls', array(
				'label' => __('Form Fields', 'wpfnl'),
			)
		);

		$this->add_control(
			'first_name',
			[
				'label' => __('First Name', 'wpfnl'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'wpfnl' ),
				'label_off' => __( 'Hide', 'wpfnl' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'last_name',
			[
				'label' => __('Last Name', 'wpfnl'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'wpfnl' ),
				'label_off' => __( 'Hide', 'wpfnl' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);
		
		$this->add_control(
			'phone',
			[
				'label' => __('Phone', 'wpfnl'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'wpfnl' ),
				'label_off' => __( 'Hide', 'wpfnl' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'acceptance_checkbox',
			[
				'label' => __('Acceptance checkbox', 'wpfnl'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'wpfnl' ),
				'label_off' => __( 'Hide', 'wpfnl' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_control(
			'acceptance_checkbox_text',
			[
				'label' => __('Acceptance text', 'wpfnl'),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'label_block' => true,
				'default' => __('I have read and agree the Terms & Condition.', 'wpfnl'),
				'condition' => [
					'acceptance_checkbox' => 'yes',
				],
			]
		);

		$this->add_control(
			'input_fields_icon',
			[
				'label' => __('Input Field Icon', 'wpfnl'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'wpfnl' ),
				'label_off' => __( 'Hide', 'wpfnl' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'field_label',
			[
				'label' => __('Field Label', 'wpfnl'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'wpfnl' ),
				'label_off' => __( 'Hide', 'wpfnl' ),
				'return_value' => 'yes',
				'default' => '',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'required_mark',
			[
				'label' => __('Field Required Mark', 'wpfnl'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'wpfnl' ),
				'label_off' => __( 'Hide', 'wpfnl' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();
	}


	/**
     * Register Button Controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_form_button_controls(){
		$this->start_controls_section(
			'wpfnl_optin_form_button_controls', array(
				'label' => __('Button', 'wpfnl'),
			)
		);

		$this->add_control(
			'btn_text',
			[
				'label' => __('Text', 'wpfnl'),
				'type' => Controls_Manager::TEXT,
				'default' => __('Submit', 'wpfnl'),
				'placeholder' => __('Submit', 'wpfnl'),
			]
		);

		$this->add_responsive_control(
			'btn_align',
			[
				'label' => __('Alignment', 'wpfnl'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'wpfnl'),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __('Center', 'wpfnl'),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __('Right', 'wpfnl'),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __('Justified', 'wpfnl'),
						'icon' => 'fa fa-align-justify',
					],
				],
				'default' => '',
				'condition' => [
					'optin_form_layout[value]!' => 'form-style1',
				],
			]
		);

		$this->add_control(
			'btn_icon',
			[
				'label' => __('Icon', 'wpfnl'),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
			]
		);

		$this->add_control(
			'btn_icon_align',
			[
				'label' => __('Icon Position', 'wpfnl'),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => __('Before Text', 'wpfnl'),
					'right' => __('After Text', 'wpfnl'),
				],
				'condition' => [
					'btn_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'btn_icon_indent',
			[
				'label' => __('Icon Spacing', 'wpfnl'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'btn_icon!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);



		$this->end_controls_section();
	}


	/**
     * Register Action After Submit Controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_action_after_submit_controls(){
		$this->start_controls_section(
			'wpfnl_action_after_submit_controls', array(
				'label' => __('Actions After Submit', 'wpfnl'),
			)
		);

		$this->add_control(
			'admin_email',
			[
				'label' => __('Admin Email', 'wpfnl'),
				'type' => Controls_Manager::TEXT,
				'separator' => 'before',
				'default' => wp_get_current_user()->user_email
			]
		);

		$this->add_control(
			'admin_email_subject',
			[
				'label' => __('Admin Email Subject', 'wpfnl'),
				'type' => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'notification_text',
			[
				'label' => __( 'Notification text', 'wpfnl' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 5,
				'placeholder' => __( 'Type notification texts here', 'wpfnl' ),
			]
		);

		$this->add_control(
			'post_action',
			[
				'label' => __( 'Other action', 'wpfnl' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'notification',
				'options' => [
					'notification'  => __( 'None', 'wpfnl' ),
					'redirect_to' 	=> __( 'Redirect to url', 'wpfnl' ),
					'next_step' => __( 'Next Step', 'wpfnl' ),
				],
			]
		);

		$this->add_control(
			'redirect_url',
			[
				'label' => __( 'Redirect url', 'wpfnl' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'wpfnl' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => true,
				],
				'condition' => [
					'post_action' => 'redirect_to',
				]
			]
		);





		// $this->add_control(
		// 	'admin_email_text',
		// 	[
		// 		'label' => __( 'Admin Email Text', 'wpfnl' ),
		// 		'type' => Controls_Manager::TEXTAREA,
		// 		'rows' => 5,
		// 		// 'default' => __( 'Notification text', 'wpfnl' ),
		// 		'placeholder' => __( 'Type texts here', 'wpfnl' ),

		// 	]
		// );

		$this->end_controls_section();
	}


	/**
     * Register Label Style Controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_form_style_controls(){
		$this->start_controls_section(
			'form_section_style',
			[
				'label' => __('Form', 'wpfnl'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'row_spacing',
			[
				'label' => __('Row Spacing', 'wpfnl'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		//--------start label style------
		$this->add_control(
            'label_style',
            [
                'label' => __('Label', 'wpfnl'),
                'type' => Controls_Manager::HEADING,
                'label_block' => true,
				'separator' => 'before',
            ]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'label' => 'Typography',
				'selector' => '{{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group:not(.acceptance-checkbox) > label',
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => __('Color', 'wpfnl'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group:not(.acceptance-checkbox) > label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'label_spacing',
			[
				'label' => __('Spacing', 'wpfnl'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group:not(.acceptance-checkbox) > label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		//--------end label style------

		//--------start terms and condition style------
		$this->add_control(
			'tnc_style',
			[
				'label' => __('Terms and Condition', 'wpfnl'),
				'type' => Controls_Manager::HEADING,
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tnc_typography',
				'label' => 'Typography',
				'selector' => '{{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group.acceptance-checkbox > label',
			]
		);

		$this->add_control(
			'tnc_color',
			[
				'label' => __('Color', 'wpfnl'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group.acceptance-checkbox > label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tnc_link_color',
			[
				'label' => __('Link Color', 'wpfnl'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group.acceptance-checkbox > label a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'checkbox_active_color',
			[
				'label' => __('Checkbox Active Color', 'wpfnl'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group.acceptance-checkbox input[type="checkbox"]:checked + label .check-box' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'checkbox_size',
			[
				'label' => __('Checkbox Size', 'wpfnl'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 18,
						'max' => 40,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group.acceptance-checkbox > label .check-box' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'checkbox_spacing',
			[
				'label' => __('Checkbox Spacing', 'wpfnl'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group.acceptance-checkbox > label' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);
		//--------end terms and condition style------

		$this->end_controls_section();
	}


	/**
     * Register Input field Style Controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_input_fields_style_controls(){
		$this->start_controls_section(
			'inputs_section_style',
			[
				'label' => __('Input Fields', 'wpfnl'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'inputs_typography',
				'label' => 'Typography',
				'selector' => '{{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group input[type=text],
				{{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group input[type=email]',
			]
		);

		$this->add_control(
			'input_text_color',
			[
				'label' => __('Text Color', 'wpfnl'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group input[type=text],
					 {{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group input[type=email]' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'input_bg_color',
			[
				'label' => __('Background Color', 'wpfnl'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group input[type=text],
					 {{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group input[type=email]' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_box_shadow',
				'selector' => '{{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group input[type=text],
								{{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group input[type=email]',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'input_border',
				'label' => __('Border', 'wpfnl'),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group input[type=text],
								{{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group input[type=email]',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'input_border_radius',
			[
				'label' => __('Border Radius', 'wpfnl'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group input[type=text],
					 {{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group input[type=email]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label' => __('Padding', 'wpfnl'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group input[type=text],
					 {{WRAPPER}} .wpfnl-optin-form .wpfnl-optin-form-group input[type=email]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}


	/**
     * Register Button Style Controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_button_style_controls(){
		$this->start_controls_section(
			'btn_section_style',
			[
				'label' => __('Button', 'wpfnl'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'label' => 'Typography',
				'selector' => '{{WRAPPER}} button.elementor-button',
			]
		);

		$this->start_controls_tabs('btn_color_style');
		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __('Normal', 'wpfnl'),
			]
		);

		$this->add_control(
			'btn_text_color',
			[
				'label' => __('Text Color', 'wpfnl'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} button.elementor-button, {{WRAPPER}} .elementor-button' => 'color: {{VALUE}}!important;',
				],
			]
		);

		$this->add_control(
			'btn_bg_color',
			[
				'label' => __('Background Color', 'wpfnl'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} button.elementor-button, {{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}}!important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow',
				'selector' => '{{WRAPPER}} button.elementor-button',
			]
		);

		$this->end_controls_tab();
		//---end normal style----

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __('Hover', 'wpfnl'),
			]
		);

		$this->add_control(
			'btn_hover_text_color',
			[
				'label' => __('Text Color', 'wpfnl'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} button.elementor-button:hover, {{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}}!important;',
				],
			]
		);

		$this->add_control(
			'btn_hover_bg_color',
			[
				'label' => __('Background Color', 'wpfnl'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} button.elementor-button:hover, {{WRAPPER}} .elementor-button:hover' => 'background-color: {{VALUE}}!important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_hover_box_shadow',
				'selector' => '{{WRAPPER}} button.elementor-button:hover',
			]
		);

		$this->end_controls_tab();
		//---end hover style----

		$this->end_controls_tabs();
		//---end butotn color style tab----

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'btn_border',
				'label' => __('Border', 'wpfnl'),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} button.elementor-button',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => __('Border Radius', 'wpfnl'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} button.elementor-button, {{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'btn_padding',
			[
				'label' => __('Padding', 'wpfnl'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} button.elementor-button, {{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}


	/**
	 * get wrapper classes
	 *
	 * @return array
	 */
	protected function get_wrapper_classes() {
		return array( 'wpfnl', 'wpfnl-optin-form', 'wpfnl-elementor-optin-form-wrapper');
	}


	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 2.1.8
	 *
	 * @access protected
	 */
	protected function render()
	{
		$settings 	= $this->get_settings_for_display();
		$classes 	= $this->get_wrapper_classes();
		$this->add_render_attribute(
			[
				'wrapper' => [
					'class' => [
						'elementor-form-fields-wrapper',
					],
				],
			]
		);

		$this->add_render_attribute('button', 'class', 'btn-optin elementor-button');

		?>
		<style>
			<?php if( '' == $settings['input_fields_icon'] ){ ?>
				.wpfnl-optin-form .wpfnl-optin-form-group input[type=text],
				.wpfnl-optin-form .wpfnl-optin-form-group input[type=email] {
					padding-right: 14px;
				}
			<?php } ?>
		</style>
		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" <?php $this->print_render_attribute_string( 'wrapper' ); ?> >
			<form method="post" <?php $this->print_render_attribute_string( 'form' ); ?>>
				<input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>" />
				<input type="hidden" name="form_id" value="<?php echo esc_attr( $this->get_id() ); ?>"/>

				<div class="wpfnl-optin-form-wrapper <?php echo $settings['optin_form_layout']; ?>" >
					<?php if( 'yes' == $settings['first_name'] ){ ?>
						<div class="wpfnl-optin-form-group first-name">

							<?php if( 'yes' == $settings['field_label'] ){ ?>
								<label for="wpfnl-first-name">
									First Name
									<?php if( 'yes' == $settings['required_mark'] ){ ?>
										<span class="required-mark">*</span>
									<?php } ?>
								</label>
							<?php } ?>

							<span class="input-wrapper">
								<?php if( 'yes' == $settings['input_fields_icon'] ){ ?>
									<span class="field-icon">
										<img src="<?php echo WPFNL_DIR_URL.'/public/assets/images/user-icon.svg'; ?>" alt="icon">
									</span>
								<?php } ?>
								<input type="text" name="first_name" id="wpfnl-first-name" <?php echo '' == $settings['field_label'] ? 'placeholder="First Name"' : ''; echo 'yes' == $settings['required_mark'] ? 'required' : ''; ?>/>
							</span>

						</div>
					<?php } ?>

					<?php if( 'yes' == $settings['last_name'] ){ ?>
						<div class="wpfnl-optin-form-group last-name">

							<?php if( 'yes' == $settings['field_label'] ){ ?>
								<label for="wpfnl-last-name">
									Last Name
									<?php if( 'yes' == $settings['required_mark'] ){ ?>
										<span class="required-mark">*</span>
									<?php } ?>
								</label>
							<?php } ?>

							<span class="input-wrapper">
								<?php if( 'yes' == $settings['input_fields_icon'] ){ ?>
									<span class="field-icon">
										<img src="<?php echo WPFNL_DIR_URL.'/public/assets/images/user-icon.svg'; ?>" alt="icon">
									</span>
								<?php } ?>
								<input type="text" name="last_name" id="wpfnl-last-name" <?php echo '' == $settings['field_label'] ? 'placeholder="Last Name"' : ''; echo 'yes' == $settings['required_mark'] ? 'required' : ''; ?>/>
							</span>
						</div>
					<?php } ?>

					<div class="wpfnl-optin-form-group email">
						<?php if( 'yes' == $settings['field_label'] ){ ?>
							<label for="wpfnl-email">
								Email
								<?php if( 'yes' == $settings['required_mark'] ){ ?>
									<span class="required-mark">*</span>
								<?php } ?>
							</label>
						<?php } ?>
						<span class="input-wrapper">
							<?php if( 'yes' == $settings['input_fields_icon'] ){ ?>
								<span class="field-icon">
									<img src="<?php echo WPFNL_DIR_URL.'/public/assets/images/email-open-icon.svg'; ?>" alt="icon">
								</span>
							<?php } ?>
							<input type="email" name="email" id="wpfnl-email" <?php echo '' == $settings['field_label'] ? 'placeholder="Email"' : ''; echo 'yes' == $settings['required_mark'] ? 'required' : ''; ?> pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" />
						</span>
					</div>

					<?php if( 'yes' == $settings['phone'] ){ ?>
						<div class="wpfnl-optin-form-group phone">

							<?php if( 'yes' == $settings['field_label'] ){ ?>
								<label for="wpfnl-phone">
									Phone
									<?php if( 'yes' == $settings['required_mark'] ){ ?>
										<span class="required-mark">*</span>
									<?php } ?>
								</label>
							<?php } ?>

							<span class="input-wrapper">
								<?php if( 'yes' == $settings['input_fields_icon'] ){ ?>
									<span class="field-icon">
										<img src="<?php echo WPFNL_DIR_URL.'/public/assets/images/phone.svg'; ?>" alt="icon">
									</span>
								<?php } ?>
								<input type="text" name="phone" id="wpfnl-phone" <?php echo '' == $settings['field_label'] ? 'placeholder="Phone"' : ''; echo 'yes' == $settings['required_mark'] ? 'required' : ''; ?>/>
							</span>
						</div>
					<?php } ?>

					<?php
					if( 'yes' == $settings['acceptance_checkbox'] ){
					?>
						<div class="wpfnl-optin-form-group acceptance-checkbox">
							<input type="checkbox" name="acceptance_checkbox" id="wpfnl-acceptance_checkbox" <?php echo 'yes' == $settings['required_mark'] ? 'required' : ''; ?> />
							<label for="wpfnl-acceptance_checkbox">
								<span class="check-box"></span>
								<?php
									echo $settings['acceptance_checkbox_text'];
									
									if( 'yes' == $settings['required_mark'] ){
										echo '<span class="required-mark">*</span>';
									} 
								?>
							</label>
						</div>
					<?php
					}
					?>
					<div class="wpfnl-optin-form-group submit align-<?php echo $settings['btn_align'] ?>">
						<button type="submit" <?php echo $this->get_render_attribute_string('button'); ?>>
							<?php $this->render_text(); ?>
							<span class="wpfnl-loader"></span>
						</button>
					</div>
				</div>
			</form>

			<div class="response"></div>
		</div>
		<?php
	}



	/**
	 * Render button text.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render_text()
	{
		$settings = $this->get_settings();
		$migrated = isset($settings['__fa4_migrated']['btn_icon']);
		$is_new = empty($settings['icon']) && Icons_Manager::is_migration_allowed();

		if (!$is_new && empty($settings['btn_icon_align'])) {
			$settings['btn_icon_align'] = $this->get_settings('btn_icon_align');
		}

		$this->add_render_attribute([
			'content-wrapper' => [
				'class' => 'elementor-button-content-wrapper',
			],
			'icon-align' => [
				'class' => [
					'elementor-button-icon',
					'elementor-align-icon-' . $settings['btn_icon_align'],
				],
			],
			'text' => [
				'class' => 'elementor-button-text',
			],
		]);

		$this->add_render_attribute('content-wrapper', 'class', 'elementor-button-content-wrapper');
		$this->add_render_attribute('text', 'class', 'elementor-button-text');

		$this->add_inline_editing_attributes('text', 'none');
		?>
		<span <?php echo $this->get_render_attribute_string('content-wrapper'); ?>>

            <?php if (!empty($settings['icon']) || !empty($settings['btn_icon']['value'])) : ?>
				<span <?php echo $this->get_render_attribute_string('icon-align'); ?>>
                    <?php if ($is_new || $migrated) :
						Icons_Manager::render_icon($settings['btn_icon'], ['aria-hidden' => 'true']);
					else : ?>
						<i class="<?php echo esc_attr($settings['icon']); ?>" aria-hidden="true"></i>
					<?php endif; ?>
                </span>
			<?php endif; ?>

            <span <?php echo $this->get_render_attribute_string('text'); ?>><?php echo $settings['btn_text']; ?></span>
        </span>
		<?php
	}


}
