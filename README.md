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

## Hooks Used

### Admin Hooks:

- **`woocommerce_product_options_general_product_data`**:
  - Adds the **Max Tracks Quantity** field to the product editing page.
  
- **`woocommerce_product_after_variable_attributes`**:
  - Adds the **Max Tracks Quantity for Variation** field to the product variations section.
  
- **`woocommerce_process_product_meta`**:
  - Saves the **Max Tracks Quantity** for a product when the product is saved.
  
- **`woocommerce_save_product_variation`**:
  - Saves the **Max Tracks Quantity for Variation** when a variation is saved.
  
- **`woocommerce_admin_order_data_after_order_details`**:
  - Displays the **tracks quantity** in the order edit page in the admin area.
  
- **`woocommerce_email_order_meta_fields`**:
  - Adds the **tracks quantity** to the email order meta fields.

### Frontend Hooks:

- **`woocommerce_before_add_to_cart_button`**:
  - Adds the **tracks preview** on the product page.
  
- **`woocommerce_add_to_cart_validation`**:
  - Validates the cart to ensure only one product is added at a time.
  
- **`woocommerce_add_cart_item_data`**:
  - Adds the **tracks quantity** to the cart item data.
  
- **`woocommerce_order_item_meta_end`**:
  - Displays the **tracks quantity** in the order details after the order item meta.
  
- **`woocommerce_cart_item_name`**:
  - Displays the **tracks quantity** in the cart item name.

### Order Hooks:

- **`woocommerce_checkout_create_order_line_item`**:
  - Adds the **tracks quantity** to the order line items during checkout.
  
- **`woocommerce_package_rates`**:
  - Adjusts the shipping cost based on the **tracks quantity**.

## Example Usage

### Example of Adding Max Tracks Quantity to a Product:

1. In the WooCommerce product edit page, you will see the **Max Track Quantity** field.
2. Enter the desired maximum track quantity for the product.
3. The plugin will calculate and display the track quantity for each cart item based on the quantity selected by the customer.

### Example of Displaying Tracks Quantity in Order Details:

1. After an order is placed, the **tracks quantity** will appear in the order details for both the customer and the admin.
2. The **shipping cost** will be automatically adjusted based on the calculated number of tracks.

## Contributing
Contributions are welcome! If you find any issues or have suggestions for improvements, please open an issue or submit a pull request.