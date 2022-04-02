<?php
$products = $this->get_products();

$type = '';
if (isset($_GET['step_id'])) {
  $step_id = sanitize_text_field( $_GET['step_id'] );
  $type = get_post_meta( $step_id, '_step_type', true );
}
?>
<div class="single-product">
    <?php require WPFNL_DIR . '/admin/partials/icons/search-icon.php'; ?>
    <select class="wpfnl-product-search" id="wpfnl-checkout-products" name="wpfnl_step_product_ids" data-placeholder="<?php esc_attr_e('Search for products', 'wpfnl'); ?>">
        <?php
          if ($type == 'upsell' || $type == 'downsell') {
            if ($products[0]) {
              ?>
                  <option value="<?php echo $products[0]['id']; ?>" selected><?php echo $products[0]['name']; ?></option>
              <?php
            }
          }
        ?>
    </select>
</div>
