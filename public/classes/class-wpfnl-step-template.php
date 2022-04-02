<?php

/**
 * The public-facing template functionality of the plugin.
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Wpfnl
 * @subpackage Wpfnl/public
 */

 class Wpfnl_Step_Template
 {
     public function init()
     {
         add_filter('template_include', [ $this, 'wpfnl_include_step_template' ]);
     }

     public function wpfnl_include_step_template($template)
     {
         //try and get the query var we registered in our query_vars() function
         $step_page = get_query_var('funnel_step');

         //if the query var has data, we must be on the right page, load our custom template
         if ($step_page) {
             return plugin_dir_path(__FILE__) . 'template/step_view.php';
         }

         return $template;
     }

     public function flush_rules()
     {
         $this->rewrite_rules();

         flush_rewrite_rules();
     }

     public function rewrite_rules()
     {
         add_rewrite_rule('funnel/(.+?)/?$', 'index.php?funnel_step=$matches[1]', 'top');
         add_rewrite_tag('%funnel_step%', '([^&]+)');
     }

     public function get_next_step($order, $current)
     {
         $current_key = array_search($current, array_column($order, 'id'));
         if (isset($order[$current_key + 1])) {
             $next_key = $order[$current_key + 1];
             $next_id = $next_key['id'];
             return $next_id;
         } else {
             return false;
         }
     }
 }
