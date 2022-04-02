<?php
/**
 * Template Name: WPFunnel - Canvas
 *
 * @package WPFunnels
 */

use WPFunnels\Wpfnl;
use WPFunnels\Wpfnl_functions;

if ( ! defined( 'ABSPATH' ) ) {
     exit; // Exit if accessed directly.
 }

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js wpfnl-template-canvas">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
		<?php
			if ( function_exists( 'wp_body_open' ) ) {
				wp_body_open();
			}
			do_action( 'wpfunnels/template_body_top' );
			$atts_string = Wpfnl_functions::get_template_container_atts();
		?>
		<div class="wpfnl-template-wrap wpfnl-template-container" <?php echo trim( $atts_string ); ?>>
			<?php do_action( 'wpfunnels/template_container_top' ); ?>
			<div class="wpfnl-primary" id="wpfnl-primary">
				<?php  Wpfnl::$instance->page_templates->print_content(); ?>
			</div>
			<?php do_action( 'wpfunnels/template_container_bottom' ); ?>
		</div>
		<?php do_action( 'wpfunnels/template_wp_footer' ); ?>
		<?php wp_footer(); ?>
	</body>
</html>
