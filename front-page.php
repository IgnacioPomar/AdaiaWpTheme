<?php
setlocale (LC_ALL, 'es_ES.utf8');

get_header ();

$themeBaseUri = get_template_directory_uri ();

?>

<style>
:root {
  --violeta: #52345d;
  --teal:    #36d1c4;
  --beige:   #f5f1eb;
}

/* ========================
   HERO
   ======================== */
#hero {
  background-color: var(--violeta);
  color:            var(--beige);
}

#hero .hero-container {
  display:        flex;
  flex-direction: column;
  justify-content: center;  /* centra verticalmente todo */
  align-items:     center;
  gap:             2rem;
  max-width:       1200px;
  margin:          0 auto;
  padding:         4rem 2rem;
}



@media (min-width: 768px) {
  #hero .hero-container {
    flex-direction: row;
    align-items:    stretch; /* que figure y content compartan altura */
    gap:            4rem;
  }
}

/* ---- contenido de texto ---- */
#hero .hero-content {
  flex:            1;
  display:         flex;
  flex-direction:  column;
  justify-content: center; /* centra verticalmente el texto */
  align-items:     center; /* centra horizontalmente (texto y botón) */
  text-align:      center;
  padding-top:     1rem;   /* un poco más de espacio arriba */
}

#hero .hero-content h1 {
  font-size:   3rem;
  line-height: 1.1;
  margin:      0 0 1rem;
  color: var(--teal);
}

#hero .hero-content h2 {
  font-size:   1.75rem;
  line-height: 1.3;
  margin:      0 0 1.5rem;
}

#hero .hero-content p {
  font-size:   1rem;
  line-height: 1.6;
  margin:      0 0 2rem;
  text-align: left; /* justifica el texto */
  color:     #C3BACC;
}

/* ---- botón ---- */
#hero .contactBtn {
  display:        inline-block;
  background-color: var(--beige);
  color:            var(--violeta);
  text-decoration:  none;
  font-weight:      600;
  padding:          1rem 2rem;
  border-radius:    2rem;
  transition:       transform 0.2s ease, background-color 0.3s ease;
}

#hero .contactBtn:hover {
  background-color: var(--teal);
  transform:        translateY(-2px);
}

span.icon.adaia-whatsapp::after {
    content: "\e61C";
    vertical-align: middle;
    font-family: icoadaia;
    font-size: x-large;
    margin-left: 10px;
    color:#01e675;
    
}

/* ---- figura / imagen ---- */
#hero .hero-figure {  display:    none;}
@media (min-width: 768px) {
#hero .hero-figure {
  flex:       1;
  display:    flex;
  align-items:    center;
  justify-content: center;
  min-height: 0; /* evita que la img infle el contenedor */
}

#hero .hero-img {
  max-width:  100%;
  max-height: 100%;
  object-fit: contain;
  display:    block;
}
}


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
