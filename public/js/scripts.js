window.addEventListener('DOMContentLoaded', event => {
    const modalImage = document.getElementById('modalImage');
    const imageModal = document.getElementById('imageModal');

    document.querySelectorAll('img[data-bs-toggle="modal"]').forEach(function(img) {
        img.addEventListener('click', function() {

            if (this.classList.contains('capture')) {
                imageModal.firstElementChild.classList.add('modal-fullscreen');
                imageModal.firstElementChild.classList.remove('modal-xl');
            } else {
                imageModal.firstElementChild.classList.remove('modal-fullscreen');
                imageModal.firstElementChild.classList.add('modal-xl');
            }

            const imageUrl = this.getAttribute('src');
            modalImage.src = imageUrl;
        });
    });
});
