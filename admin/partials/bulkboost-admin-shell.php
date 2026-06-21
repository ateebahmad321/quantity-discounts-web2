<?php
/**
 * Shared admin "shell" for every BulkBoost settings page.
 *
 * Renders the cream/emerald sidebar + sticky header described in the
 * BulkBoost design handoff, then opens the content column. Each page
 * calls bb_admin_shell_open([...]), echoes its cards, then
 * bb_admin_shell_close() (optionally passing a live-preview aside).
 *
 * @package BulkBoost/admin
 */

if (!defined('WPINC')) {
    die;
}

if (!function_exists('bb_admin_nav_items')) {
    /**
     * Top-level plugin pages shown in the sidebar nav.
     */
    function bb_admin_nav_items()
    {
        $sliders = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="4" y1="7" x2="20" y2="7"></line><circle cx="15" cy="7" r="2.3" fill="#fbfbf9"></circle><line x1="4" y1="13" x2="20" y2="13"></line><circle cx="9" cy="13" r="2.3" fill="#fbfbf9"></circle><line x1="4" y1="19" x2="20" y2="19"></line><circle cx="16" cy="19" r="2.3" fill="#fbfbf9"></circle></svg>';
        $home = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 11 L12 4 L20 11"></path><path d="M6 10 V20 H18 V10"></path></svg>';
        $minmax = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 18 V6"></path><path d="M4 18 H20"></path><rect x="7" y="11" width="3" height="5"></rect><rect x="13" y="7" width="3" height="9"></rect></svg>';
        $earn = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8"></circle><path d="M12 8 v8 M9.5 9.5 h3.2 a1.6 1.6 0 0 1 0 3.2 h-2.4 a1.6 1.6 0 0 0 0 3.2 h3.2"></path></svg>';
        $cog = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M12 3 v2 M12 19 v2 M3 12 h2 M19 12 h2 M5.6 5.6 l1.4 1.4 M17 17 l1.4 1.4 M18.4 5.6 l-1.4 1.4 M7 17 l-1.4 1.4"></path></svg>';

        return array(
            'bulkboost-bulkboost'       => array('label' => 'Dashboard',       'icon' => $home),
            'bulkboost-quantity-design' => array('label' => 'Design Settings', 'icon' => $sliders),
            'bulkboost-quantity-min-max'=> array('label' => 'Min / Max',       'icon' => $minmax),
            'bulkboost-earnings'        => array('label' => 'Earnings',         'icon' => $earn),
            'bulkboost-settings'        => array('label' => 'General Settings', 'icon' => $cog),
        );
    }
}

if (!function_exists('bb_admin_shell_open')) {
    /**
     * @param array $args active, crumb, title, actions (bool), wide (bool)
     */
    function bb_admin_shell_open($args = array())
    {
        $args = wp_parse_args($args, array(
            'active'  => 'bulkboost-bulkboost',
            'crumb'   => 'Settings',
            'title'   => 'BulkBoost',
            'actions' => false,
            'wide'    => false,
            'dashboard' => false,
        ));
        $items = bb_admin_nav_items();
        $dash_attr = $args['dashboard'] ? ' data-bb-dashboard' : '';
        ?>
        <div class="bb-admin"<?php echo $dash_attr; ?>>
            <aside class="bb-sidebar">
                <div class="bb-brand">
                    <div class="bb-logo"><i></i><i></i><i></i></div>
                    <div>
                        <div class="bb-brand-name">BulkBoost</div>
                        <div class="bb-brand-sub">Quantity discounts</div>
                    </div>
                </div>
                <nav class="bb-nav">
                    <div class="bb-nav-label">Settings</div>
                    <?php foreach ($items as $slug => $item) : ?>
                        <a class="bb-nav-item<?php echo $slug === $args['active'] ? ' is-active' : ''; ?>"
                           href="<?php echo esc_url(admin_url('admin.php?page=' . $slug)); ?>">
                            <?php echo $item['icon']; // phpcs:ignore -- static inline svg ?>
                            <span><?php echo esc_html($item['label']); ?></span>
                        </a>
                    <?php endforeach; ?>
                </nav>
                <div class="bb-tip">
                    <div class="bb-tip-title">Pro tip</div>
                    <div class="bb-tip-body">Edit any field and watch the live preview update instantly.</div>
                </div>
            </aside>

            <main class="bb-main">
                <header class="bb-header">
                    <div>
                        <div class="bb-crumb">BulkBoost &nbsp;/&nbsp; <?php echo esc_html($args['crumb']); ?></div>
                        <div class="bb-title"><?php echo esc_html($args['title']); ?></div>
                    </div>
                    <?php if ($args['actions']) : ?>
                        <div class="bb-header-actions">
                            <span class="bb-status is-saved" data-bb-status><span class="dot"></span>All changes saved</span>
                            <button type="button" class="bb-btn bb-btn-secondary" data-bb-discard disabled>Discard</button>
                            <button type="button" class="bb-btn bb-btn-primary" data-bb-save disabled>Save Changes</button>
                        </div>
                    <?php endif; ?>
                </header>

                <div class="bb-body">
                    <section class="bb-content<?php echo $args['wide'] ? ' is-wide' : ''; ?>">
        <?php
    }
}

if (!function_exists('bb_admin_shell_close')) {
    /**
     * @param string|null $aside_html Optional live-preview rail markup.
     */
    function bb_admin_shell_close($aside_html = null)
    {
        echo '</section>';
        if ($aside_html !== null) {
            echo '<aside class="bb-aside">' . $aside_html . '</aside>'; // phpcs:ignore -- trusted markup
        }
        echo '</div></main></div>';
    }
}
