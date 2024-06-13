<?php //get_template_part('templates/page', 'header'); ?>


<div class="not-found-content">
	<?php 
		$titulo = get_field("page_not_found_titulo", "options");
		$texto 	= get_field("page_not_found_texto", "options"); 
	?>
    <span class="icon icon-error-01"></span>
    <?php if ($titulo){ ?><h2><?=$titulo?></h2><?php } ?>
    <?php if ($texto){ ?><p><?=$texto?></p><?php } ?>
   
</div> <!-- not-found-content -->



<?php get_search_form(); ?>

