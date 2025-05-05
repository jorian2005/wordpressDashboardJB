<?php
namespace JB_PowerPanel\editLogin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function JB_login_url_page() {
    wp_enqueue_media();

    ?>
    <div class="wrap">
        <h1>Custom Login Instellingen</h1>

        <?php
        settings_errors('JB_login_login_slug');
        ?>

        <form method="post" action="options.php">
            <?php
            settings_fields('JB_login_settings_group');
            do_settings_sections('custom-login-url');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function JB_login_register_settings() {
    register_setting('JB_login_settings_group', 'JB_login_login_slug', 'JB_login_validate_login_slug');
    register_setting('JB_login_settings_group', 'JB_login_login_text');
    register_setting('JB_login_settings_group', 'JB_login_login_color');
    register_setting('JB_login_settings_group', 'JB_login_login_second_color');
    register_setting('JB_login_settings_group', 'JB_login_login_logo_image');	
    register_setting('JB_login_settings_group', 'JB_login_login_background_image');
    add_settings_section('JB_login_main_section', 'Instellingen', null, 'custom-login-url');
    add_settings_section('JB_login_colors_section', 'Kleuren', null, 'custom-login-url');
    add_settings_section('JB_login_images_section', 'Afbeeldingen', null, 'custom-login-url');
    // Algemeen
    add_settings_field('JB_login_login_slug', 'Loginslug:', '\JB_PowerPanel\editLogin\JB_login_login_slug_callback', 'custom-login-url', 'JB_login_main_section');
    add_settings_field('JB_login_login_text', 'Footertekst:', '\JB_PowerPanel\editLogin\JB_login_login_text_callback', 'custom-login-url', 'JB_login_main_section');
    // Kleuren
    add_settings_field('JB_login_login_color', 'Thema kleur:', '\JB_PowerPanel\editLogin\JB_login_login_theme_color_callback', 'custom-login-url', 'JB_login_colors_section');
    add_settings_field('JB_login_login_second_color', 'Tweede kleur:', '\JB_PowerPanel\editLogin\JB_login_login_second_color_callback', 'custom-login-url', 'JB_login_colors_section');
    // Afbeeldingen
    add_settings_field('JB_login_login_logo_image', 'Logo afbeelding:', '\JB_PowerPanel\editLogin\JB_login_login_logo_image_callback', 'custom-login-url', 'JB_login_images_section');
    add_settings_field('JB_login_login_background_image', 'Achtergrondafbeelding:', '\JB_PowerPanel\editLogin\JB_login_login_background_image_callback', 'custom-login-url', 'JB_login_images_section');
}
add_action('admin_init', '\JB_PowerPanel\editLogin\JB_login_register_settings');

function JB_login_login_slug_callback() {
    $value = get_option('JB_login_login_slug', 'mijn-login'); ?>
    <input type="text" name="JB_login_login_slug" value="<?= esc_attr($value) ?>" />
    <p class="description">De nieuwe login slug. Gebruik alleen letters, cijfers en streepjes.</p>
    <?php
}

function JB_login_login_text_callback() {
    $value = get_option('JB_login_login_text', '&copy; ' . date('Y') . ' - ' . get_bloginfo('name')); ?>
    <input type="text" name="JB_login_login_text" value="<?= esc_attr($value)?>" />
    <p class="description">De tekst die onderaan het inlogscherm wordt weergegeven.</p>
    <?php
}

function JB_login_login_theme_color_callback() {
    $value = get_option('JB_login_login_color', '#ff0402'); ?>
    <input type="color" name="JB_login_login_color" value="<?= esc_attr($value) ?>" />
    <p class="description">De standaard kleur van de login pagina</p>
    <?php
}

function JB_login_login_second_color_callback() {
    $value = get_option('JB_login_login_second_color', '#ff0402'); ?>
    <input type="color" name="JB_login_login_second_color" value="<?= esc_attr($value) ?>" />
    <p class="description">De tweede kleur van de loginpagina (hoverstates)</p>
    <?php
}

function JB_login_login_background_image_callback() {
    $default_image = IMAGES_PATH . 'achtergrond.webp';
    $value = get_option('JB_login_login_background_image', '');

    if (empty($value)) {
        $value = $default_image;
    }
    ?>
    <div style="margin-bottom: 10px;">
        <img id="JB_login_background_preview" src="<?= esc_url($value); ?>" style="max-width: 300px; max-height: 150px; display: block;" />
    </div>
    <button type="button" class="button" id="JB_login_upload_background_button">Afbeelding kiezen</button>
    <input type="hidden" id="JB_login_login_background_image" name="JB_login_login_background_image" value="<?= esc_attr($value); ?>" />
    <button type="button" class="button button-secondary" id="JB_login_remove_background_button" style="display: <?= ($value !== $default_image) ? 'inline-block' : 'none'; ?>;">Verwijderen</button>
    <p class="description">Selecteer of upload een achtergrondafbeelding voor het inlogscherm.</p>

    <script>
        jQuery(document).ready(function($) {
            var mediaUploader;

            $('#JB_login_upload_background_button').click(function(e) {
                e.preventDefault();

                if (typeof wp.media === 'undefined') {
                    return;
                }

                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }

                mediaUploader = wp.media({
                    title: 'Kies een achtergrondafbeelding',
                    button: { text: 'Gebruik deze afbeelding' },
                    multiple: false
                });

                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    $('#JB_login_login_background_image').val(attachment.url);
                    $('#JB_login_background_preview').attr('src', attachment.url).show();
                    $('#JB_login_remove_background_button').show();
                });

                mediaUploader.open();
            });

