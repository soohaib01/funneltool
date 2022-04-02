<?php
/**
 * Template Name: WPFunnel - Boxed
 *
 * @package WPFunnels
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
get_header();

?>
<body <?php body_class('wpfunnels-boxed-template'); ?>>
<?php
    \WPFunnels\PageTemplates\Manager::body_open();
    do_action( 'wpfunnels/page_templates/boxed/before_content' );
    echo '<div class="wpfunnels-container">';
    WPFunnels\Wpfnl::$instance->page_templates->print_content();
    echo '</div>';
    do_action( 'wpfunnels/page_templates/boxed/after_content' );
?>
<?php get_footer(); ?>
</body>
