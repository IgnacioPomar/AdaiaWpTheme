<?php
setlocale (LC_ALL, 'es_ES.utf8');

get_header ();

$themeBaseUri = get_template_directory_uri ();

?>
<script defer src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script defer src="<?=$themeBaseUri?>/assets/js/slider.js"></script>

<script>
const slides = [
    { img: '<?=$themeBaseUri?>/assets/img/sld01-sientes.jpg', text: 'Comprende lo que sientes' },
    { img: '<?=$themeBaseUri?>/assets/img/sld02-emocion.jpg', text: 'Transmite emoción' },
    { img: '<?=$themeBaseUri?>/assets/img/sld03-metas.jpg', text: 'Realiza tus metas' },
    { img: '<?=$themeBaseUri?>/assets/img/sld04-sonrie.jpg', text: 'Sonríe' }
];
</script>


<section id="slider">
	<div class="slide">
		<picture>
			<source srcset="<?=$themeBaseUri?>/assets/img/sld01-sientes.jpg.webp" type="image/webp">
			<img src="<?=$themeBaseUri?>/assets/img/sld01-sientes.jpg" >
		</picture>
		<span class="txt">Comprende lo que sientes</span>
	</div>
</section>


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
