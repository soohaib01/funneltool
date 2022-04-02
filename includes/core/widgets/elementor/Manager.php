<?php

namespace WPFunnels\Widgets\Elementor;
use WPFunnels\Widgets\Elementor\Controls\Optin_Styles;
use WPFunnels\Widgets\Elementor\Controls\Product_Control;
use WPFunnels\Wpfnl_functions;


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Main Elementor Test Extension Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class Manager
{

    /**
     * Plugin Version
     *
     * @since 1.0.0
     *
     * @var string The plugin version.
     */
    const VERSION = WPFNL_VERSION;

    /**
     * Minimum Elementor Version
     *
     * @since 1.0.0
     *
     * @var string Minimum Elementor version required to run the plugin.
     */
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

    /**
     * Minimum PHP Version
     *
     * @since 1.0.0
     *
     * @var string Minimum PHP version required to run the plugin.
     */
    const MINIMUM_PHP_VERSION = '7.0';

    /**
     * Instance
     *
     * @since 1.0.0
     *
     * @access private
     * @static
     *
     * @var Manager The single instance of the class.
     */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @return Manager An instance of the class.
     * @since 1.0.0
     *
     * @access public
     * @static
     *
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function __construct() {

    	if( $this->is_compatible() ) {
			add_action('elementor/init', [$this, 'init']);
			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'enqueue_elementor_custom_style' ) );
			add_filter( 'wpfunnels/page_template', array( $this, 'get_page_template' ) );
		}

	}


    /**
     * Add css file on  Elementor admin
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function enqueue_elementor_custom_style()
    {
        wp_enqueue_style('elementor-icon', WPFNL_URL. 'includes/core/widgets/elementor/assets/css/elemetor-icon-style.css');
    }

    /**
     * Compatibility Checks
     *
     * Checks if the installed version of Elementor meets the plugin's minimum requirement.
     * Checks if the installed PHP version meets the plugin's minimum requirement.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function is_compatible() {

        // Check if Elementor installed and activated
        if (!did_action('elementor/loaded')) {
            return false;
        }

        // Check for required Elementor version
        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            return false;
        }

        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            return false;
        }

        return true;

    }

    /**
     * Initialize the plugin
     *
     * Load the plugin only after Elementor (and other plugins) are loaded.
     * Load the files required to run the plugin.
     *
     * Fired by `plugins_loaded` action hook.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function init() {

    	$editor_compatibility = Elemenetor_Editror_Compatibility::getInstance();
    	$editor_compatibility->elementor_compatibility();

    	add_action('elementor/init', [$this, 'add_elementor_widget_categories'],9999);
        add_action('elementor/widgets/widgets_registered', [$this, 'init_widgets']);
        add_action('elementor/controls/controls_registered', [$this, 'init_controls']);
    }


    /**
     * Register Category
     *
     * @since 1.0.0
     *
     * @access private
     */
    public function add_elementor_widget_categories()
    {


        $elementsManager = \Elementor\Plugin::instance()->elements_manager;
        $elementsManager->add_category(
            'wp-funnel',
            [
                'title' => __('WPFunnels', 'wpfnl'),
                'icon' => 'fa fa-plug',
            ]
        );
    }

    /**
     * Init Widgets
     *
     * Include widgets files and register them
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function init_widgets()
    {

		if( wp_doing_ajax() ) {
			if( isset($_POST['step_id']) ) {
				$step_id = $_POST['step_id'];
			} elseif ( isset($_POST['editor_post_id'] ) ) {
				$step_id = $_POST['editor_post_id'];
			} else {
				$step_id = '';
			}
			if( !$step_id && isset($_POST['initial_document_id'] ) ) {
				$step_id = $_POST['initial_document_id'];
			}
		}
		elseif(wp_get_theme()->get('Name') == 'Woodmart'){
			$step_id = isset($_GET['post']) ? $_GET['post'] : get_the_ID();
		}else {
			$step_id = get_the_ID();
		}
		if($step_id) {
			$step = $this->widget_registration_manager($step_id);
			if ( $step ) {
				$step_type = get_post_meta($step_id, '_step_type', true);
				if($step_type == 'landing') {
					if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '>=') ) {
						\Elementor\Plugin::instance()->widgets_manager->register(new Step_Pointer());
						\Elementor\Plugin::instance()->widgets_manager->register(new OptinForm());
					} else {
						// for older version of elementor
						\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Step_Pointer());
						\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new OptinForm());
					}
				}
				if (Wpfnl_functions::is_plugin_activated('woocommerce/woocommerce.php')) {
					if($step_type == 'checkout') {
						if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '>=') ) {
							\Elementor\Plugin::instance()->widgets_manager->register(new Checkout_Form());
						} else {
							\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Checkout_Form());
						}
					}

					if($step_type == 'thankyou') {
						if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '>=') ) {
							\Elementor\Plugin::instance()->widgets_manager->register(new Order_Details());
						} else {
							\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Order_Details());
						}
					}
				}
			}
		}

    }


    /**
     * Init Controls
     *
     * Include controls files and register them
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function init_controls() {
        \Elementor\Plugin::$instance->controls_manager->register_control(\WPFunnels\Widgets\Elementor\Controls\Product_Control::ProductSelector, new Product_Control());
        \Elementor\Plugin::$instance->controls_manager->register_control('optin_styles', new Optin_Styles());
    }



    /**
     * Widget Registration manager
     *
     * @since 1.0.0
     *
     * @access private
     */
    public function widget_registration_manager($page_id) {
        return get_post_meta($page_id, '_step_type', true);
    }


	/**
	 * get page templates
	 *
	 * @param $template
	 * @return mixed
	 *
	 * @since 2.0.5
	 */
    public function get_page_template( $template ) {

		if ( Wpfnl_functions::is_elementor_active() && is_singular() ) {
			$is_preview_mode = \Elementor\Plugin::$instance->preview->is_preview_mode();
			if($is_preview_mode) {
				$document = \Elementor\Plugin::$instance->documents->get_doc_for_frontend( get_the_ID() );
				if ( $document ) {
					$template = $document->get_meta( '_wp_page_template' );
				}
			}
		}
		return $template;
	}
}

Manager::instance();
