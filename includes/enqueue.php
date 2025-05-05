<?php

declare(strict_types=1);

namespace JB_PowerPanel\enqueue;

if (!defined('ABSPATH')) {
    exit;
}

function plugin_jb_enqueue_styles() {
    wp_enqueue_style('dashboard-jb-style', CSS_PATH . 'style.css');
}
add_action('admin_enqueue_scripts', '\JB_PowerPanel\enqueue\plugin_jb_enqueue_styles');


