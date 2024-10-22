<?php

// Hook to register settings
add_action('admin_init', 'kiptrak_register_settings');

// Hook to add the settings link in the plugin page
add_filter('plugin_action_links_' . KIPTRAK_PLUGIN_BASENAME, 'kiptrak_add_settings_link');

// Register settings
function kiptrak_register_settings()
{
    register_setting('kiptrak_settings_group', 'kiptrak_backend_url');
}

// Add settings link to the plugin page
function kiptrak_add_settings_link($links)
{
    // Create a custom link
    $settings_link = '<a href="' . admin_url('admin.php?page=kiptrak-settings') . '">Settings</a>';

    // Add the custom link to the beginning of the existing links array
    array_unshift($links, $settings_link);

    return $links;
}

// Display the settings page when accessed directly via the link
function kiptrak_settings_page()
{
    // Include the settings page HTML
    require_once plugin_dir_path(__FILE__) . '../admin/settings-page.php';
}

// Hook the settings page callback without adding it to the menu
add_action('admin_menu', function () {
    // Add the settings page callback without creating a menu item
    add_submenu_page(null, 'Kiptrak Settings', 'Kiptrak', 'manage_options', 'kiptrak-settings', 'kiptrak_settings_page');
});
// Include the settings page HTML
// require_once plugin_dir_path(__FILE__) . '../admin/settings-page.php';