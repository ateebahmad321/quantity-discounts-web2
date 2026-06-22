<?php
/**
 * BulkBoost — Design Settings dashboard.
 *
 * High-fidelity rebuild of the "BulkBoost — Design Settings Dashboard" handoff.
 * Three tabs (Design / Typography / Badge) + a live storefront preview rail.
 * Controls are static markup bound by data-key/data-val; bulkboost-dashboard.js
 * hydrates them from saved settings, renders the preview, and saves via AJAX.
 *
 * @package BulkBoost/admin
 */

if (!defined('WPINC')) {
    die;
}

require_once __DIR__ . '/bulkboost-admin-shell.php';

/* ---- tiny render helpers (guarded so the partial can include safely) ---- */
if (!function_exists('bb_swatches')) {
    function bb_swatches($key, $colors)
    {
        echo '<div class="bb-swatches">';
        foreach ($colors as $c) {
            printf(
                '<button type="button" class="bb-swatch" data-key="%s" data-val="%s" style="background:%s" aria-label="%s"></button>',
                esc_attr($key),
                esc_attr($c),
                esc_attr($c),
                esc_attr($c)
            );
        }
        echo '</div>';
    }
}
if (!function_exists('bb_color_picker')) {
    function bb_color_picker($key)
    {
        printf(
            '<span class="bb-color-pick">'
            . '<input type="color" class="bb-color-input" data-key="%1$s" aria-label="Pick a custom color">'
            . '<input type="text" class="bb-hex-input" data-key="%1$s" maxlength="7" spellcheck="false" aria-label="Hex color value">'
            . '</span>',
            esc_attr($key)
        );
    }
}
if (!function_exists('bb_color_row')) {
    function bb_color_row($key, $colors)
    {
        echo '<div class="bb-color-control">';
        bb_swatches($key, $colors);
        bb_color_picker($key);
        echo '</div>';
    }
}
if (!function_exists('bb_segmented')) {
    function bb_segmented($key, $opts)
    {
        echo '<div class="bb-seg">';
        foreach ($opts as $val => $label) {
            printf(
                '<button type="button" class="bb-seg-opt" data-key="%s" data-val="%s">%s</button>',
                esc_attr($key),
                esc_attr($val),
                esc_html($label)
            );
        }
        echo '</div>';
    }
}
if (!function_exists('bb_slider')) {
    function bb_slider($key, $min, $max, $step)
    {
        printf(
            '<div class="bb-slider"><input type="range" min="%s" max="%s" step="%s" data-key="%s"><span class="val"></span></div>',
            esc_attr($min),
            esc_attr($max),
            esc_attr($step),
            esc_attr($key)
        );
    }
}
if (!function_exists('bb_weight_size')) {
    function bb_weight_size($weight_key, $size_key)
    {
        $weights = array('300' => 'Light', '400' => 'Normal', '500' => 'Medium', '600' => 'Semibold', '700' => 'Bold');
        echo '<div class="bb-fields">';
        echo '<div class="bb-field"><label>Weight</label><select class="bb-select" data-key="' . esc_attr($weight_key) . '">';
        foreach ($weights as $v => $l) {
            echo '<option value="' . esc_attr($v) . '">' . esc_html($l) . '</option>';
        }
        echo '</select></div>';
        echo '<div class="bb-field"><label>Size</label><div class="bb-stepper"><input type="number" min="8" max="48" data-key="' . esc_attr($size_key) . '"><span class="suffix">px</span></div></div>';
        echo '</div>';
    }
}

bb_admin_shell_open(array(
    'active'    => 'bulkboost-quantity-design',
    'crumb'     => 'Settings',
    'title'     => 'Design Settings',
    'actions'   => true,
    'dashboard' => true,
));
?>

<div class="bb-tabs" role="tablist">
    <button type="button" class="bb-tab is-active" data-tab="design">Design</button>
    <button type="button" class="bb-tab" data-tab="typography">Typography</button>
    <button type="button" class="bb-tab" data-tab="badge">Badge<?php if (!bulkboost_is_premium()) : ?> <span class="bb-pro-pill">PRO</span><?php endif; ?></button>
</div>

