<?php

namespace WPFunnels;

use Wpfnl_i18n;
use Wpfnl_Loader;
use WPFunnels\Admin\SetupWizard;
use WPFunnels\Admin\Wpfnl_Admin as Admin;
use WPFunnels\Admin\Notices\Notice;
use WPFunnels\Ajax_Handler\Ajax_Handler;
use WPFunnels\Classes\OrderBumpActions\Wpfnl_Order_Bump_Action;
use WPFunnels\Frontend\Wpfnl_Public;
use WPFunnels\Frontend\Wpfnl_Public as Frontend;
use WPFunnels\CPT\Wpfnl_CPT as CPT;
use WPFunnels\Data_Store\Wpfnl_Funnel_Store_Data as Funnel_Store;
use WPFunnels\Data_Store\Wpfnl_Steps_Store_Data as Step_Store;
use WPFunnels\Menu\Wpfnl_Menus as Menu;
use WPFunnels\Modules\Wpfnl_Modules_Manager as Module_Manager;
use WPFunnels\Rest\Server;
// use WPFunnels\Shortcodes\Wpfnl_Shortcode;
use WPFunnels\Shortcodes\Wpfnl_Shortcodes;
use WPFunnels\Widgets\Wpfnl_Widgets_Manager as Widget_Manager;
use WPFunnels\Batch\Elementor\Wpfnl_Batch;
use WPFunnels\Meta\Wpfnl_Default_Meta;



/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Wpfnl
 * @subpackage Wpfnl/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wpfnl
 * @subpackage Wpfnl/includes
 * @author     RexTheme <support@rextheme.com>
 */
class Wpfnl
{

    /**
     * Instance.
     *
     * Holds the plugin instance.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @var Wpfnl
     */
    public static $instance = null;

    public $module_manager;

    public $admin;

    public $plugin_public;

    public $template_manager;

    public $menu;

    public $cpt;

    public $funnel_store;

    public $step_store;

    public $batch;

    public $helper;

    public $widget_manager;

    public $page_templates;


    public $order_bump_actions;

    public $meta;

    public $frontend;

    public $shortcodes;
    public $shortcode;

    /**
     * admin notice object
     *
     * @var string $admin_notice
     */
    protected $admin_notice;

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Wpfnl_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;


	/**
	 * @var $ajax_handler
	 */
	protected $ajax_handler;

