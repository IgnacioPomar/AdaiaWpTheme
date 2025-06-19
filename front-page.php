<?php
setlocale (LC_ALL, 'es_ES.utf8');

get_header ();

$themeBaseUri = get_template_directory_uri ();

?>

<style>

</style>
  
  
  <section id="hero">
  <div class="hero-container">
    <div class="hero-content">
      <h1>Comprende lo que sientes</h1>
      <h2>Transmite emoción. Realiza tus metas. Sonríe.</h2>
      <p>
        ¿Buscas apoyo psicológico en Pozuelo? En nuestro gabinete te
        acompañamos en cada paso para que recuperes el equilibrio, conectes
        contigo mismo y logres tus metas.
      </p>
      <a
        class="contactBtn"
        href="https://wa.me/34696585022"
        target="_blank"
        rel="noreferrer noopener"
      >
        Contacta por WhatsApp <span class="icon adaia-whatsapp"></span>
      </a>
    </div>
    <div class="hero-figure">
      <img
        src="<?=$themeBaseUri?>/assets/img/tiburon.svg"
        alt="Nuestra mascota :)"
        loading="lazy"
        decoding="async"
        class="hero-img"
      />
    </div>
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