<!-- ============ DESIGN ============ -->
<div data-tab-panel="design">
    <div class="bb-intro">
        <h2>Design Settings</h2>
        <p>Style the offer cards — colors, corners, borders and the selector control.</p>
    </div>

    <div class="bb-card">
        <div class="bb-card-label">Colors</div>
        <div class="bb-rowx">
            <div><div class="name">Active background</div><div class="help">Fill of the selected offer</div></div>
            <?php bb_color_row('activeBg', array('#16231d', '#1c1c22', '#21303a', '#2a1f2e')); ?>
        </div>
        <div class="bb-rowx">
            <div><div class="name">Active text</div><div class="help">Text color inside the selected offer</div></div>
            <?php bb_color_row('activeText', array('#ffffff', '#1b1c18')); ?>
        </div>
        <div class="bb-rowx">
            <div><div class="name">Accent</div><div class="help">Selected ring &amp; radio fill</div></div>
            <?php bb_color_row('accent', array('#10976a', '#4f5bd5', '#e8643c', '#c2870e')); ?>
        </div>
        <div class="bb-rowx">
            <div><div class="name">Inactive border</div><div class="help">Outline of unselected offers</div></div>
            <?php bb_color_row('inactiveBorder', array('#e6e5df', '#cfd0c8', '#10976a')); ?>
        </div>
    </div>

    <div class="bb-card">
        <div class="bb-card-label">Shape &amp; spacing</div>
        <div class="bb-rowx">
            <div><div class="name">Corner radius</div></div>
            <?php bb_slider('radius', 0, 24, 1); ?>
        </div>
        <div class="bb-rowx">
            <div><div class="name">Border width</div></div>
            <?php bb_slider('borderW', 0, 4, 0.5); ?>
        </div>
        <div class="bb-rowx">
            <div><div class="name">Card spacing</div></div>
            <?php bb_slider('gap', 4, 24, 1); ?>
        </div>
        <div class="bb-rowx">
            <div><div class="name">Selector style</div><div class="help">Control shown on each offer</div></div>
            <?php bb_segmented('selector', array('radio' => 'Radio', 'checkbox' => 'Checkbox', 'none' => 'None')); ?>
        </div>
    </div>
</div>

<!-- ============ TYPOGRAPHY ============ -->
<div data-tab-panel="typography" style="display:none;">
    <div class="bb-intro">
        <h2>Typography</h2>
        <p>Set the weight and size of each text element in the offer cards.</p>
    </div>

    <div class="bb-card">
        <div class="bb-card-label">Text styles</div>
        <div class="bb-rowx">
            <div><div class="name">Label</div><div class="help">The offer title</div></div>
            <?php bb_weight_size('labelWeight', 'labelSize'); ?>
        </div>
        <div class="bb-rowx">
            <div><div class="name">Description</div><div class="help">Supporting line under the label</div></div>
            <?php bb_weight_size('descWeight', 'descSize'); ?>
        </div>
        <div class="bb-rowx">
            <div><div class="name">Price</div><div class="help">The current price</div></div>
            <?php bb_weight_size('priceWeight', 'priceSize'); ?>
        </div>
    </div>

    <div class="bb-card">
        <div class="bb-rowx no-border">
            <div><div class="name">Old price</div><div class="help">Strikethrough compare-at price</div></div>
            <div class="bb-control">
                <span class="bb-toggle-label">Show</span>
                <button type="button" class="bb-toggle" data-key="showOld"><span class="knob"></span></button>
            </div>
        </div>
        <div data-dim-key="showOld">
            <div class="bb-rowx">
                <div class="name" style="color:#5a5c52;">Old price style</div>
                <?php bb_weight_size('oldWeight', 'oldSize'); ?>
            </div>
        </div>
    </div>
</div>

