<?php
/**
 * Plugin Name: Dashboard JB
 * Description: Een plugin voor het aanpassen van het WordPress dashboard.
 * Version: 1.1
 * Author: Jorian Beukens
 * Author URI: https://jorianbeukens.nl
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * 
 */

 if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'maintenance.php';
require_once plugin_dir_path(__FILE__) . 'loginCustomizer.php';
require_once plugin_dir_path(__FILE__) . 'editLogin.php';
require_once plugin_dir_path(__FILE__) . 'seo.php';
require_once plugin_dir_path(__FILE__) . 'redirect.php';

function plugin_jb_menu() {
    add_menu_page(
        'Dashboard JB Instellingen',      
        'Dashboard JB',                   
        'manage_options',                 
        'dashboard-jb',                   
        'plugin_jb_settings_page',        
        plugin_dir_url(__FILE__) . 'logo.svg', 
        2                                 
    );

    add_submenu_page(
        'dashboard-jb',
        'Custom Login Instellingen',
        'Custom Login',
        'manage_options',
        'custom-login-url',
        'plugin_jb_custom_login_url_page'
    );

    add_submenu_page(
        'dashboard-jb',
        'Onderhoudsmodus Instellingen',
        'Onderhoudsmodus',
        'manage_options',
        'maintenance-mode-settings',
        'plugin_jb_maintenance_mode_page'
    );

    add_submenu_page(
        'dashboard-jb',
        'SEO Instellingen',
        'SEO Instellingen',
        'manage_options',
        'seo-settings',
        'plugin_jb_seo_page'
    );

    add_submenu_page(
        'dashboard-jb',
        'Redirect Instellingen',
        'Redirect Instellingen',
        'manage_options',
        'redirect-settings',
        'plugin_jb_redirect_page'
    );
}   
add_action('admin_menu', 'plugin_jb_menu');

function plugin_jb_enqueue_styles() {
    wp_enqueue_style('dashboard-jb-style', plugin_dir_url(__FILE__) . 'style.css');
}
add_action('admin_enqueue_scripts', 'plugin_jb_enqueue_styles');

function plugin_jb_enqueue_site_styles() {
    wp_enqueue_style('dashboard-jb-site-style', plugin_dir_url(__FILE__) . 'main.css');
}
add_action('wp_enqueue_scripts', 'plugin_jb_enqueue_site_styles');

function my_dashboard_widget() {
    wp_add_dashboard_widget('my_dashboard_widget', 'DashboardWidget - JB', 'my_dashboard_widget_callback');
}
add_action('wp_dashboard_setup', 'my_dashboard_widget');

function my_dashboard_widget_callback() {
    $options = get_option('dashboard_jb_settings');
    $custom_text = isset($options['example']) ? esc_html($options['example']) : 'Welkom op het dashboard!';
    echo '<h2>' . $custom_text . '</h2>
    <p>Deze plugin is gemaakt door <a href="https://jorianbeukens.nl" target="_blank">Jorian Beukens</a>.</p>';
}

function plugin_jb_settings_init() {
    register_setting('dashboard_jb_settings_group', 'dashboard_jb_settings');

    add_settings_section(
        'dashboard_jb_settings_section',
        'Algemene Instellingen',
        'plugin_jb_settings_section_callback',
        'dashboard-jb'
    );

    add_settings_field(
        'dashboard_jb_field_example',
        'Voorbeeld Instelling',
        'plugin_jb_settings_field_callback',
        'dashboard-jb',
        'dashboard_jb_settings_section'
    );
}
add_action('admin_init', 'plugin_jb_settings_init');

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
    ?>
    <input type="text" name="dashboard_jb_settings[example]" value="<?php echo isset($options['example']) ? esc_attr($options['example']) : ''; ?>">
    <?php
}
