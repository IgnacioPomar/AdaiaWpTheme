    </main>
	<div class="contact-footer<?=is_front_page () ? ' frontpage' : ''?>">
	
	<?php
	$contact_page_id = get_option ('adaia_contact_page');
	$current_page_id = get_queried_object_id ();

	if ($current_page_id !== intval ($contact_page_id))
	{
		$url_contacto = get_permalink ($contact_page_id);
		echo '<h2 id="contact"><a href="' . $url_contacto . '">¿Tienes una consulta? Contacta con nosotros</a></h2>';
	}
	?>
	
		<p class="email"><a href="mailto:info@psicoadaia.com">info@psicoadaia.com</a></p>
		<p class="copyright"><a href="<?=home_url ();?>/legal/aviso-legal/">AVISO LEGAL</a></p>
		<p>&copy; <?=date ("Y")?> Adaia&reg;. Todos los derechos reservados.</p>
	</div>
	<?php

	wp_footer ();
	?>
</body>
</html>