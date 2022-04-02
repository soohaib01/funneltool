<?php
namespace WPFunnels\Batch\Elementor;

use Elementor\TemplateLibrary\Source_Local;

class Wpfnl_Elementor_Source extends Source_Local
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
        $_elementor_data = get_post_meta($step_id, '_elementor_data', true);
        $content = '';
        if($_elementor_data) {
            if (is_array($_elementor_data)) {
                $content = $_elementor_data;
            } else {
                $_elementor_data = add_magic_quotes($_elementor_data);
                $content      = json_decode($_elementor_data, true);
            }
        }

        if (is_array($content)) {
            $content = $this->process_export_import_content($content, 'on_import');
            update_metadata('post', $step_id, '_elementor_data', $content);
            $this->clear_cache();
        }
    }


    public function clear_cache()
    {
        // Clear 'Elementor' file cache.
        if (class_exists('\Elementor\Plugin')) {
            \Elementor\Plugin::$instance->files_manager->clear_cache();
        }
    }
}
