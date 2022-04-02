<?php
/**
 * Template Name: WPFunnel - Checkout
 *
 * @package WPFunnels
 */

 if ( ! defined( 'ABSPATH' ) ) {
     exit; // Exit if accessed directly.
 }
 get_header();
 ?>
 <body <?php body_class('wpfunnels-fullwidth-with-header-footer-template'); ?>>
 <?php
     \WPFunnels\PageTemplates\Manager::body_open();
     do_action( 'wpfunnels/page_templates/default/before_content' );
     echo '<div class="wpfunnels-fullwidth-container" style="width:100%;">';
     WPFunnels\Wpfnl::$instance->page_templates->print_content();
     echo '</div>';
     do_action( 'wpfunnels/page_templates/default/after_content' );
 ?>
 <?php get_footer(); ?>
 </body>
