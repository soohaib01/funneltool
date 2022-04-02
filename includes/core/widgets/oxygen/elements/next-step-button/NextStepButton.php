<?php

namespace WPFunnels\Widgets\Oxygen;

use WPFunnels\Wpfnl_functions;

/**
 * Class NextStepButton
 * @package WPFunnels\Widgets\Oxygen
 */
class NextStepButton extends Elements {

    function init() {
        // Do some initial things here.
    }

    function afterInit() {
        // Do things after init, like remove apply params button and remove the add button.
        $this->removeApplyParamsButton();
        // $this->removeAddButton();
    }

    function name() {
        return 'Next Step Button';
    }

    function slug() {
        return "next-step-button";
    }

    function icon() {
		return	plugin_dir_url(__FILE__) . 'icon/next_steps.svg';
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
		}else{
			if( $this->is_builder_mode() ) {
				$id = '';
			} else {
				$id = 'wpfunnels_next_step_controller';
			}

			?>
			<div class="">
				<a href="" class="btn-default wpfnl-oxy-next-step-btn" id="<?php echo $id; ?>"> <?= $options['title_text'] ?> </a>
			</div>
			<?php
		}

    }
    function controls() {
			$this->addOptionControl(
				array(
					"type" => "textfield",
					"name" => __("Button Text",'wpfnl'),
					"slug" => "title_text",
					"default" => "Buy Now"
				)
			)->rebuildElementOnChange();
		$button = $this->addControlSection("button_style", __("Button Style",'wpfnl'), "assets/icon.png", $this);

		$icon_selector = '.wpfnl-oxy-next-step-btn';

		$button->addPreset(
			"padding",
			"menu_item_padding",
			__("Button Padding"),
			$icon_selector
		)->whiteList();

		$button->addPreset(
			"margin",
			"menu_item_margin",
			__("Button Margin"),
			$icon_selector
		)->whiteList();

		$button->addStyleControls(
			array(
				array(
					"name" => __('Background Color','wpfnl'),
					"selector" => $icon_selector."",
					"property" => 'background-color',
				),
				array(
					"name" => __('Background Hover Color','wpfnl'),
					"selector" => $icon_selector.":hover",
					"property" => 'background-color',
				),
				array(
					"name" => __('Hover Text Color','wpfnl'),
					"selector" => $icon_selector.":hover",
					"property" => 'color',
				),

			)
		);

		$button->borderSection(
			__("Button Border",'wpfnl'),
			$icon_selector."",
			$this
		);
		$button->typographySection(
			__("Typography",'wpfnl'),
			".wpfnl-oxy-next-step-btn",
			$this
		);


	}

    function defaultCSS() {

    }

}
