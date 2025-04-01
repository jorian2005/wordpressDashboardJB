<?php

if (!defined('ABSPATH')) {
    exit;
}

function my_dashboard_widget_callback() {
    $options = get_option('maintenance_mode_settings');
    $enabled = isset($options['enabled']) ? $options['enabled'] : 0;

    echo '<div class="dashboard-jb-widget">';
    echo '<h2>Onderhoudsmodus</h2>';
    echo '<p>Schakel de onderhoudsmodus in of uit met onderstaande knop.</p>';
    echo '<button id="toggle-maintenance" class="button ' . ($enabled ? 'button-primary' : 'button-secondary') . '">';
    echo $enabled ? 'Onderhoudsmodus Uitschakelen' : 'Onderhoudsmodus Inschakelen';
    echo '</button>';
    echo '</div>';
}

function my_dashboard_widget() {
    wp_add_dashboard_widget('my_dashboard_widget', 'Onderhoudsmodus - JB', 'my_dashboard_widget_callback');
}
add_action('wp_dashboard_setup', 'my_dashboard_widget');

function toggle_maintenance_mode() {
    check_ajax_referer('toggle-maintenance', 'security');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Geen toestemming.']);
    }

    $enabled = isset($_POST['enabled']) ? (int) $_POST['enabled'] : 0;

    $options = get_option('maintenance_mode_settings', []);
    $options['enabled'] = $enabled;
    update_option('maintenance_mode_settings', $options);

    wp_send_json_success(['enabled' => $enabled]);
    wp_die();
}
add_action('wp_ajax_toggle_maintenance_mode', 'toggle_maintenance_mode');

function my_plugin_admin_scripts() {
    wp_enqueue_script('jquery');

    wp_enqueue_script('maintenance-mode-script', plugin_dir_url(__FILE__) . 'js/maintenance-mode.js', ['jquery'], null, true);

    wp_localize_script('maintenance-mode-script', 'maintenanceAjax', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('toggle-maintenance')
    ]);
}
add_action('admin_enqueue_scripts', 'my_plugin_admin_scripts');
