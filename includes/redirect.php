<?php 

declare(strict_types=1);

namespace JB_PowerPanel\redirect;

if (!defined('ABSPATH')) {
    exit;
}

function JB_styling_redirect($hook) {
    if ($hook !== 'jb-powerpanel_page_redirect-settings') {
        return;
    }
    wp_enqueue_style('JB-styling-redirect', CSS_PATH . 'redirect.css');
    wp_enqueue_script('JB-styling-redirect', JS_PATH . 'redirect.js', array('jquery'), '1.0.0', true);
}
add_action('admin_enqueue_scripts', '\JB_PowerPanel\redirect\JB_styling_redirect');

function JB_redirect_page() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['redirect_nonce']) && wp_verify_nonce($_POST['redirect_nonce'], 'redirect_save')) {
        $redirect_from = $_POST['redirect_from'] ?? [];
        $redirect_to = $_POST['redirect_to'] ?? [];
        $redirects = [];

        foreach ($redirect_from as $index => $from) {
            $from = trim($from);
            $to = trim($redirect_to[$index] ?? '');
            if (!empty($from) && !empty($to)) {
                $redirects[] = $from . '=>' . $to;
            }
        }

        update_option('mijn_seo_plugin_redirects', $redirects);
        echo '<div class="updated"><p>Redirects succesvol opgeslagen.</p></div>';
    }    
    ?>

    <div class="wrap">
        <h1>Redirect Instellingen</h1>
        <p>Hier kun je redirects instellen van oude naar nieuwe URL's. Vul de oude URL in het eerste veld in en de nieuwe URL in het tweede veld.</p>
        <p>Bijvoorbeeld: <strong>Van:</strong> /oude-pagina <strong>Naar:</strong> https://voorbeeld.com/nieuwe-pagina</p>
        <p>Of: <strong>Van:</strong> /oude-pagina <strong>Naar:</strong> /nieuwe-pagina</p>
        <p>Let op: om naar een externe URL te redirecten is het nodig om https:// of http:// voor de link te plaatsen.</p>

        <form method="post" action="">
            <?php wp_nonce_field('redirect_save', 'redirect_nonce'); ?>
            <table class="form-table">
                <thead>
                    <tr>
                        <th scope="col" style="width: 45%;">Van (oude URL)</th>
                        <th scope="col" style="width: 45%;">Naar (nieuwe URL)</th>
                        <th scope="col" style="width: 10%;">Acties</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $saved_redirects = get_option('mijn_seo_plugin_redirects', []);
                    
                    if (empty($saved_redirects)) {
                        echo '<tr><td><input type="text" name="redirect_from[]" class="regular-text" placeholder="/oude-pagina" style="width: 90%;"></td>';
                        echo '<td><input type="text" name="redirect_to[]" class="regular-text" placeholder="https://voorbeeld.com/nieuwe-pagina" style="width: 90%;"></td>';
                        echo '<td><button type="button" class="button add-row">Toevoegen</button></td></tr>';
                    } else {
                        foreach ($saved_redirects as $redirect) {
                            list($from, $to) = array_map('trim', explode('=>', $redirect));
                            echo '<tr>';
                            echo '<td><input type="text" name="redirect_from[]" value="' . esc_attr($from) . '" class="regular-text" style="width: 90%;"></td>';
                            echo '<td><input type="text" name="redirect_to[]" value="' . esc_attr($to) . '" class="regular-text" style="width: 90%;"></td>';
                            echo '<td><button type="button" class="button remove-row">Verwijder</button></td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                    <tr class="new-row" style="display: none;">
                        <td><input type="text" name="redirect_from[]" class="regular-text" placeholder="/oude-pagina" style="width: 90%;"></td>
                        <td><input type="text" name="redirect_to[]" class="regular-text" placeholder="https://voorbeeld.com/nieuwe-pagina" style="width: 90%;"></td>
                        <td><button type="button" class="button remove-row">Verwijder</button></td>
                    </tr>
                </tbody>
            </table>

            <p class="submit">
                <input type="submit" name="submit" class="button-primary" value="Opslaan Redirects">
                <button type="button" class="button add-row">Redirect Toevoegen</button>
            </p>
        </form>
    </div>
    <?php
}

function JB_handle_redirects() {
    if (is_admin() || php_sapi_name() === 'cli') return;

    $redirects = get_option('mijn_seo_plugin_redirects', []);
    $current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    foreach ($redirects as $redirect) {
        if (strpos($redirect, '=>') !== false) {
            list($from, $to) = array_map('trim', explode('=>', $redirect));
            $from = '/' . ltrim($from, '/');
            $to = ltrim($to, '/');

            if (filter_var($to, FILTER_VALIDATE_URL)) {
                wp_redirect($to, 301);
                exit;
            } else {
                $to = '/' . ltrim($to, '/');
                if ($from === $current_path) {
                    wp_redirect(site_url($to), 301);
                    exit;
                }
            }
        }
    }
}

add_action('template_redirect', '\JB_PowerPanel\redirect\JB_handle_redirects');
