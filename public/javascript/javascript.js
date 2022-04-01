function mostrarContrasenya() {
    var tipo = document.getElementById("password");
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
