<?php
namespace WPFunnels\Batch\Divi;


use WPFunnels\Importer\Wpfnl_Importer_Helper;

class Wpfnl_Divi_Source
{

    /**
     *
     * @param string $step_id
     * @return array|int|void|\WP_Error
     *
     * @since 1.0.0
     */
    public function import_single_template($step_id)
    {
		add_filter( 'wp_kses_allowed_html', array( $this, 'allowed_tags_and_attributes' ), 10, 2 );
		$content = get_post_meta( $step_id, 'divi_content', true );
		if( $content ) {
			$content = Wpfnl_Importer_Helper::get_instance()->get_post_contents( $step_id );
			wp_update_post(
				array(
					'ID'           => $step_id,
					'post_content' => $content,
				)
			);

			// Delete temporary meta key.
			delete_post_meta( $step_id, 'divi_content' );
		}
    }
}
