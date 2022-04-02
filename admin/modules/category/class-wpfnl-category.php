<?php

namespace WPFunnels\Modules\Admin\Category;

use WPFunnels\Admin\Module\Wpfnl_Admin_Module;
use WPFunnels\Traits\SingletonTrait;
use WPFunnels\Wpfnl_functions;
class Module extends Wpfnl_Admin_Module
{
    use SingletonTrait;

    public function init_ajax()
    {
        // TODO: Implement init_ajax() method.
        add_action('wp_ajax_wpfnl_category_search', [ $this, 'fetch_categories' ]);
    }

	public function get_name()
	{
		// TODO: Implement get_name() method.
		return 'category';

	}

	public function get_view()
	{
		// TODO: Implement get_view() method.
	}

    /**
     * category search by name
     */
    public function fetch_categories(){
		check_ajax_referer('wpfnl-admin', 'security');
		if (isset($_GET['term'])) {
			$cat_name = (string) sanitize_text_field( wp_unslash($_GET['term']) );
		}
		if (empty($cat_name)) {
			wp_die();
		}
        global $wpdb;
        $cat_Args= "SELECT * FROM $wpdb->terms as t INNER JOIN $wpdb->term_taxonomy as tx ON t.term_id = tx.term_id WHERE tx.taxonomy = 'product_cat' AND t.name LIKE '%".$cat_name."%' ";
        $category = $wpdb->get_results($cat_Args, OBJECT);
        if(empty($category)){
            $data = [
                'status'  => 'success',
                'data'    => 'Category not found',
            ];
        }else{
            $data = [
                'status'  => 'success',
                'data'    => $category,
            ];
        }
        wp_send_json($data);

    }
}
