<?php
// If uninstall is not called from WordPress, exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

// List of option names that the plugin uses
$plugin_options = [
    'kiptrak_backend_url'
];

// Loop through and delete each option
foreach ($plugin_options as $option) {
    delete_option($option);
    delete_site_option($option); // For multisite installations
}
