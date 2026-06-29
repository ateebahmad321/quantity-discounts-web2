<?php
/**
 * BulkBoost — Min / Max quantity design page.
 *
 * @package BulkBoost/admin
 */

if (!defined('WPINC')) {
    die;
}

require_once __DIR__ . '/bulkboost-admin-shell.php';

$defaults = array(
    'min_max_background_color_active'   => '#000000',
    'min_max_background_color_inactive' => '#FFFFFF',
    'min_max_background_color_hover'    => '#DDDDDD',
    'min_max_text_color_active'         => '#FFFFFF',
    'min_max_text_color_inactive'       => '#000000',
    'min_max_text_color_hover'          => '#333333',
    'min_max_border_color_active'       => '#000000',
    'min_max_border_color_inactive'     => '#FFFFFF',
    'min_max_border_color_hover'        => '#333333',
    'min_max_size'                      => '16',
);
$options = wp_parse_args(get_option('min_max_bulkboost_settings', array()), $defaults);

blkbst_admin_shell_open(array(
    'active' => 'bulkboost-quantity-min-max',
    'crumb'  => 'Settings',
    'title'  => 'Min / Max Quantity',
    'wide'   => true,
));

/**
 * Render one color row with active / inactive / hover pickers.
 */
$row = function ($name, $prefix) use ($options) {
    echo '<div class="bb-rowx"><div><div class="name">' . esc_html($name) . '</div></div><div class="bb-control">';
    foreach (array('active' => 'Active', 'inactive' => 'Inactive', 'hover' => 'Hover') as $suffix => $label) {
        $key = 'min_max_' . $prefix . '_color_' . $suffix;
        printf(
            '<span style="display:inline-flex;flex-direction:column;gap:4px;"><span style="font-size:11px;color:#9a9c91;text-transform:uppercase;letter-spacing:.05em;">%s</span><input type="text" id="%s" name="min_max_bulkboost_settings[%s]" value="%s" class="color-field"></span>',
            esc_html($label),
            esc_attr($key),
            esc_attr($key),
            esc_attr($options[$key])
        );
    }
    echo '</div></div>';
};
?>

<div class="bb-intro">
    <h2>Min / Max Quantity</h2>
    <p>Style the quantity selector buttons. Customize the colors below and preview updates instantly.</p>
</div>

<form method="post" action="options.php">
    <?php settings_fields('min_max_bulkboost_settings'); ?>

    <div class="bb-card">
        <div class="bb-card-label">Preview</div>
        <div class="bb-rowx no-border">
            <div id="bulkboost_preview_preview" style="display:flex;flex-wrap:wrap;gap:4px;"></div>
        </div>
    </div>

    <div class="bb-card">
        <div class="bb-card-label">Colors</div>
        <?php
        $row('Background color', 'background');
        $row('Text color', 'text');
        $row('Border color', 'border');
        ?>
        <div class="bb-rowx">
            <div><div class="name">Button size</div></div>
            <div class="bb-stepper" style="width:110px;">
                <input type="number" id="min_max_size" name="min_max_bulkboost_settings[min_max_size]"
                       value="<?php echo esc_attr($options['min_max_size']); ?>">
                <span class="suffix">px</span>
            </div>
        </div>
    </div>

    <?php submit_button('Save Changes', 'primary', 'submit', true, array('class' => 'bb-btn bb-btn-primary')); ?>
</form>

<?php
blkbst_admin_shell_close();
