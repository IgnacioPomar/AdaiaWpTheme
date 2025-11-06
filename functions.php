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
	$templates ['template-with-descendants.php'] = 'Por defecto con subpáginas';
	$templates ['template-idx-sibiling.php'] = 'Con índice de hermanos';
	$templates ['template-idx-child.php'] = 'Con índice de hijos';
	return $templates;
}
add_filter ('theme_page_templates', 'add_custom_templates');


function getMnuAnchored ()
{
	$baseUrl = home_url ();
	$subpages = get_pages (array ('parent' => 0, 'sort_column' => 'menu_order'));
	$retval = array ();
	$first = true;

	foreach ($subpages as $page)
	{
		// Skip the -1 pages: in this theme ar "independent pages"
		if ($page->menu_order > 100 || $page->menu_order < 0) continue;

		if ($first)
		{
			// Primer elemento: enlace a '#'
			$retval [] = [ "$baseUrl/#", esc_html ($page->post_title)];
			$first = false;
		}
		else
		{
			$retval [] = [ "$baseUrl/#{$page->post_name}", esc_html ($page->post_title)];
		}
	}

	return $retval;
}


function addThemeFonts ()
{
	$theme = wp_get_theme ();
	$ver = $theme->get ('Version');
	$base_url = get_template_directory_uri () . '/assets/fonts/';

	$woff2 = add_query_arg ('ver', $ver, $base_url . 'icoadaia.woff2');
	$woff = add_query_arg ('ver', $ver, $base_url . 'icoadaia.woff');
	$ttf = add_query_arg ('ver', $ver, $base_url . 'icoadaia.ttf');

	echo '<style>
        @font-face {
            font-family: "icoadaia";
            src: url("' . esc_url ($woff2) . '") format("woff2"),
                 url("' . esc_url ($woff) . '") format("woff"),
                 url("' . esc_url ($ttf) . '") format("truetype");
            font-weight: normal;
            font-style: normal;
            font-display: block;
        }
    </style>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">';
}
add_action ('wp_head', 'addThemeFonts');