    /**
     * Instance.
     *
     * Ensures only one instance of the plugin class is loaded or can be loaded.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return Wpfnl An instance of the class.
     */
    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
            do_action('wpfunnels/loaded');
        }
        return self::$instance;
    }


    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('WPFNL_VERSION')) {
            $this->version = WPFNL_VERSION;
        } else {
            $this->version = '1.0.0';
        }

        $this->plugin_name = 'wpfunnels';
        $this->load_dependencies();
        $this->init_rest_api();
        $this->init_setup_wizard();

        add_action( 'admin_footer', array($this, 'admin_footer_style') );
		add_action( 'wpfunnels/pro_init', array( $this, 'after_pro_plugin_init' ) );
        add_action( 'plugins_loaded', array($this, 'load_plugin'), 99 );


        if( !Wpfnl_functions::is_plugin_activated('woocommerce/woocommerce.php') ) {
			add_action( 'admin_enqueue_scripts', [$this, 'enqueue_scripts'] );
			add_action( 'wp_ajax_plugin_install_success', array($this, 'plugin_install_success') );
			add_action( 'admin_notices', array('WPFunnels\Admin\Notices\Notice', 'woocommerce_dependency_notice') );
			return;
		}

        $this->set_locale();
        $this->init_hooks();
    }


	/**
	 * enqueue funnel admin common scripts
	 */
    public function enqueue_scripts() {
		wp_enqueue_script($this->plugin_name . '-common', WPFNL_DIR_URL . 'admin/assets/js/wpfnl-admin-common.js', [ 'jquery', 'wp-i18n', 'wp-util', 'updates' ], $this->version, true);
		wp_localize_script( $this->plugin_name. '-common', 'wpfnl_common_vars', array(
			'ajaxurl' 						=> admin_url( 'admin-ajax.php' ),
			'wpfnl_activate_plugin_nonce'	=> wp_create_nonce('wpfnl_activate_plugin_nonce')
		));
    }


	/**
	 * plugin activation ajax
	 */
    public function plugin_install_success() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array(
				'message'	=> __('You are not authorize to activate plugins.', 'wpfnl')
			) );
		}

		/** nonce verifications */
		if ( ! check_ajax_referer( 'wpfnl_activate_plugin_nonce', 'security', false ) ) {
			$response_data = array( 'message' => __('Nonce verification failed', 'wpfnl') );
			wp_send_json_error( $response_data );
		}

		\wp_clean_plugins_cache();
		$plugin_init = ( isset( $_POST['plugin_init'] ) ) ? esc_attr( $_POST['plugin_init'] ) : '';
		$activate = \activate_plugin( $plugin_init, '', false, true );

		if ( is_wp_error( $activate ) ) {
			wp_send_json_error(
				array(
					'success' => false,
					'message' => $activate->get_error_message(),
				)
			);
		}

		wp_send_json_success(
			array(
				'message' => __('Plugin activated successfully.', 'wpfnl'),
			)
		);
	}


    public function init_hooks() {
        $this->loader->add_action( 'init', $this, 'load_admin_modules' );
        $shortcode = new Wpfnl_Shortcodes();
		add_action( 'init', array( $shortcode, 'init') );
    }

    public function init_rest_api() {
        $this->loader->add_action( 'init', $this, 'load_rest_api' );
    }

    public function load_admin_modules() {
        $this->module_manager = new Module_Manager();
    }

    public function load_rest_api() {
        Server::instance()->init();
    }


	/**
	 * load plugin classes
	 */
    public function load_plugin() {
		$this->batch			= new Wpfnl_Batch();
		$this->init();
		do_action( 'wpfunnels/init' );
	}


    public function init() {
        $this->admin                    = new Admin( $this->get_plugin_name(), $this->get_version() );
        $this->frontend                 = Wpfnl_Public::getInstance();
        $this->cpt                      = new CPT();
        $this->menu                     = new Menu();
        $this->funnel_store             = new Funnel_Store();
        $this->step_store               = new Step_Store();
        $this->template_manager         = new TemplateLibrary\Manager();
        $this->widget_manager           = Widget_Manager::getInstance()->init();
        $this->page_templates           = new PageTemplates\Manager();
        $this->admin_notice             = new Notice();
        $this->order_bump_actions       = new Wpfnl_Order_Bump_Action();
        $this->meta                     = new Wpfnl_Default_Meta();
        $this->shortcodes				= Wpfnl_Shortcodes::getInstance()->init();
		$this->ajax_handler 			= new Ajax_Handler();
    }



    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Wpfnl_Loader. Orchestrates the hooks of the plugin.
     * - Wpfnl_i18n. Defines internationalization functionality.
     * - Wpfnl_Admin. Defines all hooks for the admin area.
     * - Wpfnl_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {


        /**
         * The class responsible for auto loading all files of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'vendor/autoload.php';

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/utils/class-wpfnl-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/utils/class-wpfnl-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-wpfnl-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
         require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-wpfnl-public.php';


        $this->loader = new Wpfnl_Loader();
    }


	/**
	 * after pro plugin init action
	 *
	 */
    public function after_pro_plugin_init() {

		if ( ! is_admin() ) {
			return;
		}

		if ( version_compare( WPFNL_PRO_VERSION, WPFNL_PRO_REQUIRED_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'required_wpf_pro_notice' ) );
			add_action( 'after_plugin_row_'. WPFNL_BASE, 'wpfnl_pro_update_notice_after_plugin_row' );
		}
	}


	/**
	 * required pro plugin notice
	 *
	 */
	public function required_wpf_pro_notice() {
		$class 		= 'notice notice-error';
		$message 	= __("It appears you have an older version of <strong>WPFunnels Pro</strong>. This may cause some issues with the plugin's functionality. Please update <strong>WPFunnels Pro</strong> to v". WPFNL_PRO_REQUIRED_VERSION ." and above.",'wpfnl');
		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
	}


	/**
	 * show pro dependency notice
	 * after plugin row
	 *
	 */
	public function wpfnl_pro_update_notice_after_plugin_row() {
		$message 	= __("It appears you have an older version of <strong>WPFunnels Pro</strong>. This may cause some issues with the plugin's functionality. Please update <strong>WPFunnels Pro</strong> to v". WPFNL_PRO_REQUIRED_VERSION ." and above.",'wpfnl');
		printf( '</tr><tr class="plugin-update-tr"><td colspan="5" class="plugin-update"><div class="update-message" style="background-color: #ffebe8;">%s</div></td>', $message );
	}


    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Wpfnl_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new Wpfnl_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }


    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }


    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }


    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Wpfnl_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }


    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }


    /**
     * render admin footer styles
     */
    public function admin_footer_style() {
        echo '<style>
            .wpfnl-notice {
                border-left-color: #dba617;
            }
            .wpfnl-notice .wpfnl-notice__content {
                padding: 10px 0;
            }

            .wpfnl-notice .notice-dismiss {
                top: 11px;
            }

            .wpfnl-notice .wpfnl-notice__content > p {
                font-weight: 600;
                margin: 0;
            }
            .wpfnl-notice .wpfnl-notice__actions {
                margin-top: 8px;
            }

            .wpfnl-notice .wpfnl-notice__content .wpfnl-notice-button {
                height: 36px;
                line-height: 36px;
                border-radius: 6px;
                color: #fff;
                font-weight: 500;
                padding: 0 20px;
                background: #6e42d3;
                display: inline-block;
                text-decoration: none;
                letter-spacing: 0.5px;
                transition: all 0.3s ease;
            }

            .wpfnl-notice .wpfnl-notice__content .wpfnl-notice-button:hover {
                background: #4C25A5;
            }
            .wpfnl-notice .wpfnl-notice__content .wpfnl-notice-button .notice-loader {
                border: 2px solid #8265c5;
                border-radius: 50%;
                border-top: 2px solid #fff;
                width: 10px;
                height: 10px;
                animation: spin 0.7s linear infinite;
                display: none;
                position: relative;
                top: 3px;
                margin-left: 9px;
            }
            @-webkit-keyframes spin {
                0% { -webkit-transform: rotate(0deg); }
                100% { -webkit-transform: rotate(360deg); }
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>';
    }


    /**
     * init setup wizard options
     */
    public function init_setup_wizard() {
        add_action('init', array( $this, 'register_setup_wizard_page' ));
        add_action('admin_init', array($this, 'admin_redirects'));
    }


    /**
     * register setup wizard
     */
    public function register_setup_wizard_page() {
        if (!empty($_GET['page']) && 'wpfunnels-setup' == sanitize_text_field( $_GET['page'] )) {
            add_action('admin_menu', function () {
                add_dashboard_page('WPFunnels Setup', 'WPFunnels Setup', 'manage_options', 'wpfunnels-setup', function () {
                    return '';
                });
            });
            add_action('current_screen', function () {
                new SetupWizard();
            }, 999);
        }
    }


    /**
     * Handle redirects to setup/welcome page after install and updates.
     *
     * For setup wizard, transient must be present, the user must have access rights, and we must ignore the network/bulk plugin updaters.
     */
    public function admin_redirects()
    {
        // Setup wizard redirect.
        if (get_transient('_wpfunnels_activation_redirect')) {
            $do_redirect = true;
            $current_page = isset($_GET['page']) ?  wc_clean(wp_unslash($_GET['page'])) : false; // phpcs:ignore WordPress.Security.NonceVerification

            // On these pages, or during these events, postpone the redirect.
            if (wp_doing_ajax() || is_network_admin() || !current_user_can('manage_options')) {
                $do_redirect = false;
            }

            if ( $do_redirect ) {
                delete_transient('_wpfunnels_activation_redirect');
                wp_safe_redirect(admin_url('admin.php?page=wpfunnels-setup'));
                exit;
            }
        }
    }
}
