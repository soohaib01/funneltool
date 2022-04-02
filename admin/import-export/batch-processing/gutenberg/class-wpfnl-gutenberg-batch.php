<?php

namespace WPFunnels\Batch\Gutenberg;

use WPFunnels\Batch\Wpfnl_Background_Task;

class Wpfnl_Gutenberg_Batch extends Wpfnl_Background_Task
{

    /**
     *
     * @var string
     */
    protected $action = 'wpfunnels_gutenberg_import_process';

    /**
     * @inheritDoc
     */
    protected function task($item)
    {
        // TODO: Implement task() method.
        if (class_exists('Wpfnl_Gutenberg_Source')) {
          $gutenberg_source = new Wpfnl_Gutenberg_Source();
          $gutenberg_source->import_single_template($item);
        }

        return false;
    }
}
