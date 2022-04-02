<?php

namespace WPFunnels\Modules\Admin\Settings;

use WPFunnels\Admin\Module\Wpfnl_Admin_Module;
use WPFunnels\Rollback;
use WPFunnels\Traits\SingletonTrait;
use WPFunnels\Wpfnl_functions;

class Module extends Wpfnl_Admin_Module
{
    use SingletonTrait;

    protected $validations;

    protected $prefix = '_wpfunnels_';

    protected $general_settings;

    protected $permalink_settings;

    protected $offer_settings;

    protected $user_roles;

    protected $gtm_events;

    protected $gtm_settings;

    protected $facebook_pixel_events;

    protected $facebook_pixel_settings;

    protected $utm_params;

    protected $utm_settings;


    protected $settings_meta_keys = [
        '_wpfunnels_funnel_type' => 'sales',
        '_wpfunnels_builder' => 'elementor',
        '_wpfunnels_paypal_reference' => '',
        '_wpfunnels_order_bump' => '',
        '_wpfunnels_ab_testing' => '',
        '_wpfunnels_permalink_settings' => '',
        '_wpfunnels_permalink_step_base' => 'wpfunnels',
        '_wpfunnels_permalink_flow_base' => 'step',
        '_wpfunnels_set_permalink' => 'step',
    ];

    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_init', [$this, 'after_permalink_settings_saved']);
		add_action( 'admin_post_wpfunnels_rollback', [ $this, 'post_wpfunnels_rollback' ] );
        $this->init_ajax();
    }

    public function is_wc_installed()
    {
        $path    = 'woocommerce/woocommerce.php';
        $plugins = get_plugins();

        return isset($plugins[ $path ]);
    }

    public function is_ff_installed()
    {
        $path    = 'fluentform/fluentform.php';
        $plugins = get_plugins();

        return isset($plugins[ $path ]);
    }

    public function is_elementor_installed()
    {
        $path    = 'elementor/elementor.php';
        $plugins = get_plugins();

        return isset($plugins[ $path ]);
    }


    public function enqueue_scripts()
    {
        wp_enqueue_script('settings', plugin_dir_url(__FILE__) . 'js/settings.js', ['jquery'], WPFNL_VERSION, true);
    }


    public function get_view()
    {
        $this->init_settings();


        // TODO: Implement get_view() method.
        $is_pro_activated = Wpfnl_functions::is_wpfnl_pro_activated();
        require_once WPFNL_DIR . '/admin/modules/settings/views/view.php';
    }

    /**
     * init ajax hooks for
     * saving metas
     *
     * @since 1.0.0
     */
    public function init_ajax()
    {
        $this->validations = [
            'logged_in' => true,
            'user_can' => 'manage_options',
        ];
        wp_ajax_helper()->handle('update-general-settings')
            ->with_callback([ $this, 'update_general_settings' ])
            ->with_validation($this->validations);

        wp_ajax_helper()->handle('clear-templates')
            ->with_callback([ $this, 'clear_templates_data' ])
            ->with_validation($this->validations);

		wp_ajax_helper()->handle('clear-transient')
			->with_callback([ $this, 'clear_transient_cache_data' ])
			->with_validation($this->validations);
    }


    /**
     * update handler for settings
     * page
     *
     * @param $payload
     * @return array
     * @since 1.0.0
     */
    public function update_general_settings($payload)
    {
        do_action('wpfunnels/before_settings_saved', $payload);
        $general_settings  = [
            'funnel_type'               => $payload['funnel_type'],
            'builder'                   => $payload['builder'],
            'disable_analytics'         => isset($payload['analytics_roles']) ? $payload['analytics_roles'] : '',
            'paypal_reference'          => $payload['paypal_reference'],
            'order_bump'                => $payload['order_bump'],
            'ab_testing'                => $payload['ab_testing'],
        ];

        $permalink_settings = [
            'structure'             => $payload['permalink_settings'],
            'step_base'             => $payload['permalink_step_base'],
            'funnel_base'           => $payload['permalink_funnel_base'],
        ];
        foreach ($payload as $key => $value) {

            switch ($key) {
                case 'funnel_type':
                case 'builder':
                    $cache_key = 'wpfunnels_remote_template_data_' . WPFNL_VERSION;
                    delete_transient($cache_key);
                    delete_option(WPFNL_TEMPLATES_OPTION_KEY);
                    break;
                case 'permalink_settings':
                    Wpfnl_functions::update_admin_settings($this->prefix.'permalink_saved', true);
                    break;
                case 'advanced_settings':
                    Wpfnl_functions::update_admin_settings($this->prefix.'advanced_settings', $value);
                    break;
                default:
                    break;
            }
        }
        Wpfnl_functions::update_admin_settings($this->prefix.'general_settings', $general_settings);
        Wpfnl_functions::update_admin_settings($this->prefix.'permalink_settings', $permalink_settings);
        do_action('wpfunnels/after_settings_saved', $payload );
        return [
            'success' => true
        ];
    }


    /**
     * initialize all the settings value
     *
     * @since 1.0.0
     */
    public function init_settings()
    {
        $this->general_settings     = Wpfnl_functions::get_general_settings();
        $this->permalink_settings   = Wpfnl_functions::get_permalink_settings();
        $this->offer_settings       = Wpfnl_functions::get_offer_settings();
        $this->user_roles           = Wpfnl_functions::get_user_roles();
        $this->gtm_events           = Wpfnl_functions::get_gtm_events();
        $this->gtm_settings         = Wpfnl_functions::get_gtm_settings();
        $this->facebook_pixel_events    = Wpfnl_functions::get_facebook_events();
        $this->facebook_pixel_settings  = Wpfnl_functions::get_facebook_pixel_settings();
        $this->utm_params           = Wpfnl_functions::get_utm_params();
        $this->utm_settings         = Wpfnl_functions::get_utm_settings();
    }




    /**
     * clear saved templates data
     *
     * @param $payload
     * @return array
     * @since 1.0.0
     */
    public function clear_templates_data($payload)
    {
        delete_option(WPFNL_TEMPLATES_OPTION_KEY);
        delete_transient('wpfunnels_remote_template_data_' . WPFNL_VERSION);
        return [
            'success' => true
        ];
    }


	/**
	 * @param $payload
	 * @return array
	 */
    public function clear_transient_cache_data($payload) {

		delete_transient('wpfunnels_remote_template_data_' . WPFNL_VERSION);
		do_action('wpfunnels/after_clear_transient');
		return array(
			'success' => true
		);
	}


    /**
     * after settings saved hooks
     *
     * @since 1.0.0
     */
    public function after_permalink_settings_saved()
    {
        $is_permalink_saved = get_option('_wpfunnels_permalink_saved');
        if ($is_permalink_saved) {
            flush_rewrite_rules();
            delete_option('_wpfunnels_permalink_saved');
        }
    }


    /**
     * get settings by meta key
     *
     * @param $key
     * @return mixed|string
     * @since 1.0.0
     */
    public function get_settings_by_key($key)
    {
        return isset($this->settings_meta_keys[$key]) ? $this->settings_meta_keys[$key]: '';
    }

    public function get_name()
    {
        // TODO: Implement get_name() method.
        return 'settings';
    }


	/**
	 * get rollback version of WPF
	 *
	 * @return array|mixed
	 * @since 2.3.0
	 *
	 * @src Inspired from Elementor roll back options
	 */
    public function get_roll_back_versions() {
		$rollback_versions = get_transient( 'wpfunnels_rollback_versions_' . WPFNL_VERSION );
		if ( false === $rollback_versions ) {
			$max_versions = 5;
			require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			$plugin_information = plugins_api(
				'plugin_information', [
					'slug' => 'wpfunnels',
				]
			);
			if ( empty( $plugin_information->versions ) || ! is_array( $plugin_information->versions ) ) {
				return [];
			}

			krsort( $plugin_information->versions );

			$rollback_versions = [];

			$current_index = 0;
			foreach ( $plugin_information->versions as $version => $download_link ) {
				if ( $max_versions <= $current_index ) {
					break;
				}

				$lowercase_version = strtolower( $version );
				$is_valid_rollback_version = ! preg_match( '/(trunk|beta|rc|dev)/i', $lowercase_version );

				/**
				 * Is rollback version is valid.
				 *
				 * Filters the check whether the rollback version is valid.
				 *
				 * @param bool $is_valid_rollback_version Whether the rollback version is valid.
				 */
				$is_valid_rollback_version = apply_filters(
					'wpfunnels/is_valid_rollback_version',
					$is_valid_rollback_version,
					$lowercase_version
				);

				if ( ! $is_valid_rollback_version ) {
					continue;
				}

				if ( version_compare( $version, WPFNL_VERSION, '>=' ) ) {
					continue;
				}

				$current_index++;
				$rollback_versions[] = $version;
			}

			set_transient( 'wpfunnels_rollback_versions_' . WPFNL_VERSION, $rollback_versions, WEEK_IN_SECONDS );
		}

		return $rollback_versions;
	}


	public function post_wpfunnels_rollback() {
		check_admin_referer( 'wpfunnels_rollback' );

		$rollback_versions = $this->get_roll_back_versions();
		if ( empty( $_GET['version'] ) || ! in_array( $_GET['version'], $rollback_versions ) ) {
			wp_die( esc_html__( 'Error occurred, The version selected is invalid. Try selecting different version.', 'wpfnl' ) );
		}

		$plugin_slug = basename( 'wpfunnels', '.php' );

		$rollback = new Rollback(
			[
				'version' => $_GET['version'],
				'plugin_name' => WPFNL_BASE,
				'plugin_slug' => $plugin_slug,
				'package_url' => sprintf( 'https://downloads.wordpress.org/plugin/%s.%s.zip', $plugin_slug, $_GET['version'] ),
			]
		);

		$rollback->run();

		wp_die(
			'', esc_html__( 'Rollback to Previous Version', 'wpfnl' ), [
				'response' => 200,
			]
		);
	}
}
