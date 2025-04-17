<?php
namespace DashboardJB\dashboard;

if (!defined('ABSPATH')) {
    exit;
}

function plugin_jb_settings_init() {
    register_setting('dashboard_jb_settings_group', 'dashboard_jb_settings');

    add_settings_section(
        'dashboard_jb_settings_section',
        'Algemene Instellingen',
        '\DashboardJB\dashboard\plugin_jb_settings_section_callback',
        'dashboard-jb'
    );

    add_settings_field(
        'dashboard_jb_field_example',
        'Voorbeeld Instelling',
        '\DashboardJB\dashboard\plugin_jb_settings_field_callback',
        'dashboard-jb',
        'dashboard_jb_settings_section'
    );
}
add_action('admin_init', '\DashboardJB\dashboard\plugin_jb_settings_init');

function plugin_jb_settings_section_callback() {
    echo '<p>Pas de instellingen van de Dashboard JB plugin aan.</p>';
}

function plugin_jb_settings_page() {
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

function plugin_jb_settings_field_callback() {
    $options = get_option('dashboard_jb_settings');
    $example = isset($options['example']) ? $options['example'] : '';
    if (empty($example)) {
        $example = 'Welkom op het Dashboard!'; 
    }
    ?>
    <input type="text" name="dashboard_jb_settings[example]" value="<?= $example ?>">
    <?php
}
