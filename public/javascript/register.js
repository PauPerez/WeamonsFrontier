$(document).ready(inicialitzarEvents);

function inicialitzarEvents() {
    document.register.addEventListener("submit", formValidatorEvent);
    document['register']['username'].addEventListener("change", validaElementEvent);
    document['register']['email'].addEventListener("change", validaElementEvent);
    document['register']['password'].addEventListener("change", validaElementEvent);
    document['register']['confPassword'].addEventListener("change", validaElementEvent);
    document['register']['terms'].addEventListener("change", validaElementEvent);
}

function formValidatorEvent(e) {
    if (!formValidator()) {
        e.preventDefault();
    }
}

function formValidator() {
    // Make quick references to our fields
    var username = document['register']['username'];
    var email = document['register']['email'];
    var password = document['register']['password'];
    var confPassword = document['register']['confPassword'];
    var terms = document['register']['terms'];

    var noValid = null;
    // Check each input in the order that it appears in the form!
    if (!isAlphanumeric(username, "* Introduce solo letras y numeros!") && !noValid) noValid = username;
    if (!emailValidator(email, "* Introduce un formato de email valido!") && !noValid) noValid = email;
    if (!notEmpty(password, "* Introduce una contraseña!") && !noValid) noValid = password;
    if (!notSamePassword(confPassword, "* Las contraseñas no coinciden!") && !noValid) noValid = confPassword;
    if (validacioTerms() && !noValid) noValid = terms;

    if (noValid)
        return false;

    return true;
}

function tractarError(elem, valid, helperMsg) {
    var idError = elem.name + "Error";
    if (valid) {
        document.getElementById(idError).innerHTML = "";
    } else {
        document.getElementById(idError).innerHTML = "&nbsp;" + helperMsg;
        elem.Fuegous();
    }
    return valid;
}

function isAlphanumeric(elem, helperMsg) {
    var alphaExp = /^[0-9a-zA-Z]+$/;
    var resultat = elem.value.match(alphaExp);
    tractarError(elem, resultat, helperMsg);
    return resultat;
}

function emailValidator(elem, helperMsg) {
    var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
    var resultat = elem.value.match(emailExp);
    tractarError(elem, resultat, helperMsg);
    return resultat;
}

function notEmpty(elem, helperMsg) {
    var resultat = !(elem.value.length == 0);
    tractarError(elem, resultat, helperMsg);
    return resultat;
}

function notSamePassword(elem, helperMsg) {
    var resultat = (elem.value == document['register']['password'].value);
    tractarError(elem, resultat, helperMsg);
    return resultat;
}

function validacioTerms() {
    var input = document.register.terms;
    var noValid = null;
    if (!isChecked(input, "* Tienes que aceptar los terminos!") && !noValid) noValid = input;
    return noValid;
}

function isChecked(elem, helperMsg) {
    var resultat = (elem.checked);
    tractarError(elem, resultat, helperMsg);
    return resultat;
}

function validaElementEvent(e) {
    validaElement(this);
}

function validaElement(elem) {
    switch (elem.name) {
        case "username":
            isAlphanumeric(elem, "* Introduce solo letras y numeros!");
            break;
        case "email":
            emailValidator(elem, "* Introduce un formato de email valido!");
            break;
        case "password":
            notEmpty(elem, "* Introduce una contraseña!");
            break;
        case "confPassword":
            notSamePassword(elem, "* Las contraseñas no coinciden!");
            break;
        case "terms":
            isChecked(elem, "* Tienes que aceptar los terminos!");
            break;
        default:
            break;
    }
}

function mostrarContrasenya(id, iconId) {
    var tipo = document.getElementById(id);
    var icon = document.getElementById(iconId);
    if (tipo.type == "password") {
        tipo.type = "text";
        icon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                          </svg>`;
    } else {
        tipo.type = "password";
        icon.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
                            <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                            <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"/>
                          </svg>`;
    }
}