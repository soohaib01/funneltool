<?php

namespace WPFunnels\Rest\Controllers;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use WPFunnels\Wpfnl_functions;

class RemoteFunnelsController extends Wpfnl_REST_Controller {

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
    protected $rest_base = 'remote_funnel/';


    public function update_items_permissions_check( $request ) {
        if ( ! Wpfnl_functions::wpfnl_rest_check_manager_permissions( 'products' ) ) {
            return new WP_Error( 'wpfunnels_rest_cannot_edit', __( 'Sorry, you cannot edit this resource.', 'wpfnl' ), array( 'status' => rest_authorization_required_code() ) );
        }
        return true;
    }

    /**
     * Makes sure the current user has access to READ the settings APIs.
     *
     * @since  3.0.0
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|boolean
     */
    public function get_items_permissions_check( $request ) {
        if ( ! Wpfnl_functions::wpfnl_rest_check_manager_permissions( 'products' ) ) {
            return new WP_Error( 'wpfunnels_rest_cannot_view', __( 'Sorry, you cannot list resources.', 'wpfnl' ), array( 'status' => rest_authorization_required_code() ) );
        }
        return true;
    }



    public function register_routes()
    {
        register_rest_route(
            $this->namespace, '/' . $this->rest_base . 'get_template_properties'. '/(?P<funnel_id>\d+)' , array(
                array(
                    'methods'               => WP_REST_Server::READABLE,
                    'callback'              => array( $this, 'get_template_properties' ),
                    'permission_callback'   => array( $this, 'get_items_permissions_check' ),
                ),
            )
        );

        register_rest_route(
            $this->namespace, '/' . $this->rest_base . 'get_step_properties'. '/(?P<step_id>\d+)' , array(
                array(
                    'methods'               => WP_REST_Server::READABLE,
                    'callback'              => array( $this, 'get_step_properties' ),
                    'permission_callback'   => array( $this, 'get_items_permissions_check' ),
                ),
            )
        );
    }


    public function get_template_properties($request) {
        $funnel_id  = $request['funnel_id'];
        $industries = $this->get_properties(WPFNL_TAXONOMY_TEMPLATES_INDUSTRIES);
        $builders   = $this->get_properties(WPFNL_TAXONOMY_TEMPLATES_BUILDER);

        $template_industry = wp_get_post_terms($funnel_id, WPFNL_TAXONOMY_TEMPLATES_INDUSTRIES, [ 'fields' => 'ids' ]);
        $template_builder = wp_get_post_terms($funnel_id, WPFNL_TAXONOMY_TEMPLATES_BUILDER, [ 'fields' => 'ids' ]);
        $template_type = get_post_meta($funnel_id, '_template_type', true);

        $thumbnail_id = get_post_meta($funnel_id, '_thumbnail_id', true);
        $featured_image_src = '';
        if($thumbnail_id) {
            $featured_image = wp_get_attachment_image_src($thumbnail_id);
            if($featured_image) {
                $featured_image_src = $featured_image[0];
            }
        } else {
            $thumbnail_id = '';
        }


        if ($template_industry && !is_wp_error($template_industry)) {
            $template_industry = $template_industry[0];
        } else {
            $template_industry = '';
        }

        if ($template_builder && !is_wp_error($template_builder)) {
            $template_builder = $template_builder[0];
        }else {
            $template_builder = '';
        }

        $response['success'] = true;
        $response['industries'] = $industries;
        $response['builders'] = $builders;
        $response['industry'] = $template_industry;
        $response['builder'] = $template_builder;
        $response['type'] = $template_type ? $template_type : 'free';
        $response['featuredImage'] = $featured_image_src;
        $response['thumbnailID'] = $thumbnail_id;
        return $this->prepare_item_for_response( $response, $request );
    }


    /**
     * Get properties of a single
     * funnel
     *
     * @since  2.0.0
     */
    public function get_properties( $taxonomy ) {
        $categories = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ]);
        if (!empty($categories) && !is_wp_error($categories)) {
            return $categories;
        }
        return [];
    }



    public function get_step_properties($request) {
        $step_id  = $request['step_id'];
        $thumbnail_id = get_post_meta($step_id, '_thumbnail_id', true);
        $custom_url   = get_post_meta($step_id, '_step_custom_url', true);
        $featured_image_src = '';
        if($thumbnail_id) {
            $featured_image = wp_get_attachment_image_src($thumbnail_id);
            if($featured_image) {
                $featured_image_src = $featured_image[0];
            }
        } else {
            $thumbnail_id = '';
        }
        $response['success'] = true;
        $response['featuredImage'] = $featured_image_src;
        $response['thumbnailID'] = $thumbnail_id;
        $response['customUrl'] = $custom_url;
        return $this->prepare_item_for_response( $response, $request );
    }


    /**
     * Prepare a single setting object for response.
     *
     * @since  1.0.0
     * @param object          $item Setting object.
     * @param WP_REST_Request $request Request object.
     * @return WP_REST_Response $response Response data.
     */
    public function prepare_item_for_response( $item, $request ) {
        $data     = $this->add_additional_fields_to_object( $item, $request );
        $response = rest_ensure_response( $data );
        return $response;
    }
}
