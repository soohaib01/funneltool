<?php
    get_header();
    $id = get_the_id();
    $parent_id = get_post_meta($id, '_funnel_id', true);
    $step_type = get_post_meta($id, '_step_type', true);
    $order = get_post_meta($parent_id, '_steps_order', false);
    $order = $order[0];
    $step_class = new Wpfnl_Step_Template();
    $next_step_id = $step_class->get_next_step($order, $id);
    if ($next_step_id) {
        $next_step_link = get_page_link($next_step_id);
    }

    ?>
      <h1 style="text-align:center;"><?php echo get_the_title(); ?></h1>
      <p><?php echo 'Current Page '.$id; ?></p>
      <p><?php echo 'Current Page Type '.$step_type; ?></p>
      <?php
          if ($next_step_id) {
              ?>
              <p><?php echo 'Next Page ID '.$next_step_id; ?></p>
              <p><?php echo 'Next Page Link '.$next_step_link; ?></p>
              <a href="<?php echo $next_step_link; ?>">GO Next</a>
            <?php
            echo do_shortcode('[woocommerce_checkout]');
          } else {
              echo "This is probably the last page";
          }
      ?>
    <?php
    get_footer();
?>
