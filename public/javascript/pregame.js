const N_EQUIPS = 4;
const N_WEAMONS = 4;

function custom_template(obj) {
    var data = $(obj.element).data();
    var text = $(obj.element).text();
    if (data && data['img_src']) {
        img_src = data['img_src'];
        template = $("<div><img src=\"" + img_src + "\" style=\"width:100%;height:150px;\"/><p style=\"font-weight: 700;font-size:14pt;text-align:center;\">" + text + "</p></div>");
        return template;
    }
}
var options = {
    'templateSelection': custom_template,
    'templateResult': custom_template,
}

function cambioSelects() {
    //Fors creació dels selects dels equips.
    for (let i = 1; i <= N_EQUIPS; i++) {
        for (let j = 1; j <= N_WEAMONS; j++)
            $(`#equipo${i}-${j}`).select2(options);
    }
    $('.select2-container--default .select2-selection--single').css({ 'height': '220px' });

    //Afegir id's als selects.
    var selectsWeamons = document.getElementsByClassName('select2');
    for (let i = 1; i <= selectsWeamons.length; i++) {
        if (i <= 4)
            selectsWeamons[i - 1].id = 'equipo1-' + (i);
        else if (i > 4 && i <= 8)
            selectsWeamons[i - 1].id = 'equipo2-' + (i - 4);
        else if (i > 8 && i <= 12)
            selectsWeamons[i - 1].id = 'equipo3-' + (i - 8);
        else if (i > 12 && i <= 16)
            selectsWeamons[i - 1].id = 'equipo4-' + (i - 12);
    }
}

var equipoS = $('#equipoSelec');

function showEquipoSelec() {
    for (let i = 1; i <= N_EQUIPS; i++) {
        if (i == parseInt($("#equipoSelec option:selected").text().substr(-1)))
            $(`#equipo${i}`).css({'display': 'block'});
        else
            $(`#equipo${i}`).css({'display': 'none'});
    }

}

function weamon_info(id) {
    window.location = "../user/weamon-info/" + id;
}

$(document).ready(showEquipoSelec);
$(document).ready(function () {
    cambioSelects();
    $(document).on('change', equipoS, showEquipoSelec);
});