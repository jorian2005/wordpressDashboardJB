<?php
namespace DashboardJB\loginCustomizer;

if (!defined('ABSPATH')) {
    exit;
}

$loginText = get_option('clu_login_text', '&copy; ' . date('Y') . ' - ' . get_bloginfo('name'));
$loginColor = get_option('clu_login_color', '#ff0402');
$loginSecondColor = get_option('clu_login_second_color', '#ff0402');
$default_image = plugin_dir_url(__FILE__) . 'achtergrond.webp';
$default_logo = plugin_dir_url(__FILE__) . 'logo.svg';
$background_image = get_option('clu_login_background_image', '');
$logo_image = get_option('clu_login_logo_image', '');

if (empty($background_image)) {
    $background_image = $default_image; 
}
if (empty($logo_image)) {
    $logo_image = $default_logo;
}

function custom_login_style() {
    global $loginColor;
    global $loginSecondColor;
    global $background_image;
    global $logo_image;
    echo '<style type="text/css">
        body.login {
            background-image: url("' . esc_url($background_image) . '");
            background-size: cover;
            background-position: center;
        }
        .login h1 a {
            background-image: url("' . esc_url($logo_image) . '");
            background-size: contain;
            background-repeat: no-repeat;
            height: 150px;
            width: 100%;
        }
    </style>';
    echo '<style type="text/css">
        #login-message {
            border-left: 4px solid ' . $loginColor . ';
        }
        .login form {
            background: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
        }
        .login input[type="text"], .login input[type="password"] {
            border: 1px solid #ccc;
            padding: 10px;
            font-size: 16px;
        }
        .language-switcher {
            display: none;
        }
        #backtoblog {
            display: none;
        }
        .wp-core-ui .button-primary {
            background-color: ' . $loginColor .';
            border-color: ' . $loginSecondColor .';
        }
        .wp-core-ui .button-primary:hover {
            background-color: ' . $loginSecondColor .';
            border-color: ' . $loginColor .';
        }
        .login #backtoblog a, .login #nav a {
            color: #fff;
            justify-content: center;
        }
        .bottom-text-login {
            text-align:center; 
            font-size: 14px; 
            color: #fff; 
            position: absolute; 
            bottom: 10px; 
            width: 100%;
        }
        #nav {
            display: flex;
            justify-content: center;
        }
    </style>';
}
add_action( 'login_head', '\DashboardJB\loginCustomizer\custom_login_style' );

function custom_login_url() {
    return home_url();
}
add_filter( 'login_headerurl', '\DashboardJB\loginCustomizer\custom_login_url' );

function custom_login_title() {
    return 'Welkom op mijn website!';
}
add_filter( 'login_headertitle', '\DashboardJB\loginCustomizer\custom_login_title' );

function custom_login_message() {
    global $loginText;	

    echo '<p class="bottom-text-login">' . $loginText .'</p>';
}
add_action( 'login_footer', '\DashboardJB\loginCustomizer\custom_login_message' );