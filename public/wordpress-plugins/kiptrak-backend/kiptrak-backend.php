<?php
/*
Plugin Name: Kiptrak Backend v2
Description: Kiptrak connects your WordPress site with the Kiptrak backend app, allowing you to easily embed forms, dashboards, and other content using a simple shortcode.
Version: 1.0.0
Author: Alresia
Author URI: https://github.com/alresiainc
License: GPL2
*/

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}


if (!defined('KIPTRAK_PLUGIN_DIR')) {
    define('KIPTRAK_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

if (!defined('KIPTRAK_PLUGIN_URL')) {
    define('KIPTRAK_PLUGIN_URL', plugin_dir_url(__FILE__));
}

if (!defined('KIPTRAK_PLUGIN_BASENAME')) {
    define('KIPTRAK_PLUGIN_BASENAME', plugin_basename(__FILE__));
}

if (!defined('KIPTRAK_PLUGIN_SLUG')) {
    define('KIPTRAK_PLUGIN_SLUG', plugin_basename(__FILE__));
}
if (!defined('KIPTRAK_PLUGIN_VERSION')) {
    define('KIPTRAK_PLUGIN_VERSION', '1.0.0');
}


// Main admin styles.
wp_enqueue_style(
    'kiptrak-page',
    KIPTRAK_PLUGIN_URL . "assets/css/styles.css",
    [],
    KIPTRAK_PLUGIN_VERSION
);

// Include necessary files yyy
require_once plugin_dir_path(__FILE__) . 'includes/updater.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcode.php';
// require_once plugin_dir_path(__FILE__) . 'includes/settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/kiptrak-page.php';
require_once plugin_dir_path(__FILE__) . 'includes/kiptrak-activation.php';
require_once plugin_dir_path(__FILE__) . 'includes/kiptrak-deactivation.php';


// Register activation and deactivation hooks
register_activation_hook(__FILE__, 'kiptrak_activation');
register_deactivation_hook(__FILE__, 'kiptrak_deactivation');

// Initialize the updater (only in the admin area)
add_action('plugins_loaded', 'kiptrak_init_updater');


function kiptrak_init_updater()
{
    if (is_admin()) {
        // Initialize the updater
        kiptrak_update_check();
    }
}
