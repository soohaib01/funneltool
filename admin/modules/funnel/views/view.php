<?php
$show_settings = filter_input(INPUT_GET, 'show_settings', FILTER_SANITIZE_STRING);

if ($show_settings == 1) {
    $back_link = add_query_arg(
        [
            'page'      => WPFNL_EDIT_FUNNEL_SLUG,
            'id'        => $this->funnel->get_id(),
            'step_id'   => $this->step_module->get_id(),
        ],
        admin_url('admin.php')
    );
} else {
    $back_link = add_query_arg(
        [
            'page'      => WPFNL_MAIN_PAGE_SLUG,
        ],
        admin_url('admin.php')
    );
}



$active_funnel_id = $this->funnel->get_id();
$step_names = apply_filters('wpfunnels_steps', [
    'landing'       => __('Landing', 'wpfnl'),
    'thankyou'      => __('Thank You', 'wpfnl'),
    'checkout'      => __('Checkout', 'wpfnl'),
    'upsell'        => __('Upsell', 'wpfnl'),
    'downsell'      => __('Downsell', 'wpfnl'),
]);

?>

<div id="wpfnl-root-extra" class="wpfnl"></div>
<div id="wpfnl-root" class="wpfnl"></div>
<!-- /.wpfnl -->
