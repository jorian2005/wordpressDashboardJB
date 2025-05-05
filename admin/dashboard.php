<?php
namespace JB_PowerPanel\dashboard;

if (!defined('ABSPATH')) {
    exit;
}

function plugin_jb_settings_init() {
    register_setting('dashboard_jb_settings_group', 'dashboard_jb_settings');

    add_settings_section(
        'dashboard_jb_settings_section',
        'Algemene Instellingen',
        '\JB_PowerPanel\dashboard\plugin_jb_settings_section_callback',
        'dashboard-jb'
    );

    add_settings_field(
        'dashboard_jb_field_example',
        'Widget Tekst',
        '\JB_PowerPanel\dashboard\JB_settings_field_callback',
        'dashboard-jb',
        'dashboard_jb_settings_section'
    );
}
add_action('admin_init', '\JB_PowerPanel\dashboard\plugin_jb_settings_init');

function plugin_jb_settings_section_callback() {
    echo '<p>Pas de instellingen van de Dashboard JB plugin aan.</p>';
}

function JB_settings_page() {
    ?>
    <div class="wrap">
        <h1>Dashboard JB Instellingen</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('dashboard_jb_settings_group');
            do_settings_sections('dashboard-jb');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function JB_settings_field_callback() {
    $options = get_option('dashboard_jb_settings');
    $widgetText = isset($options['widget_text']) ? $options['widget_text'] : '';
    if (empty($widgetText)) {
        $widgetText = 'Welkom op het Dashboard!'; 
    }
    ?>
    <input type="text" name="dashboard_jb_settings[widget_text]" value="<?= $widgetText ?>">
    <?php
}
