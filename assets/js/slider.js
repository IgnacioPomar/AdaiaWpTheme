$(document).ready(function() {
    let currentIndex = 0;
    const slider = $('#slider');
    const pictureElement = slider.find('.slide picture');
    const sourceElement = pictureElement.find('source');
    const imgElement = pictureElement.find('img');
    const textElement = slider.find('.slide .txt');

    function preloadImage(src) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.src = src;
            img.onload = resolve;
            img.onerror = reject;
        });
    }

    function changeSlide() {
        const nextIndex = (currentIndex + 1) % slides.length;
        const newWebp = slides[nextIndex].img + ".webp";
        const newJpg = slides[nextIndex].img;

        // Precargar la nueva imagen antes de cambiarla
        preloadImage(newJpg)
            .then(() => preloadImage(newWebp)) // Precargar WebP después del JPG
            .then(() => {
                imgElement.fadeOut(300, function() {
                    sourceElement.attr('srcset', newWebp);
                    imgElement.attr('src', newJpg);
                    imgElement.fadeIn(300);
                });

                textElement.fadeOut(300, function() {
                    $(this).text(slides[nextIndex].text).fadeIn(300);
                });

                currentIndex = nextIndex; // Actualizar índice solo después del cambio exitoso
            })
            .catch(() => console.error("Error al cargar la imagen:", newJpg));
    }

    setInterval(changeSlide, 5000);
});
