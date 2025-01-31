<?php


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

setlocale (LC_ALL, 'es_ES.utf8');

get_header ();
?>

<section id="acronyms">
    <div class="entry-content">
    <div class="row">
    <div class="col-xs-12  row-content"> 
    <div class="letter-group">
    
	      	<div class="acronym-content">
        		<span class="icon adaia-a"></span>
            	<span class="themeaning">Analiza</span>
        	</div>
		    <div class="acronym-content">
        		<span class="icon adaia-d"></span>
            	<span class="themeaning">Decide</span>
        	</div>
		    <div class="acronym-content">
        		<span class="icon adaia-a"></span>
            	<span class="themeaning">Actúa</span>
        	</div>
		    <div class="acronym-content">
        		<span class="icon adaia-i"></span>
            	<span class="themeaning">Integra</span>
        	</div>
		    <div class="acronym-content">
        		<span class="icon adaia-a"></span>
            	<span class="themeaning">Avanza</span>
        	</div>
		    
    </div> <!-- .letter-group -->
    </div></div></div>
</section>

<?php

// Obtener las subpáginas de la página actual
// $subpages = get_pages (array ('child_of' => 46, 'sort_column' => 'menu_order'));
$subpages = get_pages (array ('parent' => 0, 'sort_column' => 'menu_order'));

showSubpages ($subpages);

// get_sidebar();
get_footer ();
