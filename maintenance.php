<?php

if (!defined('ABSPATH')) {
    exit;
}

function my_plugin_maintenance_mode_page() {
    wp_enqueue_media();
    ?>
    <div class="wrap">
        <h1>Onderhoudsmodus</h1>

        <?php settings_errors('maintenance-mode'); ?>

        <form method="post" action="options.php">
            <?php
            settings_fields('maintenance-mode');
            do_settings_sections('maintenance-mode');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function my_plugin_maintenance_mode_settings_init() {
    register_setting('maintenance-mode', 'maintenance_mode_settings');

    add_settings_section(
        'maintenance-mode',
        'Instellingen',
        'my_plugin_maintenance_mode_section_callback',
        'maintenance-mode'
    );

    add_settings_field(
        'maintenance_mode_field_enabled',
        'Onderhoudsmodus Inschakelen',
        'my_plugin_maintenance_mode_field_callback',
        'maintenance-mode',
        'maintenance-mode'
    );

    add_settings_section(
        'maintenance-mode-styling',
        'Styling',
        'my_plugin_maintenance_styling_section_callback',
        'maintenance-mode'
    );

    add_settings_field(
        'maintenance_mode_field_title',
        'Titel',
        'my_plugin_maintenance_mode_title_field_callback',
        'maintenance-mode',
        'maintenance-mode'
    );

    add_settings_field(
        'maintenance_mode_field_message',
        'Bericht',
        'my_plugin_maintenance_mode_message_field_callback',
        'maintenance-mode',
        'maintenance-mode'
    );

    add_settings_field(
        'maintenance_mode_field_logo',
        'Logo',
        'my_plugin_maintenance_mode_logo_field_callback',
        'maintenance-mode',
        'maintenance-mode-styling'
    );

    add_settings_field(
        'maintenance_mode_field_image',
        'Achtergrondafbeelding',
        'my_plugin_maintenance_mode_image_field_callback',
        'maintenance-mode',
        'maintenance-mode-styling'
    );
}
add_action('admin_init', 'my_plugin_maintenance_mode_settings_init');

function enable_maintenance_mode() {
    $options = get_option('maintenance_mode_settings');
    if (isset($options['enabled']) && $options['enabled'] == 1 && !current_user_can('manage_options')) {
        $title = isset($options['title']) ? esc_html($options['title']) : 'Website in onderhoud';
        $message = isset($options['message']) ? esc_html($options['message']) : 'Onze site is tijdelijk offline voor onderhoud. Kom later terug.';
        $background = isset($options['image']) ? esc_url($options['image']) : '';
        $logo = isset($options['logo']) ? esc_url($options['logo']) : '';
        if (!$background) {
            $background = plugin_dir_url(__FILE__) . 'achtergrond.webp';
        }
        if (!$logo) {
            $logo = plugin_dir_url(__FILE__) . 'logo.svg';
        }
        if (empty($title)) {
            $title = 'Website in onderhoud';
        }
        if (empty($message)) {
            $message = 'Onze site is tijdelijk offline voor onderhoud. Kom later terug.';
        }

        wp_die('
        <style>
            html {
                background-image: url(' . $background . ');
                background-size: cover;
                background-position: center;
            }
        </style>
        
        <div style="text-align:center; margin-top: 50px; font-family: Arial, sans-serif;">
            <img src=" ' . $logo  . '" style="width: 100px;">
            <h1 style="color:#333;">' . $title . '</h1>
            <p style="color:#555; font-size:18px;">' . $message . '</p>
        </div>', $title, ['response' => 503]);
    }
}
add_action('template_redirect', 'enable_maintenance_mode');