<!-- ============ BADGE ============ -->
<div data-tab-panel="badge" style="display:none;">
    <div class="bb-intro">
        <h2>Badges</h2>
        <p>Style the three promotional badges. Turn each one on per offer inside a product's BulkBoost panel.</p>
    </div>

    <?php $bb_premium = bulkboost_is_premium(); ?>
    <div class="bb-premium-gate<?php echo $bb_premium ? '' : ' is-locked'; ?>">
        <?php if (!$bb_premium) : ?>
            <div class="bb-lock">
                <div class="bb-lock-card">
                    <div class="bb-lock-icon">★</div>
                    <strong>Badges are a Pro feature</strong>
                    <p>Unlock label, savings and free-shipping badge styling with BulkBoost Pro.</p>
                    <a class="bb-btn bb-btn-primary" href="<?php echo esc_url(bulkboost_upgrade_url()); ?>">Upgrade to Pro</a>
                </div>
            </div>
        <?php endif; ?>

        <div class="bb-card">
            <div class="bb-card-label">Label badge — HOT</div>
            <div class="bb-rowx">
                <div><div class="name">Preview</div></div>
                <span class="bb-badge-chip" data-badge-chip="hot">HOT</span>
            </div>
            <div class="bb-rowx">
                <div><div class="name">Background</div></div>
                <?php bb_color_row('labelHotBg', array('#e53935', '#e8643c', '#d4537e', '#1b1c18')); ?>
            </div>
            <div class="bb-rowx">
                <div><div class="name">Text color</div></div>
                <?php bb_color_row('labelHotText', array('#ffffff', '#1b1c18')); ?>
            </div>
        </div>

        <div class="bb-card">
            <div class="bb-card-label">Label badge — Most popular</div>
            <div class="bb-rowx">
                <div><div class="name">Preview</div></div>
                <span class="bb-badge-chip" data-badge-chip="popular">MOST POPULAR</span>
            </div>
            <div class="bb-rowx">
                <div><div class="name">Background</div></div>
                <?php bb_color_row('labelPopularBg', array('#7b3fd1', '#4f5bd5', '#10976a', '#1b1c18')); ?>
            </div>
            <div class="bb-rowx">
                <div><div class="name">Text color</div></div>
                <?php bb_color_row('labelPopularText', array('#ffffff', '#1b1c18')); ?>
            </div>
        </div>

        <div class="bb-card">
            <div class="bb-card-label">Label badge — Best deal</div>
            <div class="bb-rowx">
                <div><div class="name">Preview</div></div>
                <span class="bb-badge-chip" data-badge-chip="bestdeal">BEST DEAL 🔥</span>
            </div>
            <div class="bb-rowx">
                <div><div class="name">Background</div></div>
                <?php bb_color_row('labelBestdealBg', array('#16a34a', '#10976a', '#c2870e', '#1b1c18')); ?>
            </div>
            <div class="bb-rowx">
                <div><div class="name">Text color</div></div>
                <?php bb_color_row('labelBestdealText', array('#ffffff', '#1b1c18')); ?>
            </div>
        </div>

        <div class="bb-card">
            <div class="bb-card-label">Savings badge</div>
            <div class="bb-rowx">
                <div><div class="name">Preview</div><div class="help">Shows the percentage saved on a tier</div></div>
                <span class="bb-badge-chip" data-badge-chip="save">Save 20%</span>
            </div>
            <div class="bb-rowx">
                <div><div class="name">Background</div></div>
                <?php bb_color_row('saveBadgeBg', array('#10976a', '#4f5bd5', '#e8643c', '#1b1c18')); ?>
            </div>
            <div class="bb-rowx">
                <div><div class="name">Text color</div></div>
                <?php bb_color_row('saveBadgeText', array('#ffffff', '#1b1c18')); ?>
            </div>
        </div>

        <div class="bb-card">
            <div class="bb-card-label">Free shipping badge</div>
            <div class="bb-rowx">
                <div><div class="name">Preview</div><div class="help">Banner shown under a tier</div></div>
                <span class="bb-badge-chip" data-badge-chip="shipping">🚚 Free shipping</span>
            </div>
            <div class="bb-rowx">
                <div><div class="name">Background</div></div>
                <?php bb_color_row('shippingBadgeBg', array('#1b1c18', '#10976a', '#4f5bd5', '#e8643c')); ?>
            </div>
            <div class="bb-rowx">
                <div><div class="name">Text color</div></div>
                <?php bb_color_row('shippingBadgeText', array('#ffffff', '#1b1c18')); ?>
            </div>
        </div>
    </div>
</div>

<?php
ob_start();
?>
<div class="bb-preview-label">Live preview <span class="dot"></span></div>
<div class="bb-preview-card">
    <div class="bb-prod">
        <div class="bb-prod-img"></div>
        <div style="min-width:0;">
            <div class="bb-prod-name">Sample Product</div>
            <div class="bb-prod-sub">Choose your bundle</div>
        </div>
    </div>
    <div class="bb-offers" data-bb-offers></div>
    <button type="button" class="bb-add-cart">Add to cart</button>
</div>
<?php
$aside = ob_get_clean();
bb_admin_shell_close($aside);
