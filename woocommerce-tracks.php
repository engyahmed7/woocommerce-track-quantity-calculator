<?php
/**
 * Plugin Name: WooCommerce Tracks
 * Description: Manage track quantities and shipping calculations for WooCommerce
 * Version: 1.0.0
 * Author: Engy
 * text-domain: woocommerce-tracks
 */

if (!defined('ABSPATH')) {
    exit; 
}

class WooCommerce_Tracks {
    public function __construct() {
        $this->include_files();
        add_action('plugins_loaded', [$this, 'init']);
        add_filter( 'wc_order_is_editable', [$this,'bbloomer_custom_order_status_editable'], 9999, 2 );
    }
 
    function bbloomer_custom_order_status_editable( $allow_edit, $order ) {
        if ( $order->get_status() === 'processing' ) {
            $allow_edit = true;
        }
        return $allow_edit;
    }
    private function include_files() {
        require_once plugin_dir_path(__FILE__) . 'includes/class-tracks-admin.php';
        require_once plugin_dir_path(__FILE__) . 'includes/class-tracks-frontend.php';
        require_once plugin_dir_path(__FILE__) . 'includes/class-tracks-order.php';
    }

    public function init() {
        if (class_exists('WooCommerce')) {
            new Tracks_Admin();
            new Tracks_Frontend();
            new Tracks_Order();
        }
    }
}

new WooCommerce_Tracks();
