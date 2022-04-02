<?php

namespace WPFunnels\Modules\Admin\Funnels;

use WPFunnels\Admin\Module\Wpfnl_Admin_Module;
use WPFunnels\Data_Store\Wpfnl_Funnel_Store_Data;
use WPFunnels\Traits\SingletonTrait;

class Module extends Wpfnl_Admin_Module
{
    use SingletonTrait;


    /**
     * list of published funnels
     *
     * @var
     * @since 1.0.0
     */
    protected $funnels;


    /**
     * if needs to show pagination
     *
     * @var bool
     * @since 1.0.0
     */
    protected $pagination = false;


    /**
     * total number of funnels
     *
     * @var
     * @since 1.0.0
     */
    protected $total_funnels;


    /**
     * total number of pages
     *
     * @var
     * @since 1.0.0
     */
    protected $total_page = 1;


    /**
     * current page number
     *
     * @var
     * @since 1.0.0
     */
    protected $current_page = 1;


    /**
     * offset
     *
     * @var int
     * @since 1.0.0
     */
    protected $offset = 1;

    protected $utm_settings;


    /**
     * get view of the funnel list
     *
     * @since 1.0.0
     */
    public function get_view()
    {
        // TODO: Implement get_view() method.
        $this->current_page = isset($_GET['pageno']) ? sanitize_text_field($_GET['pageno']) : 1;
        $this->offset = ($this->current_page-1) * WPFNL_FUNNEL_PER_PAGE;

        $this->init_all_funnels();

        require_once WPFNL_DIR . '/admin/modules/funnels/views/view.php';
    }


    /**
     * get arguments for funnel
     * query
     *
     * @return array
     * @since 1.0.0
     */
    public function get_args()
    {
        $args = [
            'post_type'         => WPFNL_FUNNELS_POST_TYPE,
            'posts_per_page'    => WPFNL_FUNNEL_PER_PAGE,
            'offset'            => $this->offset,
            'post_status'       => array('publish', 'draft'),
        ];
        if (isset($_GET['s'])) {
            $args['s'] = sanitize_text_field($_GET['s']);

        }
        return $args;
    }


    /**
     * get all funnel list
     *
     * @param int $limit
     * @param int $offset
     * @since 1.0.0
     */
    public function init_all_funnels($limit = 10, $offset = 0)
    {
        $args = [
            'post_type'         => WPFNL_FUNNELS_POST_TYPE,
            'posts_per_page'    => -1,
			'post_status'       => array('publish', 'draft'),
            'suppress_filters'  => true,
            'fields'            => 'ids'
        ];
        if (isset($_GET['s'])) {
            $args['s'] = sanitize_text_field($_GET['s']);
        }
        $all_funnels = get_posts($args);
        $funnels = get_posts($this->get_args());
        $this->funnels = $this->get_formatted_funnel_array($funnels);

        $this->total_funnels = count($all_funnels) ? count($all_funnels) : 0;
        $this->pagination = count($this->funnels) ? true : false;
        if (count($this->funnels)) {
            $this->total_page = ceil($this->total_funnels / WPFNL_FUNNEL_PER_PAGE);
        }
        $this->utm_settings = $this->get_utm_settings();
    }


    /**
     * get all funnel list
     *
     * @return array
     * @since 1.0.0
     */
    public function get_formatted_funnel_array($funnels)
    {
        $_funnels = [];
        if ($funnels) {
            foreach ($funnels as $funnel) {
                $_funnel = new Wpfnl_Funnel_Store_Data();
                $_funnel->read($funnel->ID);
                $_funnel->set_data($funnel);
                $_funnels[] = $_funnel;
            }
        }
        return $_funnels;
    }

    public function init_ajax()
    {
        // TODO: Implement init_ajax() method.
    }

    public function get_name()
    {
        // TODO: Implement get_name() method.
        return 'funnels';
    }
    /**
     * Get GTM Settings
     * @return array
     */

    public function get_utm_settings() {
		$default_settings = array(
			'utm_enable'	=> 'off',
			'utm_source' 	=> '',
			'utm_medium' 	=> '',
			'utm_campaign' 	=> '',
			'utm_content' 	=> '',
		);
        $utm_settings = get_option('_wpfunnels_utm_params', $default_settings);
        return wp_parse_args($utm_settings, $default_settings);
    }
}
