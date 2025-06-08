<?php
if (! function_exists ('adaia_render_section_index'))
{


	/**
	 * Renderiza un índice de secciones basado en las páginas hijas del post actual.
	 * Muestra un título y una lista de enlaces a las páginas hijas ordenadas por menu_order.
	 */
	function adaia_render_section_index ()
	{
		global $post;

		// Determinar el objeto padre (o el propio post si no tiene padre)
		$parent = $post->post_parent ? get_post ($post->post_parent) : $post;

		$parent_slug = $parent->post_name;
		$parent_title = get_the_title ($parent->ID);

		// Recoger páginas hijas ordenadas por menu_order
		$child_pages = get_pages (array ('parent' => $parent->ID, 'sort_column' => 'menu_order', 'sort_order' => 'ASC'));

		if (empty ($child_pages))
		{
			return;
		}

		// Comenzar salida
		echo '<div id="' . esc_attr ($parent_slug) . '">';
		echo '<h1>' . esc_html ($parent_title) . '</h1>';
		echo '<ul class="wp-block-list content">';

		foreach ($child_pages as $page)
		{
			$classes = array ($page->post_name);
			$is_current = ($page->ID === $post->ID);
			if ($is_current)
			{
				$classes [] = 'selected';
			}

			echo '<li class="' . esc_attr (implode (' ', $classes)) . '">';
			echo '<strong>';

			if ($is_current)
			{
				// Título sin enlace en la página actual
				echo esc_html ($page->post_title);
			}
			else
			{
				// Enlace para las demás hijas
				printf ('<a href="%s">%s</a>', esc_url (get_permalink ($page->ID)), esc_html ($page->post_title));
			}

			echo '</strong>';
			echo '</li>';
		}

		echo '</ul>';
		echo '</div>';
	}
}

// ---------------------------- Finally show the page content ----------------------------//

get_header ();

global $post;
$parent = $post->post_parent ? get_post ($post->post_parent) : $post;
$wrapper_id = $parent->post_name . '-content';

echo '<div class="container" id="' . esc_attr ($wrapper_id) . '"><div class="content">';
adaia_render_section_index ();
while (have_posts ())
{
	the_post ();
	the_content ();
}

echo '</div></div>';
get_footer ();
