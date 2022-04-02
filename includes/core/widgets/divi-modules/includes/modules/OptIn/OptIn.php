<?php

namespace WPFunnels\Widgets\DiviModules\Modules;

use ET_Builder_Element;
use ET_Builder_Module;

class WPFNL_OptIN extends ET_Builder_Module {

	public $slug       = 'wpfnl_optin';
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

		$this->name = esc_html__( 'WPF Optin Form', 'wpfnl' );

		$this->icon_path        =  plugin_dir_path( __FILE__ ) . 'optin_form.svg';


		$this->settings_modal_toggles  = array(
			'general'  => array(
				'toggles' => array(
					'main_content' 		=> __( 'Form Layout', 'wpfnl' ),
					'form_field'     	=> __( 'Form Fields', 'wpfnl' ),
					'button'     		=> __( 'Button', 'wpfnl' ),
					'ac_submit'     	=> __( 'Actions After Submit', 'wpfnl' ),
				),
			),
		);
		$this->main_css_element = '%%order_class%%';

	}

	function get_advanced_fields_config() {

		$advanced_fields = array();
		$advanced_fields['background'] = array(
			'has_background_color_toggle'   => false, // default. Warning: to be deprecated
			'use_background_color'          => true, // default
			'use_background_color_gradient' => true, // default
			'use_background_image'          => true, // default
			'use_background_video'          => true, // default
		);

		$advanced_fields['fonts'] = array(
			'text'   => array(
				'label'    => __( 'Text', 'wpfnl' ),
				'toggle_slug' => 'body',
				'sub_toggle'  => 'p',
			),
		);

		$advanced_fields['fonts']['link'] = array(
			'label'    => __( 'Link', 'wpfnl' ),
			'css'      => array(
				'main' => "{$this->main_css_element} a",
			),
			'toggle_slug' => 'body',
			'sub_toggle'  => 'a',
		);

		$advanced_fields['fonts']['quote'] = array(
			'label'    => __( 'Blockquote', 'wpfnl' ),
			'css'      => array(
				'main' => "{$this->main_css_element} blockquote",
			),
			'line_height' => array(
				'default' => '1em',
			),
			'font_size' => array(
				'default' => '16px',
			),
			'toggle_slug' => 'body',
			'sub_toggle'  => 'quote',
		);

		$advanced_fields['borders'] = array(
			'default' => array(), // default
		);

		$advanced_fields['borders']['title'] = array(
			'css'             => array(
				'main' => array(
					'border_radii' => "%%order_class%% .et-demo-title",
					'border_styles' => "%%order_class%% .et-demo-title",
				)
			),
			'label_prefix'    => __( 'Title', 'wpfnl' ),
			'tab_slug'        => 'advanced',
			'toggle_slug'     => 'title',
		);

		$advanced_fields['text'] = array(
			'use_text_orientation'  => true, // default
			'css' => array(
				'text_orientation' => '%%order_class%%',
			),
		);

		$advanced_fields['max_width'] = array(
			'use_max_width'        => true, // default
			'use_module_alignment' => true, // default
		);

		$advanced_fields['margin_padding'] = false;


		$advanced_fields['button'] = array(
			'button' => array(
				'label' => __( 'Button', 'wpfnl' ),
				'css'   => array(
					'alignment'   => "%%order_class%% .btn-optin",
				),
				'margin_padding'  => array(
					'css' => array(
						'padding'    => ".et_pb_button",
						'important' => 'all',
					),
				),
			),
		);

		$advanced_fields['filters'] = array(
			'child_filters_target' => array(
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'title',
			),
		);

		$advanced_fields['text_shadow'] = array(
			'default' => array(), // default
		);

		return $advanced_fields;
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
				'label'            => esc_html__( 'Layout', 'wpfnl' ),
				'description'      => esc_html__( 'Checkout layout', 'wpfnl' ),
				'type'             => 'select',
				'options'          => array(
					''       			=> __( 'Default Style','wpfnl' ),
					'form-style1'       => __( 'Form Style-1','wpfnl' ),
					'form-style2'       => __( 'Form Style-2','wpfnl' ),
					'form-style3'   	=> __( 'Form Style-3','wpfnl' ),
					'form-style4'   	=> __( 'Form Style-4','wpfnl' ),
				),
				'priority'         => 80,
				'default'          => '',
				'default_on_front' => '',
				'toggle_slug'      => 'main_content',
				'sub_toggle'       => 'ul',
				'mobile_options'   => true,
				'computed_affects' => array(
					'__optinForm',
				),
			),
			'first_name'       => array(
				'label'            => __( 'Enable First Name', 'wpfnl' ),
				'description'      => __( 'Enable First  Name', 'wpfnl' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'off' => __( 'No','wpfnl' ),
					'on'  => __( 'Yes' ,'wpfnl'),
				),
				'default'          => 'off',
				'default_on_front' => 'off',
				'toggle_slug'      => 'form_field',
				'computed_affects' => array(
					'layout',
					'__optinForm'
				),
			),
			'last_name'       => array(
				'label'            => __( 'Enable Last Name', 'wpfnl' ),
				'description'      => __( 'Enable Last Name', 'wpfnl' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'off' => __( 'No','wpfnl' ),
					'on'  => __( 'Yes','wpfnl' ),
				),
				'default'          => 'off',
				'default_on_front' => 'off',
				'toggle_slug'      => 'form_field',
				'computed_affects' => array(
					'layout',
					'__optinForm'
				),
			),
			'phone'       => array(
				'label'            => __( 'Enable Phone', 'wpfnl' ),
				'description'      => __( 'Enable Phone', 'wpfnl' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'off' => __( 'No','wpfnl' ),
					'on'  => __( 'Yes','wpfnl' ),
				),
				'default'          => 'off',
				'default_on_front' => 'off',
				'toggle_slug'      => 'form_field',
				'computed_affects' => array(
					'layout',
					'__optinForm'
				),
			),
			'acceptance_checkbox'       => array(
				'label'            => __( 'Acceptance checkbox', 'wpfnl' ),
				'description'      => __( 'Acceptance checkbox', 'wpfnl' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'off' => __( 'No','wpfnl' ),
					'on'  => __( 'Yes','wpfnl' ),
				),
				'default'          => 'off',
				'default_on_front' => 'off',
				'toggle_slug'      => 'form_field',
				'computed_affects' => array(
					'layout',
					'__optinForm'
				),
			),
			'acceptance_checkbox_text'       => array(
				'label'            => __( 'Acceptance text', 'wpfnl' ),
				'description'      => __( 'Acceptance text', 'wpfnl' ),
				'type'             => 'text',
				'default'          => 'I have read and agree the Terms & Condition.',
				'default_on_front' => 'I have read and agree the Terms & Condition.',
				'toggle_slug'      => 'form_field',
				'computed_affects' => array(
					'layout',
					'__optinForm'
				),
				'show_if'          => array(
					'acceptance_checkbox' => 'on',
				),
			),
			'input_fields_icon'       => array(
				'label'            => __( 'Input Field Icon', 'wpfnl' ),
				'description'      => __( 'Input Field Icon', 'wpfnl' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'off' => __( 'No','wpfnl' ),
					'on'  => __( 'Yes','wpfnl' ),
				),
				'default'          => 'off',
				'default_on_front' => 'off',
				'toggle_slug'      => 'form_field',
				'computed_affects' => array(
					'layout',
					'__optinForm'
				),
			),
			'field_label'       => array(
				'label'            => __( 'Field Label', 'wpfnl' ),
				'description'      => __( 'Field Label', 'wpfnl' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'off' => __( 'No','wpfnl' ),
					'on'  => __( 'Yes','wpfnl' ),
				),
				'default'          => 'off',
				'default_on_front' => 'off',
				'toggle_slug'      => 'form_field',
				'computed_affects' => array(
					'layout',
					'__optinForm'
				),
			),
			'field_required_mark'       => array(
				'label'            => __( 'Field Required Mark', 'wpfnl' ),
				'description'      => __( 'Field Required Mark', 'wpfnl' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'off' => __( 'No','wpfnl' ),
					'on'  => __( 'Yes','wpfnl' ),
				),
				'default'          => 'off',
				'default_on_front' => 'off',
				'toggle_slug'      => 'form_field',
				'computed_affects' => array(
					'layout',
					'__optinForm'
				),
			),
			'button_text' => array(
				'label'           => __( 'Button Text', 'wpfnl' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => __( 'Input your desired button text, or leave blank for no button.', 'wpfnl' ),
				'toggle_slug'     => 'button',
				'default'         => 'Submit',
				'computed_affects' => array(
					'layout',
					'__optinForm'
				),
			),
			'admin_email'       => array(
				'label'            => __( 'Admin Email', 'wpfnl' ),
				'description'      => __( 'Admin Email', 'wpfnl' ),
				'type'             => 'text',
				'default'          => '',
				'default_on_front' => '',
				'toggle_slug'      => 'ac_submit',
				'computed_affects' => array(
					'layout',
					'__optinForm'
				),
			),
			'admin_email_subject'       => array(
				'label'            => __( 'Admin Email Subject', 'wpfnl' ),
				'description'      => __( 'Admin Email Subject', 'wpfnl' ),
				'type'             => 'text',
				'default'          => '',
				'default_on_front' => '',
				'toggle_slug'      => 'ac_submit',
				'computed_affects' => array(
					'layout',
					'__optinForm'
				),
			),
			'notification_text'       => array(
				'label'            => __( 'Notification text', 'wpfnl' ),
				'description'      => __( 'Notification text', 'wpfnl' ),
				'type'             => 'text',
				'default'          => '',
				'default_on_front' => '',
				'toggle_slug'      => 'ac_submit',
				'computed_affects' => array(
					'layout',
					'__optinForm'
				),
			),
			'other_action'             => array(
				'label'            => __( 'Other action', 'wpfnl' ),
				'description'      => __( 'Other action', 'wpfnl' ),
				'type'             => 'select',
				'options'          => array(
					'notification'    => __( 'None','wpfnl' ),
					'redirect_to'     => __( 'Redirect to url','wpfnl' ),
					'next_step'       => __( 'Next Step','wpfnl' ),
				),
				'priority'         => 80,
				'default'          => 'notification',
				'default_on_front' => 'notification',
				'toggle_slug'      => 'ac_submit',
				'sub_toggle'       => 'ul',
				'mobile_options'   => true,
				'computed_affects' => array(
					'__optinForm',
				),
			),
			'redirect_url'       => array(
				'label'            => __( 'Redirect url', 'wpfnl' ),
				'description'      => __( 'Redirect url', 'wpfnl' ),
				'type'             => 'text',
				'default'          => '',
				'default_on_front' => '',
				'toggle_slug'      => 'ac_submit',
				'computed_affects' => array(
					'layout',
					'__optinForm'
				),
				'show_if'          => array(
					'other_action' => 'redirect_to',
				),
			),
			'__optinForm'        => array(
				'type'                => 'computed',
				'computed_callback'   => array(
					'WPFunnels\Widgets\DiviModules\Modules\WPFNL_OptIN',
					'get_optin_form',
				),
				'computed_depends_on' => array(
					'layout',
					'last_name',
					'first_name',
					'phone',
					'acceptance_checkbox',
					'acceptance_checkbox_text',
					'input_fields_icon',
					'field_label',
					'field_required_mark',
					'admin_email',
					'admin_email_subject',
					'notification_text',
					'other_action',
					'redirect_url',
					'button_text',
				)
			),
		);
	}


	/**
	 * Computed checkout form
	 * @param $props
	 * @return string
	 */


	public static  function get_optin_form($props) {

		$step_id = isset($_POST['current_page']['id']) ? $_POST['current_page']['id'] : get_the_ID();

		$layout 					= $props['layout'] ;
		$first_name 				= $props['first_name'] == 'on' ? 'true' : false;
		$last_name 					= $props['last_name'] == 'on' ? 'true' : false;
		$phone 						= $props['phone'] == 'on' ? 'true' : false;
		$acceptance_checkbox 		= $props['acceptance_checkbox'] == 'on' ? 'true' : false;
		$input_field_icon			= $props['input_fields_icon'] == 'on' ? 'true' : false;
		$field_label 				= $props['field_label'] == 'on' ? 'true' : false;
		$admin_email 				= $props['admin_email'];
		$admin_email_subject 		= $props['admin_email_subject'];
		$notification_text 			= $props['notification_text'];
		$other_action 				= $props['other_action'];
		$redirect_url 				= $props['redirect_url'];

		$button_text           		= $props['button_text'];

		// Design related props are added via $this->advanced_options['button']['button']

		// Render button
		$name = new WPFNL_OptIN;
		$button = $name->render_button( array(
			'button_id'        => 'wpfunnels_optin-button',
			'button_text'      => $button_text,
			'button_classname'    => array(
				'btn-optin',
			),
		) );

		ob_start();
		?>
		<div class="wpfnl-optin-form wpfnl-shortcode-optin-form-wrapper" >
			<form method="post">
				<input type="hidden" name="post_id" value="<?php echo $step_id; ?>" />
				<input type="hidden" name="admin_email" value="<?php echo $admin_email; ?>" />
				<input type="hidden" name="admin_email_subject" value="<?php echo $admin_email_subject; ?>" />
				<input type="hidden" name="redirect_url" value="<?php echo $redirect_url; ?>" />
				<input type="hidden" name="notification_text" value="<?php echo $notification_text; ?>" />
				<input type="hidden" name="post_action" value="<?php echo $other_action; ?>" />


				<div class="wpfnl-optin-form-wrapper <?php echo $layout; ?>" >
					<?php if( 'on' == $props['first_name'] ){ ?>
						<div class="wpfnl-optin-form-group first-name">

							<?php if( 'on' == $props['field_label'] ){ ?>
								<label for="wpfnl-first-name">
									First Name
									<?php if( 'on' == $props['field_required_mark'] ){ ?>
										<span class="required-mark">*</span>
									<?php } ?>
								</label>
							<?php } ?>

							<span class="input-wrapper">
                                <?php if( 'on' == $props['input_fields_icon'] ){ ?>
									<span class="field-icon">
                                        <img src="<?php echo WPFNL_DIR_URL.'/public/assets/images/user-icon.svg'; ?>" alt="icon">
                                    </span>
								<?php } ?>
                                <input type="text" name="first_name" id="wpfnl-first-name" <?php echo 'off' == $props['field_label'] ? 'placeholder="First Name"' : ''; echo 'on' == $props['field_required_mark'] ? 'required' : ''; ?>/>
                            </span>

						</div>
					<?php } ?>

					<?php if( 'on' == $props['last_name'] ){ ?>
						<div class="wpfnl-optin-form-group last-name">

							<?php if( 'on' == $props['field_label'] ){ ?>
								<label for="wpfnl-last-name">
									Last Name
									<?php if( 'on' == $props['field_required_mark'] ){ ?>
										<span class="required-mark">*</span>
									<?php } ?>
								</label>
							<?php } ?>

							<span class="input-wrapper">
                                <?php if( 'on' == $props['input_fields_icon'] ){ ?>
									<span class="field-icon">
                                        <img src="<?php echo WPFNL_DIR_URL.'/public/assets/images/user-icon.svg'; ?>" alt="icon">
                                    </span>
								<?php } ?>
                                <input type="text" name="last_name" id="wpfnl-last-name" <?php echo 'off' == $props['field_label'] ? 'placeholder="Last Name"' : ''; echo 'on' == $props['field_required_mark'] ? 'required' : ''; ?>/>
                            </span>
						</div>
					<?php } ?>

					<div class="wpfnl-optin-form-group email">
						<?php if( 'on' == $props['field_label'] ){ ?>
							<label for="wpfnl-email">
								Email
								<?php if( 'on' == $props['field_required_mark'] ){ ?>
									<span class="required-mark">*</span>
								<?php } ?>
							</label>
						<?php } ?>
						<span class="input-wrapper">
                            <?php if( 'on' == $props['input_fields_icon'] ){ ?>
								<span class="field-icon">
                                    <img src="<?php echo WPFNL_DIR_URL.'/public/assets/images/email-open-icon.svg'; ?>" alt="icon">
                                </span>
							<?php } ?>
                            <input type="email" name="email" id="wpfnl-email" <?php echo 'off' == $props['field_label'] ? 'placeholder="Email"' : ''; echo 'on' == $props['field_required_mark'] ? 'required' : ''; ?> pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" />
                        </span>
					</div>

					<?php if( 'on' == $props['phone'] ){ ?>
						<div class="wpfnl-optin-form-group phone">

							<?php if( 'on' == $props['field_label'] ){ ?>
								<label for="wpfnl-phone">
									Phone
									<?php if( 'on' == $props['field_required_mark'] ){ ?>
										<span class="required-mark">*</span>
									<?php } ?>
								</label>
							<?php } ?>

							<span class="input-wrapper">
                                <?php if( 'on' == $props['input_fields_icon'] ){ ?>
									<span class="field-icon">
                                        <img src="<?php echo WPFNL_DIR_URL.'/public/assets/images/phone.svg'; ?>" alt="icon">
                                    </span>
								<?php } ?>
                                <input type="text" name="phone" id="wpfnl-phone" <?php echo 'off' == $props['field_label'] ? 'placeholder="Phone"' : ''; echo 'on' == $props['field_required_mark'] ? 'required' : ''; ?>/>
                            </span>
						</div>
					<?php } ?>

					<?php
					if( 'on' == $props['acceptance_checkbox'] ){
						?>
						<div class="wpfnl-optin-form-group acceptance-checkbox">
							<input type="checkbox" name="acceptance_checkbox" id="wpfnl-acceptance_checkbox" <?php echo 'on' == $props['field_required_mark'] ? 'required' : ''; ?> />
							<label for="wpfnl-acceptance_checkbox">
								<span class="check-box"></span>
								<?php
								echo $props['acceptance_checkbox_text'];

								if( 'on' == $props['field_required_mark'] ){
									echo '<span class="required-mark">*</span>';
								}
								?>
							</label>
						</div>
						<?php
					}
					?>
					<div class="wpfnl-optin-form-group submit align-">

						<?php echo $button; ?>

					</div>
				</div>
			</form>

			<div class="response"></div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render Optin form
	 * @param array $attrs
	 * @param null $content
	 * @param string $render_slug
	 * @return bool|string|null
	 */

	public function render( $attrs, $content = null, $render_slug ) {
		$output = self::get_optin_form( $this->props );
		return $output;
	}


	/**
	 * Helper method for rendering button markup which works compatible with advanced options' button
	 * @param array $args button settings.
	 *
	 * @return string rendered button HTML
	 */
	public function render_button( $args = array() ) {
		// Prepare arguments.
		$defaults = array(
			'button_id'           => '',
			'button_classname'    => array(),
			'button_custom'       => '',
			'button_rel'          => '',
			'button_text'         => '',
			'button_text_escaped' => false,
			'button_url'          => '',
			'custom_icon'         => '',
			'custom_icon_tablet'  => '',
			'custom_icon_phone'   => '',
			'display_button'      => true,
			'has_wrapper'         => true,
			'url_new_window'      => '',
			'multi_view_data'     => '',
			'button_data'         => '',
		);

		$args = wp_parse_args( $args, $defaults );

		// Do not proceed if display_button argument is false.
		if ( ! $args['display_button'] ) {
			return '';
		}

		$button_text = $args['button_text_escaped'] ? $args['button_text'] : esc_html( $args['button_text'] );

		// Do not proceed if button_text argument is empty and not having multi view value.
		if ( '' === $button_text && ! $args['multi_view_data'] ) {
			return '';
		}

		// Button classname.
		$button_classname = array( 'et_pb_button' );

		if ( ( '' !== $args['custom_icon'] || '' !== $args['custom_icon_tablet'] || '' !== $args['custom_icon_phone'] ) && 'on' === $args['button_custom'] ) {
			$button_classname[] = 'et_pb_custom_button_icon';
		}

		// Add multi view CSS hidden helper class when button text is empty on desktop mode.
		if ( '' === $button_text && $args['multi_view_data'] ) {
			$button_classname[] = 'et_multi_view_hidden';
		}

		if ( ! empty( $args['button_classname'] ) ) {
			$button_classname = array_merge( $button_classname, $args['button_classname'] );
		}

		// Custom icon data attribute.
		$use_data_icon = '' !== $args['custom_icon'] && 'on' === $args['button_custom'];
		$data_icon     = $use_data_icon ? sprintf(
			' data-icon="%1$s"',
			esc_attr( et_pb_process_font_icon( $args['custom_icon'] ) )
		) : '';

		$use_data_icon_tablet = '' !== $args['custom_icon_tablet'] && 'on' === $args['button_custom'];
		$data_icon_tablet     = $use_data_icon_tablet ? sprintf(
			' data-icon-tablet="%1$s"',
			esc_attr( et_pb_process_font_icon( $args['custom_icon_tablet'] ) )
		) : '';

		$use_data_icon_phone = '' !== $args['custom_icon_phone'] && 'on' === $args['button_custom'];
		$data_icon_phone     = $use_data_icon_phone ? sprintf(
			' data-icon-phone="%1$s"',
			esc_attr( et_pb_process_font_icon( $args['custom_icon_phone'] ) )
		) : '';
		$button_data = '' !== $args['button_data'];
		$button_data_type     = $button_data ? sprintf(
			' data-offertype="%1$s"',
			esc_attr( et_pb_process_font_icon( $args['button_data'] ) )
		) : '';


		// Render button.
		return sprintf(
			'%7$s<a%9$s class="%5$s" %13$s href="%1$s"%3$s%4$s%6$s%10$s%11$s%12$s>%2$s</a>%8$s',
			esc_url( $args['button_url'] ),
			et_core_esc_previously( $button_text ),
			( 'on' === $args['url_new_window'] ? ' target="_blank"' : '' ),
			et_core_esc_previously( $data_icon ),
			esc_attr( implode( ' ', array_unique( $button_classname ) ) ), // #5
			et_core_esc_previously( $this->get_rel_attributes( $args['button_rel'] ) ),
			$args['has_wrapper'] ? '<div class="et_pb_button_wrapper">' : '',
			$args['has_wrapper'] ? '</div>' : '',
			'' !== $args['button_id'] ? sprintf( ' id="%1$s"', esc_attr( $args['button_id'] ) ) : '',
			et_core_esc_previously( $data_icon_tablet ), // #10
			et_core_esc_previously( $data_icon_phone ),
			et_core_esc_previously( $args['multi_view_data'] ),
			$button_data_type
		);
	}


}

new WPFNL_OptIN;
