<?php

namespace WPFunnels\Data_Store;

interface Wpfnl_Data_Store
{
    public function set_id($id);

    public function create();

    public function read($id);

    public function delete($id);

    public function get_meta($id, $key);

    public function update_meta($id, $key, $value);

    public function delete_meta();
}
