<?php
namespace WPFunnels\Batch\Gutenberg;

use WPFunnels\Importer\Wpfnl_Importer_Helper;

class Wpfnl_Gutenberg_Source
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
        $content = Wpfnl_Importer_Helper::get_instance()->get_post_contents($step_id);
        wp_update_post(
            array(
                'ID'           => $step_id,
                'post_content' => $content,
            )
        );
    }
}
