<?php
/*
Plugin Name: Open Admin Links in New Tab
Description: This plugin adds target="_blank" attribute to admin menu links and "Add New" button links for default post type.
Version: 1.2
Author: Tahsinur Tamim
Author URI: https://www.linkedin.com/in/tahsinur-tamim-95707b170/
License: GPL v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Enqueue CSS file
function open_admin_links_in_new_tab_enqueue_styles() {
    // Define a version number for the stylesheet
    $version = '1.0';
    
    // Enqueue the stylesheet with the version parameter
    wp_enqueue_style('open-admin-links-in-new-tab-style', plugin_dir_url(__FILE__) . 'assets/css/styles.css', array(), $version);
}
add_action('admin_enqueue_scripts', 'open_admin_links_in_new_tab_enqueue_styles');

// Enqueue JavaScript file
function open_admin_links_in_new_tab_enqueue_scripts() {
    // Register the script
    wp_register_script('open-admin-links-in-new-tab-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array('jquery'), '1.0', true);
    
    // Pass settings to the script
    $selected_post_types = get_option('open_links_in_new_tab_settings')['post_types'] ?? array();
    $admin_menu_enabled = get_option('open_links_in_new_tab_settings')['admin_menu'] ?? false;
    $settings = array(
        'adminMenu' => $admin_menu_enabled,
        'selectedPostTypes' => $selected_post_types,
    );
    wp_localize_script('open-admin-links-in-new-tab-script', 'openLinksInNewTabSettings', $settings);
    
    // Enqueue the script
    wp_enqueue_script('open-admin-links-in-new-tab-script');
}
add_action('admin_enqueue_scripts', 'open_admin_links_in_new_tab_enqueue_scripts');


// Add settings page
function open_admin_links_in_new_tab_settings_page() {
    add_options_page(
        'Open Links in New Tab Settings',
        'Open Links in New Tab',
        'manage_options',
        'open_links_in_new_tab_settings',
        'open_links_in_new_tab_settings_page_content'
    );
}

// Register settings
function open_admin_links_in_new_tab_register_settings() {
    register_setting('open_links_in_new_tab_settings_group', 'open_links_in_new_tab_settings');
}

// Settings page content
function open_links_in_new_tab_settings_page_content() {
    $settings = get_option('open_links_in_new_tab_settings', array());
    ?>
    <div class="wrap" id="open-links-in-new-tab-settings">
        <h2>Open Links in New Tab Settings</h2>
        <form id="open-links-in-new-tab-form" method="post" action="options.php">
            <?php settings_fields('open_links_in_new_tab_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Open admin menu links in new tab</th>
                    <td><input type="checkbox" name="open_links_in_new_tab_settings[admin_menu]" <?php checked(isset($settings['admin_menu']) && $settings['admin_menu'] == 1); ?> value="1"></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Open new post links in new tab</th>
                    <td>
                        <em>Select post types:</em><br>
                        <?php
                        $post_types = get_post_types(['public' => true], 'objects');
                        $selected_post_types = isset($settings['post_types']) ? $settings['post_types'] : [];
                        foreach ($post_types as $post_type) {
                            ?>
                            <label>
                                <input type="checkbox" name="open_links_in_new_tab_settings[post_types][]" <?php checked(in_array($post_type->name, $selected_post_types), true); ?> value="<?php echo esc_attr($post_type->name); ?>">
                                <?php echo esc_html($post_type->label); ?>
                            </label><br>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
            </table>
            <button type="submit" name="submit" class="button-primary">Save Changes</button>
        </form>
    </div>
    <?php
}


// Hook for adding admin menus
add_action('admin_menu', 'open_admin_links_in_new_tab_settings_page');

// Hook for registering settings
add_action('admin_init', 'open_admin_links_in_new_tab_register_settings');
