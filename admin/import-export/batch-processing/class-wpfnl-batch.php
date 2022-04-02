<?php

namespace WPFunnels\Batch\Elementor;

use WPFunnels\Batch\Divi\Wpfnl_Divi_Source;
use WPFunnels\Batch\Gutenberg\Wpfnl_Gutenberg_Batch;
use WPFunnels\Batch\Gutenberg\Wpfnl_Gutenberg_Source;
use WPFunnels\Batch\Oxygen\Wpfnl_Oxygen_Batch;
use WPFunnels\Batch\Wpfnl_Divi_Batch;
use WPFunnels\Batch\Wpfnl_Elementor_Batch;
use WPFunnels\Traits\SingletonTrait;
use WPFunnels\Wpfnl_functions;

/**
 * Class Wpfnl_Batch
 * @package WPFunnels\Batch\Elementor
 */
class Wpfnl_Batch
{
	use SingletonTrait;

	/**
	 * elementor batch instance
	 *
	 * @var Wpfnl_Elementor_Batch
	 */
    protected $elementor_batch;

	/**
	 * elementor source object
	 *
	 * @var Wpfnl_Elementor_Source
	 */
    protected $elementor_source;

	/**
	 * gutenberg batch instance
	 *
	 * @var Wpfnl_Gutenberg_Batch
	 */
    protected $gutenberg_batch;

	/**
	 * gutenberg source object
	 *
	 * @var Wpfnl_Gutenberg_Source
	 */
    protected $gutenberg_source;

	/**
	 * divi batch instance
	 *
	 * @var Wpfnl_Divi_Batch
	 */
	protected $divi_batch;

	/**
	 * divi source object
	 *
	 * @var Wpfnl_Divi_Source
	 */
	protected $divi_source;


	/**
	 * @var Oxygen source
	 */
	protected $oxygen_source;

    public function __construct()
    {
        $page_builder = Wpfnl_functions::get_builder_type();

        if ( 'elementor' === $page_builder && (class_exists('\Elementor\Plugin') || Wpfnl_functions::is_plugin_activated('elementor/elementor.php')) ) {
            $this->elementor_batch 	= new Wpfnl_Elementor_Batch();
//            $this->elementor_source = new Wpfnl_Elementor_Source();
        }

        if ( 'gutenberg' === $page_builder ) {
            $this->gutenberg_batch 	= new Wpfnl_Gutenberg_Batch();
//            $this->gutenberg_source = new Wpfnl_Gutenberg_Source();
        }

		if ( 'divi-builder' === $page_builder ) {
			$this->divi_batch 	= new Wpfnl_Divi_Batch();
//			$this->divi_source 	= new Wpfnl_Divi_Source();
		}

//		if ( 'oxygen' === $page_builder ) {
//			$this->oxygen_source 	= new Wpfnl_Oxygen_Batch();
//		}

		// start content importing after step is imported
		add_action( 'wpfunnels_after_step_import', [$this, 'start_processing'], 10, 2 );
    }

    /**
     * Start the batch import process
     *
     * @param int $step_id
     * @param string $builder
     * @since 1.0.0
     */
    public function start_processing($step_id = 0, $builder = 'elementor')
    {
    	if ($builder === 'elementor' && class_exists('\Elementor\Plugin')) {
            $this->elementor_batch->push_to_queue($step_id);
            $this->elementor_batch->save()->dispatch();
        }

        if ($builder === 'gutenberg') {
            $this->gutenberg_batch->push_to_queue($step_id);
            $this->gutenberg_batch->save()->dispatch();
        }

		if ($builder === 'divi-builder') {
			$this->divi_batch->push_to_queue($step_id);
			$this->divi_batch->save()->dispatch();
		}
    }
}
