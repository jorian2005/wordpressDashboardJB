<?php
namespace DashboardJB\dashboardWidget;

if (!defined('ABSPATH')) {
    exit;
}

function my_dashboard_widget() {
    wp_add_dashboard_widget('my_dashboard_widget', 'DashboardWidget - JB', '\DashboardJB\dashboardWidget\my_dashboard_widget_callback');
}
add_action('wp_dashboard_setup', '\DashboardJB\dashboardWidget\my_dashboard_widget');

function my_dashboard_widget_callback() {
    $options = get_option('dashboard_jb_settings');
    $custom_text = isset($options['example']) ? esc_html($options['example']) : 'Welkom op het dashboard!';
    echo '<h2>' . $custom_text . '</h2>
    <p>Deze plugin is gemaakt door <a href="https://jorianbeukens.nl" target="_blank">Jorian Beukens</a>.</p>';
}
