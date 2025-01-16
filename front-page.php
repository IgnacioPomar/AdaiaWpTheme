<?php
setlocale (LC_ALL, 'es_ES.utf8');

get_header ();


function showSubpages (&$subpages)
{
	

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

			
			
			//Show the current page
			echo "<h2 class=\"content $spClass\" id=\"$spId\">" . esc_html ($subpage->post_title) . '</h2>';
			echo "<div class=\"content $spClass\" id=\"$spId\">" . apply_filters ('the_content', $subpage->post_content) . '</div>';
			
			
			//show the children if any
			$subSubpages = get_pages (array ('parent' => $subpage->ID, 'sort_column' => 'menu_order'));
			if ($subSubpages)
			{
			    echo "<hr />";
			    showSubpages ($subpages);
			    echo "<hr />";
			}
			
				
			
			
		}
		echo '</div>';
	}
}


// Obtener las subpáginas de la página actual
// $subpages = get_pages (array ('child_of' => 46, 'sort_column' => 'menu_order'));
$subpages = get_pages (array ('parent' => 0, 'sort_column' => 'menu_order'));

showSubpages ($subpages);

// get_sidebar();
get_footer ();
