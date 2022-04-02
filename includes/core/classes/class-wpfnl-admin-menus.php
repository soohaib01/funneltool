<?php

namespace WPFunnels\Menu;

use WPFunnels\Admin\SetupWizard;
use WPFunnels\Wpfnl;
use WPFunnels\Wpfnl_functions;

/**
 * Class Wpfnl_Menus
 * @package Wpfnl
 */
class Wpfnl_Menus
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_plugin_menus']);
        add_filter('admin_head', [$this, 'remove_submenu'], 10, 2);
        add_filter('admin_head', [$this, 'remove_notices_from_funnel_window'], 10, 2);
        add_action('admin_init', [$this, 'disallow_all_step_view']);
        add_action('admin_footer', [$this, 'doc_link_with_new_page']);

        if( isset($_GET['page']) && 'edit_funnel' === $_GET['page'] ) {
			add_filter( "admin_body_class", array($this, 'add_folded_menu_class') );
		}
    }


    /**
     * register plugin menus and submenus
     *
     * @since 1.0.0
     */
    public function register_plugin_menus()
    {
        add_menu_page(
            __('WP Funnels', 'wpfnl'),
            __('WP Funnels', 'wpfnl'),
            'manage_options',
            WPFNL_MAIN_PAGE_SLUG,
            '',
            WPFNL_DIR_URL . 'admin/assets/images/funnel.svg',
            6
        );

        add_submenu_page(
            WPFNL_MAIN_PAGE_SLUG,
            __('Funnels', 'wpfnl'),
            __('Funnels', 'wpfnl'),
            'manage_options',
            WPFNL_MAIN_PAGE_SLUG,
            [$this, 'render_funnels_page']
        );

        add_submenu_page(
            WPFNL_MAIN_PAGE_SLUG,
            __('Settings', 'wpfnl'),
            __('Settings', 'wpfnl'),
            'manage_options',
            WPFNL_GLOBAL_SETTINGS_SLUG,
            [$this, 'render_settings_page']
        );

        add_submenu_page(
            WPFNL_MAIN_PAGE_SLUG,
            __('Documentation', 'wpfnl'),
            '<span id="wpfnl-documentation">'. __('Documentation', 'wpfnl').'</span>',
            'manage_options',
            'documentation',
            [$this, 'redirect_to_documentation_page']
        );

        add_submenu_page(
            WPFNL_MAIN_PAGE_SLUG,
            __('Edit Funnel', 'wpfnl'),
            __('Edit Funnel', 'wpfnl'),
            'manage_options',
            WPFNL_EDIT_FUNNEL_SLUG,
            [$this, 'render_edit_funnel_page']
        );

		add_submenu_page(
			WPFNL_MAIN_PAGE_SLUG,
			__('Request a Feature', 'wpfnl'),
			'<span id="wpfnl-request-feature">'. __('Request a Feature', 'wpfnl').'</span>',
			'manage_options',
			'request_a_feature',
			[$this, 'redirect_to_feature_request_page']
		);

        if ( !Wpfnl_functions::is_wpfnl_pro_activated() ) {
			add_submenu_page(
				WPFNL_MAIN_PAGE_SLUG,
				'',
				'<span class="dashicons dashicons-star-filled" style="font-size: 17px; color:#1fb3fb;"></span> ' . __('Go Pro', 'wpfnl'),
				'manage_options',
				'go_wpfnl_pro',
				[$this, 'redirect_to_pro']
			);
        } elseif ( version_compare( WPFNL_PRO_VERSION, '1.2.9' , '<=' ) ) {
			/**
			 * this will be removed in future, all license
			 * related functionalities will be moved to pro
			 */
			add_submenu_page(
				WPFNL_MAIN_PAGE_SLUG,
				__('License', 'wpfnl'),
				__('License', 'wpfnl'),
				'manage_options',
				'wpf-license',
				[$this, 'render_license']
			);
		}
    }


    /**
     * render funnel page
     *
     * @since 1.0.0
     */
    public function render_funnels_page()
    {
        Wpfnl::$instance->module_manager->get_admin_modules('funnels')->get_view();
    }


    /**
     * render edit funnel page.
     *
     * @since 1.0.0
     */
    public function render_edit_funnel_page()
    {
        $funnel_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
        Wpfnl::$instance->module_manager->get_admin_modules('funnel')->init($funnel_id);
        Wpfnl::$instance->module_manager->get_admin_modules('funnel')->get_view();
    }

    /**
     * render create funnel page
     *
     * @since 1.0.0
     */
    public function render_create_funnel_page()
    {
        Wpfnl::$instance->module_manager->get_admin_modules('create-funnel')->get_view();
    }


    /**
     * render settings page
     *
     * @since 1.0.0
     */
    public function render_settings_page()
    {
        Wpfnl::$instance->module_manager->get_admin_modules('settings')->get_view();
    }


    /**
     * redirect to documentation page
     *
     * @since 1.0.0
     */
    public function redirect_to_documentation_page()
    {
        $pro_url = add_query_arg( 'wpfunnels-dashboard', '1', 'https://getwpfunnels.com/resources/' );
		wp_redirect($pro_url);
		exit();
    }

	/**
	 * render license page for funnel
	 *
	 * @since 2.0.0
	 */
	public function render_license() {
		require WPFNL_DIR . '/admin/partials/license.php';
	}

    /**
     * remove submenu from plugin menu
     *
     * @since 1.0.0
     */
    public function remove_submenu()
    {
        remove_submenu_page(WPFNL_MAIN_PAGE_SLUG, 'edit_funnel');
    }


    /**
     * remove all notices from funnel window
     *
     * @since 2.0.0
     */
    public function remove_notices_from_funnel_window() {
        if (!empty($_GET['page']) && 'edit_funnel' == sanitize_text_field( $_GET['page'] )) {
            remove_all_actions( 'admin_notices' );
        }
    }


    /**
     * force user to visit all steps page
     *
     * @since 1.0.0
     */
    public function disallow_all_step_view()
    {
        global $pagenow;
        if ('edit.php' === $pagenow && isset($_GET['post_type']) && WPFNL_STEPS_POST_TYPE === sanitize_text_field($_GET['post_type'])) {
            $funnel_link = add_query_arg(
                [
                    'page' => WPFNL_MAIN_PAGE_SLUG,
                ],
                admin_url('admin.php')
            );
            wp_safe_redirect($funnel_link);
            exit;
        }
    }

    /**
     * redirect user to pro url
     */
    public function redirect_to_pro()
    {
        wp_redirect('https://getwpfunnels.com/pricing/');
    }


	/**
	 * redirect to feature request page
	 */
	public function redirect_to_feature_request_page() {
		$pro_url = add_query_arg( 'wpfunnels-dashboard', '1', 'https://getwpfunnels.com/ideas/' );
		wp_redirect($pro_url);
		exit();
	}


    /**
     * Open with new page when documenation is clicked
     */
    public function doc_link_with_new_page(){
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#wpfnl-documentation').parent().attr('target','_blank');
                $('#wpfnl-request-feature').parent().attr('target','_blank');
            });
        </script>
        <?php
    }


    public function add_folded_menu_class($classes) {
		return $classes." folded";
	}
}
