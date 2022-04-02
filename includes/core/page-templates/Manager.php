<?php

namespace WPFunnels\PageTemplates;

use WPFunnels\Base_Manager;
use WPFunnels\Wpfnl_functions;

class Manager extends Base_Manager {


    const TEMPLATE_Boxed = 'wpfunnels_boxed';


    const TEMPLATE_DEFAULT = 'wpfunnels_default';


    const TEMPLATE_FULLWIDTH_WITHOUT_HEADER_FOOTER = 'wpfunnels_fullwidth_without_header_footer';


    const TEMPLATE_CHECKOUT = 'wpfunnels_checkout';


    protected $print_callback;

	/**
	 * body class of the page templates
	 *
	 * @var $body_class
	 */
    protected $body_class = array();


    public function __construct() {
        add_action( 'init', [ $this, 'add_wp_templates_support' ] );
        add_filter( 'template_include', [ $this, 'template_include' ], 90 );
    }


    public function add_wp_templates_support() {
        $post_types = array( WPFNL_STEPS_POST_TYPE );

        foreach ( $post_types as $post_type ) {
            add_filter( "theme_{$post_type}_templates", [ $this, 'add_funnel_page_templates' ], 9999, 4 );
        }
    }


    public function template_include( $template ) {
        if ( is_singular() ) {

            global $post;

            // Return template if post is empty
            if ( ! $post ) {
                return $template;
            }

            if ( is_object( $post ) && WPFNL_STEPS_POST_TYPE === $post->post_type ) {
                add_filter( 'next_post_rel_link', '__return_empty_string' );
                add_filter( 'previous_post_rel_link', '__return_empty_string' );

				$page_template = apply_filters( 'wpfunnels/page_template', $this->get_page_template($post->ID) );
                $template_path = $this->get_template_path( $page_template );
                if ( $template_path ) {
                    $template = $template_path;
                }
            }
        }

        return $template;
    }


    private function get_page_template($post_id) {
        return get_post_meta( $post_id, '_wp_page_template', true );
    }


    /**
     * @param $page_templates
     * @param $wp_theme
     * @param $post
     * @return array
     *
     * @since 1.0.0
     */
    public function add_funnel_page_templates( $page_templates, $wp_theme, $post ) {
        $wpfnl_templates = [
            self::TEMPLATE_DEFAULT    						=> _x( 'WPFunnels Default', 'Page Template', 'wpfnl' ),
            self::TEMPLATE_FULLWIDTH_WITHOUT_HEADER_FOOTER	=> _x( 'WPFunnels Canvas', 'Page Template', 'wpfnl' ),
            self::TEMPLATE_Boxed   							=> _x( 'WPFunnels Boxed', 'Page Template', 'wpfnl' ),
        ];

        $wpfnl_templates = $wpfnl_templates + $page_templates;

        return $wpfnl_templates;
    }


    public function set_print_callback( $callback ) {
        $this->print_callback = $callback;
    }


    public function print_callback() {
        while ( have_posts() ) :
            the_post();
            the_content();
        endwhile;
    }


    public function print_content() {

        if ( ! $this->print_callback ) {
            $this->print_callback = [ $this, 'print_callback' ];
        }

        call_user_func( $this->print_callback );
    }


    public function get_template_path( $page_template ) {
        $template_path = '';
        switch ( $page_template ) {
            case self::TEMPLATE_DEFAULT:
                $template_path = __DIR__ . '/templates/template-default.php';
                $this->body_class[] = $page_template;
                break;
            case self::TEMPLATE_FULLWIDTH_WITHOUT_HEADER_FOOTER:
                $template_path = __DIR__ . '/templates/template-canvas.php';
				$this->body_class[] = $page_template;
                break;
			case self::TEMPLATE_Boxed:
				$template_path = __DIR__ . '/templates/template-boxed.php';
				$this->body_class[] = $page_template;
				break;
        }
        if( file_exists($template_path) ) {
			add_filter( 'body_class', array( $this, 'body_class' ) );
			return $template_path;
		}
        return $template_path;
    }


	/**
	 * assign body clas for specific template
	 *
	 * @param $classes
	 * @return array
	 */
    public function body_class( $classes ) {
		return array_merge( $classes, $this->body_class );
	}


    public function action_register_template_control( $document ) {
        if ( $document instanceof PageBase || $document instanceof LibraryPageDocument ) {
            $this->register_template_control( $document );
        }
    }


    public function register_template_control( $document, $control_id = 'template' ) {
        if ( ! Utils::is_cpt_custom_templates_supported() ) {
            return;
        }

        require_once ABSPATH . '/wp-admin/includes/template.php';

        $document->start_injection( [
            'of' => 'post_status',
            'fallback' => [
                'of' => 'post_title',
            ],
        ] );

        $control_options = [
            'options' => array_flip( get_page_templates( null, $document->get_main_post()->post_type ) ),
        ];

        $this->add_template_controls( $document, $control_id, $control_options );

        $document->end_injection();
    }


    public function add_template_controls( Document $document, $control_id, $control_options ) {
        // Default Control Options
        $default_control_options = [
            'label' => __( 'Page Layout', 'wpfnl' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'default',
            'options' => [
                'default' => __( 'Default', 'wpfnl' ),
            ],
        ];

        $control_options = array_replace_recursive( $default_control_options, $control_options );

        $document->add_control(
            $control_id,
            $control_options
        );

        $document->add_control(
            $control_id . '_default_description',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<b>' . __( 'Default Page Template from your theme', 'wpfnl' ) . '</b>',
                'content_classes' => 'elementor-descriptor',
                'condition' => [
                    $control_id => 'default',
                ],
            ]
        );

        $document->add_control(
            $control_id . '_canvas_description',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<b>' . __( 'No header, no footer, just Elementor', 'wpfnl' ) . '</b>',
                'content_classes' => 'elementor-descriptor',
                'condition' => [
                    $control_id => self::TEMPLATE_Boxed,
                ],
            ]
        );

        $document->add_control(
            $control_id . '_header_footer_description',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<b>' . __( 'This template includes the header, full-width content and footer', 'wpfnl' ) . '</b>',
                'content_classes' => 'elementor-descriptor',
                'condition' => [
                    $control_id => self::TEMPLATE_HEADER_FOOTER,
                ],
            ]
        );

        if ( $document instanceof Kit ) {
            $document->add_control(
                'reload_preview_description',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => __( 'Changes will be reflected in the preview only after the page reloads.', 'wpfnl' ),
                    'content_classes' => 'elementor-descriptor',
                ]
            );
        }
    }


    public static function body_open() {
        if ( function_exists( 'wp_body_open' ) ) {
            wp_body_open();
        } else {
            do_action( 'wp_body_open' );
        }
    }


    public function get_name() {
        return 'page-templates';
    }

}
