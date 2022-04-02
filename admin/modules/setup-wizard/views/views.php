<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?> >
<head>
    <meta name="viewport" content="width=device-width"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php esc_html_e('WPFunnels - Setup Wizard', 'wpfnl'); ?></title>
    <?php do_action('admin_print_styles'); ?>
    <?php do_action('admin_head'); ?>
    <script type="text/javascript">
        addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
        var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>';
        var pagenow = '';
    </script>
</head>
<body class="wpfunnels-setup wp-core-ui">
    <div id="wpfunnels_setup_wizard"></div>
    <?php
        wp_enqueue_media(); // add media
        wp_print_scripts(); // window.wp
        do_action('admin_footer');
    ?>
</body>
</html>
