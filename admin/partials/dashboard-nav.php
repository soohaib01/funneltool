<?php

use WPFunnels\Wpfnl_functions;

$nav_menus = apply_filters('wpfnl_dashboard_nav_lists', [
        'overview' => [
            'title' => __('Overview', 'wpfnl'),
            'icon'  => 'overview-icon',
            'page'  => WPFNL_MAIN_PAGE_SLUG,
        ],
        // 'create_funnel' => [
        //     'title' => __('Create Funnel', 'wpfnl'),
        //     'icon'  => 'funnel-icon',
        //     'page'  => WPFNL_CREATE_FUNNEL_SLUG,
        // ],
        // 'report' => [
        //     'title' => __('Report', 'wpfnl'),
        //     'icon'  => 'graph-icon',
        //     'page'  => WPFNL_MAIN_PAGE_SLUG,
        // ],
        'settings' => [
            'title' => __('Settings', 'wpfnl'),
            'icon'  => 'settings-icon-2x',
            'page'  => WPFNL_GLOBAL_SETTINGS_SLUG,
        ],
        // 'documentation' => [
        //     'title' => __('Documentation', 'wpfnl'),
        //     'icon'  => 'doc-icon',
        //     'page'  => WPFNL_MAIN_PAGE_SLUG,
        // ],
    ]);

?>

<ul class="wpfnl-dashboard__nav-ul">
    <li class="logo">
        <?php require_once WPFNL_DIR . '/admin/partials/icons/logo.php'; ?>
    </li>

    <?php
        foreach ($nav_menus as $key => $menu) {
            $is_active = Wpfnl_functions::define_active_class($key);
            $active_class = $is_active ? 'active' : '';
            $link = add_query_arg(
                [
                    'page' => $menu['page'],
                ],
                admin_url('admin.php')
            );
            if($key === 'create_funnel') $link='#';
            ?>

            <li class="wpfnl-dashboard__nav-li <?php echo $key .' '. $active_class; ?>">
                <a id="<?php echo "wpfnl-tab-".$key ?>" href="<?php echo esc_url($link); ?>">
                    <?php include WPFNL_DIR . '/admin/partials/icons/'.$menu['icon'].'.php'; ?>
                    <?php echo $menu['title']; ?>
                </a>
            </li>
        <?php
        }
    ?>
</ul>
