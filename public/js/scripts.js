window.addEventListener('DOMContentLoaded', event => {

    // Définition de la taille de la modale en fonction du type d'image (afficher les captures d'écran dans une modale fullscreen
    document.querySelectorAll('img[data-bs-toggle="modal"]').forEach(function(img) {
        img.addEventListener('click', imageModalSize);
    });

    // Effet de flou sur le texte des cards projets
    document.querySelectorAll('a.collapse-button').forEach(function(button) {
        button.addEventListener('click', toggleTextBlur);
    });
});

function imageModalSize() {
    const modalImage = document.getElementById('modalImage');
    const imageModal = document.getElementById('imageModal');

    if (this.classList.contains('capture')) {
        imageModal.firstElementChild.classList.add('modal-fullscreen');
        imageModal.firstElementChild.classList.remove('modal-xl');
    } else {
        imageModal.firstElementChild.classList.remove('modal-fullscreen');
        imageModal.firstElementChild.classList.add('modal-xl');
    }

    const imageUrl = this.getAttribute('src');
    modalImage.src = imageUrl;
}

function toggleTextBlur() {
    const buttonDiv = this.parentNode;
    const cardContent = buttonDiv.parentNode;
    let bluredText = cardContent.querySelector('.blur-text');

    if (bluredText.classList.contains('blur-end')) {
        bluredText.classList.toggle('blur-end');

    } else {
        setTimeout(function () {
            bluredText.classList.toggle('blur-end');
        }, 200);
    }
}
