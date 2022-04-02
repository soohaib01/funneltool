<?php

namespace WPFunnels\Widgets\Oxygen;

use WPFunnels\Wpfnl_functions;

/**
 * Class Optin
 * @package WPFunnels\Widgets\Oxygen
 */
class Optin extends Elements {

    function init() {
        // Do some initial things here.
    }

    function afterInit() {
        // Do things after init, like remove apply params button and remove the add button.
        $this->removeApplyParamsButton();
        // $this->removeAddButton();
    }

    function name() {
        return 'WPF Optin';
    }

    function slug() {
        return "wpfnl-optin";
    }

    function icon() {
		return	plugin_dir_url(__FILE__) . 'icon/optin_form.svg';
    }

//    function button_place() {
//        // return "interactive";
//    }

    function button_priority() {
        // return 9;
    }


    function render($options, $defaults, $content) {
		if (!Wpfnl_functions::check_if_this_is_step_type('landing')){
			echo __('Sorry, Please place the element in WPFunnels Landing page');
		}

		else{

        $step_id = isset($_GET['post_id']) ? $_GET['post_id'] : get_the_ID();
        $layout 					= $options['layout'] ;
        $first_name 				= $options['first_name'] == 'on' ? 'true' : false;
        $last_name 					= $options['last_name'] == 'on' ? 'true' : false;
        $acceptance_checkbox 		= $options['acceptance_checkbox'] == 'on' ? 'true' : false;
        $input_field_icon			= $options['input_fields_icon'] == 'on' ? 'true' : false;
        $field_label 				= $options['field_label'] == 'on' ? 'true' : false;
        $admin_email 				= $options['admin_email'];
        $admin_email_subject 		= $options['admin_email_subject'];
        $notification_text 			= $options['notification_text'];
        $other_action 				= $options['other_action'];
        $redirect_url 				= $options['redirect_url'];

        $button_text           		= $options['button_text'];
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
                    <?php if( 'on' == $options['first_name'] ){ ?>
                        <div class="wpfnl-optin-form-group first-name">

                            <?php if( 'on' == $options['field_label'] ){ ?>
                                <label for="wpfnl-first-name">
                                    First Name
                                    <?php if( 'on' == $options['field_required_mark'] ){ ?>
                                        <span class="required-mark">*</span>
                                    <?php } ?>
                                </label>
                            <?php } ?>

                            <span class="input-wrapper">
                                <?php if( 'on' == $options['input_fields_icon'] ){ ?>
                                    <span class="field-icon">
                                        <img src="<?php echo WPFNL_DIR_URL.'/public/assets/images/user-icon.svg'; ?>" alt="icon">
                                    </span>
                                <?php } ?>
                                <input type="text" name="first_name" id="wpfnl-first-name" <?php echo 'off' == $options['field_label'] ? 'placeholder="First Name"' : ''; echo 'on' == $options['field_required_mark'] ? 'required' : ''; ?>/>
                            </span>

                        </div>
                    <?php } ?>

                    <?php if( 'on' == $options['last_name'] ){ ?>
                        <div class="wpfnl-optin-form-group last-name">

                            <?php if( 'on' == $options['field_label'] ){ ?>
                                <label for="wpfnl-last-name">
                                    Last Name
                                    <?php if( 'on' == $options['field_required_mark'] ){ ?>
                                        <span class="required-mark">*</span>
                                    <?php } ?>
                                </label>
                            <?php } ?>

                            <span class="input-wrapper">
                                <?php if( 'on' == $options['input_fields_icon'] ){ ?>
                                    <span class="field-icon">
                                        <img src="<?php echo WPFNL_DIR_URL.'/public/assets/images/user-icon.svg'; ?>" alt="icon">
                                    </span>
                                <?php } ?>
                                <input type="text" name="last_name" id="wpfnl-last-name" <?php echo 'off' == $options['field_label'] ? 'placeholder="Last Name"' : ''; echo 'on' == $options['field_required_mark'] ? 'required' : ''; ?>/>
                            </span>
                        </div>
                    <?php } ?>


                    <div class="wpfnl-optin-form-group email">
                        <?php if( 'on' == $options['field_label'] ){ ?>
                            <label for="wpfnl-email">
                                Email
                                <?php if( 'on' == $options['field_required_mark'] ){ ?>
                                    <span class="required-mark">*</span>
                                <?php } ?>
                            </label>
                        <?php } ?>
                        <span class="input-wrapper">
                            <?php if( 'on' == $options['input_fields_icon'] ){ ?>
                                <span class="field-icon">
                                    <img src="<?php echo WPFNL_DIR_URL.'/public/assets/images/email-open-icon.svg'; ?>" alt="icon">
                                </span>
                            <?php } ?>
                            <input type="email" name="email" id="wpfnl-email" <?php echo 'off' == $options['field_label'] ? 'placeholder="Email"' : ''; echo 'on' == $options['field_required_mark'] ? 'required' : ''; ?> pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" />
                        </span>
                    </div>

					<?php if( 'on' == $options['phone'] ){ ?>
						<div class="wpfnl-optin-form-group phone">

							<?php if( 'on' == $options['field_label'] ){ ?>
								<label for="wpfnl-phone">
									Phone
									<?php if( 'on' == $options['field_required_mark'] ){ ?>
										<span class="required-mark">*</span>
									<?php } ?>
								</label>
							<?php } ?>

							<span class="input-wrapper">
                                <?php if( 'on' == $options['input_fields_icon'] ){ ?>
									<span class="field-icon">
                                        <img src="<?php echo WPFNL_DIR_URL.'/public/assets/images/phone.svg'; ?>" alt="icon">
                                    </span>
								<?php } ?>
                                <input type="text" name="phone" id="wpfnl-phone" <?php echo 'off' == $options['field_label'] ? 'placeholder="Phone"' : ''; echo 'on' == $options['field_required_mark'] ? 'required' : ''; ?>/>
                            </span>
						</div>
					<?php } ?>

                    <?php
                    if( 'on' == $options['acceptance_checkbox'] ){
                        ?>
                        <div class="wpfnl-optin-form-group acceptance-checkbox">
                            <input type="checkbox" name="acceptance_checkbox" id="wpfnl-acceptance_checkbox" <?php echo 'on' == $options['field_required_mark'] ? 'required' : ''; ?> />
                            <label for="wpfnl-acceptance_checkbox">
                                <span class="check-box"></span>
                                <?php
                                echo $options['acceptance_checkbox_text'];

                                if( 'on' == $options['field_required_mark'] ){
                                    echo '<span class="required-mark">*</span>';
                                }
                                ?>
                            </label>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="wpfnl-optin-form-group submit align-center">
                        <button type="submit" class="btn-optin-oxygen btn-optin">
                            <?php echo $options['button_text'] ?>
                            <span class="wpfnl-loader"></span>
                        </button>
                    </div>
                </div>
            </form>

            <div class="response"></div>
        </div>
        <?php
		}

    }
    function controls() {
        // Layout
        $layout = $this->addControlSection("optin_layout", __("Layout","wpfnl"), "assets/icon.png", $this);
        $layout->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => __("Layout","wpfnl"),
                "slug" => 'layout',
                "default" => ""
            )
        )->setValue(array(
            ''       			=> __( 'Default Style',"wpfnl" ),
            'form-style1'       => __( 'Form Style-1',"wpfnl" ),
            'form-style2'       => __( 'Form Style-2',"wpfnl" ),
            'form-style3'   	=> __( 'Form Style-3',"wpfnl" ),
            'form-style4'   	=> __( 'Form Style-4',"wpfnl" ),
        ))->rebuildElementOnChange();

