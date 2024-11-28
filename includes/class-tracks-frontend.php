<?php
class Tracks_Frontend
{
    public function __construct()
    {
        add_action('woocommerce_before_add_to_cart_button', [$this, 'add_tracks_preview']);
        add_filter('woocommerce_add_to_cart_validation', [$this, 'validate_cart'], 10, 3);
        add_filter('woocommerce_add_cart_item_data', [$this, 'add_tracks_to_cart_item'], 10, 3);
        add_action('woocommerce_order_item_meta_end', [$this, 'display_tracks_in_order_details'], 10, 4);
        add_filter('woocommerce_cart_item_name', [$this, 'display_tracks_in_cart'], 10, 3);
        // add_action('wp_footer', [$this, 'add_tracks_script']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }


    public function enqueue_scripts()
    {
        if (is_product()) {
            error_log(plugins_url('/assets/tracks-quantity.js', __FILE__));
            wp_enqueue_script(
                'tracks-frontend-js',
                plugins_url('/assets/tracks-quantity.js', __FILE__),
                array(),
                '1.0.0',
                true,
                filemtime(plugins_url('/assets/tracks-quantity.js', __FILE__))
            );
        }
    }


    public function add_tracks_preview()
    {
        global $product;
        $max_tracks = get_post_meta($product->get_id(), '_max_tracks_quantity', true);

        if ($product->is_type('variable') && isset($_GET['variation_id']) && $_GET['variation_id']) {
            $variation_id = $_GET['variation_id'];
            $max_tracks = get_post_meta($variation_id, '_variation_max_tracks_quantity', true);
        }

        if ($max_tracks) {
            echo '<div id="tracks-preview" style="margin-top: 10px; font-size: 14px;">
                <small>' . __('Number of tracks will be calculated based on your quantity.', 'woocommerce-tracks') . '</small>
              </div>';
            echo '<input type="hidden" id="max-tracks" value="' . esc_attr($max_tracks) . '">';
        }
    }



    public function add_tracks_to_cart_item($cart_item_data, $product_id, $variation_id)
    {
        $max_tracks = $variation_id
            ? get_post_meta($variation_id, '_variation_max_tracks_quantity', true)
            : get_post_meta($product_id, '_max_tracks_quantity', true);

        $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;

        $tracks_quantity = ceil($quantity / $max_tracks);

        error_log('Tracks Quantity Added: ' . $tracks_quantity);

        $cart_item_data['tracks_quantity'] = $tracks_quantity;
        return $cart_item_data;
    }


    public function validate_cart($passed, $product_id, $quantity)
    {
        if (WC()->cart->get_cart_contents_count() > 0) {
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

    public function display_tracks_in_cart($product_name, $cart_item, $cart_item_key)
    {
        if (isset($cart_item['tracks_quantity'])) {
            error_log('Tracks Quantity in Cart: ' . $cart_item['tracks_quantity']);

            $tracks_quantity = $cart_item['tracks_quantity'];
            $product_name .= sprintf(
                '<br><small><strong>Tracks Quantity: %d</strong></small>',
                $tracks_quantity
            );
        }

        return $product_name;
    }
}
