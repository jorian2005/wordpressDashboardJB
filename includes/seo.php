<?php
namespace JB_PowerPanel\seo;

if (!defined('ABSPATH')) {
    exit;
}

function JB_generate_sitemap() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
    $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    $sitemap .= '<url>';
    $sitemap .= '<loc>' . esc_url(home_url()) . '</loc>';
    $sitemap .= '<lastmod>' . date('c') . '</lastmod>';
    $sitemap .= '<changefreq>daily</changefreq>';
    $sitemap .= '<priority>1.0</priority>';
    $sitemap .= '</url>';

    $post_types = get_post_types(['public' => true], 'names');

    foreach ($post_types as $post_type) {
        if (in_array($post_type, ['attachment', 'revision', 'nav_menu_item'])) {
            continue;
        }

        $posts = get_posts([
            'post_type' => $post_type,
            'post_status' => 'publish',
            'numberposts' => -1
        ]);

        foreach ($posts as $post) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . esc_url(get_permalink($post->ID)) . '</loc>';
            $sitemap .= '<lastmod>' . get_the_modified_date('c', $post->ID) . '</lastmod>';
            $sitemap .= '<changefreq>weekly</changefreq>';
            $sitemap .= '<priority>0.8</priority>';
            $sitemap .= '</url>';
        }
    }

    $taxonomies = get_taxonomies(['public' => true], 'names');

    foreach ($taxonomies as $taxonomy) {
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => true,
        ]);

        foreach ($terms as $term) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . esc_url(get_term_link($term)) . '</loc>';
            $sitemap .= '<lastmod>' . date('c') . '</lastmod>';
            $sitemap .= '<changefreq>monthly</changefreq>';
            $sitemap .= '<priority>0.5</priority>';
            $sitemap .= '</url>';
        }
    }

    $sitemap .= '</urlset>';

    $file = ABSPATH . 'sitemap.xml';
    file_put_contents($file, $sitemap);
}


function JB_seo_page() {
    ?>
    <div class="wrap">
        <h1>SEO Instellingen</h1>

        <form method="post">
            <h2>XML Sitemap Genereren</h2>
            <p>Klik hieronder om een XML-sitemap te genereren voor je website.</p>
            <input type="submit" name="generate_sitemap" value="Genereer Sitemap">
        </form>

        <?php
        if ( isset( $_POST['generate_sitemap'] ) ) {
            JB_generate_sitemap();
            echo '<p>Sitemap is succesvol gegenereerd en opgeslagen als sitemap.xml in de root van je website.</p>';
        }
        ?>

        <hr>

        <form method="post">
            <h2>SEO Analysetools</h2>
            <textarea name="seo_content" rows="10" cols="50" placeholder="Voer hier je tekst in voor SEO-analyse..."></textarea>
            <br>
            <input type="submit" name="analyse_submit" value="Analyseer SEO">
        </form>

        <?php
        if ( isset( $_POST['analyse_submit'] ) ) {
            $content = sanitize_textarea_field($_POST['seo_content']);
            $word_count = str_word_count($content);
            $keywords = str_word_count($content, 1);
            $keyword_density = array_count_values($keywords);
            $readability_score = calculate_readability_score($content);

            echo '<h3>Analyse Resultaten</h3>';
            echo '<p>Woordtelling: ' . $word_count . '</p>';
            echo '<h4>Zoekwoorddichtheid:</h4>';
            echo '<ul>';
            foreach ($keyword_density as $word => $count) {
                echo '<li>' . $word . ': ' . $count . ' keer</li>';
            }
            echo '</ul>';
            echo '<h4>Meta Description:</h4>';
            echo '<h4>Leesbaarheidsscore:</h4>';
            echo '<p>Leesbaarheidsscore: ' . $readability_score . '</p>';
        }
        ?>

        <hr>
    </div>
    <?php
}

function calculate_readability_score($content) {
    $sentences = preg_split('/[.!?]+/', $content);
    $words = str_word_count($content);
    $syllables = count_syllables($content);

    if ($sentences == 0) {
        return 0;
    }

    $readability = 206.835 - (1.015 * ($words / count($sentences))) - (84.6 * ($syllables / $words));
    return round($readability, 2);
}

function count_syllables($content) {
    $syllables = 0;
    $content = strtolower($content);
    $content = preg_replace('/[^a-z]/', ' ', $content);

    $words = explode(' ', $content);
    foreach ($words as $word) {
        $word = trim($word);
        if ($word) {
            $syllables += count_syllables_in_word($word);
        }
    }
    return $syllables;
}

function count_syllables_in_word($word) {
    $syllables = 0;
    $vowels = ['a', 'e', 'i', 'o', 'u', 'y'];
    $word = str_split($word);
    
    $in_syllable = false;
    foreach ($word as $letter) {
        if (in_array($letter, $vowels)) {
            if (!$in_syllable) {
                $syllables++;
                $in_syllable = true;
            }
        } else {
            $in_syllable = false;
        }
    }
    return $syllables;
}

