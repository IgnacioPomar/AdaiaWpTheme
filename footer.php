    </main>
	<div class="contact-footer<?=is_front_page () ? ' frontpage' : ''?>">
	

	<!-- TODO: get email and usr of "aviso Legal" from its page ID -->
	<?php
	$legalPageId = get_option ('adaia_legal_page');
	$legalUrl = get_permalink ($legalPageId);
	$infoEmail = get_option ('adaia_email');
	?>
	
		<p class="email"><a href="<?=$infoEmail?>"><?=$infoEmail?></a></p>
		<p class="copyright"><a href="<?=$legalUrl?>">AVISO LEGAL</a></p>
		<p>&copy; <?=date ("Y")?> Adaia&reg;. Todos los derechos reservados.</p>
	</div>
	<?php

	wp_footer ();
	?>
</body>
</html>