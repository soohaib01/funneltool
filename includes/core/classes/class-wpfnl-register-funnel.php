<?php
namespace WPFunnels\CPT;

use WPFunnels\Wpfnl_functions;

class Wpfnl_CPT
{
    protected $args;

    protected $is_gutenberg_editor_active = false;

    public function __construct()
    {
        add_action('init', [ $this, 'register_funnel_cpt' ]);
        add_action('init', [ $this, 'register_funnel_steps' ]);
        add_action('init', [ $this, 'register_steps_meta' ]);
        add_filter('post_type_link', [ $this, 'step_post_type_permalinks' ], 10, 3);
        add_action('init', [ $this, 'rewrite_step_rule' ]);
        add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_assets' ] );
        add_action( 'admin_footer', [ $this, 'print_admin_js_template' ] );

        if ( ! is_admin() ) {
            add_action( 'pre_get_posts', array( $this, 'add_steps_cpt_to_main_query' ), 20 );
        }
    }


    /**
     * register post type for
     * funnel
     *
     * @since 1.0.0
     */
    public function register_funnel_cpt()
    {
        $labels = [
            'name'                  => _x('Funnels', 'Funnel general name', 'wpfnl'),
            'singular_name'         => _x('Funnel', 'Funnel singular name', 'wpfnl'),
        ];
        $args = [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => false,
            'show_in_menu'       => true,
            'query_var'          => true,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'show_in_rest'       => true,
            'supports'           => [ 'thumbnail' ],
        ];
        register_post_type(WPFNL_FUNNELS_POST_TYPE, $args);
    }


    /**
     * register post type for
     * steps
     *
     * @since 1.0.0
     */
    public function register_funnel_steps()
    {
        $permalink_settings = Wpfnl_functions::get_permalink_settings();

        $labels = [
            'name'                  => _x('Steps', 'Steps general name', 'wpfnl'),
            'singular_name'         => _x('Step', 'Step singular name', 'wpfnl'),
            'edit_item'             => __( 'Edit Step', 'wpfnl' ),
        ];
        $args = [
            'labels'              => $labels,
            'public'              => true,
            'query_var'           => true,
            'can_export'          => true,
            'exclude_from_search' => true,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_admin_bar'   => false,
            'show_in_rest'        => true,
            'supports'            => [ 'title', 'editor', 'elementor', 'revisions', 'thumbnail', 'custom-fields', ],
            'capability_type'     => 'post',
            'map_meta_cap'        => true,
        ];

        if ( isset($_GET['ct_builder']) && 'true' == $_GET['ct_builder'] ) {
			$args['show_in_admin_bar'] = true;
		}

        if (!empty($permalink_settings['structure'])) {
            $structure = 'default';
            switch ($permalink_settings['structure']) {
                case 'funnel-step':
                    $structure = '/wpfunnels/%funnel_name%/wpfunnel_steps';
                    break;
                case 'funnel':
                    $structure = '/wpfunnels/%funnel_name%';
                    break;
                case 'step':
                    $structure = '%funnel_name%/wpfunnel_steps';
                    break;
            }
            if ($structure !== 'default') {
                $args['rewrite'] = [
                    'slug'       => $structure,
                    'with_front' => false,
                ];
            }
        } elseif (!empty($permalink_settings['step_base'])) {
            $args['rewrite'] = [
                'slug'       => $permalink_settings['step_base'],
                'with_front' => false,
            ];
        }
        register_post_type(WPFNL_STEPS_POST_TYPE, $args);
    }