        $form_field = $this->addControlSection("optin_form_field", __("Form Fields","wpfnl"), "assets/icon.png", $this);

        //first name
        $form_field->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => __("Enable First  Name","wpfnl"),
                "slug" => 'first_name',
                "default" => "off"
            )
        )->setValue(array(
            'off'       => __('No',"wpfnl" ),
            'on'       => __('Yes' ,"wpfnl"),
        ))->rebuildElementOnChange();

        //Last Name
        $form_field->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => __("Enable Last Name","wpfnl"),
                "slug" => 'last_name',
                "default" => "off"
            )
        )->setValue(array(
            'off'       => __('No',"wpfnl" ),
            'on'       => __('Yes',"wpfnl" ),
        ))->rebuildElementOnChange();
        //Last Name
        $form_field->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => __("Enable Phone","wpfnl"),
                "slug" => 'phone',
                "default" => "off"
            )
        )->setValue(array(
            'off'       => __('No',"wpfnl" ),
            'on'       => __('Yes',"wpfnl" ),
        ))->rebuildElementOnChange();

        //Acceptance
        $form_field->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => __("Acceptance checkbox","wpfnl"),
                "slug" => 'acceptance_checkbox',
                "default" => "off"
            )
        )->setValue(array(
            'off'       => __('No',"wpfnl" ),
            'on'       => __('Yes',"wpfnl" ),
        ))->rebuildElementOnChange();

        //Acceptance Text
        $form_field->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __("Acceptance text","wpfnl"),
                "slug" => 'acceptance_checkbox_text',
                "default" => __("I have read and agree the Terms & Condition.","wpfnl"),
                "condition" => 'acceptance_checkbox=on',
            )
        )->rebuildElementOnChange();



        //Input field Icon
        $form_field->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => __("Input Field Icon","wpfnl"),
                "slug" => 'input_fields_icon',
                "default" => "off"
            )
        )->setValue(array(
            'off'       => __('No',"wpfnl" ),
            'on'       => __('Yes' ,"wpfnl"),
        ))->rebuildElementOnChange();

        //Input field Label
        $form_field->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => __("Field Label","wpfnl"),
                "slug" => 'field_label',
                "default" => "off"
            )
        )->setValue(array(
            'off'       => __('No',"wpfnl" ),
            'on'       => __('Yes',"wpfnl" ),
        ))->rebuildElementOnChange();

        //Input field Require Mark
        $form_field->addOptionControl(
            array(
                "type" => 'dropdown',
                "name" => __("Field Required Mark","wpfnl"),
                "slug" => 'field_required_mark',
                "default" => "off"
            )
        )->setValue(array(
            'off'       => __('No',"wpfnl" ),
            'on'       => __('Yes',"wpfnl" ),
        ))->rebuildElementOnChange();

        //Button Text
        $button = $this->addControlSection("optin_form_button", __("Form Button","wpfnl"), "assets/icon.png", $this);
        $button_selector = '.wpfnl-optin-form-group .btn-optin-oxygen';

        $button->addOptionControl(
            array(
                "type" => 'textfield',
                "name" => __("Button Text","wpfnl"),
                "slug" => 'button_text',
                "default" => "Submit"
            )
        )->rebuildElementOnChange();

		$button->typographySection(
			__("Typography","wpfnl"),
			$button_selector."",
			$this
		);
		$button->borderSection(
			__("Button Border","wpfnl"),
			$button_selector."",
			$this
		);
		$button->addPreset(
			"padding",
			"menu_item_padding",
			__("Button Padding","wpfnl"),
			$button_selector
		)->whiteList();

		$button->addPreset(
			"margin",
			"menu_item_margin",
			__("Button Margin","wpfnl"),
			$button_selector
		)->whiteList();

		$button->addStyleControls(
			array(
				array(
					"name" => __('Background Color',"wpfnl"),
					"selector" => $button_selector."",
					"property" => 'background-color',
				),
				array(
					"name" => __('Background Hover Color',"wpfnl"),
					"selector" => $button_selector.":hover",
					"property" => 'background-color',
				),

			)
		);

		//Form style
		$form_style = $this->addControlSection("optin_form_style", __("Form Style","wpfnl"), "assets/icon.png", $this);
		$form_selector = '.wpfnl-optin-form form';

		$form_style->typographySection(
			__("Typography","wpfnl"),
			".wpfnl-optin-form .wpfnl-optin-form-group > label",
			$this
		);
		$form_style->addStyleControls(
			array(
				array(
					"name" => __('Checkbox Size',"wpfnl"),
					"selector" => ".wpfnl-optin-form .wpfnl-optin-form-group.acceptance-checkbox > label .check-box",
					"property" => 'width|height',
					"control_type" => 'slider-measurebox',
					"unit" => 'px'
				),

				array(
					"name" => __('Checkbox Spacing',"wpfnl"),
					"selector" => ".wpfnl-optin-form .wpfnl-optin-form-group.acceptance-checkbox > label",
					"property" => 'padding-left',
					"control_type" => 'slider-measurebox',
					"unit" => 'px'
				),
				array(
					"name" => __('Row Spacing',"wpfnl"),
					"selector" => ".wpfnl-optin-form .wpfnl-optin-form-group:not(:last-child)",
					"property" => 'margin-bottom',
					"control_type" => 'slider-measurebox',
					"unit" => 'px'
				),
			)
		);

		$input_style = $this->addControlSection("optin_form_input_style", __("Form Input Style","wpfnl"), "assets/icon.png", $this);

		$input_style->typographySection(
			__("Typography","wpfnl"),
			".wpfnl-optin-form .wpfnl-optin-form-group input",
			$this
		);
		$input_style->borderSection(
			__("Button Border","wpfnl"),
			".wpfnl-optin-form .wpfnl-optin-form-group input",
			$this
		);
		$input_style->addStyleControls(
			array(
				array(
					"name" => __('Background Color',"wpfnl"),
					"selector" => ".wpfnl-optin-form .wpfnl-optin-form-group input",
					"property" => 'background-color',
				),

			)
		);




		//Admin Email
		$ac_submit = $this->addControlSection("optin_after_submit", __("Action After Submission","wpfnl"), "assets/icon.png", $this);
		$admin_email = get_option( 'admin_email' );
		$ac_submit->addOptionControl(
			array(
				"type" => 'textfield',
				"name" => __("Admin Email","wpfnl"),
				"slug" => 'admin_email',
				"default" => $admin_email
			)
		)->rebuildElementOnChange();

		//Admin Email Subject
		$ac_submit->addOptionControl(
			array(
				"type" => 'textfield',
				"name" => __("Admin Email Subject","wpfnl"),
				"slug" => 'admin_email_subject',
				"default" => ""
			)
		)->rebuildElementOnChange();

		//Notification Text
		$ac_submit->addOptionControl(
			array(
				"type" => 'textfield',
				"name" => __("Notification text","wpfnl"),
				"slug" => 'notification_text',
				"default" => ""
			)
		)->rebuildElementOnChange();

		// Other action
		$ac_submit->addOptionControl(
			array(
				"type" => 'dropdown',
				"name" => __("Other action","wpfnl"),
				"slug" => 'other_action',
				"default" => "notification"
			)
		)->setValue(array(
			'notification'    => __( 'None' ),
			'redirect_to'     => __( 'Redirect to url',"wpfnl" ),
			'next_step'       => __( 'Next Step' ),
		))->rebuildElementOnChange();

		//Redirect URL
		$ac_submit->addOptionControl(
			array(
				"type" => 'textfield',
				"name" => __("Redirect url","wpfnl"),
				"slug" => 'redirect_url',
				"default" => "",
				"condition" => 'other_action=redirect_to',
			)
		)->rebuildElementOnChange();

    }

    function defaultCSS() {

    }

}
