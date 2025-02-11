<?php
/*
Plugin Name: Divi Form PDF Builder
Plugin URI: https://github.com/dein-github-repo
Description: Ein Beispiel-Plugin für automatische Updates von GitHub.
Version: 1.0.1
Author:  Noël Schaller
Author URI: https://deine-website.com
License: GPL2
GitHub Plugin URI: 90S31D0N/divi-from-pdf-builder
*/

if (!defined('ABSPATH')) {
    exit; // Sicherheitscheck
}

function my_custom_plugin_activate() {
    // Aktivierungscode hier
}
register_activation_hook(__FILE__, 'my_custom_plugin_activate');

function my_custom_plugin_deactivate() {
    // Deaktivierungscode hier
}
register_deactivation_hook(__FILE__, 'my_custom_plugin_deactivate');

// Funktion zum Prüfen auf Updates von GitHub
function check_for_github_updates($transient) {
    // Wenn keine Anfrage läuft, nichts tun
    if (empty($transient->checked)) {
        return $transient;
    }

    // Die URL deines GitHub-Repositories, das die Version enthält
    $repo_url = 'https://github.com/90S31D0N/divi-from-pdf-builder.git';

    // API-Anfrage zu GitHub, um die neueste Release-Version zu bekommen
    $response = wp_remote_get($repo_url);
    
    // Wenn keine Antwort oder Fehler
    if (is_wp_error($response)) {
        return $transient;
    }

    // Antwort als JSON dekodieren
    $release = json_decode(wp_remote_retrieve_body($response));

    if (!empty($release) && isset($release->tag_name)) {
        // Prüfen, ob eine neue Version verfügbar ist
        $latest_version = $release->tag_name;
        $current_version = $transient->checked['divi-from-pdf-builder/divi-from-pdf-builder.php'];

        // Wenn es ein Update gibt
        if (version_compare($current_version, $latest_version, '<')) {
            $plugin = array(
                'slug'        => 'divi-from-pdf-builder',
                'new_version' => $latest_version,
                'url'         => $release->html_url,
                'package'     => $release->zipball_url,
            );

            $transient->response['divi-from-pdf-builder/divi-from-pdf-builder.php'] = $plugin;
        }
    }

    return $transient;
}
add_filter('site_transient_update_plugins', 'check_for_github_updates');

