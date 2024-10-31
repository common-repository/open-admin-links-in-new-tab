jQuery(document).ready(function($) {
    console.log("JavaScript loaded successfully.");
    
    // Function to add target="_blank" attribute based on settings
    function addBlankTarget() {
        // Log the settings for debugging
        console.log("Admin Menu Enabled:", openLinksInNewTabSettings.adminMenu);
        console.log("Selected Post Types:", openLinksInNewTabSettings.selectedPostTypes);

        // Get the page title and convert it to lowercase
        var pageTitle = $('h1.wp-heading-inline').text().toLowerCase();

        // Add target="_blank" attribute to admin menu links if setting is enabled
        if (openLinksInNewTabSettings.adminMenu) {
            $('#adminmenu a').attr('target', '_blank');
        } else {
            $('#adminmenu a').removeAttr('target');
        }

        // Add target="_blank" attribute to "Add New" link for selected post types if setting is enabled
        if (openLinksInNewTabSettings.selectedPostTypes.length > 0) {
            // Loop through each selected post type
            openLinksInNewTabSettings.selectedPostTypes.forEach(function(postType) {
                // Check if the page title contains the current post type
                if (pageTitle.includes(postType.toLowerCase())) {
                    // Add target="_blank" attribute to the "Add New" link for the matched post type
                    $('.wrap a.page-title-action').attr('target', '_blank');
                }
            });
        }
    }

    // Call the function to add target="_blank" attribute based on settings initially
    addBlankTarget();
});
