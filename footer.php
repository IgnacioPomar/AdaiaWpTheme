    </main>
	<div class="contact-footer<?=is_front_page () ? ' frontpage' : ''?>">
	

	<!-- TODO: get email and usr of "aviso Legal" from its page ID -->
	
		<p class="email"><a href="mailto:info@psicoadaia.com">info@psicoadaia.com</a></p>
		<p class="copyright"><a href="<?=home_url ();?>/legal/aviso-legal/">AVISO LEGAL</a></p>
		<p>&copy; <?=date ("Y")?> Adaia&reg;. Todos los derechos reservados.</p>
	</div>
	<?php

	wp_footer ();
	?>
</body>
</html>