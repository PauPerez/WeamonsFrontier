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
        elem.focus();
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