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
        'redirect_url' => ''
    ), $atts, 'kiptrak');

    // Sanitize the attributes
    $type = sanitize_text_field($atts['type']);
    $id = sanitize_text_field($atts['id']);
    $key = sanitize_text_field($atts['key']);
    $order_id = sanitize_text_field($atts['order_id']);
    $stage = sanitize_text_field($atts['stage']);
    $redirect_url = esc_url($atts['redirect_url']);

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

        // Build the base iframe URL
        $iframe_url = rtrim(trailingslashit($site_url), '/') . '/link/form/get-view';

        // Prepare query parameters
        $query_params = array();
        if (!empty($key)) {
            $query_params['key'] = $key;
        }
        if (!empty($id)) {
            $query_params['id'] = $id;
        }
        if (!empty($order_id)) {
            $query_params['order_id'] = $order_id;
        }
        if (!empty($stage)) {
            $query_params['stage'] = $stage;
        }
        if (!empty($redirect_url)) {
            $query_params['redirect_url'] = $redirect_url;
        }
        $query_params['errors_in_json'] = 'yes';


        // Append the query parameters to the URL
        if (!empty($query_params)) {
            $iframe_url = add_query_arg($query_params, $iframe_url);
        }

        // Fetch the response from the URL
        $response = wp_remote_get($iframe_url, array(
            'timeout' => 15 // Increase the timeout to 15 seconds or more if needed
        ));


        // Handle errors or unexpected response codes
        if (is_wp_error($response)) {
            return 'Error: Unable to connect to the Kiptrak Backend site. Please check your connection.';
        }

        $status_code = wp_remote_retrieve_response_code($response);
        $response_body = wp_remote_retrieve_body($response);

        // Decode the JSON response
        $json_response = json_decode($response_body, true);


        // return $json_response;

        // If the response is not 200
        if ($status_code !== 200) {
            $error_message = isset($json_response['message']) ? $json_response['message'] : 'The requested content could not be found or loaded.';

            return 'Error: ' . esc_html($error_message) . ' (HTTP Status Code: ' . $status_code . ')';
        }

        // If a valid JSON response is returned, display the message
        if (isset($json_response['message'])) {
            return esc_html($json_response['message']);
        }

        // Generate the iframe HTML with full width and height styles
        $iframe_html = <<<HTML
            <div style="width:100%; height:100vh; overflow:hidden; position:relative;">
                <iframe id="kiptrak-iframe" src="{$iframe_url}" style="width:100%; height:100%; border:none; position:absolute; top:0; left:0;" allowfullscreen></iframe>
            </div>
            HTML;

        return $iframe_html;
    } elseif ($type === 'order') {
        // Build the base iframe URL
        $iframe_url = rtrim(trailingslashit($site_url), '/') . '/link/form/get-view';
        if ($stage == 'thankYou') {

            if ($order_id == 'any') {
                $order_id = isset($_GET['kiptrak-backend-order-id']) ? $_GET['kiptrak-backend-order-id'] : '';
                if (empty($order_id)) {
                    return 'Error: Sorry order not available now!. Url param missing, can not get order details.';
                }
            }
            // Prepare query parameters
            $query_params = array();

            if (!empty($order_id)) {
                $query_params['order_id'] = $order_id;
            }
            if (!empty($stage)) {
                $query_params['stage'] = $stage;
            }

            $query_params['errors_in_json'] = 'yes';

            // Append the query parameters to the URL
            if (!empty($query_params)) {
                $iframe_url = add_query_arg($query_params, $iframe_url);
            }

            error_log($iframe_url);

            // Fetch the response from the URL
            $response = wp_remote_get($iframe_url, array(
                'timeout' => 15 // Increase the timeout to 15 seconds or more if needed
            ));


            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                return 'Error: Unable to connect to the Kiptrak Backend site. ' . esc_html($error_message);
            }

            $status_code = wp_remote_retrieve_response_code($response);
            $response_body = wp_remote_retrieve_body($response);

            error_log($response_body);

            // Decode the JSON response
            $json_response = json_decode($response_body, true);


            // return $json_response;

            // If the response is not 200
            if ($status_code !== 200) {
                $error_message = isset($json_response['message']) ? $json_response['message'] : 'The requested content could not be found or loaded.';

                return 'Error: ' . esc_html($error_message) . ' (HTTP Status Code: ' . $status_code . ')';
            }

            // If a valid JSON response is returned, display the message
            if (isset($json_response['message'])) {
                return esc_html($json_response['message']);
            }

            // Generate the iframe HTML with full width and height styles
            $iframe_html = <<<HTML
                <div style="width:100%; height:100vh; overflow:hidden; position:relative;">
                    <iframe id="kiptrak-iframe" src="{$iframe_url}" style="width:100%; height:100%; border:none; position:absolute; top:0; left:0;" allowfullscreen></iframe>
                </div>
                HTML;

            return $iframe_html;
        }
    }

    // If the type is not recognized, return an error
    return 'Error: Invalid type specified.';
}

// Register the shortcode
add_shortcode('kiptrak', 'kiptrak_shortcode');
