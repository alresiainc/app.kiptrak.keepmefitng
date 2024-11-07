<?php


// Register the settings
add_action('admin_init', 'kiptrak_register_settings');

// Include the Parsedown library (adjust path if necessary)
require_once plugin_dir_path(__FILE__) . 'libs/Parsedown.php';

// Hook to add the settings link on the plugin page
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
    $settings_link = '<a href="' . admin_url('admin.php?page=kiptrak-backend') . '">KipTrak Page</a>';

    // Add the custom link to the beginning of the existing links array
    array_unshift($links, $settings_link);

    return $links;
}

// Hook the settings page callback without adding it to the menu
add_action('admin_menu', function () {
    // Add the settings page callback without creating a menu item

    add_menu_page(
        'Kiptrak Back-end Plugin Page', // Page title
        'Kiptrak', // Menu title (you can set it to an empty string if you prefer)
        'manage_options', // Capability required
        'kiptrak-backend', // Menu slug
        'kiptrak_info_page', // Callback function to display the page
        '', // Icon URL (optional)
        null // Position (optional)
    );
});

// The main plugin information page
function kiptrak_info_page()
{
    // Check if the current user has the required capability
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

?>
    <div class="kiptrak-page">
        <h1>Kiptrak Back-end Plugin Page</h1>
        <h2 class="nav-tab-wrapper">
            <a href="?page=kiptrak-backend&tab=settings"
                class="nav-tab <?php echo kiptrak_active_tab('settings'); ?>">Settings</a>
            <a href="?page=kiptrak-backend&tab=about" class="nav-tab <?php echo kiptrak_active_tab('about'); ?>">About</a>
            <a href="?page=kiptrak-backend&tab=documentation"
                class="nav-tab <?php echo kiptrak_active_tab('documentation'); ?>">Documentation</a>
        </h2>
        <div class="nav-content-wrapper">
            <?php
            $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'settings';
            if ($active_tab == 'settings') {
                kiptrak_settings_tab();
            } elseif ($active_tab == 'about') {
                kiptrak_about_tab();
            } elseif ($active_tab == 'documentation') {
                kiptrak_documentation_tab();
            }
            ?>
        </div>
    </div>
<?php
}

// Determine the active tab
function kiptrak_active_tab($tab)
{
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'settings';
    return $active_tab == $tab ? 'nav-tab-active' : '';
}

// Settings tab content
function kiptrak_settings_tab()
{

?>
    <h2>Settings</h2>

<?php
    require_once plugin_dir_path(__FILE__) . '../admin/settings-form.php';
}


// Changelog tab content
function kiptrak_about_tab()
{
    // Define the path to the changelog file
    $changelog_file_path = plugin_dir_path(__FILE__) . '../changelog.md';

    $plugin_data = get_plugin_data(plugin_dir_path(__FILE__) . '../kiptrak-backend.php');

    /// Check if the markdown file exists
    if (file_exists($changelog_file_path)) {
        // Get the contents of the markdown file
        $markdown_content = file_get_contents($changelog_file_path);

        // Initialize Parsedown
        $Parsedown = new Parsedown();

        // Convert markdown to HTML
        $changelog_content = $Parsedown->text($markdown_content);
    } else {
        $changelog_content = 'Documentation file not found.';
    }
    $about_content = $plugin_data['Description'];

?>
    <h1><?php echo $plugin_data['Name']; ?> <?php echo $plugin_data['Version']; ?></h1>
    <h2>Description</h2>
    <div class="about-content">
        <?php echo $about_content; ?>
    </div>

    <h2>Changelog</h2>
    <div class="section-changelog">
        <?php echo $changelog_content; ?>
    </div>
<?php
}

// Documentation tab content
function kiptrak_documentation_tab()
{
    // Define the path to the documentation markdown file
    $documentation_file_path = plugin_dir_path(__FILE__) . '../documentation.md'; // Make sure this path is correct

    // Check if the markdown file exists
    if (file_exists($documentation_file_path)) {
        // Get the contents of the markdown file
        $markdown_content = file_get_contents($documentation_file_path);

        // Initialize Parsedown
        $Parsedown = new Parsedown();

        // Convert markdown to HTML
        $documentation_content = $Parsedown->text($markdown_content);
    } else {
        $documentation_content = 'Documentation file not found.';
    }
?>
    <h2>Documentation</h2>
    <div class="documentation-content">
        <?php echo $documentation_content; ?>
    </div>
<?php
}

?>