    /**
     * register meta fields for steps post type
     */
    public function register_steps_meta() {

		register_meta('post', 'order-bump-settings', array(
			'single'       => true,
			'type'         => 'object',
			'default'	   => array(
				'selectedStyle' 	=> 'style1',
				'position' 			=> 'after-order',
				'product' 			=> '',
				'quantity' 			=> '1',
				'price' 			=> '25',
				'salePrice' 		=> '',
				'htmlPrice'	 		=> '',
				'productImage' 		=> array(
					'url'	=> '',
					'id'	=> '',
				),
				'highLightText' 	=> '6D Screen Protector (20% OFF)',
				'checkBoxLabel' 	=> 'Grab This Offer With One Click!',
				'productDescriptionText' => 'Get this scratch proof 6D Tempered Glass Screen Protector for your iPhone. Keep your phone',
				'discountOption' 	=> 'original',
				'discountapply' 	=> 'regular',
				'discountValue' 	=> '',
				'discountPrice' 	=> '',
				'couponName' 		=> '',
				'obNextStep' 		=> '',
				'productName' 		=> '',
				'productType' 		=> '',
				'isEnabled' 		=> 'no',
				'isReplace' 		=> '',
				'replace' 			=> 0,
				'obPrimaryColor' 	=> '',
			),
			'show_in_rest' => array(
				'schema' => array(
					'type'       => 'object',
					'properties' => array(
						'selectedStyle' => array(
							'type' => 'string',
						),
						'position'  => array(
							'type' => 'string',
						),
						'product' => array(
							'type' => 'string',
						),
						'quantity'  => array(
							'type' => 'string',
						),
						'price' => array(
							'type' => 'string',
						),
						'salePrice'  => array(
							'type' => 'string',
						),
						'htmlPrice' => array(
							'type' => 'string',
						),
						'productImage'  => array(
							'type' => 'object',
							'properties' => array(
								'id'  => array(
									'type' => 'string',
								),
								'url' => array(
									'type' => 'string',
								),
							)
						),
						'highLightText' => array(
							'type' => 'string',
						),
						'checkBoxLabel' => array(
							'type' => 'string',
						),
						'productDescriptionText'  => array(
							'type' => 'string',
						),
						'discountOption' => array(
							'type' => 'string',
						),
						'discountapply'  => array(
							'type' => 'string',
						),
						'discountPrice'  => array(
							'type' => 'string',
						),
						'discountValue' => array(
							'type' => 'string',
						),
						'couponName'  => array(
							'type' => 'string',
						),
						'obNextStep'  => array(
							'type' => 'string',
						),
						'productName'  => array(
							'type' => 'string',
						),
						'productType' => array(
							'type' => 'string',
						),
						'isEnabled'  => array(
							'type' => 'string',
						),
						'isReplace'  => array(
							'type' => 'string',
						),
						'replace'  => array(
							'type' => 'boolean',
						),
						'obPrimaryColor'  => array(
							'type' => 'string',
						),
					),
				),
			),
		));

		$this->register_string_type_meta('order-bump', 'no' );
		$this->register_string_type_meta('_wpfnl_thankyou_order_overview', 'on' );
		$this->register_string_type_meta('_wpfnl_thankyou_order_details', 'on' );
		$this->register_string_type_meta('_wpfnl_thankyou_billing_details', 'on' );
		$this->register_string_type_meta('_wpfnl_thankyou_shipping_details', 'on' );
    }


    /**
     * register string type meta for steps post type.
     *
     * @param $meta_key
     * @param $default_value
     *
     * @since 2.0.3
     */
    public function register_string_type_meta( $meta_key, $default_value ) {
        register_meta(
            'post',
            $meta_key,
            array(
                'single'       => true,
                'type'         => 'string',
                'show_in_rest' => array(
                    'schema' => array(
                        'type'  	=> 'string',
                        'default' 	=> $default_value,
                    ),
                ),
                'auth_callback' => function(){ return true; }
            )
        );
    }



    /**
     * rewrite rules for steps permalink
     *
     * @since 1.0.0
     */
    public function rewrite_step_rule()
    {
        $permalink_settings = Wpfnl_functions::get_permalink_settings();
        if (isset($permalink_settings['structure'])) {
            switch ($permalink_settings['structure']) {
                case 'funnel-step':
                    add_rewrite_rule('^' . $permalink_settings['funnel_base'] . '/([^/]*)/' . $permalink_settings['step_base'] . '/([^\/]*)/?', 'index.php?wpfunnel_steps=$matches[2]', 'top');
                    break;
                case 'funnel':
                    add_rewrite_rule('^' . $permalink_settings['funnel_base'] . '/([^/]*)/([^/]*)/?', 'index.php?wpfunnel_steps=$matches[2]', 'top');
                    break;
                case 'step':
                    add_rewrite_rule('([^/]*)/' . $permalink_settings['step_base'] . '/([^\/]*)/?', 'index.php?wpfunnel_steps=$matches[2]', 'top');
                    break;
                default:
                    break;
            }
        }
    }


