<?php
namespace WPFunnels\Widgets\Gutenberg\BlockTypes;


/**
 * NextStep button class.
 */
class NextStepButton extends AbstractBlock {

    protected $defaults = array(
        'outline'   => 'fill',
        'buttonColor'   => 'red',
        'buttonRadius'   => 5,
        'paddingTopBottom'   => 14,
        'paddingLeftRight'   => 25,
        'buttonFontSize'   => '18',
        'borderStyle'   => 'solid',
        'borderWidth'   => 1,
        'borderColor'   => '#39414d',
    );

    /**
     * Block name.
     *
     * @var string
     */
    protected $block_name = 'next-step-button';


    /**
     * Render the Featured Product block.
     *
     * @param array  $attributes Block attributes.
     * @param string $content    Block content.
     * @return string Rendered block type output.
     */
    protected function render( $attributes, $content ) {
        $attributes = wp_parse_args( $attributes, $this->defaults );
        $dynamic_css = $this->generate_assets($attributes);
        $new_content = "<style>$dynamic_css</style>".$content;
        return $this->inject_html_data_attributes( $new_content, $attributes );
    }


    /**
     * get generated dynamic styles from $attributes
     *
     * @param $attributes
     * @param $post
     * @return array|string
     */
    protected function get_generated_dynamic_styles( $attributes, $post ) {
        $selectors = array(
            '.wpfunnels-landing-block' => array(
                'background-color' => $attributes['buttonColor'],
                'border-radius' => $attributes['buttonRadius'],
                'padding-top' => $attributes['paddingTopBottom'],
                'padding-bottom' => $attributes['paddingTopBottom'],
                'padding-left' => $attributes['paddingLeftRight'],
                'padding-right' => $attributes['paddingLeftRight'],
                'font-size' => $attributes['buttonFontSize'],
                'border-style' => $attributes['borderStyle'],
                'border-width' => $attributes['borderWidth'],
                'border-color' => $attributes['borderColor'],
            ),
        );
        return $this->generate_css($selectors);
    }


    /**
     * Get the styles for the wrapper element (background image, color).
     *
     * @param array       $attributes Block attributes. Default empty array.
     * @return string
     */
    public function get_styles( $attributes ) {
        $style      = '';
        return $style;
    }


    /**
     * Get class names for the block container.
     *
     * @param array $attributes Block attributes. Default empty array.
     * @return string
     */
    public function get_classes( $attributes ) {
        $classes = array( 'wpfnl-block-' . $this->block_name );
        return implode( ' ', $classes );
    }


    /**
     * Extra data passed through from server to client for block.
     *
     * @param array $attributes  Any attributes that currently are available from the block.
     *                           Note, this will be empty in the editor context when the block is
     *                           not in the post content on editor load.
     */
    protected function enqueue_data( array $attributes = [] ) {
        parent::enqueue_data( $attributes );
    }


    /**
     * Get the frontend script handle for this block type.
     *
     * @see $this->register_block_type()
     * @param string $key Data to get, or default to everything.
     * @return array|string
     */
    protected function get_block_type_script( $key = null ) {
        $script = [
            'handle'       => 'wpfnl-next-step-button-frontend',
            'path'         => $this->get_block_asset_build_path( 'next-step-button-frontend' ),
            'dependencies' => [],
        ];
        return $key ? $script[ $key ] : $script;
    }
}
