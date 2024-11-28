<?php
class Tracks_Order {
    public function __construct() {
        add_action('woocommerce_checkout_create_order_line_item', [$this, 'add_tracks_to_order'], 10, 4);
        add_action('woocommerce_cart_calculate_fees', [$this, 'adjust_shipping_cost']);
    }

    public function add_tracks_to_order($item, $cart_item_key, $values, $order) {
        $product_id = $values['product_id'];
        $max_tracks = get_post_meta($product_id, '_max_tracks_quantity', true);

        if ($max_tracks) {
            $quantity = $values['quantity'];
            $tracks = ceil($quantity / $max_tracks);
            $item->update_meta_data('_tracks_quantity', $tracks);
        }
    }

    public function adjust_shipping_cost($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;

    $total_tracks = 0;

    foreach (WC()->cart->get_cart() as $cart_item) {
        $product_id = $cart_item['product_id'];
        
        $max_tracks = (int) get_post_meta($product_id, '_max_tracks_quantity', true);

        if ($max_tracks <= 0) {
            $max_tracks = 1; 
        }

        $quantity = $cart_item['quantity'];

        $tracks = ceil($quantity / $max_tracks);
        $total_tracks += $tracks;
    }

    if ($total_tracks > 0) {
        $packages = WC()->shipping->get_packages();

        foreach ($packages as $package_key => $package) {
            if (!empty($package['rates'])) {
                foreach ($package['rates'] as $rate_id => $rate) {
                    $new_cost = $rate->cost * $total_tracks;
                }

                WC()->cart->add_fee(__('Shipping Adjustment', 'woocommerce-tracks'), $new_cost);
            }
        }
    }
}

}
