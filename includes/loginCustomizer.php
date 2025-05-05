<?php
namespace JB_PowerPanel\loginCustomizer;

if (!defined('ABSPATH')) {
    exit;
}

$default_image = plugin_dir_url(__FILE__) . "achtergrond.webp";
$default_logo = plugin_dir_url(__FILE__) . "logo.svg";

function JB_login_enqueue_styles() {
    $plugin_url = plugin_dir_url(__FILE__);
    wp_enqueue_style('JB-login-style', CSS_PATH . 'login.css', [], '1.0.0');
    $loginColor = get_option('JB_login_login_color', '#ff0402');
    $loginSecondColor = get_option('JB_login_login_second_color', '#ff0402');
    $background_image = get_option('JB_login_login_background_image', $plugin_url . 'achtergrond.webp');
    $logo_image = get_option('JB_login_login_logo_image', $plugin_url . 'logo.svg');

    if (empty($background_image)) {
        $background_image = $default_image;
    }
    if (empty($logo_image)) {
        $logo_image = $default_logo;
    }

    $custom_css = "
        :root {
            --login-color: {$loginColor};
            --login-second-color: {$loginSecondColor};
        }
        body.login {
            background-image: url('{$background_image}');
        }
        .login h1 a {
            background-image: url('{$logo_image}');
        }
    ";
    wp_add_inline_style('JB-login-style', $custom_css);
}
add_action('login_enqueue_scripts', '\JB_PowerPanel\loginCustomizer\JB_login_enqueue_styles');

function JB_styling_login() {
    if ($GLOBALS['pagenow'] === 'wp-login.php') {
        wp_enqueue_style('JB-styling-login', CSS_PATH . 'login.css');
    }
}
add_action('admin_enqueue_scripts', '\JB_PowerPanel\loginCustomizer\JB_styling_login');

function JB_login_url() {
    return home_url();
}
add_filter("login_headerurl", "\JB_PowerPanel\loginCustomizer\JB_login_url");

function JB_login_title() {
    return "Welkom op mijn website!";
}
add_filter("login_headertitle", "\JB_PowerPanel\loginCustomizer\JB_login_title");

function JB_login_message() {
    $loginText = get_option("JB_login_login_text", "&copy; " . date("Y") . " - " . get_bloginfo("name"));
    echo '<p class="bottom-text-login">' . $loginText . '</p>';
}
add_action("login_footer", "\JB_PowerPanel\loginCustomizer\JB_login_message");
