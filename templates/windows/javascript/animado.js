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
        if (alturaAnimado - 500 < scrollTop) {
            animadoAbajo[i].style.opacity = 1;
            animadoAbajo[i].classList.add("mostrarAbajo");
        }
    }
}

window.onload = function() {
    window.addEventListener("scroll", mostrarScrollArriba);
    window.addEventListener("scroll", mostrarScrollAbajo);
}