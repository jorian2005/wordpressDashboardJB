<?php
namespace DashboardJB\enqueue;

if (!defined('ABSPATH')) {
    exit;
}

function plugin_jb_enqueue_styles() {
    wp_enqueue_style('dashboard-jb-style', CSS_PATH . 'style.css');
}
add_action('admin_enqueue_scripts', '\DashboardJB\enqueue\plugin_jb_enqueue_styles');

// Enqueue site styles
function plugin_jb_enqueue_site_styles() {
    wp_enqueue_style('dashboard-jb-site-style', CSS_PATH . 'main.css');
}
add_action('wp_enqueue_scripts', '\DashboardJB\enqueue\plugin_jb_enqueue_site_styles');