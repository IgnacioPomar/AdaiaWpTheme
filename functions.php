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

