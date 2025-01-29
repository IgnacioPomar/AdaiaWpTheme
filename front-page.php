<?php
setlocale (LC_ALL, 'es_ES.utf8');

get_header ();


function showSubpages (&$subpages, $class = "")
{
	if ($subpages)
	{
		echo '<div class="subpages ' . $class . '">';
		foreach ($subpages as $subpage)
		{
			// Skip the -1 pages: in this theme ar "independent pages"
			if ($subpage->menu_order > 100) continue;

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
				echo "<div class=\"container\" id=\"$spId\"><div class=\"content\">" . apply_filters ('the_content', $subpage->post_content) . '</div></div>';
			}

			// show the children if any
			$subSubpages = get_pages (array ('parent' => $subpage->ID, 'sort_column' => 'menu_order'));
			if ($subSubpages)
			{
				showSubpages ($subSubpages, $spId);
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
