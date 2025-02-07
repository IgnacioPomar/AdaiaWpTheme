$(document).ready(function() {
    let currentIndex = 0;
    const slider = $('#slider');
    const pictureElement = slider.find('.slide picture');
    const sourceElement = pictureElement.find('source');
    const imgElement = pictureElement.find('img');
    const textElement = slider.find('.slide .txt');

    function changeSlide() {
        currentIndex = (currentIndex + 1) % slides.length;

        // Obtener nuevas rutas de imagen (WebP y JPG)
        const newWebp = slides[currentIndex].img + ".webp";
        const newJpg = slides[currentIndex].img;

        // Animación de cambio de imagen
        imgElement.fadeOut(300, function() {
            sourceElement.attr('srcset', newWebp); // Cambia el source WebP
            imgElement.attr('src', newJpg); // Cambia el fallback JPG/PNG
            imgElement.fadeIn(300);
        });

        // Animación de cambio de texto
        textElement.fadeOut(300, function() {
            $(this).text(slides[currentIndex].text).fadeIn(300);
        });
    }

    setInterval(changeSlide, 5000);
});
