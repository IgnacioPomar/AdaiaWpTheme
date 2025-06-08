<?php


/**
 * Template Name: Índice de Hijos
 * Description: Displays a list of child pages of the current page using the Excerpt field.
 */

// Function to output the header with child pages index
function showHeaderWithChildsIdx ($pageId)
{
	// Retrieve direct child pages sorted by menu order
	$childPages = get_pages (array ('parent' => $pageId, 'sort_column' => 'menu_order'));

	if (! empty ($childPages))
	{
		echo '<ul class="wp-block-list">';

		foreach ($childPages as $childPage)
		{
			$childSlug = get_post_field ('post_name', $childPage->ID);
			$childTitle = esc_html ($childPage->post_title);
			$childPermalink = esc_url (get_permalink ($childPage->ID));

			// Use excerpt if available, otherwise trim content
			$childDescription = get_post_field ('post_excerpt', $childPage->ID);
			if (empty ($childDescription))
			{
				$childDescription = wp_trim_words ($childPage->post_content, 25, '…');
			}
			$childDescription = esc_html ($childDescription);

			echo '<li class="' . esc_attr ($childSlug) . '">';
			echo '<strong>' . $childTitle . '</strong> ' . $childDescription . '<br><br><br>';
			echo '<a href="' . $childPermalink . '">Leer más</a>';
			echo '</li>';
		}

		echo '</ul>';
		echo '<p></p>';
	}
	else
	{
		echo '<p>No child pages to display.</p>';
	}

	echo '</div>'; // .content
	echo '</div>'; // .container
}
// ---------------------------- Finally, display the page content ----------------------------//

if (isset ($GLOBALS ['currentPage']))
{
	// We are inside a view of multiple pages...
	$page = &$GLOBALS ['currentPage'];

	echo "<div class=\"container\" id=\"$page->post_name\">";

	// YAGNI: if the page has a featured image, show it

	echo '<div class="content">';
	echo '<h1 class="wp-block-heading">' . $page->post_title . '</h1>';
	showHeaderWithChildsIdx ($page->ID);
	echo apply_filters ('the_content', $page->post_content);
	echo '</div></div>';
}
else
{
	// Directly called, so, we are in a single page view
	get_header ();
	if (have_posts ())
	{
		// This MUST be a page, so, it'll be only once, we dont need to iterate
		// while (have_posts ())
		{
			the_post ();

			$pageID = get_the_ID ();
			$pageName = get_post_field ('post_name');

			echo "<div class=\"container\" id=\"$pageName\">";

			// YAGNI: if the page has a featured image, show it

			echo '<div class="content">';
			echo '<h1 class="wp-block-heading">' . get_the_title () . '</h1>';
			showHeaderWithChildsIdx ($pageID);
			echo apply_filters ('the_content', get_the_content ());
			echo '</div></div>';

			// Si la subpágina tiene una imagen destacada, mostrarla
			/*
			 * if (has_post_thumbnail ($pageID))
			 * {
			 * echo '<div class="featured-image">' . get_the_post_thumbnail ($pageID, 'full') . '</div>';
			 * }
			 */

			echo '<div class="content">';
			echo apply_filters ('the_content', get_the_content ());
			echo '</div></div>';
		}
	}
	else
	{
		// Mensaje en caso de que no haya contenido
		echo '<p>No se encontró contenido en esta página.</p>';
	}
	get_footer ();
}