function habilitar_excerpt_para_paginas ()
{
	add_post_type_support ('page', 'excerpt');
}
add_action ('init', 'habilitar_excerpt_para_paginas');

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
	// Registramos las opciones del tema
	register_setting ('settings_adaia', 'adaia_legal_page');
	register_setting ('settings_adaia', 'adaia_privacy_page');
	register_setting ('settings_adaia', 'adaia_cookie_page');

	register_setting ('settings_adaia', 'adaia_main_phone', [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field', 'default' => '']);
	register_setting ('settings_adaia', 'adaia_email', [ 'type' => 'string', 'sanitize_callback' => 'sanitize_email', 'default' => '']);

	add_settings_section ('section_adaia_footer', 'Footer Configuration', null, 'settings_adaia');

	// añadimos los controladores del tema
	add_settings_field ('adaia_legal_page', 'Página de aviso Legal', function ()
	{
		$selected = get_option ('adaia_legal_page');
		wp_dropdown_pages ([ 'name' => 'adaia_legal_page', 'selected' => $selected, 'show_option_none' => '— Selecciona una página —', 'option_none_value' => '']);
	}, 'settings_adaia', 'section_adaia_footer');

	add_settings_field ('adaia_privacy_page', 'Página de politica de privacidad', function ()
	{
		$selected = get_option ('adaia_privacy_page');
		wp_dropdown_pages ([ 'name' => 'adaia_privacy_page', 'selected' => $selected, 'show_option_none' => '— Selecciona una página —', 'option_none_value' => '']);
	}, 'settings_adaia', 'section_adaia_footer');

	add_settings_field ('adaia_cookie_page', 'Página de politica de cookies', function ()
	{
		$selected = get_option ('adaia_cookie_page');
		wp_dropdown_pages ([ 'name' => 'adaia_cookie_page', 'selected' => $selected, 'show_option_none' => '— Selecciona una página —', 'option_none_value' => '']);
	}, 'settings_adaia', 'section_adaia_footer');

	add_settings_field ('adaia_main_phone', 'Teléfono de contacto', function ()
	{
		$val = get_option ('adaia_main_phone', '');
		printf ('<input type="tel" id="adaia_main_phone" name="adaia_main_phone" value="%s" class="regular-text">', esc_attr ($val));
	}, 'settings_adaia', 'section_adaia_footer');

	add_settings_field ('adaia_email', 'Email de contacto', function ()
	{
		$val = get_option ('adaia_email', '');
		printf ('<input type="email" id="adaia_email" name="adaia_email" value="%s" class="regular-text" >', esc_attr ($val));
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


// --------------------------------------------------------------------------------------------------------------
// ------------------------------------------- Theme custom functions -------------------------------------------
// --------------------------------------------------------------------------------------------------------------

/**
 * Show the subpages of the current page
 *
 * @param array $subpages
 *        	Array of subpages to show
 * @param string $class
 *        	Additional class for the container
 */
function showSubpages (&$subpages, $class = "")
{
	if ($subpages)
	{
		echo '<div class="subpages ' . $class . '">';
		foreach ($subpages as $subpage)
		{
			// Skip the -1 pages: in this theme ar "independent pages"
			if ($subpage->menu_order > 100 || $subpage->menu_order < 0) continue;

			// sp comes from Sub Page
			$spId = $subpage->post_name;

			// Show the current page
			$template_slug = get_page_template_slug ($subpage->ID);
			if ($template_slug)
			{
				$template = locate_template ($template_slug);
				if ($template)
				{
					$GLOBALS ['currentPage'] = &$subpage;
					include ($template);
				}
			}
			else
			{
				echo "<div class=\"container\" id=\"$spId\">";

				// Si la subpágina tiene una imagen destacada, mostrarla
				if (has_post_thumbnail ($subpage->ID))
				{
					echo '<div class="featured-image">' . get_the_post_thumbnail ($subpage->ID, 'full') . '</div>';
				}

				echo '<div class="content">';
				echo apply_filters ('the_content', $subpage->post_content);
				echo '</div></div>';
			}
		}
		echo '</div>';
	}
}


/**
 * Get meta value with fallback to another meta key if empty
 *
 * @param int $postId
 *        	Post ID
 * @param string $metaKey
 *        	Meta key to get
 * @param string $fallbackMetaKey
 *        	Fallback meta key to get if the first is empty
 * @return mixed Meta value
 */
function getMetaWithFallback ($postId, $metaKey, $fallbackMetaKey)
{
	$val = get_post_meta ($postId, $metaKey, true);
	if (empty ($val))
	{
		$val = get_post_meta ($postId, $fallbackMetaKey, true);
	}
	return $val;
}


/**
 * Function exclusive for template-team.php.
 * Format the team member information.
 *
 * @param int $id
 *        	Post ID of the team member
 * @param string $postTitle
 *        	Title of the post
 * @param string $postName
 *        	Name of the post
 * @param string $content
 *        	Content of the post
 */
function formatTeam ($id, $postTitle, $postName, $content)
{
	$teamMemberName = $postTitle;
	// $titulo = get_post_meta ($id, '_team_titulo', true);
	// $colegiada = get_post_meta ($id, '_team_colegiada', true);

	// Transitional: use new meta with fallback to old meta
	$titulo = getMetaWithFallback ($id, '_team_titulo', 'Titulo');
	$colegiada = getMetaWithFallback ($id, '_team_colegiada', 'Colegiada');
	// End transitional

	// Prepare the vars
	$titulo = ($titulo) ? esc_html ($titulo) : '';
	$colegiada = ($colegiada) ? esc_html ($colegiada) : '';

	echo '<div id="' . $postName . '" class="team-member">';

	$imgUrl = get_the_post_thumbnail_url ($id, 'large');
	if ($imgUrl)
	{
		$webpUrl = $imgUrl . '.webp';
		echo '<picture><source srcset="' . esc_url ($webpUrl) . '" type="image/webp">';
		echo '<img class="team-info-image" src="' . $imgUrl . '" alt="' . $teamMemberName . '" height="588">';
		echo '</picture>';
	}

	$toggleId = 'toggleTeamInfo' . $postName;
	echo '<input type="checkbox" id="' . $toggleId . '" />';
	// team member sumary
	{
		echo '<label for="' . $toggleId . '" class="team-info-sumary"><content>';
		echo "<h3>$teamMemberName</h3><p>" . $titulo . '</p><p>' . $colegiada . '</p>';
		echo '</content></label>';
	}

	// Team member description
	{
		echo '<label for="' . $toggleId . '" class="team-info-details"><content>';

		echo "<h3>$teamMemberName</h3><h4>" . $titulo . '</h4><h4>' . $colegiada . '</h4>';

		echo apply_filters ('the_content', $content);
		echo '</content></label>';
	}

	echo '</div>';
}

// --------------------------------------------------------------------------------------------------------------
// ----------------------- Campos personalizados al usar equipo -------------------------------------------------
// --------------------------------------------------------------------------------------------------------------

// === Metabox para plantilla "Miembro del equipo" (template-team.php) ===
add_action ('add_meta_boxes', function ()
{
	add_meta_box ('team_member_fields', 'Datos del miembro del equipo', function ($post)
	{
		$tpl = get_page_template_slug ($post) ?: '';
		?>
            <div id="team-meta-wrapper"
                 data-target-template="template-team.php"
                 data-current-template="<?php

		echo esc_attr ($tpl);
		?>">
                <?php

		wp_nonce_field ('team_member_fields_nonce_action', 'team_member_fields_nonce');
		?>

                <p>
                    <label for="team_colegiada"><strong>Colegiada</strong></label><br>
                    <input type="text" id="team_colegiada" name="team_colegiada" class="widefat"
                           value="<?=esc_attr (getMetaWithFallback ($post->ID, '_team_colegiada', 'Colegiada'));?>">
                </p>

                <p>
                    <label for="team_titulo"><strong>Título</strong></label><br>
                    <input type="text" id="team_titulo" name="team_titulo" class="widefat"
                           value="<?=esc_attr (getMetaWithFallback ($post->ID, '_team_titulo', 'Titulo'));?>">
                </p>

                <p class="description">
                    Estos campos solo aplican si la página usa la plantilla <code>Miembro del equipo</code>.
                </p>
            </div>
            <?php
	}, 'page', 'side', 'default');
});

// === Guardado seguro (solo si la plantilla es template-team.php) ===
add_action ('save_post_page', function ($post_id, $post, $update)
{
	if (! isset ($_POST ['team_member_fields_nonce']) || ! wp_verify_nonce ($_POST ['team_member_fields_nonce'], 'team_member_fields_nonce_action'))
	{
		return;
	}
	if (defined ('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if (! current_user_can ('edit_post', $post_id)) return;

	$tpl = get_page_template_slug ($post_id) ?: '';
	if ($tpl !== 'template-team.php')
	{
		// Si cambian a otra plantilla, limpias para no dejar "basura"
		delete_post_meta ($post_id, '_team_colegiada');
		delete_post_meta ($post_id, '_team_titulo');
		return;
	}

	update_post_meta ($post_id, '_team_colegiada', sanitize_text_field ($_POST ['team_colegiada'] ?? ''));
	update_post_meta ($post_id, '_team_titulo', sanitize_text_field ($_POST ['team_titulo'] ?? ''));

	// Delete old meta to keep clean
	delete_post_meta ($post_id, 'Colegiada');
	delete_post_meta ($post_id, 'Titulo');
}, 10, 3);

// === Mostrar/ocultar en vivo (editor clásico) ===
add_action ('admin_enqueue_scripts', function ($hook)
{
	if ($hook !== 'post.php' && $hook !== 'post-new.php') return;
	wp_add_inline_script ('jquery-core', <<<JS
	jQuery(function($){
	  function toggleTeamBoxClassic(){
	    var target = 'template-team.php';
	    var sel = $('#page_template').val();
	    var visible = (sel === target);
	    $('#team_member_fields').toggle(!!visible);
	  }
	  // Inicial y cambios
	  toggleTeamBoxClassic();
	  $(document).on('change', '#page_template', toggleTeamBoxClassic);
	});
	JS);
});

// === Mostrar/ocultar en vivo (Gutenberg) ===
add_action ('enqueue_block_editor_assets', function ()
{
	// Script inline pequeño que observa el atributo 'template' del post
	$js = <<<JS
	( function(wp){
	  function toggle() {
	    var target = 'template-team.php';
	    var panel = document.getElementById('team_member_fields');
	    if (!panel) return;
	    var sel = wp.data.select('core/editor').getEditedPostAttribute('template');
	    panel.style.display = (sel === target) ? '' : 'none';
	  }
	  // Inicial y suscripción a cambios en el editor
	  wp.domReady(toggle);
	  var unsubscribe = wp.data.subscribe(function(){
	    toggle();
	  });
	} )(window.wp);
	JS;
	wp_add_inline_script ('wp-data', $js);
});



//--------------------------------------------------------------------------------------------------------------
//----------------------- Campos personalizados al usar equipo - END -------------------------------------------
//--------------------------------------------------------------------------------------------------------------
	
