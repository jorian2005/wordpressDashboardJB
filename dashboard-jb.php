<?php
/**
 * Plugin Name: Dashboard JB
 * Description: Een plugin voor het aanpassen van het WordPress dashboard.
 * Version: 1.2
 * Author: Jorian Beukens
 * Author URI: https://jorianbeukens.nl
 * License: GPL3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/maintenance.php';
require_once plugin_dir_path(__FILE__) . 'includes/loginCustomizer.php';
require_once plugin_dir_path(__FILE__) . 'includes/editLogin.php';
require_once plugin_dir_path(__FILE__) . 'includes/seo.php';
require_once plugin_dir_path(__FILE__) . 'includes/redirect.php';
require_once plugin_dir_path(__FILE__) . 'includes/enqueue.php';

require_once plugin_dir_path(__FILE__) . 'admin/dashboard-widget.php';
require_once plugin_dir_path(__FILE__) . 'admin/settings-page.php';
require_once plugin_dir_path(__FILE__) . 'admin/dashboard.php';
require_once plugin_dir_path(__FILE__) . 'admin/analytics.php';

define('IMAGES_PATH', plugin_dir_url(__FILE__) . 'assets/images/');
define('CSS_PATH', plugin_dir_url(__FILE__) . 'assets/css/');
define('JS_PATH', plugin_dir_url(__FILE__) . 'assets/js/');

function plugin_jb_menu() {
    add_menu_page(
        'Dashboard JB Instellingen',      
        'Dashboard JB',                   
        'manage_options',                 
        'dashboard-jb',                   
        '\DashboardJB\dashboard\plugin_jb_settings_page',
        plugin_dir_url(__FILE__) . 'assets/images/logo.svg', 
        2                                 
    );

    add_submenu_page(
        'dashboard-jb',
        'Custom Login Instellingen',
        'Custom Login',
        'manage_options',
        'custom-login-url',
        '\DashboardJB\editLogin\plugin_jb_custom_login_url_page'
    );

    add_submenu_page(
        'dashboard-jb',
        'Onderhoudsmodus Instellingen',
        'Onderhoudsmodus',
        'manage_options',
        'maintenance-mode-settings',
        '\DashboardJB\maintenance\plugin_jb_maintenance_mode_page'
    );

    add_submenu_page(
        'dashboard-jb',
        'SEO Instellingen',
        'SEO Instellingen',
        'manage_options',
        'seo-settings',
        '\DashboardJB\seo\plugin_jb_seo_page'
    );

    add_submenu_page(
        'dashboard-jb',
        'Redirect Instellingen',
        'Redirect Instellingen',
        'manage_options',
        'redirect-settings',
        '\DashboardJB\redirect\plugin_jb_redirect_page'
    );

    add_submenu_page(
        'dashboard-jb',
        'Analytics Instellingen',
        'Analytics',
        'manage_options',
        'site-views-settings',
        '\DashboardJB\analytics\JB_render_analytics_page'
    );
}   
add_action('admin_menu', 'plugin_jb_menu');


