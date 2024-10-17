<?php

// Kiptrak Shortcode Function
function kiptrak_shortcode($atts)
{
    // Get the stored URL from settings
    $site_url = esc_url(get_option('kiptrak_backend_url'));

    // Extract attributes passed to the shortcode
    $atts = shortcode_atts(array(
        'type' => '',
        'id' => '',
        'order_id' => '',
        'stage' => '',
        'key' => '',
    ), $atts, 'kiptrak');

    // Sanitize the attributes
    $type = sanitize_text_field($atts['type']);
    $id = sanitize_text_field($atts['id']);
    $key = sanitize_text_field($atts['key']);
    $order_id = sanitize_text_field($atts['order_id']);
    $stage = sanitize_text_field($atts['stage']);

    // Check if the site URL is configured
    if (empty($site_url)) {
        return 'Error: Kiptrak site URL must be configured in the settings.';
    }

    // Check if the type is provided
    if (empty($type)) {
        return 'Error: The type must be provided.';
    }

    // If the type is 'form', the 'id' or 'key' attribute must be provided
    if ($type === 'form') {
        if (empty($id) && empty($key)) {
            return 'Error: The ID or Key must be provided when using the form type.';
        }

        // Build the iframe URL
        if ($id) {
            $iframe_url = rtrim(trailingslashit($site_url), '/') . '/form-short-code/' . $id;
        } else if ($key) {
            $iframe_url = rtrim(trailingslashit($site_url), '/') . '/get-form/' . $key;
        }

        // Add order_id and stage if they are provided
        if (!empty($order_id)) {
            $iframe_url .= '/' . $order_id;
        }
        if (!empty($stage)) {
            $iframe_url .= '/' . $stage;
        }

        // Check if the URL returns a valid response before rendering the iframe
        $response = wp_remote_get($iframe_url);
        error_log($iframe_url);
        error_log(json_encode($response));

        // Handle errors or unexpected response codes
        if (is_wp_error($response)) {
            return 'Error: Unable to connect to the Kiptrak site. Please check your connection.';
        }

        $status_code = wp_remote_retrieve_response_code($response);
        if ($status_code !== 200) {
            return 'Error: The requested content could not be found or loaded (HTTP Status Code: ' . $status_code . ').';
        }

        // Generate the iframe HTML with full width and height styles
        $iframe_html = <<<HTML
            <div style="width:100%; height:100vh; overflow:hidden; position:relative;">
                <iframe id="kiptrak-iframe" src="{$iframe_url}" style="width:100%; height:100%; border:none; position:absolute; top:0; left:0;" allowfullscreen></iframe>
            </div>
            HTML;

        return $iframe_html;
    }

    // If the type is not recognized, return an error
    return 'Error: Invalid type specified.';
}


// Register the shortcode
add_shortcode('kiptrak', 'kiptrak_shortcode');
