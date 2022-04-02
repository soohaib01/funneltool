<?php

namespace WPFunnels\TemplateLibrary;

use WPFunnels\Wpfnl;

class Manager
{

    /**
     * register funnels sources
     *
     * @var array
     */
    protected $_registered_sources = [];

    /**
     * ajax validation args
     *
     * @var Validation
     */
    protected $validations;


    /**
     * view of the template-library module
     *
     * @var View
     */
    protected $view;


    /**
     * Manager constructor.
     * initializing the template library manger with
     * ajax registering sources and ajax hooks
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->add_actions();
        $this->init_ajax();
        $this->register_default_sources();
    }


    /**
     * initialize all ajax actions for template-library
     *
     * @since 1.0.0
     */
    public function init_ajax()
    {
        $this->validations = [
            'logged_in' => true,
            'user_can' => 'manage_options',
        ];
        wp_ajax_helper()->handle('wpfunnel-get-templates-data')
            ->with_callback([$this, 'get_funnel_templates'])
            ->with_validation($this->validations);

        wp_ajax_helper()->handle('wpfunnel-import-funnel')
            ->with_callback([$this, 'import_funnel'])
            ->with_validation($this->validations);

        wp_ajax_helper()->handle('wpfunnel-import-step')
            ->with_callback([$this, 'import_step'])
            ->with_validation($this->validations);

        wp_ajax_helper()->handle('wpfunnel-after-funnel-creation')
            ->with_callback([$this, 'after_funnel_creation'])
            ->with_validation($this->validations);

        wp_ajax_helper()->handle('wpfunnel-get-step-templates-data')
            ->with_callback([$this, 'get_step_templates'])
            ->with_validation($this->validations);

        wp_ajax_helper()->handle('wpfunnel-after-step-creation')
            ->with_callback([$this, 'after_step_creation'])
            ->with_validation($this->validations);

        wp_ajax_helper()->handle('wpfunnels-activate-plugin')
            ->with_callback([$this, 'activate_plugin'])
            ->with_validation($this->validations);
    }


