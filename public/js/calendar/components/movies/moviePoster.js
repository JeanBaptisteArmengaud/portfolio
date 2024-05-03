const moviePoster = {
    init: function () {
        const posterInputEl = document.querySelector('input#movie_poster');
        const posterURL = posterInputEl.value;

        moviePoster.posterPreview(posterURL);

        posterInputEl.addEventListener("input", moviePoster.handleUpdatePosterPreview);
    },

    posterPreview: function (posterURL) {
        const imgElement = document.querySelector('img.movie-poster');
        imgElement.src = posterURL;
    },

    handleUpdatePosterPreview: function (event) {
        const posterURL = event.target.value;
        moviePoster.posterPreview(posterURL)
    }
}

document.addEventListener('DOMContentLoaded', moviePoster.init);
