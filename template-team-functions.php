<?php


function formatTeam ($id, $postTitle, $postName, $content)
{
	$teamMemberName = $postTitle;
	$titulo = get_post_meta ($id, 'Titulo', true);
	$colegiada = get_post_meta ($id, 'Colegiada', true);

	// Prepare the vars
	$titulo = ($titulo) ? '<h4>' . esc_html ($titulo) . '</h4>' : '';
	$colegiada = ($colegiada) ? '<h4>' . esc_html ($colegiada) . '</h4>' : '';

	echo '<div id="' . $postName . '" class="team-member">';

	// if (has_post_thumbnail ())
	$thumbnail_url = get_the_post_thumbnail_url ($id, 'large');
	if ($thumbnail_url)
	{
		echo '<img class="team-info-image" src="' . $thumbnail_url . '" alt="' . $teamMemberName . '" scale="0" >';
	}

	// team member sumary
	{
		echo '<div class="team-info-sumary">';
		echo "<h3>$teamMemberName</h3>" . $titulo . $colegiada;
		echo '</div>';
	}

	// Team member description
	{
		echo '<div class="team-info-details">';

		echo "<h3>$teamMemberName</h3>" . $titulo . $colegiada;

		echo apply_filters ('the_content', $content);
		echo '</div>';
	}

	echo '</div>';
}