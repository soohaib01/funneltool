<?php
namespace WPFunnels\Admin\Module\Steps;

class Wpfnl_Steps_Factory
{
    protected $id;

    protected $step;

    public static function build($module)
    {
        $class_name = "WPFunnels\\Modules\Admin\\Steps\\".ucfirst($module).'\Module';
        if (!class_exists(ucfirst($class_name))) {
            throw new \Exception('Invalid Steps Module.');
        } else {
            return new $class_name();
        }
    }
}
