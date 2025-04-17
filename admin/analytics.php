<?php
namespace DashboardJB\analytics;

if (!defined('ABSPATH')) exit;

function JB_track_page_view() {
    if (is_admin() || is_user_logged_in()) {
        return;
    }

    $ip = $_SERVER['REMOTE_ADDR'];

    $today = date('Y-m-d');
    
    $total_views = (int) get_option('JB_total_views', 0);
    update_option('JB_total_views', $total_views + 1);

    $daily_views = get_option('JB_daily_views', []);
    if (!isset($daily_views[$today])) {
        $daily_views[$today] = 0;
    }
    $daily_views[$today]++;
    update_option('JB_daily_views', $daily_views);

    $ips = get_option('JB_ips_' . $today, []);
    if (!in_array($ip, $ips)) {
        $ips[] = $ip;
        update_option('JB_ips_' . $today, $ips);
    }
}
add_action('template_redirect', __NAMESPACE__ . '\JB_track_page_view');

function JB_get_user_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    return $_SERVER['REMOTE_ADDR'];
}

function JB_render_analytics_page() {
    $total = (int) get_option('JB_total_views', 0);
    $daily = get_option('JB_daily_views', []);
    krsort($daily);

    $labels = [];
    $data = [];
    $i = 0;
    foreach ($daily as $date => $views) {
        $labels[] = $date;
        $data[] = $views;
        if (++$i >= 30) break;
    }
    $labels = array_reverse($labels);
    $data = array_reverse($data);

    echo '<div class="wrap"><h1>Site Statistieken</h1>';
    echo '<p><strong>Totaal aantal weergaven:</strong> ' . $total . '</p>';
    
    echo '<canvas id="jbChart" height="100"></canvas>';
    echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
    echo '<script>
        const ctx = document.getElementById("jbChart").getContext("2d");
        new Chart(ctx, {
            type: "line",
            data: {
                labels: ' . json_encode($labels) . ',
                datasets: [{
                    label: "Weergaven per dag",
                    data: ' . json_encode($data) . ',
                    borderColor: "#0073aa",
                    backgroundColor: "rgba(0, 115, 170, 0.2)",
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { ticks: { autoSkip: true, maxTicksLimit: 10 }},
                    y: { beginAtZero: true }
                }
            }
        });
    </script>';

    echo '<table class="widefat"><thead><tr><th>Datum</th><th>Weergaven</th><th>Unieke Bezoekers</th></tr></thead><tbody>';
    foreach ($daily as $date => $views) {
        $ips = get_option('JB_ips_' . $date, []);
        echo '<tr><td>' . $date . '</td><td>' . $views . '</td><td>' . count($ips) . '</td></tr>';
    }
    echo '</tbody></table></div>';
}

function JB_dashboard_widget_display() {
    $total = (int) get_option('JB_total_views', 0);
    $daily = get_option('JB_daily_views', []);
    krsort($daily);

    $labels = [];
    $data = [];
    $i = 0;
    foreach ($daily as $date => $views) {
        $labels[] = date('d-m-Y', strtotime($date));
        $data[] = $views;
        if (++$i >= 7) break;
    }
    $labels = array_reverse($labels);
    $data = array_reverse($data);

    echo '<canvas id="jbDashboardChart" height="100"></canvas>';
    echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
    echo '<script>
        const ctx = document.getElementById("jbDashboardChart").getContext("2d");
        new Chart(ctx, {
            type: "line",
            data: {
                labels: ' . json_encode($labels) . ',
                datasets: [{
                    label: "Weergaven per dag",
                    data: ' . json_encode($data) . ',
                    borderColor: "#0073aa",
                    backgroundColor: "rgba(0, 115, 170, 0.2)",
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { ticks: { autoSkip: true, maxTicksLimit: 7 }},
                    y: { beginAtZero: true }
                }
            }
        });
    </script>';

    $today = date('Y-m-d');
    $today_views = $daily[$today] ?? 0;
    $ips = get_option('JB_ips_' . $today, []);
    echo '<p><strong>Vandaag:</strong> ' . $today_views . ' weergaven (' . count($ips) . ' uniek)</p>';
    echo '<p><strong>Totaal:</strong> ' . $total . ' weergaven</p>';
    echo '<p><strong>Unieke bezoekers vandaag:</strong> ' . count($ips) . '</p>';
}


function JB_register_dashboard_widget() {
    wp_add_dashboard_widget('jb_analytics_widget', 'Site Statistieken - JB', __NAMESPACE__ . '\JB_dashboard_widget_display');
}
add_action('wp_dashboard_setup', __NAMESPACE__ . '\JB_register_dashboard_widget');
