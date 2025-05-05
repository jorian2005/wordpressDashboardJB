<?php
namespace JB_PowerPanel\dashboardWidget;

if (!defined('ABSPATH')) {
    exit;
}

function JB_dashboard_widget() {
    wp_add_dashboard_widget('JB_dashboard_widget', 'DashboardWidget - JB', '\JB_PowerPanel\dashboardWidget\JB_widget_callback', null, null, 'normal', 'high');
}
add_action('wp_dashboard_setup', '\JB_PowerPanel\dashboardWidget\JB_dashboard_widget');

function JB_widget_callback() {
    $options = get_option('dashboard_jb_settings');
    $custom_text = isset($options['widget_text']) ? esc_html($options['widget_text']) : 'Welkom op het dashboard!';
    echo '<h2>' . $custom_text . '</h2>
    <p>Deze plugin is gemaakt door <a href="https://jorianbeukens.nl" target="_blank">Jorian Beukens</a>.</p>';
}
