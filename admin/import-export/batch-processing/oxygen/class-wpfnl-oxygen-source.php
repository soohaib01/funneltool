<?php
namespace WPFunnels\Batch\Oxygen;

use WPFunnels\Importer\Wpfnl_Importer_Helper;

class Wpfnl_Oxygen_Source
{

    /**
     * import single template contents
     *
     * @param string $step_id
     * @return array|int|void|\WP_Error
     * @since 1.0.0
     */
    public function import_single_template($step_id)
    {
        $content = $this->get_content($step_id);
    }


    public function get_content($step_id) {
		$ct_shortcodes 	= get_post_meta( $step_id, 'ct_builder_shortcodes', true );
		$content = stripslashes( $ct_shortcodes );

	}
}
