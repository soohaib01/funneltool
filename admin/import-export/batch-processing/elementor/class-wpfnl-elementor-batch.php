<?php

namespace WPFunnels\Batch;

use WPFunnels\Batch\Elementor\Wpfnl_Elementor_Source;

class Wpfnl_Elementor_Batch extends Wpfnl_Background_Task
{
    /**
     * Image Process
     *
     * @var string
     */
    protected $action = 'wpfunnels_elementor_import_process';

    /**
     * @inheritDoc
     */
    protected function task($item)
    {
        // TODO: Implement task() method.
        $elementor_source = new Wpfnl_Elementor_Source();
        $elementor_source->import_single_template($item);
        return false;
    }
}
