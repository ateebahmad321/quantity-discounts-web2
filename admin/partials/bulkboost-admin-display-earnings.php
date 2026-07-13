<?php
/**
 * BulkBoost — Earnings report (Pro).
 *
 * Completed orders that contain BulkBoost products, aggregated per product,
 * with an optional date filter. Included from BLKBST_BulkBoost_Admin so $this
 * is the admin instance. In the Free version only the upsell below remains —
 * the report itself is rendered by a Pro-only method that is stripped from
 * the Free build.
 *
 * @package BulkBoost/admin
 */

if (!defined('WPINC')) {
    die;
}

require_once __DIR__ . '/bulkboost-admin-shell.php';

blkbst_admin_shell_open(array(
    'active' => 'bulkboost-earnings',
    'crumb'  => 'Analytics',
    'title'  => 'Analytics',
    'wide'   => true,
));

// This "if" block will be auto removed from the Free version.
if (blkbst_fs()->can_use_premium_code__premium_only()) {
    $this->BLKBST_render_earnings_report__premium_only();
    blkbst_admin_shell_close();
    return;
}

// ---- Free (or unlicensed): upsell ----
blkbst_admin_upsell(
    'Analytics is a Pro feature',
    'See exactly how much revenue BulkBoost generates — completed orders grouped by product, with date filtering and totals.'
);
blkbst_admin_shell_close();
