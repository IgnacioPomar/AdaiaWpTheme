<?php

// Allow featured Image
// https://stackoverflow.com/a/30235452/74785
add_theme_support ('post-thumbnails');



function add_custom_templates($templates) {
    $templates['page-contact-form.php'] = 'Formulario de contacto';
    $templates['template-team.php'] = 'Miembro del equipo';
    return $templates;
}
add_filter('theme_page_templates', 'add_custom_templates');
