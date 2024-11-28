<?php
class Tracks_Admin
{
    public function __construct()
    {
        add_action('woocommerce_product_options_general_product_data', [$this, 'add_max_tracks_field']);
        add_action('woocommerce_product_after_variable_attributes', [$this, 'add_variation_max_tracks_field'], 10, 3);

        add_action('woocommerce_process_product_meta', [$this, 'save_max_tracks_field']);
        add_action('woocommerce_save_product_variation', [$this, 'save_variation_max_tracks_field'], 10, 2);

        add_action('woocommerce_admin_order_data_after_order_details', [$this, 'display_tracks_in_order_edit'], 10, 1);

        add_filter('woocommerce_email_order_meta_fields', [$this, 'add_tracks_to_emails'], 10, 3);

    }


    public function add_max_tracks_field()
    {
        woocommerce_wp_text_input([
            'id' => '_max_tracks_quantity',
            'label' => __('Max Tracks Quantity', 'woocommerce-tracks'),
            'description' => __('Maximum number of items per track for this product.', 'woocommerce-tracks'),
            'type' => 'number',
            'desc_tip' => true,
            'custom_attributes' => ['min' => 1]
        ]);
    }

    public function add_variation_max_tracks_field($loop, $variation_data, $variation)
    {
        $variation_max_tracks = get_post_meta($variation->ID, '_variation_max_tracks_quantity', true);
?>
        <div class="form-row form-row-full">
            <label>
                <?php _e('Max Tracks Quantity for Variation', 'woocommerce-tracks'); ?>
                <input
                    type="number"
                    size="5"
                    name="_variation_max_tracks_quantity[<?php echo $loop; ?>]"
                    value="<?php echo esc_attr($variation_max_tracks); ?>"
                    class="short"
                    min="1" />
            </label>
        </div>
<?php
    }

    public function save_max_tracks_field($post_id)
    {
        $max_tracks = isset($_POST['_max_tracks_quantity']) ? wc_clean($_POST['_max_tracks_quantity']) : '';
        update_post_meta($post_id, '_max_tracks_quantity', $max_tracks);
    }

    public function save_variation_max_tracks_field($variation_id, $loop)
    {
        if (isset($_POST['_variation_max_tracks_quantity'][$loop])) {
            $max_tracks = wc_clean($_POST['_variation_max_tracks_quantity'][$loop]);
            update_post_meta($variation_id, '_variation_max_tracks_quantity', $max_tracks);
        }
    }

    public function display_tracks_in_order_edit($order)
    {
        echo '<div class="tracks-order-info">';
        echo '<h3>' . __('Tracks Quantity Details', 'woocommerce-tracks') . '</h3>';

        foreach ($order->get_items() as $item_id => $item) {
            $tracks_quantity = $item->get_meta('_tracks_quantity', true);
            if ($tracks_quantity) {
                echo '<p>';
                echo '<strong>' . esc_html($item->get_name()) . ':</strong> ';
                echo __('Tracks Quantity: ', 'woocommerce-tracks') . $tracks_quantity;
                echo '</p>';
            }
        }

        echo '</div>';
    }


    public function add_tracks_to_emails($fields, $sent_to_admin, $order)
    {
        foreach ($order->get_items() as $item_id => $item) {
            $tracks_quantity = $item->get_meta('_tracks_quantity', true);
            if ($tracks_quantity) {
                $fields['tracks_quantity'] = [
                    'label' => __('Tracks Quantity', 'woocommerce-tracks'),
                    'value' => $tracks_quantity
                ];
            }
        }
        return $fields;
    }
}
