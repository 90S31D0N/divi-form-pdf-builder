<?php
/*
Plugin Name: Divi Form PDF Builder
Plugin URI: https://github.com/dein-github-repo
Description: Ein Beispiel-Plugin für automatische Updates von GitHub.
Version: 1.0.0
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

//GitHub Updater einbinden
require_once plugin_dir_path(__FILE__) . 'git-updater/git-updater.php';
