$(document).ready(inicialitzarEvents);

function inicialitzarEvents() {
    document.emailChangePassword.addEventListener("submit", formValidatorEvent);
    document['emailChangePassword']['_email'].addEventListener("change", validaElementEvent);
    document['emailChangePassword']['_repeatEmail'].addEventListener("change", validaElementEvent);
}

function formValidatorEvent(e) {
    if (!formValidator()) {
        e.preventDefault();
    }
}

function formValidator() {
    // Make quick references to our fields
    var email = document['emailChangePassword']['_email'];
    var repeatEmail = document['emailChangePassword']['_repeatEmail'];

    var noValid = null;
    // Check each input in the order that it appears in the form!
    if (!emailValidator(email, "* Introduce un formato de email valido!") && !noValid) noValid = email;
    if (!notSameEmail(repeatEmail, "* Los emails no coinciden!") && !noValid) noValid = repeatEmail;

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
        elem.focus();
    }
    return valid;
}

function emailValidator(elem, helperMsg) {
    var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
    var resultat = elem.value.match(emailExp);
    tractarError(elem, resultat, helperMsg);
    return resultat;
}

function notSameEmail(elem, helperMsg) {
    var resultat = (elem.value == document['emailChangePassword']['_email'].value);
    tractarError(elem, resultat, helperMsg);
    return resultat;
}

function validaElementEvent(e) {
    validaElement(this);
}

function validaElement(elem) {
    switch (elem.name) {
        case "_email":
            emailValidator(elem, "* Introduce un formato de email valido!");
            break;
        case "_emailRepeat":
            notSameEmail(elem, "* Los emails no coinciden!");
            break;
        default:
            break;
    }
}