            $('#JB_login_remove_background_button').click(function() {
                $('#JB_login_login_background_image').val('');
                $('#JB_login_background_preview').attr('src', '<?= esc_url($default_image); ?>').show();
                $(this).hide();
            });
        });
    </script>
    <?php
}

function JB_login_login_logo_image_callback() {
    $default_logo = IMAGES_PATH . 'logo.svg';
    $value = get_option('JB_login_login_logo_image', '');

    if (empty($value)) {
        $value = $default_logo;
    }
    ?>
    <div style="margin-bottom: 10px;">
        <img id="JB_login_logo_preview" src="<?= esc_url($value); ?>" style="height: 150px; display: block;" />
    </div>
    <button type="button" class="button" id="JB_login_upload_logo_button">Afbeelding kiezen</button>
    <input type="hidden" id="JB_login_login_logo_image" name="JB_login_login_logo_image" value="<?= esc_attr($value); ?>" />
    <button type="button" class="button button-secondary" id="JB_login_remove_logo_button" style="display: <?= ($value !== $default_logo) ? 'inline-block' : 'none'; ?>;">Verwijderen</button>
    <p class="description">Selecteer of upload een achtergrondafbeelding voor het inlogscherm.</p>

    <script>
        jQuery(document).ready(function($) {
            var mediaUploader;

            $('#JB_login_upload_logo_button').click(function(e) {
                e.preventDefault();

                if (typeof wp.media === 'undefined') {
                    return;
                }

                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }

                mediaUploader = wp.media({
                    title: 'Kies een achtergrondafbeelding',
                    button: { text: 'Gebruik deze afbeelding' },
                    multiple: false
                });

                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    $('#JB_login_login_logo_image').val(attachment.url);
                    $('#JB_login_logo_preview').attr('src', attachment.url).show();
                    $('#JB_login_remove_logo_button').show();
                });

                mediaUploader.open();
            });

            $('#JB_login_remove_logo_button').click(function() {
                $('#JB_login_login_logo_image').val('');
                $('#JB_login_logo_preview').attr('src', '<?= esc_url($default_logo); ?>').show();
                $(this).hide();
            });
        });
    </script>
    <?php
}

function JB_login_enqueue_admin_scripts($hook) {
    error_log("Hook geladen: " . $hook);

    if ($hook === 'settings_page_custom-login-url') { 
        wp_enqueue_media();
        wp_enqueue_script('jquery');
        error_log("wp_enqueue_media() is geladen op: " . $hook);
    }
}
add_action('admin_enqueue_scripts', '\JB_PowerPanel\editLogin\JB_login_enqueue_admin_scripts');

function JB_login_validate_login_slug($input) {
    if ($input === 'wp-admin' || $input === 'wp-login.php') {
        add_settings_error(
            'JB_login_login_slug',
            'invalid_slug',
            'De slug kan niet "wp-admin" of "wp-login.php" zijn.',
            'error'
        );
        return get_option('JB_login_login_slug');
    }
    
    return sanitize_text_field($input);
}

function JB_login_block_default_login() {
    if (strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false && $_SERVER['REQUEST_METHOD'] !== 'POST' && (!isset($_GET['action']) || $_GET['action'] !== 'logout')) {
        wp_redirect(home_url());
        exit;
    }
    if (strpos($_SERVER['REQUEST_URI'], 'wp-admin') !== false && !is_user_logged_in()) {
        wp_redirect(home_url());
        exit;
    }
}
add_action('init', '\JB_PowerPanel\editLogin\JB_login_block_default_login');

function JB_login_custom_login() {
    $custom_slug = get_option('JB_login_login_slug', 'inloggen');
    
    if (strpos($_SERVER['REQUEST_URI'], '/' . $custom_slug) !== false) {
        global $error, $user_login;
        
        $error = isset($error) ? $error : '';
        $user_login = isset($user_login) ? $user_login : '';
        
        $_GET['redirect_to'] = home_url();
        $_REQUEST['log'] = isset($_REQUEST['log']) ? sanitize_user($_REQUEST['log']) : '';
        
        require_once ABSPATH . 'wp-login.php';
        exit;
    }
}
add_action('init', '\JB_PowerPanel\editLogin\JB_login_custom_login');

function JB_login_handle_logout() {
    if (isset($_GET['action']) && $_GET['action'] === 'logout') {
        check_admin_referer('log-out');
        $user = wp_get_current_user();
        wp_logout();
        
        $custom_slug = get_option('JB_login_login_slug', 'mijn-login');
        $redirect_to = add_query_arg(
            array(
                'loggedout' => 'true',
                'wp_lang'   => get_user_locale($user),
            ),
            home_url('/' . $custom_slug)
        );

        wp_safe_redirect($redirect_to);
        exit;
    }
}
add_action('init', '\JB_PowerPanel\editLogin\JB_login_handle_logout');
