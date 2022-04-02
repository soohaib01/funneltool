<?php

namespace WPFunnels\Widgets\Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Icons_Manager;
use Elementor\Core\Schemes\Color as Scheme_Color;
use WPFunnels\Data_Store\Wpfnl_Steps_Store_Data;
use WPFunnels\Wpfnl_functions;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/**
 * Funnel sell Reject button
 *
 * @since 1.0.0
 */
class Step_Pointer extends Widget_Base
{

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_name()
	{
		return 'wpfnl-next-step';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_title()
	{
		return __('Next Step Button', 'wpfnl');
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_icon()
	{
		return 'icon-wpfnl next-step';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_categories()
	{
		return ['wp-funnel'];
	}


	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @return array Widget scripts dependencies.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_script_depends()
	{
		return ['next-step-widget'];
	}

	/**
	 * Get button sizes.
	 *
	 * Retrieve an array of button sizes for the button widget.
	 *
	 * @return array An array containing button sizes.
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 */
	public static function get_button_sizes()
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
	 * Get funnel types.
	 *
	 * Retrieve an array of funnel types.
	 *
	 * @return array An array containing funnel types.
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 */
	public function get_funnel_types()
	{
		$response = array(
			'null' => __('Select Type', 'wpfnl'),
		);
		$response['checkout'] = __('Checkout', 'wpfnl');
		// $response['lead'] = __('Lead', 'wpfnl');

		return $response;
	}

	/**
	 * Get all WC products
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function get_products_array()
	{
		$products = array();
		if (in_array('woocommerce/woocommerce.php', WPFNL_ACTIVE_PLUGINS)) {
			$ids = wc_get_products(array('return' => 'ids', 'limit' => -1));
			foreach ($ids as $id) {
				$title = get_the_title($id);
				$products[$id] = $title;
			}
		}
		return $products;
	}

	/**
	 * Get all wp pages
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function get_available_pages()
	{
		$data = array();
		global $wpdb;
		if (in_array('fluentform/fluentform.php', WPFNL_ACTIVE_PLUGINS)) {
			$sql = $wpdb->prepare("SELECT id FROM {$wpdb->prefix}fluentform_forms");
			$results = $wpdb->get_results($sql);
			foreach ($results as $value) {
				$sql = $wpdb->prepare("SELECT title FROM {$wpdb->prefix}fluentform_forms WHERE id=$value->id");
				$title = $wpdb->get_results($sql);
				$data[$value->id] = $title[0]->title;
			}
		}
		return $data;
	}

	protected function get_available_steps()
	{
		$step_id = get_the_ID();
		$funnel_id = get_post_meta($step_id, '_funnel_id', true);
		if (!$funnel_id) {
			return null;
		}
		$data = array();
		$steps = get_post_meta($funnel_id, '_steps_order', true);
		if($steps) {
			foreach ($steps as $step_key => $step_value) {
				$data[$step_value['id']] = $step_value['name'];
			}
		}

		return $data;
	}

	public function get_prev_next_link_options()
	{
		$associate_funnel_id = get_post_meta(get_the_ID(), '_funnel_id', true);
		$steps_array = [
			'landing' => 'Landing',
			'checkout' => 'Checkout',
			'upsell' => 'Upsell',
			'downsell' => 'Downsell',
			'thankyou' => 'Thankyou'
		];
		$option_group = [];
		foreach ($steps_array as $key => $value) {
			$args = [
				'posts_per_page' => -1,
				'orderby' => 'date',
				'order' => 'DESC',
				'post_type' => WPFNL_STEPS_POST_TYPE,
				'post_status' => 'publish',
				'post__not_in' => [$this->get_id()],
				'meta_query' => [
					'relation' => 'AND',
					[
						'key' => '_step_type',
						'value' => $key,
						'compare' => '=',
					],
					[
						'key' => '_funnel_id',
						'value' => $associate_funnel_id,
						'compare' => '=',
					],
				],
			];
			$query = new \WP_Query($args);
			$steps = $query->posts;
			if ($steps) {
				foreach ($steps as $s) {
					$option_group[$key][] = [
						'id' => $s->ID,
						'title' => $s->post_title,
					];
				}
			}
		}
		return $option_group;
	}

	/**
	 * Get all funnel steps
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function get_steps_array($type = 'upsell')
	{
		$options = $this->get_prev_next_link_options();
		$response = array();
		if (isset($options[$type])) {
			$prime_data = $options[$type];
			foreach ($prime_data as $data) {
				$response[$data['id']] = $data['title'];
			}
		}

		return $response;
	}

	/**
	 * Register the widget controls.
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls()
	{
		$this->start_controls_section(
			'section_button_controller',
			[
				'label' => __('Next Step Button', 'wpfnl'),
			]
		);

		$general_data = get_option('_wpfunnels_general_settings');
		$funnel_types = $general_data['funnel_type'];

		if ($funnel_types == 'sales') {
			$button_type_default = 'checkout';
		} else {
			$button_type_default = 'lead';
		}

		$this->add_control(
			'button_type_selector',
			[
				'label' => __('Select Button Type', 'wpfnl'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'checkout',
				'options' => $this->get_funnel_types(),
			]
		);

//        $this->add_control(
//            'checkout_product_selector',
//            [
//                'label' => __( ' Checkout Product', 'wpfnl' ),
//                'type' => \WPFunnels\Widgets\Elementor\Controls\Product_Control::ProductSelector,
//                'options' => $this->get_products_array(),
//                'multiple' => true,
//                'condition' => [
//                  'button_type_selector' => 'checkout',
//                ]
//            ]
//        );

		// $this->add_control(
		//     'checkout_product_selector',
		//     [
		//         'label' => __(' Checkout Product', 'wpfnl'),
		//         'type' => \WPFunnels\Widgets\Elementor\Controls\Product_Control::ProductSelector,
		//         'multiple' => false,
		//         'condition' => [
		//             'button_type_selector' => 'checkout',
		//         ]
		//     ]
		// );

		$this->add_control(
			'lead_type_selector',
			[
				'label' => __('Lead Type', 'wpfnl'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'page' => __('Page', 'wpfnl'),
					'popup' => __('Popup', 'wpfnl'),
				],
				'condition' => [
					'button_type_selector' => 'lead',
				]
			]
		);

//		$this->add_control(
//			'lead_type_page_selector',
//			[
//				'label' => __('Fluent Form Selector', 'wpfnl'),
//				'type' => \Elementor\Controls_Manager::SELECT,
//				'default' => '',
//				'options' => $this->get_available_pages(),
//				'condition' => [
//					'lead_type_selector' => 'page',
//				]
//			]
//		);

		$this->add_control(
			'fluent_form_next_step',
			[
				'label' => __('Select Next Step', 'wpfnl'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $this->get_available_steps(),
				'condition' => [
					'button_type_selector' => 'lead',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button',
			[
				'label' => __('Next Step Button Content', 'wpfnl'),
				'condition' => [
					'button_type_selector' => 'checkout',
				]
			]
		);

		$this->add_control(
			'text',
			[
				'label' => __('Text', 'wpfnl'),
				'type' => Controls_Manager::TEXT,
				'default' => __('Go Next', 'wpfnl'),
				'placeholder' => __('Go Next', 'wpfnl'),
			]
		);

		$this->add_responsive_control(
			'align',
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
				'prefix_class' => 'elementor%s-align-',
				'default' => '',
			]
		);

		$this->add_control(
			'size',
			[
				'label' => __('Size', 'wpfnl'),
				'type' => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => self::get_button_sizes(),
			]
		);


		$this->add_control(
			'next_step_button_icon',
			[
				'label' => __('Icon', 'wpfnl'),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
			]
		);

		$this->add_control(
			'next_step_button_icon_align',
			[
				'label' => __('Icon Position', 'wpfnl'),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => __('Before Text', 'wpfnl'),
					'right' => __('After Text', 'wpfnl'),
				],
				'condition' => [
					'next_step_button_icon[value]!' => '',
				],
			]
		);

		$this->add_control(
			'next_step_button_icon_indent',
			[
				'label' => __('Icon Spacing', 'wpfnl'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'next_step_button_icon!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'view',
			[
				'label' => __('View', 'wpfnl'),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __('Button', 'wpfnl'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'button_type_selector' => 'checkout',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'next_step_button_typography',
				'label' => 'Typography',
				'selector' => '{{WRAPPER}} a.elementor-button',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'next_step_button_text_shadow',
				'selector' => '{{WRAPPER}} a.elementor-button',
			]
		);

		$this->start_controls_tabs('tabs_button_style');

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __('Normal', 'wpfnl'),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => __('Text Color', 'wpfnl'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => __('Background Color', 'wpfnl'),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __('Hover', 'wpfnl'),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => __('Text Color', 'wpfnl'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => __('Background Color', 'wpfnl'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __('Border Color', 'wpfnl'),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => __('Animation', 'wpfnl'),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __('Border', 'wpfnl'),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .elementor-button',
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
					'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => __('Padding', 'wpfnl'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors' => [
					'{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		
		$this->end_controls_section();
		
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render()
	{
		$settings = $this->get_settings();
		
		$this->add_render_attribute('wrapper', 'class', 'elementor-button-wrapper');
		$this->add_render_attribute('button', 'class', 'elementor-button');
		$products = '';
		if (!empty($settings['size'])) {
			$this->add_render_attribute('button', 'class', 'elementor-size-' . $settings['size']);
		}

		if ($settings['hover_animation']) {
			$this->add_render_attribute('button', 'class', 'elementor-animation-' . $settings['hover_animation']);
		}
		if (isset($settings['button_type_selector']) && $settings['button_type_selector'] == 'checkout') {
			if( !Wpfnl_functions::is_plugin_activated('woocommerce/woocommerce.php') ) {
				echo '<p style="color: red;">Please activate WooCommerce</p>';
			}

			// $products = implode(",", $settings['checkout_product_selector']);
			$products_array = get_post_meta(get_the_ID(), 'checkout_product_selector', true);
			if ($products_array) {
				$products = implode(",", $products_array);
			}

			?>
			<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
				<a href="#" data-id="<?php echo get_the_ID(); ?>" data-products="<?php echo $products; ?>"
				   id="wpfunnels_next_step_controller"
				   style="cursor: pointer;" <?php echo $this->get_render_attribute_string('button'); ?>>
					<?php $this->render_text(); ?>
				</a>
			</div>
			<span class="wpfnl-alert" id="wpfnl-next-button-loader"></span>
			<?php
		} elseif (isset($settings['button_type_selector']) && $settings['button_type_selector'] == 'lead') {
			if (!in_array('fluentform/fluentform.php', WPFNL_ACTIVE_PLUGINS)) {
				echo '<p style="color: red;">Please activate Fluent Forms</p>';
			}
			$form = $settings['lead_type_page_selector'];
			if (isset($settings['lead_type_selector']) && $settings['lead_type_selector'] == 'page') {
				echo do_shortcode('[fluentform id="' . $form . '"]');
			} elseif (isset($settings['lead_type_selector']) && $settings['lead_type_selector'] == 'popup') {
				echo do_shortcode('[fluentform_modal form_id="' . $form . '" ]');
				//echo '<a href="#" id="modal-id">show modal</a>';
			}
		}

	}


	/**
	 * Render button text.
	 *
	 * Render button widget text.
	 *
	 * @since 1.5.0
	 * @access protected
	 */
	protected function render_text()
	{
		$settings = $this->get_settings();
		$migrated = isset($settings['__fa4_migrated']['next_step_button_icon']);
		$is_new = empty($settings['icon']) && Icons_Manager::is_migration_allowed();

		if (!$is_new && empty($settings['next_step_button_icon_align'])) {

			$settings['next_step_button_icon_align'] = $this->get_settings('next_step_button_icon_align');
		}

		$this->add_render_attribute([
			'content-wrapper' => [
				'class' => 'elementor-button-content-wrapper',
			],
			'icon-align' => [
				'class' => [
					'elementor-button-icon',
					'elementor-align-icon-' . $settings['next_step_button_icon_align'],
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

            <?php if (!empty($settings['icon']) || !empty($settings['next_step_button_icon']['value'])) : ?>
				<span <?php echo $this->get_render_attribute_string('icon-align'); ?>>
                    <?php if ($is_new || $migrated) :
						Icons_Manager::render_icon($settings['next_step_button_icon'], ['aria-hidden' => 'true']);
					else : ?>
						<i class="<?php echo esc_attr($settings['icon']); ?>" aria-hidden="true"></i>
					<?php endif; ?>
                </span>
			<?php endif; ?>

            <span <?php echo $this->get_render_attribute_string('text'); ?>><?php echo $settings['text']; ?></span>
        </span>
		<?php
	}


	/**
	 * Fnd next step url
	 *
	 * @since 1.5.0
	 * @access protected
	 */
	public function get_next_step_url()
	{
		$id = get_the_ID();
		$funnel_id = get_post_meta($id, '_funnel_id', true);
		$steps_order = get_post_meta($funnel_id, '_steps_order', true);
		$step_object = new Wpfnl_Steps_Store_Data();
		$next_step_id = $step_object->get_next_step($steps_order, $funnel_id);
		if ($next_step_id) {
			$next_step_url = get_post_permalink($next_step_id);
		} else {
			$next_step_url = '#';
		}
		return $next_step_url;
	}
}
