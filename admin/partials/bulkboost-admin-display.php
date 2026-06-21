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

bb_admin_shell_open(array(
    'active' => 'bulkboost-bulkboost',
    'crumb'  => 'Dashboard',
    'title'  => 'Welcome to BulkBoost',
    'wide'   => true,
));
?>

<div class="bb-card" style="padding:22px;">
    <p class="bb-prose" style="margin-top:0;">Thank you for choosing <strong>BulkBoost</strong>! This powerful tool helps you turn
        bigger carts into bigger revenue:</p>
    <ul class="bb-prose">
        <li><strong>Increased Sales:</strong> Encourage larger orders and boost revenue.</li>
        <li><strong>Improved Average Order Value (AOV):</strong> Incentivize customers to spend more.</li>
        <li><strong>Customizable Discounts:</strong> Tailor discounts to suit your business needs.</li>
        <li><strong>Enhanced Customer Experience:</strong> Simplify purchasing with clear discounts.</li>
        <li><strong>Flexible Pricing Options:</strong> Offer discounts on specific products or entire orders.</li>
        <li><strong>Real-time Savings Display:</strong> Show customers how much they save instantly.</li>
    </ul>
    <a class="bb-cta" style="font-size:16px;" target="_blank"
       href="https://bulkboost.com/products/quantity-breaks-and-discounts/">Try Premium 14 days for free →</a>
</div>

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

<div class="bb-card" style="padding:22px;">
    <div class="bb-card-label" style="padding-top:0;">Tutorial videos</div>
    <div class="bb-videos">
        <div>
            <strong class="bb-prose">Set up a product for BulkBoost</strong>
            <iframe height="180" src="https://youtube.com/embed/Zqm8zoEqq38" frameborder="0" allowfullscreen></iframe>
        </div>
        <div>
            <strong class="bb-prose">Customise the BulkBoost design</strong>
            <iframe height="180" src="https://www.youtube.com/embed/2xqk_XSJlac" frameborder="0" allowfullscreen></iframe>
        </div>
        <div>
            <strong class="bb-prose">Quantity breaks on variable products</strong>
            <iframe height="180" src="https://www.youtube.com/embed/o0DeTZ4gcaM" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
</div>

<?php
bb_admin_shell_close();
