<?php

namespace WPFunnels\Rest\Controllers;

use GuzzleHttp\Psr7\Request;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use WPFunnels\Wpfnl_functions;
use WPFunnels\Wpfnl;
use Elementor\Core\Kits\Manager;
class SettingsController extends Wpfnl_REST_Controller {

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
    protected $rest_base = 'settings/(?P<group_id>[\w-]+)';


    protected $rest_plugin_active_base = 'settings/activate_wc_plugins';

    public function update_items_permissions_check( $request ) {
        $permission = current_user_can('manage_options');
        if ( ! Wpfnl_functions::wpfnl_rest_check_manager_permissions( 'settings' ) ) {
            return new WP_Error( 'woocommerce_rest_cannot_edit', __( 'Sorry, you cannot edit this resource.', 'wpfnl' ), array( 'status' => rest_authorization_required_code() ) );
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
        if ( ! Wpfnl_functions::wpfnl_rest_check_manager_permissions( 'settings' ) ) {
            return new WP_Error( 'wpfunnels_rest_cannot_view', __( 'Sorry, you cannot list resources.', 'wpfnl' ), array( 'status' => rest_authorization_required_code() ) );
        }
        return true;
    }

    public function register_routes()
    {
        register_rest_route(
            $this->namespace, '/' . $this->rest_base . '/(?P<settings_id>[\w-]+)', array(
                'args'   => array(
                    'settings_id' => array(
                        'description'       => __( 'Settings group ID.', 'wpfnl' ),
                        'type'              => 'string',
                    )
                ),
                array(
                    'methods'               => WP_REST_Server::READABLE,
                    'callback'              => array( $this, 'get_item' ),
                    'permission_callback'   => array( $this, 'get_items_permissions_check' ),
                ),
                array(
                    'methods'               => WP_REST_Server::EDITABLE,
                    'callback'              => array( $this, 'update_settings' ),
                    'permission_callback' => array( $this, 'update_items_permissions_check' ),
                    'args'                  => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
                )
            )
        );

        register_rest_route(
            $this->namespace, '/' . $this->rest_plugin_active_base , array(
                array(
                    'methods'               => WP_REST_Server::EDITABLE,
                    'callback'              => array( $this, 'activate_wc_plugins' ),
                    'permission_callback'   => array( $this, 'update_items_permissions_check' ),
                    'args'                  => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
                )
            )
        );
    }


    /**
     * Get all settings in a group.
     *
     * @param string $group_id Group ID.
     * @return array|WP_Error
     */
    public function get_group_settings( $group_id ) {
        if ( empty( $group_id ) ) {
            return new WP_Error( 'rest_setting_setting_group_invalid', __( 'Invalid setting group.', 'wpfnl' ), array( 'status' => 404 ) );
        }
        $settings = Wpfnl_functions::get_admin_settings( $group_id );
        if ( empty( $settings ) ) {
            return new WP_Error( 'rest_setting_setting_group_invalid', __( 'Invalid setting group.', 'wpfnl' ), array( 'status' => 404 ) );
        }

        $filtered_settings = array();
        foreach ($settings as $key => $setting) {
            $filtered_settings[] = array(
                'id'    => $key,
                'value' => $setting
            );
        }
        return $filtered_settings;
    }


    /**
     * Get setting data.
     *
     * @since  3.0.0
     * @param string $group_id Group ID.
     * @param string $setting_id Setting ID.
     * @return stdClass|WP_Error
     */
    public function get_setting( $group_id, $setting_id ) {
        if ( empty( $setting_id ) ) {
            return new WP_Error( 'rest_setting_setting_invalid', __( 'Invalid setting.', 'wpfnl' ), array( 'status' => 404 ) );
        }

        $settings = $this->get_group_settings( $group_id );

        if ( is_wp_error( $settings ) ) {
            return $settings;
        }

        $array_key = array_keys( wp_list_pluck( $settings, 'id' ), $setting_id );

        if ( empty( $array_key ) ) {
            return new WP_Error( 'rest_setting_setting_invalid', __( 'Invalid setting.', 'wpfnl' ), array( 'status' => 404 ) );
        }

        $setting = $settings[ $array_key[0] ];
        return $setting;
    }


    /**
     * active required plugin based on slug
     *
     * @param $plugin_name
     * @return WP_Error|WP_REST_Response
     * @since 1.0.0
     */
    public function activate_wc_plugins( \WP_REST_Request $request ) {
        if( !function_exists('activate_plugin') ) {
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }
        $CLPermission = $request['permission'];
        
        $plugin_slug_arr = array(
            'woocommerce/woocommerce.php' => false,
            'cart-lift/cart-lift.php' => false,
        );
        $should_activated = array(
            'woocommerce/woocommerce.php' => false,
            'cart-lift/cart-lift.php' => false,
        );
        if($CLPermission == ''){
            unset($should_activated[ 'cart-lift/cart-lift.php' ]);
            unset($plugin_slug_arr[ 'cart-lift/cart-lift.php' ]);
        }
        foreach ( $plugin_slug_arr as $slug => $is_silent ) {
            $data = activate_plugin( $slug, '', false, $is_silent );
            if ( !is_wp_error( $data ) ) {
                $should_activated[ $slug ] = true;
                
            }
        }
        delete_transient( '_wc_activation_redirect' );
        foreach ( $should_activated as $slug => $is_activated ) {
            if(!$is_activated) {
                $settings['success'] = false;
                return rest_ensure_response( $settings );
            }
        }

        $settings['success'] = true;
        return rest_ensure_response( $settings );
    }


    /**
     * disable activation redirect
     */
    public function disable_wc_activation_redirect() {
        delete_transient( '_wc_activation_redirect' );
    }



    /**
     * activate required plugins
     *
     * @param $plugin_name
     */
    public function active_plugin($plugin_slug,$permission = 1) {
		$plugin_slug_arr = array();
       
    	switch ($plugin_slug) {
            case 'elementor':
				$plugin_slug_arr = array(
					'elementor/elementor.php' => true,
				);
				break;
			case 'gutenberg':
                if($permission == 1){
                    $plugin_slug_arr = array(
                        'qubely/qubely.php' => true,
                    );
                }
				break;
            case 'divi-builder':
                $is_divi_installed = Wpfnl_functions::wpfnl_check_is_plugin_installed( $plugin_slug.'/divi-builder.php' );
                $is_divi_theme_active = Wpfnl_functions::wpfnl_is_theme_active( 'Divi' );
                if( $is_divi_installed ){
                    $plugin_slug_arr = array(
                        'divi-builder/divi-builder.php' => true,
                    );
                }
                break;
                
		}
		if($plugin_slug_arr) {
			foreach ( $plugin_slug_arr as $slug => $is_silent ) {
				$activate[ $slug ] = activate_plugin( $slug, '', false, $is_silent );
			}
		}

        if(is_plugin_active( 'elementor/elementor.php' )){
            Manager::create_default_kit();
        }
    }


    /**
     * Update a single setting in a group.
     *
     * @since  3.0.0
     * @param  WP_REST_Request $request Request data.
     * @return WP_Error|WP_REST_Response
     */
    public function update_settings( $request ) {

        if( $request['settings_id'] === 'permalink') {
            $default = Wpfnl_functions::get_permalink_settings();
            $settings = Wpfnl_functions::get_admin_settings( '_wpfunnels_permalink_settings', $default);
        } elseif ($request['settings_id'] === 'funnel_type') {
            $default = Wpfnl_functions::get_general_settings();
            $settings = Wpfnl_functions::get_admin_settings( $request['group_id'], $default);
        }
        else {
            $default = Wpfnl_functions::get_general_settings();
            $settings = Wpfnl_functions::get_admin_settings( $request['group_id'], $default );

            /** reset templates data if any */
			delete_option(WPFNL_TEMPLATES_OPTION_KEY);
			delete_transient('wpfunnels_remote_template_data_' . WPFNL_VERSION);
        }

        if ( !$settings ) {
            return new WP_Error( 'rest_setting_invalid', __( 'Invalid setting.', 'wpfnl' ), array( 'status' => 404 ) );
        }
        if ( empty( $settings ) ) {
            return new WP_Error( 'rest_setting_invalid', __( 'Invalid setting.', 'wpfnl' ), array( 'status' => 404 ) );
        }

        if($request['type'] !== 'ignore_activation' ) {
            $this->active_plugin($request['slug'],$request['permission']);
        }

        if($request['settings_id'] === 'permalink') {
            $settings['structure'] = $request['settings'];
            $settings['funnel_base'] = $request['funnelBase'];
            $settings['step_base'] = $request['stepBase'];
            Wpfnl_functions::update_admin_settings('_wpfunnels_permalink_saved', true);
            Wpfnl_functions::update_admin_settings($request['group_id'], $settings);
        }else {
            $settings[$request['settings_id']] = $request['value'];
            Wpfnl_functions::update_admin_settings($request['group_id'], $settings);
        }
        $settings['success'] = true;
        $settings = $this->prepare_item_for_response( $settings, $request );
        return rest_ensure_response( $settings );
    }


    /**
     * Prepare a single setting object for response.
     *
     * @since  3.0.0
     * @param object          $item Setting object.
     * @param WP_REST_Request $request Request object.
     * @return WP_REST_Response $response Response data.
     */
    public function prepare_item_for_response( $item, $request ) {
        $data     = $this->add_additional_fields_to_object( $item, $request );
        $response = rest_ensure_response( $data );
        $response->add_links( $this->prepare_links( $request['settings_id'] ) );
        return $response;
    }
}
