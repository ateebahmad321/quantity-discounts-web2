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

$bb_premium = function_exists('bul_fs') && bul_fs()->is_premium();

bb_admin_shell_open(array(
    'active' => 'bulkboost-settings',
    'crumb'  => 'Cart & Checkout',
    'title'  => 'Cart & Checkout',
));

if (!$bb_premium) {
    // ---- Free: upsell ----
    bb_admin_upsell(
        'Cart & Checkout is a Pro feature',
        'Lock the quantity a customer chose on the product page so it can\'t be changed in the cart or at checkout.'
    );
    bb_admin_shell_close();
    return;
}
?>

<div class="bb-intro">
    <h2>Cart &amp; Checkout</h2>
    <p>Lock the quantity chosen on the product page so it can't be changed later.</p>
</div>

<form method="post" action="options.php">
    <?php settings_fields('bulkboost_general_settings'); ?>
    <div class="bb-card">
        <div class="bb-rowx no-border">
            <div>
                <div class="name">Quantity field in Cart</div>
                <div class="help">Disabling locks the cart quantity to what was chosen on the product page.</div>
            </div>
            <select class="bb-select" style="width:150px;" name="bulkboost_general_settings[disable_quantity_cart]">
                <option value="enabled" <?php selected($general['disable_quantity_cart'] ?? '', 'enabled'); ?>>Enabled</option>
                <option value="disabled" <?php selected($general['disable_quantity_cart'] ?? '', 'disabled'); ?>>Disabled</option>
            </select>
        </div>
        <div class="bb-rowx">
            <div>
                <div class="name">Quantity field in Checkout</div>
                <div class="help">Disabling removes quantity selection at checkout.</div>
            </div>
            <select class="bb-select" style="width:150px;" name="bulkboost_general_settings[disable_quantity_checkout]">
                <option value="enabled" <?php selected($general['disable_quantity_checkout'] ?? '', 'enabled'); ?>>Enabled</option>
                <option value="disabled" <?php selected($general['disable_quantity_checkout'] ?? '', 'disabled'); ?>>Disabled</option>
            </select>
        </div>
    </div>

    <?php submit_button('Save Changes', 'primary', 'submit', true, array('class' => 'bb-btn bb-btn-primary')); ?>
</form>

<?php
bb_admin_shell_close();