    /**
     * add view for template-library
     *
     * @since 1.0.0
     */
    public function add_actions()
    {
        add_action('admin_footer', [$this, 'add_default_view']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function enqueue_scripts($hook)
    {
        if (in_array($hook, ['toplevel_page_wp_funnels'])) {
            $funnel_id = 0;
            if (isset($_GET['page'])) {
                if ($_GET['page'] === 'edit_funnel') {
                    if (isset($_GET['id'])) {
                        $funnel_id = sanitize_text_field($_GET['id']);
                    }
                }
            }
            wp_enqueue_script('funnel-template-library', WPFNL_URL . 'admin/assets/dist/js/template-library.min.js', ['jquery', 'wp-util'], '1.0.0', true);
        }
    }


    /**
     * register template source for import/export
     *
     * @since 1.0.0
     */
    private function register_default_sources()
    {
        $sources = [
            'local',
            'remote',
        ];
        foreach ($sources as $source_filename) {
            $class_name = ucwords($source_filename);
            $class_name = str_replace('-', '_', $class_name);
            $this->register_source(__NAMESPACE__ . '\Wpfnl_Source_' . $class_name);
        }
    }


    /**
     * @param $source_class
     * @param array $args
     * @return bool|\WP_Error
     * @since 1.0.0
     */
    public function register_source($source_class, $args = [])
    {
        if (!class_exists($source_class)) {
            return new \WP_Error('source_class_name_not_exists');
        }
        $source_instance = new $source_class($args);
        $source_id = $source_instance->get_source();

        if (isset($this->_registered_sources[$source_id])) {
            return new \WP_Error('source_exists');
        }
        $this->_registered_sources[$source_id] = $source_instance;
        return true;
    }


    /**
     * get all registered sources
     *
     * @return array
     * @since 1.0.0
     */
    public function get_registered_sources()
    {
        return $this->_registered_sources;
    }


    /**
     * get template source by source name.
     * e.g: remote
     *
     * @param $id
     * @return false|Wpfnl_Source_Base
     * @since 1.0.0
     */
    public function get_source($id)
    {
        $sources = $this->get_registered_sources();
        if (!isset($sources[$id])) {
            return false;
        }
        return $sources[$id];
    }


    /**
     * get step templates
     *
     * @param $payload
     * @return array
     */
    public function get_step_templates($payload)
    {
        $template_data = $this->get_source('remote')->get_funnels();
        return [
            'success' => true,
            'data' => $template_data,
        ];
    }

    /**
     * get funnel templates for viewing
     *
     * @return array
     * @since 1.0.0
     */
    public function get_funnel_templates()
    {
        $template_data = $this->get_source('remote')->get_funnels();
        return [
            'success' => true,
            'data' => $template_data,
        ];
    }

    /**
     * add default view for template library
     *
     * @since 1.0.0
     */
    public function add_default_view()
    {
        $default_view = [
            'admin/template-library/view/template-library.php',
        ];
        foreach ($default_view as $view) {
            $this->add_view(WPFNL_DIR . $view);
        }
        $this->print_template();
    }


    /**
     * add view
     *
     * @param $view
     * @param string $type
     * @since 1.0.0
     */
    public function add_view($view, $type = 'path')
    {
        if ('path' === $type) {
            ob_start();
            if (file_exists($view)) {
                include $view;
            }
            $this->view = ob_get_clean();
        }
    }

    /**
     *
     * @since 1.0.0
     */
    public function print_template()
    {
        echo $this->view;
    }


    /**
     * import templates from
     * remote server
     *
     * @param $payload
     * @return array
     * @since 1.0.0
     */
    public function import_funnel($payload)
    {
        $source = $this->get_source($payload['source']);
        return $source->import_funnel($payload);
    }


    /**
     * import wp funnel steps
     * from remote servers
     *
     * @param $payload
     * @return array
     * @since 1.0.0
     */
    public function import_step($payload)
    {
        $source = $this->get_source($payload['source']);
        return $source->import_step($payload);
    }


    /**
     * after funnel import
     * redirect to new funnel edit url
     *
     * @param $payload
     * @return array
     * @since 1.0.0
     */
    public function after_funnel_creation($payload)
    {
        $source = $this->get_source($payload['source']);
        return $source->after_funnel_creation($payload);
    }

    /**
     * after funnel import
     * redirect to new funnel edit url
     *
     * @param $payload
     * @return array
     * @since 1.0.0
     */
    public function after_step_creation($payload)
    {
        $funnel_id = $payload['funnelID'];
        $step_id = $payload['stepID'];
        $step = get_post($step_id);
        $step_type = get_post_meta($step_id, '_step_type', true);
        $step_name = $step->post_title;
        $step_component = $step_type . $step_id;
        $node_id = 0;
        $step_edit_link = base64_encode(get_edit_post_link($step_id));
        $step_view_link = base64_encode(get_post_permalink($step_id));

        $funnel_json = get_post_meta($funnel_id, 'funnel_data', true);
        $funnel_data = $funnel_json;

        $node_data = $funnel_data['drawflow']['Home']['data'];

        foreach ($node_data as $node_key => $node_value) {
            $node_id = $node_value['id'] + 1;
        }

        $step_data = array(
            'step_id' => $step_id,
            'step_type' => $step_type,
            'step_edit_link' => $step_edit_link,
            'step_view_link' => $step_view_link,
        );
        $step_array = array(
            'id' => $node_id,
            'name' => $step_type,
            'data' => $step_data,
            'class' => $step_type,
            'html' => $step_component,
            'step_name' => $step_name,
            'typenode' => 'vue',
            'inputs' => $this->get_connector_input($step_type),
            'outputs' => $this->get_connector_output($step_type),
            'pos_x' => 100,
            'pos_y' => 100,
        );

        $funnel_data['drawflow']['Home']['data'][] = $step_array;

        $final_data = json_encode($funnel_data);

        $identifier_json = get_post_meta($funnel_id, 'funnel_identifier', true);
        $identifier_json = preg_replace('/\: *([0-9]+\.?[0-9e+\-]*)/', ':"\\1"', $identifier_json);
        $identifier = json_decode($identifier_json, true);
        $identifier[$node_id] = $step_id;
        $final_identifier = json_encode($identifier);

        update_post_meta($funnel_id, 'funnel_data', $funnel_data);
        update_post_meta($funnel_id, 'funnel_identifier', $final_identifier);

        $source = $this->get_source($payload['source']);
        return $source->after_step_creation($payload);
    }

    public function get_connector_input($type)
    {
        if ($type == 'landing') {
            $input = array();
        } elseif ($type == 'checkout') {
            $input = array(
                "input_1" => array(
                    "connections" => array(),
                ),
            );
        } else {
            $input = array(
                "input_1" => array(
                    "connections" => array(),
                ),
            );
        }
        return $input;
    }

    public function get_connector_output($type)
    {
        if ($type == 'landing') {
            $output = array(
                "output_1" => array(
                    "connections" => array(),
                ),
            );
        } elseif ($type == 'checkout') {
            $output = array(
                "output_1" => array(
                    "connections" => array(),
                ),
            );
        } else {
            $output = array();
        }
        return $output;
    }


    /**
     * activate plugin with ajax call
     *
     * @param $payload
     * @since 2.0.0
     */
    public function activate_plugin( $payload ) {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array(
                'message'   => __('Sorry you are not allowed to do this operation', 'wpfnl'),
            ) );
        }
        \wp_clean_plugins_cache();
        $plugin_file = ( isset( $payload['pluginFile'] ) ) ? esc_attr( $payload['pluginFile'] ) : '';
        $activate = \activate_plugin( $plugin_file, '', false, true );

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
                'message'   => __('Plugin is installed successfully', 'wpfnl'),
            )
        );
    }
}
