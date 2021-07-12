function formSubminEventListener(formElem) {
    formElem.addEventListener('submit', async(e) => {
        e.preventDefault();
        try {
            var data = new FormData(formElem);
            console.log(data);

            let response = await fetch(formElem.action, {
                method: 'POST',
                body: data
            });

            let result = await response.json();

            switch (result.status) {
                case 'error':
                    alert(result.status + ' - ' + result.message);
                    break;
                case 'error-multiple':
                    let combinedMessage = '';
                    for (let message of result.messages) {
                        combinedMessage += message;
                    }
                    alert(combinedMessage);
                    break;
                case 'redirect':
                    window.location.href = window.location.origin + result.message;
                    break;
            }

            //alert(result.status + ' - ' + result.message);
            // document.location.reload();
        } catch (error) {
            alert(error.message);
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    var forms = document.getElementsByClassName('formElem');
    const addPersonBtn = document.getElementById('add-person');

    for (let form of forms) {
        formSubminEventListener(form);
    }

    if (addPersonBtn) {
        addPersonBtn.addEventListener('click', async(e) => {
            e.preventDefault();

            const personFieldsetTemplate = `
            <fieldset class="person-filedset" name="person-{id}">
                <div class="delete" id="delete-person-{id}">Delete person</div>
                <h2>Person-{id}</h2>
                <h2>Login</h2>
                <p><input type="text" name="login-{id}"></p>
                <h2>Password</h2>
                <p><input type="password" name="password-{id}"></p>
                <h2>Confirm password</h2>
                <p><input type="password" name="conf_password-{id}"></p>
                <h2>Email</h2>
                <p><input type="text" name="email-{id}"></p>
            </fieldset>`;

            if (window.currentPersonId === undefined) {
                window.currentPersonId = 1;
            }

            var template = document.createElement("template");

            template.innerHTML = (personFieldsetTemplate.replaceAll("{id}", ++window.currentPersonId)).trim();
            var fieldsetElement = template.content.firstChild;

            addPersonBtn.parentElement.insertAdjacentElement('beforeend', fieldsetElement);

            var deleteBtn = document.getElementById('delete-person-{id}'.replace("{id}", window.currentPersonId));
            deleteBtn.addEventListener('click', async(e) => {
                deleteBtn.parentElement.remove();
            });
        })
    }
});