    </main>
	<div class="contact-footer<?=is_front_page () ? ' frontpage' : ''?>">
		<h2 id="contact"><a href="<?=get_permalink (get_option ('adaia_contact_page'));?>">¿Tienes una consulta? Contacta con nosotros</a></h2>
		<p class="email"><a href="mailto:info@psicoadaia.com">info@psicoadaia.com</a></p>
		<p class="copyright"><a href="<?=home_url ();?>/legal/aviso-legal/">AVISO LEGAL</a></p>
		<p>&copy; <?=date ("Y")?> Adaia&reg;. Todos los derechos reservados.</p>
	</div>
	<?php

	wp_footer ();
	?>
</body>
</html>