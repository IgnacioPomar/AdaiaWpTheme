<?php
get_header ();


function showSubpages ($parent)
{
	// Obtener las subpáginas de la página actual
	// $subpages = get_pages (array ('child_of' => 46, 'sort_column' => 'menu_order'));
	$subpages = get_pages (array ('parent' => $parent, 'sort_column' => 'menu_order'));

	if ($subpages)
	{
		echo '<div class="subpages">';
		foreach ($subpages as $subpage)
		{
			// sp comes from Sub Page
			$spId = strtolower (str_replace (' ', '_', $subpage->post_title));
			$spClass = basename (get_page_template_slug ($subpage->ID), '.php') ?? 'default';
			$spClass = strtolower (str_replace (' ', '_', $spClass));

			if (CFS ()->get ('showsubpages', $subpage->ID) === 1)
			{
				showSubpages ($subpage->ID);
			}
			else
			{
				echo "<h2 class=\"content $spClass\" id=\"$spId\">" . esc_html ($subpage->post_title) . '</h2>';
				echo "<div class=\"content $spClass\" id=\"$spId\">" . apply_filters ('the_content', $subpage->post_content) . '</div>';
				// echo '<p><strong>' . esc_html ('ShowSubpages') . ':</strong> ' . esc_html (CFS ()->get ('showsubpages', $subpage->ID)) . '</p>';
			}

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

showSubpages (get_post_parent ());

// get_sidebar();
get_footer ();
