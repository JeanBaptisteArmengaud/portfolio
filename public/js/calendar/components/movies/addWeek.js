const addWeek = {
    init: function () {
        document
            .querySelectorAll('.add_item_link')
            .forEach(btn => {
                btn.addEventListener("click", addWeek.addFormToCollection)
            });
        document
            .querySelectorAll('ul.screeningSchedules li')
            .forEach((week) => {
                addWeek.addFormDeleteLink(week)
            });

        document
            .querySelectorAll('input[id$="weekStart"]')
            .forEach((weekStartDate) => {
                weekStartDate.addEventListener("change", addWeek.handleAddWeekEndDate)
            });
        document
            .querySelectorAll('input[id$="weekEnd"]')
            .forEach((weekEndInput) => {
                addWeek.hideWeekEndInput(weekEndInput);
            });


    },

    addFormToCollection: function(element) {
        const collectionHolder = document.querySelector('.' + element.currentTarget.dataset.collectionHolderClass);

        const item = document.createElement('li');

        item.innerHTML = collectionHolder
            .dataset
            .prototype
            .replace(
                /__name__/g,
                collectionHolder.dataset.index
            );

        collectionHolder.appendChild(item);

        collectionHolder.dataset.index++;

        addWeek.addFormDeleteLink(item);
        addWeek.hideWeekEndInput(item.querySelector('input[id$="weekEnd"]'));

        item
            .querySelector('input[id$="weekStart"]')
            .addEventListener(
                'change',
                addWeek.handleAddWeekEndDate
            );

    },

    addFormDeleteLink: function (week) {
        const container = document.createElement('div');
        container.classList.add('col-3', 'mb-3', 'd-flex', 'align-items-center');

        const btnGroup = document.createElement('div');
        btnGroup.classList.add('btn-group')
        btnGroup.insertAdjacentHTML('afterbegin',
            '<button type="button" class="btn btn-sm btn-outline-danger dropdown-toggle" ' +
            'data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
            '<i class="bi bi-trash" aria-hidden="true"></i>' +
            '</button>' +
            '<ul class="dropdown-menu">' +
            '<li><button class="dropdown-item">Oui, supprimer cette semaine</button></li>' +
            '<li><a class="dropdown-item" href="#" data-bs-toggle="dropdown">Oups !</a></li>' +
            '</ul>'
            );

        const weekStartInput = week.querySelector('input[id$="weekStart"]');

        week.firstElementChild.classList.add('row');
        weekStartInput.parentElement.classList.add('col-9');

        container.appendChild(btnGroup)
        weekStartInput.parentElement.after(container);

        const removeFormButton = btnGroup.querySelector('button.dropdown-item');

        removeFormButton.addEventListener('click', (event) => {
            event.preventDefault();
            week.remove();
        });
    },

    hideWeekEndInput: function (weekEndInput) {
        weekEndInput.parentElement.style.display = "none";
    },

    handleAddWeekEndDate: function (event) {
        const weekStartParentEl = event.currentTarget.parentElement;
        const weekParentEl = weekStartParentEl.parentElement;
        const weekEndInput = weekParentEl.querySelector('input[id$="weekEnd"]');
        const weekEndDate = new Date(event.target.value);


        addWeek.addWeekEndDate(weekEndInput, weekEndDate);
    },

    addWeekEndDate: function (weekEndInput, weekEndDate) {

        weekEndDate.setDate(weekEndDate.getDate() + 7);
        weekEndInput.valueAsDate = weekEndDate;

    }
};

document.addEventListener('DOMContentLoaded', addWeek.init);