    /**
     * steps permalink creation
     *
     * @param $post_link
     * @param $post
     * @param $leavename
     * @return string|string[]
     * @since 1.0.0
     */
    public function step_post_type_permalinks( $post_link, $post, $leavename )
    {

        if (isset($post->post_type) && WPFNL_STEPS_POST_TYPE == $post->post_type) {
            $funnel_id      = get_post_meta($post->ID, '_funnel_id', true);
            $funnel_name    = get_post_field('post_title', $funnel_id) ? get_post_field('post_title', $funnel_id) : 'No Title';
            $funnel_name    = sanitize_title($funnel_name);
            $permalink_settings = Wpfnl_functions::get_permalink_settings();

            if (isset($permalink_settings['structure']) && ! empty($permalink_settings['structure'])) {
                if($permalink_settings['structure'] !== 'default') {
                    $sep       = '/';
//                    $post_link = preg_replace('/\bwpfunnels\b/u', $permalink_settings['funnel_base'], $post_link);
                    $post_link = preg_replace('/\/wpfunnels\//', '/'.$permalink_settings['funnel_base'].'/', $post_link);
                    $search    = [ $sep . '%funnel_name%', $sep . 'wpfunnel_steps' ];
                    $replace   = [ $sep . $funnel_name, $sep . $permalink_settings['step_base'] ];
                    $post_link = str_replace($search, $replace, $post_link);
                } else {
                    if (isset($_REQUEST['elementor-preview'])) { //phpcs:ignore
                        return $post_link;
                    }

                    $structure = get_option('permalink_structure');

                    if ('/%postname%/' === $structure) {
                        $post_link = str_replace('/' . $post->post_type . '/', '/', $post_link);
                    }
                }

            } else {

                // If elementor page preview, return post link as it is.
                if (isset($_REQUEST['elementor-preview'])) { //phpcs:ignore
                    return $post_link;
                }

                $structure = get_option('permalink_structure');

                if ('/%postname%/' === $structure) {
                    $post_link = str_replace('/' . $post->post_type . '/', '/', $post_link);
                }
            }
        }
        return $post_link;
    }


    /**
     * Add step post type to the main query
     *
     * @param $query
     * @since 1.0.0
     */
    public function add_steps_cpt_to_main_query( $query ) {
        $post_types = '';
        if ( ! $query->is_main_query() ) {
            return;
        }

        if ( isset( $query->query['post_type'] ) ) {
            return;
        }

        if ( !isset( $query->query['page'] ) && !isset( $query->query_vars['page_id'] )) {
            return;
        }

        if ( empty( $query->query['name'] ) && empty( $query->query_vars['page_id'] ) ) {
            return;
        }
        if ( isset( $query->query_vars['post_type'] ) && is_array( $query->query_vars['post_type'] ) ) {
            $post_types = $query->get('post_type');
            if (!empty($post_types)) {
                if(!is_array($post_types)) {
                    $post_types = explode(',', $post_types);
                }
            }
            $post_types[] = WPFNL_STEPS_POST_TYPE;
            $post_types = array_map('trim', $post_types);
            $post_types = array_filter($post_types);

            $query->set('post_type', $post_types);

        } else {
            $query->set( 'post_type', array( 'post', 'page', WPFNL_STEPS_POST_TYPE ) );
        }
        return $query;
    }


    /**
     * check if this is gutenberg window
     *
     * @since 2.0.3
     */
    public function enqueue_assets() {
        if (!current_user_can('manage_options')) {
            return;
        }
        $this->is_gutenberg_editor_active = true;
        wp_enqueue_script( 'wpfnl-admin' . '-common', WPFNL_DIR_URL . '/admin/assets/js/wpfnl-admin-common.js', [ 'jquery' ], WPFNL_VERSION, true );
    }

    /**
     * print admin js template - will add back to funnel button on admin
     *
     * @since 2.0.3
     */
    public function print_admin_js_template() {
        if ( ! $this->is_gutenberg_editor_active ) {
            return;
        }
        if (WPFNL_STEPS_POST_TYPE !== get_post_type()) {
            return;
        }
        $funnel_id = get_post_meta( get_the_id(), '_funnel_id', true );
        $funnel_window_url = add_query_arg(
            array(
                'page'      => WPFNL_EDIT_FUNNEL_SLUG,
                'id'        => $funnel_id,
                'step_id'   => get_the_id(),
            ),
            admin_url('admin.php')
        );


        ?>

        <script id="wpfnl-gutenberg-button-switch-mode" type="text/html">
            <div id="wpfnl-switch-mode">
                <a href="<?php echo esc_url($funnel_window_url); ?>" id="wpfnl-switch-mode-button" type="button" class="button button-primary button-large">
                    <span class="wpfnl-switch-mode-on"><?php echo __( '&#8592; Back to Funnel Window', 'wpfnl' ); ?></span>
                </a>
            </div>
        </script>
    <?php
    }


    function is_elementor(){
        global $post;
        return \Elementor\Plugin::$instance->db->is_built_with_elementor($post->ID);
    }

}
