<?php
setlocale (LC_ALL, 'es_ES.utf8');

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
			$spId = strtolower (iconv ('UTF-8', 'ASCII//TRANSLIT//IGNORE', (str_replace (' ', '_', $subpage->post_title))));
			// $spId = strtolower (str_replace (' ', '_', $subpage->post_title));
			$spClass = basename (get_page_template_slug ($subpage->ID), '.php') ?? 'default';
			$spClass = strtolower (str_replace (' ', '_', $spClass));

			if (CFS ()->get ('showsubpages', $subpage->ID) === 1)
			{
				echo $subpage->ID . '<hr />';
				showSubpages ($subpage->ID);
			}
			else
			{
				echo "<h2 class=\"content $spClass\" id=\"$spId\">" . esc_html ($subpage->post_title) . '</h2>';
				echo "<div class=\"content $spClass\" id=\"$spId\">" . apply_filters ('the_content', $subpage->post_content) . '</div>';
				// echo '<p><strong>' . esc_html ('ShowSubpages') . ':</strong> ' . esc_html (CFS ()->get ('showsubpages', $subpage->ID)) . '</p>';
			}
		}
		echo '</div>';
	}
}

showSubpages (get_post_parent ());

// get_sidebar();
get_footer ();
