$(document).ready(inicialitzarEvents);

function inicialitzarEvents() {
    document.login.addEventListener("submit", formValidatorEvent);
    document['login']['_username'].addEventListener("change", validaElementEvent);
    document['login']['_password'].addEventListener("change", validaElementEvent);
}

function formValidatorEvent(e) {
    if (!formValidator()) {
        e.preventDefault();
    }
}

function formValidator() {
    // Make quick references to our fields
    var email = document['login']['_username'];
    var password = document['login']['_password'];

    var noValid = null;
    // Check each input in the order that it appears in the form!
    if (!emailValidator(email, "* Introduce un formato de email valido!") && !noValid) noValid = email;
    if (!notEmpty(password, "* Introduce una contraseña!") && !noValid) noValid = password;

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

function notEmpty(elem, helperMsg) {
    var resultat = !(elem.value.length == 0);
    tractarError(elem, resultat, helperMsg);
    return resultat;
}

function validaElementEvent(e) {
    validaElement(this);
}

function validaElement(elem) {
    switch (elem.name) {
        case "_username":
            emailValidator(elem, "* Introduce un formato de email valido!");
            break;
        case "_password":
            notEmpty(elem, "* Introduce una contraseña!");
            break;
        default:
            break;
    }
}

function mostrarContrasenya(id) {
    var tipo = document.getElementById(id);
    if (tipo.type == "password") {
        tipo.type = "text";
    } else {
        tipo.type = "password";
    }
}

function confirmacioEliminar() {
    var opcio = confirm("Estas seguro que quieres eliminar-lo?");
    if (opcio == true) {
        return true;
	  } else {
      return false;
    }
}