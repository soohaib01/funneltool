<?php

/**
 * Fired during plugin activation
 *
 * @link       https://rextheme.com
 * @since      1.0.0
 *
 * @package    Wpfnl
 * @subpackage Wpfnl/includes
 */

use WPFunnels\Wpfnl;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wpfnl
 * @subpackage Wpfnl/includes
 * @author     RexTheme <support@rextheme.com>
 */
class Wpfnl_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        self::set_wpfunnels_activation_transients();
        self::update_wpfunnles_version();
        self::update_wpfunnels_db_version();
        self::update_installed_time();
    }

    /**
     * Update WP Funnels version to current.
     *
     * @since 1.0.0
     */
    private static function update_wpfunnles_version()
    {
        update_site_option('wpfunnels_version', Wpfnl::get_instance()->get_version());
    }


    /**
     * See if we need to redirect the admin to setup wizard or not.
     *
     * @since 1.0.0
     */
    private static function set_wpfunnels_activation_transients()
    {
        if (self::is_new_install()) {
            set_transient('_wpfunnels_activation_redirect', 1, 30);
        }
    }

    /**
     * brand new install of wpfunnels
     *
     * @return bool
     * @since 1.0.0
     */
    public static function is_new_install()
    {
        return is_null(get_site_option('wpfunnels_version', null));
    }

    /**
     * Update db version to current
     *
     * @param null $version
     * @since 1.0.0
     */
    private static function update_wpfunnels_db_version($version = null)
    {
		update_site_option('wpfunnels_db_version', is_null($version) ? Wpfnl::get_instance()->get_version() : $version);
    }


    /**
     * Retrieve the time when funnel is installed
     *
     * @return int|mixed|void
     * @since 2.0.0
     */
    public static function get_installed_time() {
        $installed_time = get_option( 'wpfunnels_installed_time' );
        if ( ! $installed_time ) {
            $installed_time = time();
			update_site_option( 'wpfunnels_installed_time', $installed_time );
        }
        return $installed_time;
    }


    public static function update_installed_time() {
        self::get_installed_time();
    }
}
