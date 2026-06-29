<?php
/**
 * BulkBoost — Earnings report (Pro).
 *
 * Completed orders that contain BulkBoost products, aggregated per product,
 * with an optional date filter. Included from BLKBST_BulkBoost_Admin so $this
 * is the admin instance.
 *
 * @package BulkBoost/admin
 */

if (!defined('WPINC')) {
    die;
}

require_once __DIR__ . '/bulkboost-admin-shell.php';

$bb_premium = function_exists('bul_fs') && bul_fs()->is_premium();

bb_admin_shell_open(array(
    'active' => 'bulkboost-earnings',
    'crumb'  => 'Analytics',
    'title'  => 'Analytics',
    'wide'   => true,
));

if (!$bb_premium) {
    // ---- Free: upsell ----
    bb_admin_upsell(
        'Analytics is a Pro feature',
        'See exactly how much revenue BulkBoost generates — completed orders grouped by product, with date filtering and totals.'
    );
    bb_admin_shell_close();
    return;
}

// ---- Pro: the report ----
$start = isset($_GET['start_date']) ? sanitize_text_field(wp_unslash($_GET['start_date'])) : '';
$end   = isset($_GET['end_date']) ? sanitize_text_field(wp_unslash($_GET['end_date'])) : '';
// Keep only valid Y-m-d values.
$start = preg_match('/^\d{4}-\d{2}-\d{2}$/', $start) ? $start : '';
$end   = preg_match('/^\d{4}-\d{2}-\d{2}$/', $end) ? $end : '';

$data  = $this->BLKBST_get_earnings_data($start, $end);
$rows  = $data['rows'];
$total = $data['total'];
$date_format = get_option('date_format') . ', ' . get_option('time_format');
?>

<div class="bb-intro">
    <h2>Analytics</h2>
    <p>Completed orders that used BulkBoost, grouped by product.</p>
</div>

<form method="get" class="bb-earnings-filter">
    <input type="hidden" name="page" value="bulkboost-earnings">
    <label>Start date <input type="date" name="start_date" value="<?php echo esc_attr($start); ?>"></label>
    <label>End date <input type="date" name="end_date" value="<?php echo esc_attr($end); ?>"></label>
    <button type="submit" class="bb-btn bb-btn-primary">Filter</button>
    <?php if ($start || $end) : ?>
        <a class="bb-btn bb-btn-secondary" href="<?php echo esc_url(admin_url('admin.php?page=bulkboost-earnings')); ?>">Reset</a>
    <?php endif; ?>
</form>

<div class="bb-card" style="padding:6px 0;">
    <table class="bb-earnings-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Sales</th>
                <th>Quantity</th>
                <th>Date of last order</th>
                <th>Solution</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($rows)) : ?>
                <tr><td colspan="5" class="bb-earnings-empty">No completed BulkBoost orders found for this period.</td></tr>
            <?php else : ?>
                <?php foreach ($rows as $row) : ?>
                    <tr>
                        <td>
                            <a href="<?php echo esc_url(get_edit_post_link($row['product_id'])); ?>"><?php echo esc_html($row['name']); ?></a>
                        </td>
                        <td><?php echo wp_kses_post(wc_price($row['sales'])); ?></td>
                        <td><?php echo esc_html($row['quantity']); ?></td>
                        <td><?php echo $row['last_order'] ? esc_html(date_i18n($date_format, $row['last_order'])) : '—'; ?></td>
                        <td><?php echo esc_html($row['type']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="bb-earnings-total">Total Earned: <?php echo wp_kses_post(wc_price($total)); ?></div>

<div class="bb-card" style="padding:18px 22px;">
    <div class="bb-card-label" style="padding-top:0;">Important notice</div>
    <p class="bb-prose" style="margin-top:0;">A few criteria for the orders shown above:</p>
    <ol class="bb-prose">
        <li><strong>Completed orders only.</strong> Only orders with the <strong>Completed</strong> status are included, so the figures reflect finalized, fully processed transactions.</li>
        <li><strong>BulkBoost products only.</strong> A product is counted when it currently uses a BulkBoost solution — Quantity Bundle Blocks or Min/Max Quantity.</li>
    </ol>
</div>

<?php
bb_admin_shell_close();
