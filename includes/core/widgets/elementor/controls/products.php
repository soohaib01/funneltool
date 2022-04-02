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
class Product_Control extends \Elementor\Base_Data_Control
{
    const ProductSelector = 'product_selector';


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
        return self::ProductSelector;
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
        return [
            'options' => [],
            'multiple' => false,
            'select2options' => [
                'minimumInputLength' => 3,
                'allowClear' => true,
                'maximumSelectionLength' => 1,
                'ajax' => [
                    'url'=>         '/hello',
                ]
            ],
        ];
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
        // Scripts
        wp_register_script('product-selector-control', WPFNL_URL.'includes/core/widgets/elementor/controls/assets/js/product-selector.js', [ 'jquery-elementor-select2', 'jquery' ], WPFNL_VERSION);
        wp_enqueue_script('product-selector-control');
        wp_localize_script('product-selector-control', 'WPFunnelVars', [
            'ajaxurl'   => admin_url('admin-ajax.php'),
            'security'  => wp_create_nonce('wpfnl-admin'),
        ]);
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
        $control_uid = $this->get_control_uid();
        $product = '';
        $product_obj = null;
        $product = get_post_meta(get_the_ID(), 'elementor_product_selector', true);
        $formatted_name = '';
        if(!empty($product)) {
            $product_obj = wc_get_product( $product );
            if($product_obj) $formatted_name = $product_obj->get_formatted_name();
        }

        ?>
        <div class="elementor-control-field">
            <# if ( data.label ) {#>
                <label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <# } #>
            <div class="elementor-control-input-wrapper elementor-control-unit-5">
                <# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
                <select id="<?php echo $control_uid; ?>" class="elementor-select2" type="select2" {{ multiple }} data-setting="{{ data.name }}">
                    <?php if($product_obj) { ?>
                        <option value="<?php echo $product; ?>" selected="selected"><?php echo $formatted_name; ?></option>
                    <?php }?>
                </select>
            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }
}
