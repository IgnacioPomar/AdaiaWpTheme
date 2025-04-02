<?php

// Allow featured Image
// https://stackoverflow.com/a/30235452/74785
add_theme_support ('post-thumbnails');
add_theme_support ('title-tag');


function AdaiaWpTheme_enqueue_styles ()
{
	wp_enqueue_style ('AdaiaWpTheme-style', get_stylesheet_uri (), array (), wp_get_theme ()->get ('Version'), 'all');
}
add_action ('wp_enqueue_scripts', 'AdaiaWpTheme_enqueue_styles');


function add_custom_templates ($templates)
{
	$templates ['page-contact-form.php'] = 'Formulario de contacto';
	$templates ['template-team.php'] = 'Miembro del equipo';
	return $templates;
}
add_filter ('theme_page_templates', 'add_custom_templates');


function getMnuAnchored ()
{
	$baseUrl = home_url ();
	$subpages = get_pages (array ('parent' => 0, 'sort_column' => 'menu_order'));
	$retval = array ();
	foreach ($subpages as $page)
	{
		// Skip the -1 pages: in this theme ar "independent pages"
		if ($page->menu_order > 100 || $page->menu_order < 0) continue;

		$retval [] = "<a href=\"$baseUrl/#{$page->post_name}\">" . esc_html ($page->post_title) . '</a>';
	}
	return $retval;
}


function addIconFont ()
{
	echo '<style>
        @font-face {
            font-family: "icoadaia";
            src: url("' . get_template_directory_uri () . '/assets/fonts/icoadaia_v2.woff2") format("woff2"),
                 url("' . get_template_directory_uri () . '/assets/fonts/icoadaia_v2.woff") format("woff"),
                 url("' . get_template_directory_uri () . '/assets/fonts/icoadaia_v2.ttf") format("truetype");
            font-weight: normal;
            font-style: normal;
            font-display: block;
        }
    </style>';
}
add_action ('wp_head', 'addIconFont');

// ----------- Disable emojis in WordPress ------------
add_action ('init', 'smartwp_disable_emojis');


function smartwp_disable_emojis ()
{
	remove_action ('wp_head', 'print_emoji_detection_script', 7);
	remove_action ('admin_print_scripts', 'print_emoji_detection_script');
	remove_action ('wp_print_styles', 'print_emoji_styles');
	remove_filter ('the_content_feed', 'wp_staticize_emoji');
	remove_action ('admin_print_styles', 'print_emoji_styles');
	remove_filter ('comment_text_rss', 'wp_staticize_emoji');
	remove_filter ('wp_mail', 'wp_staticize_emoji_for_email');
	add_filter ('tiny_mce_plugins', 'disable_emojis_tinymce');
}


function disable_emojis_tinymce ($plugins)
{
	if (is_array ($plugins))
	{
		return array_diff ($plugins, array ('wpemoji'));
	}
	else
	{
		return array ();
	}
}


function addManualHeader ()
{
	$ruta = get_template_directory () . '/manualHeader.txt'; // May me use get_stylesheet_directory

	if (file_exists ($ruta))
	{
		$contenido = file_get_contents ($ruta);
		echo $contenido;
	}
}
add_action ('wp_head', 'addManualHeader');


// ----------- Disable gutenberg_styles ------------
function remove_gutenberg_styles_for_guests ()
{
	// Check if the user is not logged in
	if (! is_admin () && ! is_user_logged_in ())
	{
		// Remove Gutenberg block library CSS
		wp_dequeue_style ('wp-block-library');
		wp_dequeue_style ('wp-block-library-theme');
		wp_dequeue_style ('wc-block-style'); // If using WooCommerce
		wp_dequeue_style ('global-styles'); // For WordPress 5.9+ global styles
	}
}
add_action ('wp_enqueue_scripts', 'remove_gutenberg_styles_for_guests', 100);

// ----------- Theme personalized fields ------------
// Registrar el menú en Ajustes
add_action ('admin_menu', function ()
{
	add_options_page ('Ajustes del tema Adaia', // Título de la página
	'Adaia Theme Settings', // Nombre del menú
	'manage_options', // Capacidad necesaria
	'settings_adaia', // Slug del menú
	'show_settings_adaia' // Función de callback
	);
});

// Registrar el campo de configuración
add_action ('admin_init', function ()
{
	register_setting ('settings_adaia', 'adaia_contact_page');

	add_settings_section ('section_adaia_footer', 'Footer Configuration', null, 'settings_adaia');

	add_settings_field ('adaia_contact_page', 'Página de contacto', function ()
	{
		// $valor = esc_attr (get_option ('adaia_contact_page', 'contacto'));
		// echo "<input type='text' name='adaia_contact_page' value='$valor' />";
		$selected = get_option ('adaia_contact_page');
		wp_dropdown_pages ([ 'name' => 'adaia_contact_page', 'selected' => $selected, 'show_option_none' => '— Selecciona una página —', 'option_none_value' => '']);
	}, 'settings_adaia', 'section_adaia_footer');
});


// Mostrar la página HTML de ajustes
function show_settings_adaia ()
{
	?>
    <div class="wrap">
        <h1>Adaia Theme Settings</h1>
        <form method="post" action="options.php">
            <?php
	settings_fields ('settings_adaia');
	do_settings_sections ('settings_adaia');
	submit_button ();
	?>
        </form>
    </div>
    <?php
}
