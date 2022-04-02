<?php

namespace WPFunnels\Traits;

trait SingletonTrait {

    private static $instance;

    protected function __construct() {}

    /**
     * Get class instance.
     *
     * @return object Instance.
     */
    public static function getInstance() {
        if (!self::$instance) {
            // new self() will refer to the class that uses the trait
            self::$instance = new self();
        }

        return self::$instance;
    }

	/**
	 * Prevent cloning.
	 */
	public function __clone() {}


	public function __wakeup() {
        wc_doing_it_wrong( __FUNCTION__, __( 'Unserializing instances of this class is forbidden.', 'wpfnl' ), '4.6' );
        die();
    }
}
