<?php

namespace WPFunnels\Importer;

use WPFunnels\Importer\Image\Wpfnl_Image_Importer;
use WPFunnels\Wpfnl;

class Wpfnl_Importer_Helper {

    private static $instance;

    public static function get_instance() {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * get post contents
     *
     * @param $step_id
     * @return string|string[]
     * @since 1.0.0
     */
    public function get_post_contents($step_id) {
        // get the post contents
        $content = get_post_field( 'post_content', $step_id );

        $content = stripslashes( $content );

        // get all links from $content
        $links = wp_extract_urls( $content );

        if ( empty( $links ) ) {
            return $content;
        }

        $normal_links = [];
        $image_links = [];
        $mapping_array = [];

        // Step 1: store image link and normal links
        foreach ( $links as $key => $link ) {
            if ( preg_match( '/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*\.(?:jpg|png|gif|jpeg)/i', $link ) ) {
                $image_links[] = $link;
            } else {
                $normal_links = $link;
            }
        }

        // Step 2: save image to the site
        if ( ! empty( $image_links ) ) {
            foreach ( $image_links as $key => $image_url ) {
                $image            = array(
                    'url' => $image_url,
                    'id'  => 0,
                );
                $saved_image = Wpfnl_Image_Importer::get_instance()->import($image);

                if($saved_image) {
                    $mapping_array[] = array(
                        'old'   => $image_url,
                        'new'   => $saved_image['url'],
                    );
                }
            }
        }

        // Step 3: replace image url with new one
        foreach ( $mapping_array as $key => $mapping ) {
            $content = str_replace( $mapping['old'], $mapping['new'], $content );
        }
        return $content;
    }
}
