<?php
class Tracks_Order
{
    public function __construct()
    {
        add_action('woocommerce_checkout_create_order_line_item', [$this, 'add_tracks_to_order'], 10, 4);
        add_filter('woocommerce_package_rates', [$this, 'adjust_flat_rate_cost'], 10, 2);
    }

    public function add_tracks_to_order($item, $cart_item_key, $values, $order)
    {
        $product_id = $values['product_id'];
        $max_tracks = get_post_meta($product_id, '_max_tracks_quantity', true);

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
            $max_tracks = (int) get_post_meta($product_id, '_max_tracks_quantity', true);

            if ($max_tracks <= 0) {
                $max_tracks = 1;
            }

            $quantity = $cart_item['quantity'];
            $tracks = ceil($quantity / $max_tracks);
            $total_tracks += $tracks;
        }

        foreach ($rates as $rate_id => $rate) {
            if ('flat_rate' === $rate->method_id) {
                $flat_rate_base_cost = $rate->cost;
                $tracks_fee = $flat_rate_base_cost * ($total_tracks - 1);

                $rate->add_meta_data('base_cost', $flat_rate_base_cost);
                $rate->add_meta_data('tracks_fee', $tracks_fee);

                $rates[$rate_id]->cost = $flat_rate_base_cost + $tracks_fee;

                $rates[$rate_id]->label .= sprintf(
                    ' (Base: %s + Tracks Fee: %s)',
                    wc_price($flat_rate_base_cost),
                    wc_price($tracks_fee)
                );
            }
        }

        return $rates;
    }
}
