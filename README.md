# WooCommerce Track Quantity Calculator

## Overview
The **WooCommerce Track Quantity Calculator** is a custom plugin for WooCommerce that allows store owners to manage the **track quantities** for their products. The plugin enables the admin to define the **maximum track quantity** for each product. It also calculates the total number of tracks based on the quantity selected by the user and affects the shipping cost accordingly. Additionally, the **tracks quantity** is displayed in the order details, emails, and the backend order page.

## Features

1. **Custom Product Field**:
   - Allows the admin to set a **maximum track quantity** for each product in the product settings.
   
2. **Frontend Track Quantity Calculation**:
   - When a user selects a quantity for a product, the plugin calculates and previews the **number of tracks** below the quantity field on the product page.

3. **Shipping Cost Adjustment**:
   - The **calculated tracks** will affect the shipping cost. For example, if the shipping cost is $10 and the number of tracks is 2, the shipping cost will be adjusted to $20 (2 x 10).

4. **Display Tracks in Order Details**:
   - The **number of tracks** will be shown as item meta in the **order view** on both the frontend and backend, as well as in email notifications.

5. **Admin Control for Order Edit**:
   - The admin can edit the **tracks quantity** from the **backend order page**, and the system will recalculate the shipping cost accordingly.

6. **Single Product Restriction**:
   - Ensures that the cart can only contain one product to prevent different products with different track quantities.

7. **Supports Simple and Variable Products**:
   - Works with both **simple** and **variable products**.

## Installation

1. Download the plugin files.
2. Upload the plugin folder to the `wp-content/plugins/` directory.
3. Activate the plugin through the **Plugins** menu in WordPress.

## Usage

1. **Setting the Max Track Quantity**:
   - Go to the product editing page in WooCommerce.
   - Under the **Product Data** section, you will see a new field called **Max Track Quantity** where you can specify the maximum number of items per track for the product.

2. **Track Quantity Preview on Product Page**:
   - On the product page, when the customer selects a quantity, the number of tracks will be calculated and displayed below the quantity selector.

3. **Order Details**:
   - When an order is placed, the **number of tracks** will be displayed in the order details page for both the customer and the admin.
   - The **shipping cost** will be adjusted based on the calculated tracks.

4. **Admin Control**:
   - The admin can change the **tracks quantity** from the backend order page. The system will recalculate the shipping cost accordingly.

## Example

For example, if the **max track quantity** for a product is set to 5, and a user selects 15 units of that product, the plugin will calculate that there are **3 tracks** (15 / 5 = 3). If the **shipping cost** is $10 per track, the shipping cost will be adjusted to **$30** (3 x $10).

## Development

### Requirements:
- WooCommerce v5.0 or higher

### Files:
- **includes/class-tracks-admin.php**: Contains functionality for managing the backend settings, including the product custom field and order handling.
- **includes/class-tracks-frontend.php**: Handles the frontend logic, including the calculation and display of tracks quantity.
- **includes/class-tracks-order.php**: Manages the order logic, including adjusting the shipping cost and showing tracks in order details.

### Hooks & Actions:
- `woocommerce_product_options_general_product_data`: Add custom product fields for max track quantity.
- `woocommerce_cart_item_name`: Display tracks quantity in the cart page.
- `woocommerce_checkout_create_order_line_item`: Save the tracks quantity to the order.
- `woocommerce_cart_calculate_fees`: Adjust shipping costs based on the number of tracks.

## License

This plugin is released under the [MIT License](https://opensource.org/licenses/MIT).
