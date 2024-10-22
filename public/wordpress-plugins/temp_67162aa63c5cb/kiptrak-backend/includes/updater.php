<?php
function kiptrak_update_check()
{
    // Get the site URL and remove any trailing slashes
    $kiptrakSiteUrl = rtrim(get_option('kiptrak_backend_url'), '/');
    $plugin_url = $kiptrakSiteUrl . '/wordpress-plugin/';

    // Check if the site URL is set
    if (empty($kiptrakSiteUrl)) {

        return; // Exit early if no URL is set
    }

    // Construct the update check URL
    $updateCheckUrl = $kiptrakSiteUrl . '/wordpress/plugin-update/kiptrak-backend/check';

    error_log($updateCheckUrl);
    // Log the update check URL


    // Get the correct plugin basename from the main plugin file
    $plugin_basename = KIPTRAK_PLUGIN_BASENAME; // Adjust to point to index.php

    // Check for updates
    add_filter('pre_set_site_transient_update_plugins', function ($transient) use ($updateCheckUrl, $plugin_url, $plugin_basename) {


        // Make the API request
        $response = wp_remote_get($updateCheckUrl);

        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {

            return $transient;
        }

        $data = json_decode(wp_remote_retrieve_body($response));
        if (json_last_error() !== JSON_ERROR_NONE) {

            return $transient;
        }

        if (isset($data->new_version) && isset($data->download_url)) {
            if (version_compare($data->new_version, get_plugin_data(KIPTRAK_PLUGIN_BASENAME)['Version'], '>')) {

                $plugin_info = new stdClass();
                $plugin_info->slug = $plugin_basename;
                $plugin_info->new_version = $data->new_version;
                $plugin_info->url = $plugin_url; // Assuming the homepage is in the API response
                $plugin_info->package = $data->download_url;


                $transient->response[$plugin_basename] = $plugin_info;
            }
        } else {
            //
        }

        return $transient;
    });

    add_filter('plugins_api', function ($false, $action, $response) use ($updateCheckUrl, $plugin_url, $plugin_basename) {



        // Check if the response is an object and if the slug matches
        if (is_object($response) && isset($response->slug) && $response->slug === $plugin_basename) {
            // Make the API request again to fetch additional info
            $response_remote = wp_remote_get($updateCheckUrl);

            if (is_wp_error($response_remote) || wp_remote_retrieve_response_code($response_remote) !== 200) {
                return $false;
            }

            $data = json_decode(wp_remote_retrieve_body($response_remote));

            if (json_last_error() !== JSON_ERROR_NONE) {

                return $false;
            }



            if (isset($data->new_version)) {


                // $plugin_data = get_plugin_data(KIPTRAK_PLUGIN_BASENAME);
                // Get plugin data from the main plugin file
                $plugin_data = get_plugin_data(plugin_dir_path(__FILE__) . '../kiptrak-backend.php');



                // Prepare a new stdClass object for the response
                $new_response = new stdClass();
                $new_response->new_version = $data->new_version; // Use the version from the API
                $new_response->name = $plugin_data['Name'];
                $new_response->slug = $plugin_basename;
                $new_response->plugin = $plugin_basename;
                $new_response->tested = $data->tested;
                $new_response->requires = $data->requires;
                $new_response->author = '<a href="https://github.com/alresiainc">Alresia</a>';
                $new_response->homepage = $plugin_url; // URL for the plugin homepage
                $new_response->download_link = $data->download_url; // Download link from the API
                $new_response->sections = array(
                    'description' => $plugin_data['Description'],
                    'changelog' => $data->changelog,
                );


                return $new_response; // Return the new response object
            }
        }

        return $false; // Return false if not applicable
    }, 20, 3);
}
