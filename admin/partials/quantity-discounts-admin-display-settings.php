<div class="wrap">
    <h2>Quantity Discounts General Settings</h2>
    <hr>
    <form method="post" action="options.php">
		<?php
		settings_fields( 'quantity_discounts_general_settings' ); ?>
        <div id="general-settings" class="tab-content">
            <table class="form-table">
                <!-- Show Quantity Field in Cart Page -->
                <tr valign="top">
                    <th scope="row">Quantity Field in Cart Page:</th>
                    <td>
                        <select id="quantity_cart" disabled name="quantity_discounts_general_settings[disable_quantity_cart]">
                            <option value="enabled" <?php
							selected( get_option( 'quantity_discounts_general_settings' )['disable_quantity_cart'],
								'enabled' ); ?>>Enabled
                            </option>
                            <option value="disabled" <?php
							selected( get_option( 'quantity_discounts_general_settings' )['disable_quantity_cart'],
								'disabled' ); ?>>Disabled
                            </option>
                        </select>
                        <p class="description">
                            Disabling this option quantity field will be disabled directly in Cart page to make sure
                            clients select only quantity which was selected in product page.
                        </p>
                    </td>
                </tr>

                <!-- Show Quantity Field in Checkout Page -->
                <tr valign="top">
                    <th scope="row">Quantity Field in Checkout Page:</th>
                    <td>
                        <select id="quantity_checkout"
                                disabled
                                name="quantity_discounts_general_settings[disable_quantity_checkout]">
                            <option value="enabled" <?php
							selected( get_option( 'quantity_discounts_general_settings' )['disable_quantity_checkout'],
								'enabled' ); ?>>Enabled
                            </option>
                            <option value="disabled" <?php
							selected( get_option( 'quantity_discounts_general_settings' )['disable_quantity_checkout'],
								'disabled' ); ?>>Disabled
                            </option>
                        </select>
                        <p class="description">
                            Disabling this option will remove the quantity selection from the checkout page, preventing
                            users from changing quantities at checkout.
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        <a style="color:darkgreen; display:inline-block; font-weight:700;" target="_blank" href="https://wpiron.com/products/quantity-breaks-and-discounts/">Try Premium for 14 days for free!</a>
    </form>
</div>

<style>
    .wrap {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        color: #333;
    }

    h2, h3 {
        color: #285577;
    }

    .thank-you-section ul {
        list-style: inside square;
        padding-left: 0;
    }

    ul li {
        margin-bottom: 8px;
    }

    .thank-you-section .tutorial-videos {
        margin-top: 30px;
        background-color: #f9f9f9;
        padding: 15px;
        border-radius: 8px;
    }

    .thank-you-section table {
        width: 100%;
        text-align: center;
    }

    .thank-you-section .settings-links a {
        display: block;
        margin-top: 10px;
        text-decoration: none;
        color: #1a73e8;
    }

    .thank-you-section .settings-links a:hover {
        text-decoration: underline;
    }

    iframe {
        max-width: 100%;
    }
</style>