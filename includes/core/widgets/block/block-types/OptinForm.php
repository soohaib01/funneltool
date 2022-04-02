<?php

/**
 * Optin form class
 */

namespace WPFunnels\Widgets\Gutenberg\BlockTypes;

/**
 * Class OptinForm
 * @package WPFunnels\Widgets\Gutenberg\BlockTypes
 */
class OptinForm extends AbstractBlock {

	/**
	 * Block name.
	 *
	 * @var string
	 */
	protected $block_name = 'optin-form';


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
			'handle'       => 'wpfnl-optin-form-frontend',
			'path'         => $this->get_block_asset_build_path( 'optin-form-frontend' ),
			'dependencies' => [],
		];
		return $key ? $script[ $key ] : $script;
	}
}
