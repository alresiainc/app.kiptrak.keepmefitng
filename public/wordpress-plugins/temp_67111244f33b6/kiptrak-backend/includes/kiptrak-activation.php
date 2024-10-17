<?php

// Activation function
function kiptrak_activation()
{
    // Initialize settings if necessary
    if (get_option('kiptrak_backend_url') === false) {
        add_option('kiptrak_backend_url', '');
    }

    // Set a transient to indicate that the plugin has just been activated and needs a redirect
    set_transient('kiptrak_activation_redirect', true, 30);
}

// Function to handle redirection after activation
function kiptrak_redirect_to_settings_page()
{
    // Check if the transient is set and if the user has permission to manage options
    if (get_transient('kiptrak_activation_redirect') && current_user_can('manage_options')) {
        // Delete the transient to prevent repeated redirects
        delete_transient('kiptrak_activation_redirect');

        // Check if the URL is empty
        if (get_option('kiptrak_backend_url') === '') {
            // Redirect to the settings page
            wp_redirect(admin_url('admin.php?page=kiptrak-backend&tab=settings'));
            exit;
        }
    }
}
// Function to show admin notice if the plugin is active but the backend URL is not set
function kiptrak_check_backend_url()
{
    // Check if the user has permission to manage options
    if (current_user_can('manage_options')) {
        // Check if the kiptrak_backend_url option is empty
        if (empty(get_option('kiptrak_backend_url'))) {
            // Display the admin notice
            echo '<div class="notice notice-warning is-dismissible">';
            echo '<p><strong>Warning:</strong> The Kiptrak Plugin is active, but the backend URL is not set. The backend functionality will not be available until this is configured. Please go to the <a href="' . esc_url(admin_url('admin.php?page=kiptrak-backend&tab=settings')) . '">settings page</a> to set it up.</p>';
            echo '</div>';
        }
    }
}


// Add a hook to check for the activation redirect
add_action('admin_init', 'kiptrak_redirect_to_settings_page');
// Hook the function to display the notice in the admin dashboard
add_action('admin_notices', 'kiptrak_check_backend_url');
