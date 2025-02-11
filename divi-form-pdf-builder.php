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

// Update-Check-Funktion
function mein_plugin_check_update() {
    // GitHub API URL für die neueste Release-Version
    $url = 'https://api.github.com/repos/90S31D0N/divi-form-pdf-generator/releases/latest';

    // Abrufen der Daten von GitHub
    $response = wp_remote_get($url, array('headers' => array('User-Agent' => 'Mein-Plugin')));

    if (is_wp_error($response)) {
        return;
    }

    $data = json_decode(wp_remote_retrieve_body($response));

    // Überprüfen, ob eine neue Version verfügbar ist
    $latest_version = $data->tag_name;

    if (version_compare($latest_version, get_plugin_data(__FILE__)['Version'], '>')) {
        // Neue Version verfügbar, Update durchführen
        mein_plugin_update($data->zipball_url);
    }
}
add_action('admin_init', 'mein_plugin_check_update');

// Funktion zum Aktualisieren des Plugins
function mein_plugin_update($zip_url) {
    $plugin_dir = plugin_dir_path(__FILE__);
    $zip_file = $plugin_dir . 'mein-plugin.zip';

    // Zip-Datei von GitHub herunterladen
    $response = wp_remote_get($zip_url);
    $file = fopen($zip_file, 'w');
    fwrite($file, wp_remote_retrieve_body($response));
    fclose($file);

    // Entpacken der Zip-Datei
    $zip = new ZipArchive;
    if ($zip->open($zip_file) === TRUE) {
        $zip->extractTo($plugin_dir);
        $zip->close();
    }

    // Löschen der ZIP-Datei
    unlink($zip_file);

    // Nach dem Update die Seite neu laden
    wp_redirect($_SERVER['REQUEST_URI']);
    exit;
}