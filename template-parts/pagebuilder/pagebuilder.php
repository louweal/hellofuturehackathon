<?php

/**
 * Template:			pagebuilder.php
 * Description:			Master template that controls all the pagebuilder templates
 */


if (in_array('advanced-custom-fields-pro/acf.php', apply_filters('active_plugins', get_option('active_plugins'))) == false) {
    //plugin is not active
    return;
}

// Modify this name to the name 
// of the ACF pagebuilder.
$pagebuilder = 'pagebuilder';

// Loop over pagebuilder rows
if (have_rows($pagebuilder)) {
    while (have_rows($pagebuilder)) {
        the_row();

        // Get template name
        $template = get_row_layout();

        // Get template with the name of the template
        if (!empty(locate_template('template-parts/pagebuilder/pagebuilder-' . $template . ".php"))) {
            get_template_part('./template-parts/pagebuilder/pagebuilder', $template);
        } else {
            echo "Pagebuilder template '" . $template . "' not found.<br>";
        }
    }
}
