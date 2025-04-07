<?php

if (!defined('ABSPATH')) {
    exit;
}

function plugin_jb_maintenance_mode_page() {
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

function plugin_jb_maintenance_mode_settings_init() {
    register_setting('maintenance-mode', 'maintenance_mode_settings');

    add_settings_section(
        'maintenance-mode',
        'Instellingen',
        'plugin_jb_maintenance_mode_section_callback',
        'maintenance-mode'
    );

    add_settings_field(
        'maintenance_mode_field_enabled',
        'Onderhoudsmodus Inschakelen',
        'plugin_jb_maintenance_mode_field_callback',
        'maintenance-mode',
        'maintenance-mode'
    );

    add_settings_section(
        'maintenance-mode-styling',
        'Styling',
        'plugin_jb_maintenance_styling_section_callback',
        'maintenance-mode'
    );

    add_settings_field(
        'maintenance_mode_field_title',
        'Titel',
        'plugin_jb_maintenance_mode_title_field_callback',
        'maintenance-mode',
        'maintenance-mode'
    );

    add_settings_field(
        'maintenance_mode_field_message',
        'Bericht',
        'plugin_jb_maintenance_mode_message_field_callback',
        'maintenance-mode',
        'maintenance-mode'
    );

    add_settings_field(
        'maintenance_mode_field_logo',
        'Logo',
        'plugin_jb_maintenance_mode_logo_field_callback',
        'maintenance-mode',
        'maintenance-mode-styling'
    );

    add_settings_field(
        'maintenance_mode_field_image',
        'Achtergrondafbeelding',
        'plugin_jb_maintenance_mode_image_field_callback',
        'maintenance-mode',
        'maintenance-mode-styling'
    );
}
add_action('admin_init', 'plugin_jb_maintenance_mode_settings_init');

function plugin_jb_maintenance_mode_section_callback() {
    echo '<p>Configureer hier de instellingen voor de onderhoudsmodus.</p>';
}

function plugin_jb_maintenance_styling_section_callback() {
    echo '<p>Configureer hier de stijl van de onderhoudsmodus.</p>';
}

function plugin_jb_maintenance_mode_field_callback() {
    $options = get_option('maintenance_mode_settings');
    $enabled = isset($options['enabled']) ? esc_attr($options['enabled']) : '';
    echo '<input type="checkbox" id="maintenance_mode_field_enabled" name="maintenance_mode_settings[enabled]" value="1" ' . checked(1, $enabled, false) . '>';
}

function plugin_jb_maintenance_mode_title_field_callback() {
    $options = get_option('maintenance_mode_settings');
    $title = isset($options['title']) ? esc_attr($options['title']) : '';
    echo '<input type="text" id="maintenance_mode_field_title" name="maintenance_mode_settings[title]" value="' . $title . '">';
}

function plugin_jb_maintenance_mode_message_field_callback() {
    $options = get_option('maintenance_mode_settings');
    $message = isset($options['message']) ? esc_attr($options['message']) : '';
    echo '<textarea id="maintenance_mode_field_message" name="maintenance_mode_settings[message]" rows="5" cols="50">' . $message . '</textarea>';
}

function plugin_jb_maintenance_mode_logo_field_callback() {
    $options = get_option('maintenance_mode_settings');
    $logo = isset($options['logo']) ? esc_attr($options['logo']) : '';
    echo '<input type="hidden" id="maintenance_mode_field_logo" name="maintenance_mode_settings[logo]" value="' . $logo . '">';
    echo '<button id="maintenance_mode_field_logo_button" class="button">Selecteer Logo</button>';
    if ($logo) {
        echo '<button id="maintenance_mode_field_logo_remove_button" class="button">Verwijder Logo</button>';
    }
    echo '<div id="maintenance_mode_field_logo_preview">' . ($logo ? '<img src="' . $logo . '" style="max-width: 100px;">' : '') . '</div>';
    ?>
    <script>
        jQuery(document).ready(function($) {
            $('#maintenance_mode_field_logo_button').click(function(e) {
                e.preventDefault();
                var image = wp.media({
                    title: 'Selecteer Logo',
                    multiple: false
                }).open().on('select', function(e) {
                    var uploaded_image = image.state().get('selection').first();
                    var image_url = uploaded_image.toJSON().url;
                    $('#maintenance_mode_field_logo').val(image_url);
                    $('#maintenance_mode_field_logo_preview').html('<img src="' + image_url + '" style="max-width: 100px;">');
                });
            });

            $('#maintenance_mode_field_logo_remove_button').click(function(e) {
                e.preventDefault();
                $('#maintenance_mode_field_logo').val('');
                $('#maintenance_mode_field_logo_preview').html('');
            });
        });
    </script>
    <?php
}

function plugin_jb_maintenance_mode_image_field_callback() {
    $options = get_option('maintenance_mode_settings');
    $image = isset($options['image']) ? esc_attr($options['image']) : '';
    echo '<input type="hidden" id="maintenance_mode_field_image" name="maintenance_mode_settings[image]" value="' . $image . '">';
    echo '<button id="maintenance_mode_field_image_button" class="button">Selecteer Afbeelding</button>';
    if ($image) {
        echo '<button id="maintenance_mode_field_image_remove_button" class="button">Verwijder Afbeelding</button>';
    }
    echo '<div id="maintenance_mode_field_image_preview">' . ($image ? '<img src="' . $image . '" style="max-height: 100px;">' : '') . '</div>';
    ?>
    <script>
        jQuery(document).ready(function($) {
            $('#maintenance_mode_field_image_button').click(function(e) {
                e.preventDefault();
                var image = wp.media({
                    title: 'Selecteer Afbeelding',
                    multiple: false
                }).open().on('select', function(e) {
                    var uploaded_image = image.state().get('selection').first();
                    var image_url = uploaded_image.toJSON().url;
                    $('#maintenance_mode_field_image').val(image_url);
                    $('#maintenance_mode_field_image_preview').html('<img src="' + image_url + '" style="max-height: 100px;">');
                });
            });

            $('#maintenance_mode_field_image_remove_button').click(function(e) {
                e.preventDefault();
                $('#maintenance_mode_field_image').val('');
                $('#maintenance_mode_field_image_preview').html('');
            });
        });
    </script>
    <?php
}

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
