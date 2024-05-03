
document.addEventListener('DOMContentLoaded', function() {
    let calendarEl = document.getElementById('calendar');
    let moviesEl = document.getElementById('movies');
    let eventsData = JSON.parse(calendarEl.dataset.shows);
    console.log(eventsData);
    // let moviesData = JSON.parse(moviesEl.dataset.movies);
    const body = document.querySelector('body');
    const flashMessage = document.createElement('div');




    let calendar = new FullCalendar.Calendar(calendarEl, {
        height: "90vh",
        locale: 'fr',
        timeZone: 'Europe/Paris',
        schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
        themeSystem: 'bootstrap5',
        initialView: 'resourceTimeGridDay',
        droppable: true,

        headerToolbar: {
            left: 'title,addMovie,toggleEditable',
            center: 'resourceTimeGridDay,resourceTimeGridWeek',
            right: 'prevMonth,prev,today,next,nextMonth',
        },

        resources: [
            { id: 1, title: 'Salle 1' },
            { id: 2, title: 'Salle 2' },
        ],

        // Premier jour de la semaine : mercredi
        firstDay: 3,



        datesAboveResources: true,
        allDaySlot: false,
        nowIndicator: true,
        slotDuration: '00:15:00',
        slotLabelFormat: {
            hour: 'numeric',
            minute: '2-digit',
            omitZeroMinute: false,
        },
        scrollTime: '13:00:00',
        eventOverlap: false,
        navLinks: true,

        customButtons: {
            addMovie: {
                text: 'Ajouter un film',
                click: function() {
                    window.location = window.location.href + '/movie/new';
                }
            },
            toggleEditable: {
                text: 'Editer les séances',
                click: function () {
                    let toggle = calendar.getOption('editable');
                    calendar.setOption('editable', !toggle);
                    let text = document.querySelector('button.fc-toggleEditable-button');
                    // console.log(toggle);

                    if (calendar.getOption('editable') == true) {
                        text.textContent = 'Enregistrer les modifications';
                    }

                    if (calendar.getOption('editable') == false) {
                        text.textContent = 'Editer les séances';
                    }
                }
            },
            prevMonth: {
                text: '<-1 mois',
                click: function () {
                    calendar.incrementDate({month: -1})
                }
            },
            nextMonth: {
                text: '+1 mois>',
                click: function () {
                    calendar.incrementDate({month: 1})
                }
            }
        },

        eventDurationEditable: false,
        events: eventsData,

        // eventContent: {
        //     html: '<i>Test</i>'
        // },

        eventDrop: function(info) {
            // alert(info.event.title + " was dropped on " + info.event.start.toUTCString());

            if (!confirm("Are you sure about this change?")) {
                info.revert();
            } else {
                // console.log(info);

                const data = {
                    id: info.event.id,
                    start: info.event.start.toJSON(),
                    resource: info.newResource,
                };
                console.log(data);

                const httpHeaders = new Headers();
                httpHeaders.append("Content-Type", "application/json");

                const fetchOptions = {
                    method: 'PATCH',
                    mode: 'cors',
                    cache: 'no-cache',
                    headers: httpHeaders,
                    body: JSON.stringify(data)
                };
                console.log(JSON.stringify(data));

                fetch(window.location.href + '/api/shows/' + info.event.id, fetchOptions)
                    .then(
                        function(response) {

                            if (response.status == 204) {

                                alert('ajout effectué');

                                return response.json();
                            }
                            else {
                                alert('L\'ajout a échoué');
                                info.revert();
                            }
                        }
                    )
            }
        },

        eventReceive: function (info) {

                const data = {
                    start: info.event.start,
                    resource: info.event.getResources()[0]['id'],
                    movieId: info.event.id,
                };

                const httpHeaders = new Headers();
                httpHeaders.append("Content-Type", "application/json");

                const fetchOptions = {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    headers: httpHeaders,
                    body: JSON.stringify(data)
                };

                fetch(window.location.href + '/api/shows', fetchOptions)
                    .then(
                        function(response) {

                            if (response.status == 201) {

                                return response.json();
                            }
                            else {
                                alert('L\'ajout a échoué');
                                info.revert();
                            }
                        }
                    )
                    .then(function (data) {
                        info.event.setProp('url', window.location.href + '/show/' + data.show.id + '/edit');
                        info.event.setEnd(data.showEnd);
                        }
                    )
        },
        datesSet: function (dateInfo) {

            const data = {
                start: dateInfo.start,
                end: dateInfo.end,
            };

            const httpHeaders = new Headers();
            httpHeaders.append("Content-Type", "application/json");

            const fetchOptions = {
                method: 'POST',
                mode: 'cors',
                cache: 'no-cache',
                headers: httpHeaders,
                body: JSON.stringify(data)
            };

            fetch(window.location.href + '/api/movies-by-dates', fetchOptions)
                .then(
                    function(response) {

                        if (response.status == 200) {

                            return response.json();
                        }
                        else {
                            throw new Error('No Content');
                        }
                    }
                )
                .then(function (data) {
                        bindMovies(data);
                    }
                )
                .catch(error => {
                    removeMovies();

                    const divAlertElement = document.querySelector('div.alert');

                    if (divAlertElement === null) {
                        flashMessage.className = 'alert alert-warning';
                        flashMessage.textContent = 'Aucun film programmé cette semaine';
                        body.prepend(flashMessage);
                    }
                })
        },

        // viewDidMount: function (arg) {
        //     console.log(calendar.getDate());
        // }

        eventDragStart: function (info) {
            console.log(info.event);
        }
    });

    calendar.render();

    function bindMovies(moviesData) {

        removeMovies();
        removeFlashMessage();


        moviesData.forEach((movie, index) => {
            const movieDiv = document.createElement("div");
            const posterEl = document.createElement('img');
            posterEl.src = movie.extendedProps.poster;
            posterEl.width = '50';
            movieDiv.classList.add("movie-item");
            movieDiv.classList.add("list-group-item");
            // movieDiv.classList.add("alert-secondary");
            movieDiv.textContent = movie.title;
            movieDiv.dataset.event = JSON.stringify(moviesData[index]);
            movieDiv.prepend((posterEl));
            moviesEl.append(movieDiv);
        });
    }

    function removeMovies() {
        const moviesToDelete = moviesEl.querySelectorAll('div.movie-item');
        moviesToDelete.forEach((item) => {
            item.remove();
        });
    }

    function removeFlashMessage() {
        flashMessage.remove();
    }



    new FullCalendar.Draggable(moviesEl, {
        itemSelector: '.movie-item',
    });


    // delete moviesEl.dataset.movies;
    delete calendarEl.dataset.shows;
});
