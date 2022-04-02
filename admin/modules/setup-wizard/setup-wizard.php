<?php

namespace WPFunnels\Admin;

use WPFunnels\Traits\SingletonTrait;

class SetupWizard
{
    use SingletonTrait;


    public $step_name;

    public $steps;

    public function __construct()
    {
        $this->setup_wizard();
    }

    /**
     * initialize setup wizards
     *
     * @since 1.0.0
     */
    private function setup_wizard()
    {

        $steps = array(
            'type' => array(
                'name'      => 'Funnel Type',
                'slug'      => 'funnel-type',
                'icon'      => WPFNL_URL . 'admin/assets/images/funnel-type.svg',
                'iconActive'=> WPFNL_URL . 'admin/assets/images/funnel-type-active.svg',
                'iconCompleted'=> WPFNL_URL . 'admin/assets/images/funnel-type-completed.svg',
                'completed' => false,
                'isActive'  => false,
            ),
            'builder' => array(
                'name'      => 'Builder Type',
                'slug'      => 'builder-type',
                'icon'      => WPFNL_URL . 'admin/assets/images/builder.svg',
                'iconActive'=> WPFNL_URL . 'admin/assets/images/builder-active.svg',
                'iconCompleted'=> WPFNL_URL . 'admin/assets/images/builder-completed.svg',
                'completed' => false,
                'isActive'  => false,
            ),
            'permalink' => array(
                'name'      => 'Permalink',
                'slug'      => 'permalink',
                'icon'      => WPFNL_URL . 'admin/assets/images/permalink.svg',
                'iconActive'=> WPFNL_URL . 'admin/assets/images/permalink-active.svg',
                'iconCompleted'=> WPFNL_URL . 'admin/assets/images/permalink-completed.svg',
                'completed' => false,
                'isActive'  => false,
            ),
            'thankyou' => array(
                'name'      => 'Thank You',
                'slug'      => 'thankyou',
                'icon'      => WPFNL_URL . 'admin/assets/images/thankyou.svg',
                'iconActive'=> WPFNL_URL . 'admin/assets/images/thankyou-active.svg',
                'iconCompleted'=> WPFNL_URL . 'admin/assets/images/thankyou-completed.svg',
                'completed' => false,
                'isActive'  => false,
            ),
        );
        $this->step_name = isset( $_GET['step'] ) ? sanitize_text_field( $_GET['step'] ) : current( array_keys( $steps ) );
        foreach ( $steps as $key => $step ) {
            if( $key === $this->step_name ) {
                $step['isActive'] = true;
                $this->steps[$key] = $step;
            } else {
                $step['completed'] = array_search($this->step_name,array_keys($steps)) > array_search($key,array_keys($steps)) ;
                $this->steps[$key] = $step;
            }
        }
        $installed_plugins = get_plugins();

        wp_enqueue_style('setup-wizard', WPFNL_URL . 'admin/assets/css/wpfnl-admin.css', false, '1.1', 'all');
        wp_enqueue_script('setup-wizard', WPFNL_URL . 'admin/assets/dist/js/setup-wizard.min.js', array('jquery', 'wp-util', 'updates'), time(), true);
        wp_localize_script('setup-wizard', 'setup_wizard_obj',
            array(
                'rest_api_url'          => esc_url_raw(get_rest_url()),
                'dashboard_url'         => esc_url_raw(admin_url('admin.php?page=' . WPFNL_MAIN_PAGE_SLUG)),
                'settings_url'          => class_exists( 'WooCommerce' ) ? esc_url_raw(admin_url('admin.php?page=wpfnl_settings')) : esc_url_raw(admin_url()),
                'wizard_url'            => esc_url_raw(admin_url('admin.php?page=wpfunnels-setup')),
                'home_url'              => esc_url_raw(home_url()),
                'nonce'                 => wp_create_nonce('wp_rest'),
                'current_step'          => $this->step_name,
                'steps'                 => $this->steps,
                'next_step_link'        => $this->get_next_step_link(),
                'prev_step_link'        => $this->get_prev_step_link(),
                'is_woo_installed'      => isset( $installed_plugins['woocommerce/woocommerce.php'] ) ? 'yes' : 'no',
                'is_elementor_installed'=> isset( $installed_plugins['elementor/elementor.php'] ) ? 'yes' : 'no',
                'is_ff_installed'       => isset( $installed_plugins['fluentform/fluentform.php'] ) ? 'yes' : 'no',
                'is_cl_installed'       => isset( $installed_plugins['cart-lift/cart-lift.php'] ) ? 'yes' : 'no',
				'is_qb_installed'       => isset( $installed_plugins['qubely/qubely.php'] ) ? 'yes' : 'no',
				'is_woo_active'         => is_plugin_active( 'woocommerce/woocommerce.php' ) ? 'yes' : 'no',
                'is_elementor_active'   => is_plugin_active( 'elementor/elementor.php' ) ? 'yes' : 'no',
                'is_ff_active'          => is_plugin_active( 'fluentform/fluentform.php' ) ? 'yes' : 'no',
                'is_cl_active'          => is_plugin_active( 'cart-lift/cart-lift.php' ) ? 'yes' : 'no',
                'is_qb_active'          => is_plugin_active( 'qubely/qubely.php' ) ? 'yes' : 'no',
            )
        );
        $this->output_html();
    }




    /**
     * get next step link
     *
     * @return string|void
     * @since 1.0.0
     */
    private function get_next_step_link() {
        $keys       = array_keys( $this->steps );
        $step_index = array_search( $this->step_name, $keys, true );
        $step_index = ( count( $keys ) == $step_index + 1 ) ? $step_index : $step_index + 1;
        $step       = $keys[ $step_index ];
        return admin_url( 'admin.php?page=wpfunnels-setup&step=' . $step );
    }


    /**
     * get prev step link
     *
     * @return string|void
     * @since 1.0.0
     */
    private function get_prev_step_link() {
        $keys       = array_keys( $this->steps );
        $step = '';
        $step_index = array_search( $this->step_name, $keys, true );
        $step_index = ( count( $keys ) == $step_index - 1 ) ? $step_index : $step_index - 1;
        if (isset($keys[ $step_index ])) {
            $step       = $keys[ $step_index ];
        }

        return admin_url( 'admin.php?page=wpfunnels-setup&step=' . $step );
    }


    /**
     * output the rendered contents
     *
     * @since 1.0.0
     */
    private function output_html()
    {
        require_once plugin_dir_path(__FILE__) . 'views/views.php';
        exit();
    }
}
