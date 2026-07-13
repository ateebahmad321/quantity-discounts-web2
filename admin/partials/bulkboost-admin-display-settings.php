<?php
/**
 * BulkBoost — Cart & Checkout settings page (Pro).
 *
 * Included from BLKBST_BulkBoost_Admin so $this is the admin instance. In the
 * Free version only the upsell below remains — the settings form is rendered
 * by a Pro-only method that is stripped from the Free build.
 *
 * @package BulkBoost/admin
 */

if (!defined('WPINC')) {
    die;
}

require_once __DIR__ . '/bulkboost-admin-shell.php';

blkbst_admin_shell_open(array(
    'active' => 'bulkboost-settings',
    'crumb'  => 'Cart & Checkout',
    'title'  => 'Cart & Checkout',
));

// This "if" block will be auto removed from the Free version.
if (blkbst_fs()->can_use_premium_code__premium_only()) {
    $this->BLKBST_render_cart_checkout_form__premium_only();
    blkbst_admin_shell_close();
    return;
}

// ---- Free (or unlicensed): upsell ----
blkbst_admin_upsell(
    'Cart & Checkout is a Pro feature',
    'Lock the quantity a customer chose on the product page so it can\'t be changed in the cart or at checkout.'
);
blkbst_admin_shell_close();
