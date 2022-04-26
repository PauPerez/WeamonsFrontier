window.onload = function () {
    let movs = document.getElementsByClassName("moviment");
    for (let i = 0; i < movs.length; i++) {
        movs[i].addEventListener("click", attack);
        
    }
}

function attack() {
    let active = document.getElementById("activeEnemy").innerHTML;
    let hBar = document.getElementById(active).getElementsByClassName('health-bar')[0];
    let bar = hBar.getElementsByClassName('bar')[0];
    let hit = hBar.getElementsByClassName('hit')[0];
    let textm = document.getElementById("textMenu");
    let atacm = document.getElementById("atacMenu");

    let total = hBar.getAttribute("data-total");
    let value = hBar.getAttribute("data-value");


    var damage = Math.floor(Math.random()*parseInt(this.getAttribute("atac")));
    var newValue = value - damage;
    var barWidth = (newValue / total) * 100;
    var hitWidth = (damage / value) * 100 + "%";
    
    hit.style.width = hitWidth;
    hBar.setAttribute("data-value",newValue);

    atacm.style.display = "none";

    if (value <= 0) {
        textm.innerHTML = "El weamon enemic ha caigut!";
    }else{
        textm.innerHTML = "El teu weamon ha atacat";
    }
    textm.style.display = "inline";


    setTimeout(function () {
        hit.style.width = 0;
        bar.style.width = barWidth+'%';
    },500);

    

    setTimeout(function () {
        
        textm.style.display = "none";
        atacm.style.display = "flex";
        if (value <= 0) {
            pointCounter("jugador");
        }
    },1000);

    
}

function pointCounter(player) {
    let points=0;
    if (player == "jugador") {
        points = document.getElementById("deadEnemies").innerHTML;
        points = parseInt(points)+1;
    }else{
        points = document.getElementById("deadPlayers").innerHTML;
        points = parseInt(points)+1;
    }

    if(points==4){
        document.getElementById("textMenu").style.display="none";
        document.getElementById("battle").style.display="none";
        document.getElementById("atacMenu").style.display="none";
    }
}