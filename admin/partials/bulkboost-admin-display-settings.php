<?php
/**
 * BulkBoost — General Settings page.
 *
 * @package BulkBoost/admin
 */

if (!defined('WPINC')) {
    die;
}

require_once __DIR__ . '/bulkboost-admin-shell.php';

$general = get_option('bulkboost_general_settings', array(
    'disable_quantity_cart'     => 'enabled',
    'disable_quantity_checkout' => 'enabled',
));

bb_admin_shell_open(array(
    'active' => 'bulkboost-settings',
    'crumb'  => 'Settings',
    'title'  => 'General Settings',
));
?>

<div class="bb-intro">
    <h2>General Settings</h2>
    <p>Control how the quantity field behaves across the cart and checkout. <em>(Premium)</em></p>
</div>

<form method="post" action="options.php">
    <?php settings_fields('bulkboost_general_settings'); ?>
    <div class="bb-card">
        <div class="bb-rowx no-border">
            <div>
                <div class="name">Quantity field in Cart</div>
                <div class="help">Disabling locks the cart quantity to what was chosen on the product page.</div>
            </div>
            <select class="bb-select" style="width:150px;" disabled name="bulkboost_general_settings[disable_quantity_cart]">
                <option value="enabled" <?php selected($general['disable_quantity_cart'] ?? '', 'enabled'); ?>>Enabled</option>
                <option value="disabled" <?php selected($general['disable_quantity_cart'] ?? '', 'disabled'); ?>>Disabled</option>
            </select>
        </div>
        <div class="bb-rowx">
            <div>
                <div class="name">Quantity field in Checkout</div>
                <div class="help">Disabling removes quantity selection at checkout.</div>
            </div>
            <select class="bb-select" style="width:150px;" disabled name="bulkboost_general_settings[disable_quantity_checkout]">
                <option value="enabled" <?php selected($general['disable_quantity_checkout'] ?? '', 'enabled'); ?>>Enabled</option>
                <option value="disabled" <?php selected($general['disable_quantity_checkout'] ?? '', 'disabled'); ?>>Disabled</option>
            </select>
        </div>
    </div>
    <a class="bb-cta" target="_blank" href="https://bulkboost.com/products/quantity-breaks-and-discounts/">Unlock these with Premium — 14 days free →</a>
</form>

<?php
bb_admin_shell_close();
