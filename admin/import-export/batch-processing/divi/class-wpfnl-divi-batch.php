<?php

namespace WPFunnels\Batch;



use WPFunnels\Batch\Divi\Wpfnl_Divi_Source;

class Wpfnl_Divi_Batch extends Wpfnl_Background_Task
{
    /**
     * Image Process
     *
     * @var string
     */
    protected $action = 'wpfunnels_divi_import_process';

    /**
     * @inheritDoc
     */
    protected function task($item)
    {
        // TODO: Implement task() method.
        $elementor_source = new Wpfnl_Divi_Source();
        $elementor_source->import_single_template($item);
        return false;
    }
}
