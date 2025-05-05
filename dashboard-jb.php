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

declare(strict_types=1);

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

function plugin_jb_add_head_data() {
    echo '<!-- Deze site maakt gebruik van de JB PowerPanel plugin.
    Meer info: https://jorianbeukens.nl | GitHub: https://github.com/jorian2005 -->';
}
add_action('wp_head', 'plugin_jb_add_head_data', 1);

function plugin_jb_menu() {
    add_menu_page(
        'JB PowerPanel Instellingen',      
        'JB PowerPanel',
        'manage_options',                 
        'jb-powerpanel',                   
        '\JB_PowerPanel\dashboard\JB_settings_page',
        plugin_dir_url(__FILE__) . 'assets/images/logo.svg',
        2
    );

    add_submenu_page(
        'jb-powerpanel',
        'Custom Login Instellingen',
        'Custom Login',
        'manage_options',
        'custom-login-url',
        '\JB_PowerPanel\editLogin\JB_login_url_page'
    );

    add_submenu_page(
        'jb-powerpanel',
        'Onderhoudsmodus Instellingen',
        'Onderhoudsmodus',
        'manage_options',
        'maintenance-mode-settings',
        '\JB_PowerPanel\maintenance\JB_maintenance_page'
    );

    add_submenu_page(
        'jb-powerpanel',
        'SEO Instellingen',
        'SEO Instellingen',
        'manage_options',
        'seo-settings',
        '\JB_PowerPanel\seo\JB_seo_page'
    );

    add_submenu_page(
        'jb-powerpanel',
        'Redirect Instellingen',
        'Redirect Instellingen',
        'manage_options',
        'redirect-settings',
        '\JB_PowerPanel\redirect\JB_redirect_page'
    );

    add_submenu_page(
        'jb-powerpanel',
        'Analytics Instellingen',
        'Analytics',
        'manage_options',
        'site-views-settings',
        '\JB_PowerPanel\analytics\JB_render_analytics_page'
    );
}   
add_action('admin_menu', 'plugin_jb_menu');


