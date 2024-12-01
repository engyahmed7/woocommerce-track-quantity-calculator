<?php
class Tracks_Frontend
{
    public function __construct()
    {
        add_action('woocommerce_before_add_to_cart_button', [$this, 'add_tracks_preview']);
        add_filter('woocommerce_add_to_cart_validation', [$this, 'validate_cart'], 10, 3);
        add_action('woocommerce_order_item_meta_end', [$this, 'display_tracks_in_order_details'], 10, 4);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

        add_filter('woocommerce_add_cart_item_data', [$this, 'add_tracks_cart_item_data'], 10, 3);
        add_filter('woocommerce_get_item_data', [$this, 'display_tracks_cart_item_meta'], 10, 2);
        add_action('woocommerce_checkout_create_order_line_item', [$this, 'add_tracks_to_order_items'], 10, 4);
    }


    public function add_tracks_cart_item_data($cart_item_data, $product_id, $variation_id) 
{
    $tracks_quantity = 0;

    if ($variation_id) {
        $tracks_quantity = get_post_meta($variation_id, '_variation_max_tracks_quantity', true);
    } else {
        $tracks_quantity = get_post_meta($product_id, '_max_tracks_quantity', true);
    }

    if ($tracks_quantity) {
        $cart_item_data['tracks_quantity'] = $tracks_quantity;
    }

    return $cart_item_data;
}


public function display_tracks_cart_item_meta($item_data, $cart_item)
{
    if (isset($cart_item['tracks_quantity'])) {
        $tracks_total = ceil($cart_item['quantity'] / $cart_item['tracks_quantity']);

        $item_data[] = array(
            'key' => __('Tracks', 'woocommerce-tracks'),
            'value' => $tracks_total
        );
    }

    return $item_data;
}


    public function add_tracks_to_order_items($item, $cart_item_key, $values, $order)
    {
        if (isset($values['tracks_quantity'])) {
            $tracks_total = $values['tracks_quantity'] * $values['quantity'];
            $item->add_meta_data('_tracks_quantity', $tracks_total);
        }
    }

    public function enqueue_scripts()
    {
        if (is_product()) {
            wp_enqueue_script(
                'tracks-frontend-js',
                plugins_url('/assets/tracks-quantity.js', __FILE__),
                array(),
                '1.0.0',
                true
            );

            global $product;
            if ($product->is_type('variable')) {
                $variations = $product->get_available_variations();
                $variation_data = [];
                foreach ($variations as $variation) {
                    $variation_data[$variation['variation_id']] = get_post_meta($variation['variation_id'], '_variation_max_tracks_quantity', true);
                }

                wp_localize_script('tracks-frontend-js', 'tracksData', [
                    'variationMaxTracks' => $variation_data,
                ]);
            }
        }
    }

    public function add_tracks_preview()
    {
        global $product;
        $max_tracks = get_post_meta($product->get_id(), '_max_tracks_quantity', true);

        if ($product->is_type('variable')) {
            echo '<div id="tracks-preview" style="margin-top: 10px; font-size: 14px;">
            <small>' . __('Select a variation to see track quantity.', 'woocommerce-tracks') . '</small>
          </div>';
            echo '<input type="hidden" id="max-tracks" value="">';
        } else {
            if ($max_tracks) {
                echo '<div id="tracks-preview" style="margin-top: 10px; font-size: 14px;">
            <small>' . __('Number of tracks will be calculated based on your quantity.', 'woocommerce-tracks') . '</small>
          </div>';
                echo '<input type="hidden" id="max-tracks" value="' . esc_attr($max_tracks) . '">';
            }
        }
    }

    public function validate_cart($passed, $product_id, $quantity)
    {
        if (WC()->cart->get_cart_contents_count() > 1) {
            wc_add_notice(__('Only one product can be ordered at a time.', 'woocommerce-tracks'), 'error');
            return false;
        }

        return $passed;
    }


    public function display_tracks_in_order_details($item_id, $item, $order, $plain_text)
    {
        $tracks_quantity = $item->get_meta('_tracks_quantity');

        if ($tracks_quantity) {
            echo '<br><small>' .
                sprintf(__('Tracks: %d', 'woocommerce-tracks'), $tracks_quantity) .
                '</small>';
        }
    }
}
