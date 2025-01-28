<?php
get_header ();
if (have_posts ())
{

	while (have_posts ())
	{
		the_post ();
		echo '<div class="mainPage">';
		echo '<h1>' . get_the_title () . '</h1>';
		echo '<div class="content">' . apply_filters ('the_content', get_the_content ()) . '</div>';
		echo '</div>';
	}
}
get_footer ();
// get_sidebar();

