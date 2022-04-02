<?php
/**
 * Template Name: WPFunnel - Checkout
 *
 * @package WPFunnels
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

get_header();
$checkout = WC()->checkout();

?>
<body <?php body_class('wpfunnels-checkout-template'); ?>>
    <?php \WPFunnels\PageTemplates\Manager::body_open();  ?>
    <div id="content" class="site-content">
        <div class="wpfunnels-checkout-containers woocommerce-page" style="max-width: 1200px; margin: 0 auto; ">
              <?php WPFunnels\Wpfnl::$instance->page_templates->print_content(); ?>
              <?php get_footer(); ?>
        </div>
    </div>
</body>
