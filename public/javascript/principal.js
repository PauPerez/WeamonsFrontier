let animadoArriba = document.getElementsByClassName("animado_arriba_principal");

function mostrarScrollArriba() {
    let scrollTop = document.documentElement.scrollTop;
    for (var i = 0; i < animadoArriba.length; i++) {
        let alturaAnimado = animadoArriba[i].offsetTop;
        if (alturaAnimado - 500 < scrollTop) {
            animadoArriba[i].style.opacity = 1;
            animadoArriba[i].classList.add("mostrarArriba");
        }
    }
}

let animadoAbajo = document.getElementsByClassName("animado_abajo_principal");

function mostrarScrollAbajo() {
    let scrollTop = document.documentElement.scrollTop;
    for (var i = 0; i < animadoAbajo.length; i++) {
        let alturaAnimado = animadoAbajo[i].offsetTop;
        if (alturaAnimado - 800 < scrollTop) {
            animadoAbajo[i].style.opacity = 1;
            animadoAbajo[i].classList.add("mostrarAbajo");
        }
    }
}

function cambiarOrden() {
    var screenWidth = screen.width;
    var divObjetivo = document.getElementById("divCambio");
    /*var txt = `<div class="div_imagen_principal animado_abajo_principal">
                <img src="{{ asset('./img/fondos/principal1.jpg') }}">
               </div>`;*/
    var div = document.createElement("div");
    div.className = "div_imagen_principal animado_abajo_principal";
    div.innerHTML = `<img src="/M14/WeamonsFrontier/public/./img/fondos/principal1.jpg">`;
    if (screenWidth <= 1200) {
        divObjetivo.insertAdjacentElement("beforebegin", div);
    } else {
        divObjetivo.insertAdjacentElement("afterend", div);
    }
}

window.addEventListener("scroll", mostrarScrollArriba);
window.addEventListener("scroll", mostrarScrollAbajo);
window.addEventListener("DOMContentLoaded", cambiarOrden);