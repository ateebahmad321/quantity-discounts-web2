<?php
/**
 * BulkBoost — main dashboard / welcome page.
 *
 * @package BulkBoost/admin
 */

if (!defined('WPINC')) {
    die;
}

require_once __DIR__ . '/bulkboost-admin-shell.php';

blkbst_admin_shell_open(array(
    'active' => 'bulkboost-bulkboost',
    'crumb'  => 'Dashboard',
    'title'  => 'Welcome to BulkBoost',
    'wide'   => true,
));
?>


<div class="bb-card" style="padding:22px;">
    <div class="bb-card-label" style="padding-top:0;">Quick links</div>
    <div class="bb-rowx">
        <div>
            <div class="name">🎨 Quantity Discount Design</div>
            <div class="help">Customize colors, typography and badges for the offer widget.</div>
        </div>
        <a class="bb-btn bb-btn-secondary" href="<?php echo esc_url(admin_url('admin.php?page=bulkboost-quantity-design')); ?>">Open</a>
    </div>
    <div class="bb-rowx">
        <div>
            <div class="name">📐 Min / Max Quantity</div>
            <div class="help">Define minimum and maximum quantity limits.</div>
        </div>
        <a class="bb-btn bb-btn-secondary" href="<?php echo esc_url(admin_url('admin.php?page=bulkboost-quantity-min-max')); ?>">Open</a>
    </div>
    <div class="bb-rowx">
        <div>
            <div class="name">💰 Earnings</div>
            <div class="help">Estimate the extra revenue BulkBoost can generate.</div>
        </div>
        <a class="bb-btn bb-btn-secondary" href="<?php echo esc_url(admin_url('admin.php?page=bulkboost-earnings')); ?>">Open</a>
    </div>
    <div class="bb-rowx">
        <div>
            <div class="name">⚙️ General Settings</div>
            <div class="help">Configure quantity-field behavior on cart &amp; checkout.</div>
        </div>
        <a class="bb-btn bb-btn-secondary" href="<?php echo esc_url(admin_url('admin.php?page=bulkboost-settings')); ?>">Open</a>
    </div>
</div>


<?php
blkbst_admin_shell_close();
