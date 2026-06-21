<?php
/**
 * BulkBoost — Earnings page (premium upsell).
 *
 * @package BulkBoost/admin
 */

if (!defined('WPINC')) {
    die;
}

require_once __DIR__ . '/bulkboost-admin-shell.php';

bb_admin_shell_open(array(
    'active' => 'bulkboost-earnings',
    'crumb'  => 'Settings',
    'title'  => 'Earnings',
    'wide'   => true,
));
?>

<div class="bb-intro">
    <h2>Earnings &amp; Reports</h2>
    <p>Track the extra revenue BulkBoost generates with detailed sales reports. <em>Available in Premium.</em></p>
</div>

<div class="bb-card" style="padding:22px;">
    <a class="bb-cta" style="font-size:16px;" target="_blank"
       href="https://bulkboost.com/products/quantity-breaks-and-discounts/">Try Premium 14 days for free →</a>
    <div class="bb-card-label">How it looks</div>
    <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'img/earnings.png'); ?>" alt="Earnings report preview"
         style="width:100%; max-width:820px; display:block; border-radius:12px; border:1px solid var(--bb-border);">
</div>

<?php
bb_admin_shell_close();
