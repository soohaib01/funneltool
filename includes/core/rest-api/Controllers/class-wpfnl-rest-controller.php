<?php
namespace WPFunnels\Rest\Controllers;

use WP_REST_Controller;

abstract class Wpfnl_REST_Controller extends WP_REST_Controller {
    /**
     * Endpoint namespace.
     *
     * @var string
     */
    protected $namespace = 'wpfunnels/v1';

    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = '';


    /**
     * Prepare links for the request.
     *
     * @since  3.0.0
     * @param string $setting_id Setting ID.
     * @param string $group_id Group ID.
     * @return array Links for the given setting.
     */
    protected function prepare_links( $setting_id ) {
        $base  = str_replace( '(?P<settings_id>[\w-]+)', $setting_id, $this->rest_base );
        $links = array(
            'self'       => array(
                'href' => get_rest_url( sprintf( '/%s/%s/%s', $this->namespace, $base, $setting_id ) ),
            ),
            'collection' => array(
                'href' => get_rest_url( sprintf( '/%s/%s', $this->namespace, $base ) ),
            ),
        );
        return $links;
    }
}
