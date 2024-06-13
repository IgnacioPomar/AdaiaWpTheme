<?php
get_header ();

// Mostrar el contenido de la página seleccionada
if (have_posts ())
{
	while (have_posts ())
	{
		the_post ();
		echo '<div class="mainPage">';
		echo '<h1>' . get_the_title () . '</h1>';
		echo '<div class="content">' . get_the_content () . '</div>';
		echo '</div>';
	}
}

if (is_page ())
{
	// Obtener el ID de la página actual
	$current_page_id = get_the_ID ();

	// Obtener las subpáginas de la página actual
	$subpages = get_pages (array ('child_of' => $current_page_id, 'sort_column' => 'menu_order'));

	if ($subpages)
	{
		echo '<div class="subpages">';
		foreach ($subpages as $subpage)
		{
			// sp comes from Sub Page
			$spId = strtolower (str_replace (' ', '_', $subpage->post_title));
			$spClass = basename (get_page_template_slug ($subpage->ID), '.php') ?? 'default';
			$spClass = strtolower (str_replace (' ', '_', $spClass));

			echo "<div class=\"content $spClass\" id=\"$spId\">" . apply_filters ('the_content', $subpage->post_content) . '</div>';

			/*
			 * $subpage_attributes = get_object_vars ($subpage);
			 * // Mostrar todos los atributos de la subpágina
			 * echo '<div class="attributes">';
			 * foreach ($subpage_attributes as $attribute_name => $attribute_value)
			 * {
			 * echo '<p><strong>' . esc_html ($attribute_name) . ':</strong> ' . esc_html ($attribute_value) . '</p>';
			 * }
			 */
			/*
			 * echo '<p><strong>Template:</strong> ' . esc_html (get_page_template_slug ($subpage->ID)) . '</p>';
			 * echo '<p><strong>' . esc_html ('prueba') . ':</strong> ' . esc_html (CFS ()->get ('prueba', $subpage->ID)) . '</p>';
			 */
		}
		echo '</div>';
	}
}
// get_sidebar();
get_footer ();
