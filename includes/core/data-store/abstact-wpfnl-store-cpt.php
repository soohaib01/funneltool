<?php

namespace WPFunnels\Data_Store;

use WPFunnels\Exception\Wpfnl_Data_Exception;

abstract class Wpfnl_Abstract_Store_data implements Wpfnl_Data_Store
{
    protected $id;

    abstract public function set_data(\WP_Post $post);

    public function set_id($id)
    {
        // TODO: Implement set_id() method.
        $this->id = $id;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function get_meta($id, $key)
    {
        // TODO: Implement get_meta() method.
        return get_post_meta($id, $key, true);
    }

    public function update_meta($id, $key, $value)
    {
        if(is_array($value)) {
            if(count($value)) {
                update_post_meta($id, $key, $value);
            }else {
                delete_post_meta( $id, $key );
            }
        } elseif (!empty($value)) {
            update_post_meta($id, $key, $value);
        } else {
            delete_post_meta( $id, $key );
        }
    }

    public function delete_meta()
    {
        // TODO: Implement delete_meta() method.
    }


    public function read($id)
    {
        // TODO: Implement read() method.
    }


    /**
     * when invalid data is found exception will
     * be thrown without reading from DB
     *
     * @param $code
     * @param $message
     * @param int $http_status_code
     * @param array $data
     * @throws Wpfnl_Data_Exception
     * @since 1.0.0
     */
    protected function error($code, $message, $http_status_code = 400, $data = [])
    {
        throw new Wpfnl_Data_Exception($code, $message, $http_status_code, $data);
    }
}
