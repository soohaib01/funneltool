<?php
namespace WPFunnels\Admin\Notices;
use WPFunnels\Wpfnl_functions;

class Notice {

    /**
     * Stores notices.
     *
     * @var array
     */
    private static $notices = array();

    /**
     * Array of notices - name => callback.
     *
     * @var array
     */
    private static $core_notices = array(
//        'elementor_dependency'        => 'elementor_dependency_notice',
        'woocommerce_dependency'      => 'woocommerce_dependency_notice',
//        'gutenberg_dependency'        => 'gutenberg_dependency_notice',
    );

    public function __construct() {
    	$validations = [
			'logged_in' => true,
			'user_can' => 'manage_options',
		];

        add_action('admin_notices', [$this, 'show_admin_notices']);

		wp_ajax_helper()->handle('wpfunnels-activate-plugin')
			->with_callback([$this, 'activate_plugin'])
			->with_validation($validations);
    }


    /**
     * Get notices
     *
     * @return array
     */
    public static function get_notices() {
        return self::$core_notices;
    }


    public function show_admin_notices() {
        foreach ( self::get_notices() as $id => $call_back_function ) {
            self::$call_back_function();
        }
    }


    /**
     * print admin notices
     *
     * @param $options
     * @return bool
     * @since 2.0.0
     */
    public static function print_notice($options) {
        if ( ! current_user_can( 'manage_options' ) ) {
            return false;
        }
        $default_options = [
            'id' 			=> null,
            'title' 		=> '',
            'description' 	=> '',
            'classes' 		=> [ 'notice', 'wpfnl-notice' ], // We include WP's default notice class so it will be properly handled by WP's js handler
            'type' 			=> '',
            'dismissible' 	=> true,
            'icon' 			=> '',
            'button' 		=> [],
            'button_secondary' => [],
        ];
        $options = array_replace_recursive( $default_options, $options );
        $classes = $options['classes'];
        if ( $options['dismissible'] ) {
            $classes[] = 'is-dismissible';
        }

        if ( $options['type'] ) {
            $classes[] = 'wpfnl-notice--' . $options['type'];
        }

        $wrapper_attributes = [
            'class' => $classes,
        ];
        if ( $options['id'] ) {
            $wrapper_attributes['data-notice_id'] = $options['id'];
        }
        ?>
        <div <?php echo self::render_html_attributes( $wrapper_attributes ); ?> >
            <div class="wpfnl-notice__content">
                <?php if ( $options['description'] ) { ?>
                    <p><?php echo $options['description']; ?></p>
					<p class="wpfnl-notice-message"></p>
                    <div class="wpfnl-notice__actions">
                        <?php
                        foreach ( $options['button'] as $index => $button_settings ) {
                            if ( empty( $button_settings['text'] ) ) {
                                continue;
                            }
                            self::print_button( $button_settings );
                        } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php }


    /**
     * show plugin dependency notices for elementor
     */
    public static function elementor_dependency_notice() {
        $settings = Wpfnl_functions::get_general_settings();
        if ($settings['builder']== 'elementor') {
            $plugin = 'elementor/elementor.php';
            $show_notice_info = self::should_show_plugin_dependency_notice($plugin, 'elementor', "Elementor");
			$status = $show_notice_info['action'] === 'inactive' ? 'inactive' : 'not installed';
			$action = $show_notice_info['action'] === 'inactive' ? 'activate' : 'install';
            if($show_notice_info['show_notice']) {
                $options = array(
                    'id' 			=> 'elementor',
                    'title' 		=> 'Elementor',
					'description' 	=> __("It seems Elementor is {$status} on your site. As you have selected Elementor as builder option, you need to {$action} Elementor.", 'wpfnl'),
                    'classes' 		=> [ 'notice', 'wpfnl-notice' ], // We include WP's default notice class so it will be properly handled by WP's js handler
                    'type' 			=> 'install-plugin',
                    'dismissible' 	=> true,
                    'icon' 			=> '',
                    'slug'			=> $plugin,
                    'button' => array(
                        array(
							'classes' => [ 'wpfnl-notice-button', 'wpfnl-'.$action.'-plugin' ],
                            'icon' 			=> '',
                            'new_tab' 		=> false,
                            'text' 			=> $show_notice_info['action_label'],
                            'type' 			=> '',
                            'url' 			=> $show_notice_info['action_url'],
                            'variant' 		=> '',
                            'before' 		=> '',
							'data-slug'		=> 'elementor',
							'id'			=> 'wpfnl-'.$action.'-plugin'
                        )
                    ),
                    'button_secondary' => [],
                );
                self::print_notice($options);
            }
        }
    }


    /**
     * show plugin dependency notices for WooCommerce
     */
    public static function woocommerce_dependency_notice() {
        $settings = Wpfnl_functions::get_general_settings();

        $plugin = 'woocommerce/woocommerce.php';
        $show_notice_info = self::should_show_plugin_dependency_notice($plugin, 'woocommerce', "WooCommerce");
		$status = $show_notice_info['action'] === 'inactive' ? 'inactive' : 'not installed';
		$action = $show_notice_info['action'] === 'inactive' ? 'activate' : 'install';

		if($show_notice_info['show_notice']) {
            $options = array(
                'id' => 'woocommerce',
                'title' => 'WooCommerce',
                'description' => __("Opps.. Looks like WooCommerce is not activated on your website. WPFunnels requires WooCommerce to work properly. Please {$action} WooCommerce if you want to continue creating sales funnels using WPFunnels.", 'wpfnl'),
                'classes' => [ 'notice', 'wpfnl-notice' ], // We include WP's default notice class so it will be properly handled by WP's js handler
                'type' => 'install-plugin',
                'dismissible' => true,
                'icon' => '',
				'slug'			=> $plugin,
                'button' => array(
                    array(
						'classes' => [ 'wpfnl-notice-button', 'wpfnl-'.$action.'-plugin' ],
						'icon' 			=> '',
						'new_tab' 		=> false,
						'text' 			=> $show_notice_info['action_label'],
						'type' 			=> '',
						'url' 			=> $show_notice_info['action_url'],
						'variant' 		=> '',
						'before' 		=> '',
						'data-slug'		=> 'woocommerce',
						'id'			=> 'wpfnl-'.$action.'-plugin'
                    )
                ),
                'button_secondary' => [],
            );
            self::print_notice($options);
        }
    }


    public static function gutenberg_dependency_notice() {
		$settings = Wpfnl_functions::get_general_settings();
		if ($settings['builder']== 'gutenberg') {
			$plugin = 'qubely/qubely.php';
			$show_notice_info = self::should_show_plugin_dependency_notice( $plugin, 'qubely', "Qubely" );
			$status = $show_notice_info['action'] === 'inactive' ? 'inactive' : 'not installed';
			$action = $show_notice_info['action'] === 'inactive' ? 'activate' : 'install';

			if($show_notice_info['show_notice']) {
				$options = array(
					'id' => 'qubely',
					'title' => 'Qubely',
					'description' => __("It seems Qubely is {$status} on your site. As you have selected Gutenberg as builder option, you need to {$action} Qubely.", 'wpfnl'),
					'classes' => [ 'notice', 'wpfnl-notice' ], // We include WP's default notice class so it will be properly handled by WP's js handler
					'type' => $action.'-plugin',
					'dismissible' => true,
					'icon' => '',
					'slug'			=> $plugin,
					'button' => array(
						array(
							'classes' => [ 'wpfnl-notice-button', 'wpfnl-'.$action.'-plugin' ],
							'icon' => '',
							'new_tab' => false,
							'text' => $show_notice_info['action_label'],
							'type' => '',
							'url' => $show_notice_info['action_url'],
							'variant' => '',
							'before' => '',
						)
					),
					'button_secondary' => [],
				);
				self::print_notice($options);
			}
		}
	}


    /**
     * should show plugin dependency notices or not
     *
     * @param $plugin
     * @param $slug
     * @param $plugin_name
     * @return array
     * @since 2.0.0
     */
    public static function should_show_plugin_dependency_notice( $plugin, $slug, $plugin_name ) {
        $action_url = '';
        $show_notice = false;
        $action_label = '';
        $action = 'inactive';
        if ( Wpfnl_functions::is_plugin_installed($plugin) ) {
            if ( ! Wpfnl_functions::is_plugin_activated($plugin) ) {
                $url = sprintf('plugins.php?action=activate&plugin=%s&plugin_status=all&paged=1&s', $plugin);
                $action_url = wp_nonce_url($url, 'activate-plugin_' . $plugin);
                $show_notice = true;
                $action_label = __("Activate {$plugin_name}", 'wpfnl');
            }
        } else {
            $show_notice = true;
            $action = 'install-plugin';
            $action_url = wp_nonce_url(
                add_query_arg(
                    array(
                        'action' => $action,
                        'plugin' => $slug
                    ),
                    admin_url( 'update.php' )
                ),
                $action.'_'.$slug
            );
            $action_label = __("Install {$plugin_name}", 'wpfnl');
            $action = 'not-installed';
        }
        return array(
            'show_notice'   => $show_notice,
            'action_url'    => $action_url,
            'action_label'  => $action_label,
            'action'  		=> $action,
        );
    }

    /**
     * render html attributes
     *
     * @param array $attributes
     * @return string
     * @since 2.0.0
     */
    public static function render_html_attributes( array $attributes ) {

        $rendered_attributes = [];

        foreach ( $attributes as $attribute_key => $attribute_values ) {
            if ( is_array( $attribute_values ) ) {
                $attribute_values = implode( ' ', $attribute_values );
            }

            $rendered_attributes[] = sprintf( '%1$s="%2$s"', $attribute_key, esc_attr( $attribute_values ) );
        }

        return implode( ' ', $rendered_attributes );
    }


    /**
     * render button for admin notices
     *
     * @param $options
     */
    public static function print_button($options) {
        $default_options = [
            'classes' 	=> [ 'wpfnl-notice-button' ],
            'icon'	 	=> '',
            'new_tab' 	=> false,
            'text' 		=> '',
            'type' 		=> '',
            'url' 		=> '',
            'variant' 	=> '',
            'before' 	=> '',
			'id'		=> '',
			'data-slug' => '',
        ];

        $options = array_replace_recursive( $default_options, $options );

        if ( empty( $options['text'] ) ) {
            return;
        }

        $html_tag = ! empty( $options['url'] ) ? 'a' : 'button';
        $before = '';
        $icon = '';
        $attributes = [];

        if ( ! empty( $options['icon'] ) ) {
            $icon = '<i class="' . $options['icon'] . '"></i>';
        }

        $classes = $options['classes'];

        if ( ! empty( $options['type'] ) ) {
            $classes[] = 'wpfnl-button--' . $options['type'];
        }

        if ( ! empty( $options['url'] ) ) {
            $attributes['href'] = $options['url'];
            if ( $options['new_tab'] ) {
                $attributes['target'] = '_blank';
            }
        }
        $attributes['class'] 		= $classes;
        $attributes['id'] 			= $options['id'];
        $attributes['data-slug'] 	= $options['data-slug'];
        $attributes['href'] 		= '#';
        $html = $before . '<' . $html_tag . ' ' . self::render_html_attributes( $attributes ) . '>';
        $html .= $icon;
        $html .= '<span>' . sanitize_text_field( $options['text'] ) . '</span>';
        $html .= '<span class="notice-loader"></span>';
        $html .= '</' . $html_tag . '>';
        echo $html;
    }
}
