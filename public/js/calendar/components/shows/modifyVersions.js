const modifyVersions = {

    init: function () {
        document
            .querySelectorAll('button[id$="_modify"]')
            .forEach(btn => {
                btn.addEventListener("click", modifyVersions.displayForm)
            });
    },

    displayForm: function(event) {
        const buttonId = event.currentTarget.id;
        const versionsListId = buttonId.replace("_modify", "");
        const currentVersions = document.querySelector('div#' + versionsListId);

        currentVersions.style.display = "none";

        const movieTitle = document.querySelector('h1#movie_title');
        const movieVersionsData = JSON.parse(movieTitle.dataset.versions);
        const versionsForm = document.createElement('form');
        versionsForm.classList.add('form');


        const currentVersionsUlElement = currentVersions.querySelector('ul');
        const currentSelectedVersions = JSON.parse(currentVersionsUlElement.dataset.currentVersions);

        for (const version of movieVersionsData) {
            const checkboxDiv = document.createElement('div');
            checkboxDiv.classList.add('form-check', 'form-check-inline');

            const checkboxInput = document.createElement('input');
            checkboxInput.classList.add('form-check-input');
            checkboxInput.type = "checkbox";
            checkboxInput.id = versionsListId + "_" + version.id;
            checkboxInput.value = version.id;
            checkboxInput.name = version.name;

            const checkboxLabel = document.createElement('label');
            checkboxLabel.classList.add('form-check-label');
            checkboxLabel.htmlFor = checkboxInput.id;
            checkboxLabel.textContent = version.name;

            checkboxDiv.append(checkboxInput, checkboxLabel);

            versionsForm.append(checkboxDiv);
        }


        for (selectedVersion of currentSelectedVersions) {
            const versionId = 'versions_' + selectedVersion.id;
            const versionInput = versionsForm.querySelector('input[id$=' + versionId + ']');
            versionInput.checked = true;
        }


        const submitButton = document.createElement('button');
        submitButton.type = "submit";
        submitButton.classList.add('btn', 'btn-sm', 'btn-success');
        submitButton.textContent = "Enregistrer";

        const cancelButton = document.createElement('button');
        cancelButton.type = "button";
        cancelButton.classList.add('btn', 'btn-sm', 'btn-danger');
        cancelButton.textContent = "Annuler";

        versionsForm.append(submitButton, cancelButton);
        currentVersions.parentElement.append(versionsForm);

        versionsForm.addEventListener('submit', modifyVersions.versionsPatchRequest);
    },

    versionsPatchRequest: function (event) {
        event.preventDefault();

        const formParent = event.currentTarget.parentElement;
        const showId = formParent.dataset.showId;

        const selectedElements = formParent.querySelectorAll("input[type=checkbox]:checked");
        let newVersions = [];

        selectedElements.forEach(element => {
            newVersions.push(element.value);
        });


        const data = {
            showId: showId,
            versions: newVersions,
        };

        const httpHeaders = new Headers();
        httpHeaders.append("Content-Type", "application/json");

        const fetchOptions = {
            method: 'PATCH',
            mode: 'cors',
            cache: 'no-cache',
            headers: httpHeaders,
            body: JSON.stringify(data)
        };


        fetch(window.location.origin + '/calendar/api/shows/' + showId + '/versions', fetchOptions)
            .then(
                function(response) {

                    if (response.status == 200) {

                        alert('ajout effectué');

                        return response.json();
                    }
                    else {
                        alert('L\'ajout a échoué');
                    }
                }
            )
            .then(function (jsonResponse) {
                console.log(jsonResponse);
                }

            )
    },
}
document.addEventListener('DOMContentLoaded', modifyVersions.init);
