<?php
class Tracks_Order
{
    public function __construct()
    {
        add_action('woocommerce_checkout_create_order_line_item', [$this, 'add_tracks_to_order'], 10, 4);
        add_filter('woocommerce_package_rates', [$this, 'adjust_flat_rate_cost'], 10, 2);
        add_action('woocommerce_order_before_calculate_totals', [$this, 'recalculate_tracks_shipping'], 10, 2);
    }

    public function recalculate_tracks_shipping($and_taxes, $order)
    {
        $total_tracks = 0;

        foreach ($order->get_items() as $item) {
            $tracks = $item->get_meta('_tracks_quantity');
            $total_tracks += (int)$tracks;
        }

        foreach ($order->get_shipping_methods() as $shipping_method) {
            $shipping_method_id = $shipping_method->get_method_id();
            $base_cost = $shipping_method->get_meta('base_cost');
            $tracks_fee = $base_cost * ($total_tracks - 1);

            $new_label = sprintf(
                '%s (Base: %s + Tracks Fee: %s)',
                $shipping_method_id,
                wc_price($base_cost),
                wc_price($tracks_fee)
            );

            $shipping_method->set_method_title($new_label);
            $shipping_method->set_total($base_cost + $tracks_fee);
            $shipping_method->update_meta_data('tracks_fee', $tracks_fee);
            $shipping_method->save();
        }
    }

    public function add_tracks_to_order($item, $cart_item_key, $values, $order)
    {
        $product_id = $values['product_id'];
        $variation_id = isset($values['variation_id']) ? $values['variation_id'] : null;

        $max_tracks = 0;

        if ($variation_id) {
            $max_tracks = get_post_meta($variation_id, '_variation_max_tracks_quantity', true);
        }

        if (!$max_tracks) {
            $max_tracks = get_post_meta($product_id, '_max_tracks_quantity', true);
        }

        if ($max_tracks) {
            $quantity = $values['quantity'];
            $tracks = ceil($quantity / $max_tracks);
            $item->update_meta_data('_tracks_quantity', $tracks);
        }
    }

    public function adjust_flat_rate_cost($rates, $package)
    {
        $total_tracks = 0;

        foreach (WC()->cart->get_cart() as $cart_item) {
            $product_id = $cart_item['product_id'];
            $variation_id = isset($cart_item['variation_id']) ? $cart_item['variation_id'] : null;
            $max_tracks = 0;

            if ($variation_id) {
                $max_tracks = (int) get_post_meta($variation_id, '_variation_max_tracks_quantity', true);
            }

            if (!$max_tracks) {
                $max_tracks = (int) get_post_meta($product_id, '_max_tracks_quantity', true);
            }

            if ($max_tracks <= 0) {
                $max_tracks = 1;
            }

            $quantity = $cart_item['quantity'];
            $tracks = ceil($quantity / $max_tracks);
            $total_tracks += $tracks;
        }

        foreach ($rates as $rate_id => $rate) {
            $base_cost = $rate->cost;
            $tracks_fee = $base_cost * ($total_tracks - 1); 

            $rate->add_meta_data('base_cost', $base_cost);
            $rate->add_meta_data('tracks_fee', $tracks_fee);
            $rates[$rate_id]->cost = $base_cost + $tracks_fee;
            $rates[$rate_id]->label .= sprintf(
                ' (Base: %s + Tracks Fee: %s)',
                wc_price($base_cost),
                wc_price($tracks_fee)
            );
        }

        return $rates;
    }
}
