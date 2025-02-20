<?php


function formatTeam ($id, $postTitle, $postName, $content)
{
	$teamMemberName = $postTitle;
	$titulo = get_post_meta ($id, 'Titulo', true);
	$colegiada = get_post_meta ($id, 'Colegiada', true);

	// Prepare the vars
	$titulo = ($titulo) ? esc_html ($titulo) : '';
	$colegiada = ($colegiada) ? esc_html ($colegiada) : '';

	echo '<div id="' . $postName . '" class="team-member">';

	$imgUrl = get_the_post_thumbnail_url ($id, 'large');
	if ($imgUrl)
	{
		$webpUrl = $imgUrl . '.webp';
		echo '<picture><source srcset="' . esc_url ($webpUrl) . '" type="image/webp">';
		echo '<img class="team-info-image" src="' . $imgUrl . '" alt="' . $teamMemberName . '" height="588">';
		echo '</picture>';
	}

	$toggleId = 'toggleTeamInfo' . $postName;
	echo '<input type="checkbox" id="' . $toggleId . '" />';
	// team member sumary
	{
		echo '<label for="' . $toggleId . '" class="team-info-sumary"><content>';
		echo "<h3>$teamMemberName</h3><p>" . $titulo . '</p><p>' . $colegiada . '</p>';
		echo '</content></label>';
	}

	// Team member description
	{
		echo '<label for="' . $toggleId . '" class="team-info-details"><content>';

		echo "<h3>$teamMemberName</h3><h4>" . $titulo . '</h4><h4>' . $colegiada . '</h4>';

		echo apply_filters ('the_content', $content);
		echo '</content></label>';
	}

	echo '</div>';
}