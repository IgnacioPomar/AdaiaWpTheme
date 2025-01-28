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
		    //Skip the -1 pages: in this theme ar "independent pages"
		    if ($subpage->menu_order < 0) continue;
		    
		    
			// sp comes from Sub Page
		    $spId =  $subpage->post_name;
		    
			//var_dump($subpage);

			
			//Show the current page
			echo "<div class=\"content\" id=\"$spId\">" . apply_filters ('the_content', $subpage->post_content) . '</div>';
			
			
			//show the children if any
			$subSubpages = get_pages (array ('parent' => $subpage->ID, 'sort_column' => 'menu_order'));
			if ($subSubpages)
			{
			    echo "<div class=\"$spId\">" . showSubpages ($subSubpages) . '</div>';
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
