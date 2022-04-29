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

$(function() {
    $(".toggle").on("click", function() {
        if ($(".item").hasClass("active")) {
            $(".item").removeClass("active");
            $(this).find("a").html("<i class='fas fa-bars'></i>");
        } else {
            $(".item").addClass("active");
            $(this).find("a").html("<i class='fas fa-times'></i>");
        }
    });
});

