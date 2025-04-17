<?php
namespace DashboardJB\redirect;

if (!defined('ABSPATH')) {
    exit;
}

function plugin_jb_redirect_page() {
    ?>
    <div class="wrap">
        <h1>Redirect Instellingen</h1>
    </div>
    <?php
    if ( isset($_POST['redirect_from']) && isset($_POST['redirect_to']) ) {
        $redirects = [];
        foreach ($_POST['redirect_from'] as $index => $from) {
            $from = trim(sanitize_text_field($from));
            $to = trim(sanitize_text_field($_POST['redirect_to'][$index]));
            if ($from && $to) {
                $redirects[] = $from . ' => ' . $to;
            }
        }
        update_option('mijn_seo_plugin_redirects', $redirects);
        echo '<p>Redirects opgeslagen.</p>';
    }

    $saved_redirects = get_option('mijn_seo_plugin_redirects', []);
    echo '<form method="post" action="">';
    echo '<table class="form-table" style="width: 50%;" id="redirect-table">';
    echo '<thead><tr><th scope="col" style="width: 45%;">Van (oude URL)</th><th scope="col" style="width: 45%;">Naar (nieuwe URL)</th></tr></thead><tbody>';

    if (empty($saved_redirects)) {
        echo '<tr><td style="padding-left: 0px;"><input type="text" name="redirect_from[]" class="regular-text" style="width: 90%;"></td><td><input type="text" name="redirect_to[]" class="regular-text" style="width: 90%;"></td></tr>';
    } else {
        foreach ($saved_redirects as $redirect) {
            list($from, $to) = array_map('trim', explode('=>', $redirect));
            echo '<tr>';
            echo '<td style="padding-left: 0px;"><input type="text" name="redirect_from[]" value="' . esc_attr($from) . '" class="regular-text" style="width: 90%;"></td>';
            echo '<td><input type="text" name="redirect_to[]" value="' . esc_attr($to) . '" class="regular-text" style="width: 90%;"></td>';
            echo '</tr>';
        }
    }
    echo '<tr><td style="padding-left: 0px;"><input type="text" name="redirect_from[]" class="regular-text" style="width: 90%;"></td><td><input type="text" name="redirect_to[]" class="regular-text" style="width: 90%;"></td></tr>';
    echo '</tbody></table>';
    echo '<p><button type="button" id="add-row" class="button">Nieuwe Rij Toevoegen</button></p>';
    echo '<p class="submit"><input type="submit" class="button-primary" value="Opslaan Redirects"></p>';
    echo '</form>';

    echo '<script>
        document.getElementById("add-row").addEventListener("click", function() {
            var table = document.getElementById("redirect-table").getElementsByTagName("tbody")[0];
            var newRow = table.insertRow();
            var cell1 = newRow.insertCell(0);
            var cell2 = newRow.insertCell(1);
            cell1.style.paddingLeft = "0px";
            cell1.innerHTML = \'<input type="text" name="redirect_from[]" class="regular-text" style="width: 90%;">\';
            cell2.innerHTML = \'<input type="text" name="redirect_to[]" class="regular-text" style="width: 90%;">\';
        });
    </script>';
}

function mijn_seo_plugin_do_redirects() {
    if (is_admin() || php_sapi_name() === 'cli') return;

    $redirects = get_option('mijn_seo_plugin_redirects', []);
    $current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    foreach ($redirects as $redirect) {
        if (strpos($redirect, '=>') !== false) {
            list($from, $to) = array_map('trim', explode('=>', $redirect, 2));
            if ($from === $current_path) {
                wp_redirect(site_url($to), 301);
                exit;
            }
        }
    }
}
add_action('template_redirect', '\DashboardJB\redirect\mijn_seo_plugin_do_redirects');