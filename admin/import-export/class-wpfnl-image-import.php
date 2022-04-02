<?php

namespace WPFunnels\Importer\Image;

use WP_Http;

class Wpfnl_Image_Importer {

    private static $instance;

    public static function get_instance() {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * import images
     *
     * @param $image
     * @return array|bool
     * @since 1.0.0
     */
    public function import($image) {
        if( !class_exists( 'WP_Http' ) )
            include_once( ABSPATH . WPINC . '/class-http.php' );

        $http = new WP_Http();
        $response = $http->request( $image['url'] );
        if( $response['response']['code'] != 200 ) {
            return false;
        }

        $upload = wp_upload_bits( basename($image['url']), null, $response['body'] );
        if( !empty( $upload['error'] ) ) {
            return false;
        }

        $file_path = $upload['file'];
        $file_name = basename( $file_path );
        $file_type = wp_check_filetype( $file_name, null );
        $attachment_title = sanitize_file_name( pathinfo( $file_name, PATHINFO_FILENAME ) );
        $wp_upload_dir = wp_upload_dir();

        $post_info = array(
            'guid'           => $wp_upload_dir['url'] . '/' . $file_name,
            'post_mime_type' => $file_type['type'],
            'post_title'     => $attachment_title,
            'post_content'   => '',
            'post_status'    => 'inherit',
        );

        // Create the attachment
        $attachment_id = wp_insert_attachment( $post_info, $file_path );

        // Include image.php
        require_once( ABSPATH . 'wp-admin/includes/image.php' );

        // Define attachment metadata
        $attach_data = wp_generate_attachment_metadata( $attachment_id, $file_path );

        // Assign metadata to attachment
        wp_update_attachment_metadata( $attachment_id,  $attach_data );
        return array(
            'id'  => $attachment_id,
            'url' => $upload['url'],
        );
    }
}
