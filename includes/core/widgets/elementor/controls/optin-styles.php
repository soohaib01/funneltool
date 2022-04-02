<?php
namespace WPFunnels\Widgets\Elementor\Controls;

use Composer\Installers\Plugin;
use Elementor\Controls_Stack;

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor select2 control.
 *
 * A base control for creating select2 control. Displays a select box control
 * based on select2 jQuery plugin @see https://select2.github.io/ .
 * It accepts an array in which the `key` is the value and the `value` is the
 * option name. Set `multiple` to `true` to allow multiple value selection.
 *
 * @since 1.0.0
 */
class Optin_Styles extends \Elementor\Base_Data_Control
{

	/**
	 * Get select2 control type.
	 *
	 * Retrieve the control type, in this case `select2`.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type()
	{
		return 'optin_styles';
	}

	/**
	 * Get select2 control default settings.
	 *
	 * Retrieve the default settings of the select2 control. Used to return the
	 * default settings while initializing the select2 control.
	 *
	 * @since 1.8.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings()
	{
		return [];
	}


	/**
	 * Enqueue emoji one area control scripts and styles.
	 *
	 * Used to register and enqueue custom scripts and styles used by the emoji one
	 * area control.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue()
	{
		wp_register_script('optin-style-control', WPFNL_URL.'includes/core/widgets/elementor/controls/assets/js/optin-styles.js', [ 'jquery' ], WPFNL_VERSION);
		wp_enqueue_script('optin-style-control');
	}


	private function get_styles() {
		return array(
			'form-style1' => array(
				'label'	=> 'Style 1',
				'image'	=> WPFNL_URL.'/includes/core/widgets/elementor/assets/images/styles/style-1.png'
			),
			'form-style2' => array(
				'label'	=> 'Style 2',
				'image'	=> WPFNL_URL.'/includes/core/widgets/elementor/assets/images/styles/style-2.png'
			),
		);
	}


	/**
	 * Render select2 control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function content_template()
	{
		$styles = $this->get_styles();

		?>

		<#  console.log('Event', data);  #>
		<div class="elementor-control-structure-presets">
			<?php foreach ($styles as $key => $style){ ?>
				<div class="single-style">
					<input id="<?php echo $key; ?>" type="radio" name="elementor-control-structure-preset-{{ data._cid }}" data-setting="structure" value="<?php echo $key; ?>">
					<label for="<?php echo $key; ?>" class="elementor-control-structure-preset">
						<img src="<?php echo $style['image']; ?>"/>
					</label>
					<div class="elementor-control-structure-preset-title"></div>
				</div>
			<?php } ?>
		</div>
	<?php }
}
