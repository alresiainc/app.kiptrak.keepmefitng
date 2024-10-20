<?php
// Plugin deactivation function
function kiptrak_deactivation()
{
    // Set a transient to show a notice after the plugin is deactivated
    set_transient('kiptrak_deactivation_notice', true, 30);
}

// Function to display the deactivation notice
function kiptrak_show_deactivation_notice()
{
    // Check if the transient is set and if the user has permission to manage options
    if (get_transient('kiptrak_deactivation_notice') && current_user_can('manage_options')) {
        // Delete the transient to prevent the notice from showing multiple times
        delete_transient('kiptrak_deactivation_notice');

        // Features that will be unavailable
        $features = [
            'Connect with kiptrak backend',
            'Sync data with kiptrak backend',
            'Access to kiptrak forms',
            'Use Kiptrak shortcodes',
        ];

        // Convert the features list to an HTML unordered list
        $features_list = '<ul>';
        foreach ($features as $feature) {
            $features_list .= '<li>' . esc_html($feature) . '</li>';
        }
        $features_list .= '</ul>';

        // Display the admin notice
        echo '<div class="notice notice-warning is-dismissible">';
        echo '<p><strong>Kiptrak Plugin Deactivated:</strong> The following features will no longer be available:</p>';
        echo $features_list;
        echo '</div>';
    }
}

// Add the admin notice action
add_action('admin_notices', 'kiptrak_show_deactivation_notice');
