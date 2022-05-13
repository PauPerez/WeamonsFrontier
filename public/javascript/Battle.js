window.onload = function () {
    let movs = document.getElementsByClassName("moviment");
    for (let i = 0; i < movs.length; i++) {
        movs[i].addEventListener("click", attack);
        
    }

    document.getElementById(document.getElementById("activeEnemy").innerHTML).style.display = "inline";
    document.getElementById(document.getElementById("activeWeamon").innerHTML).style.display = "inline";
    document.getElementById(document.getElementById("activeWeamon").innerHTML+"atacMenu").style.display = "flex";
}

function attack() {
    let active = document.getElementById("activeEnemy").innerHTML;
    let weamon = document.getElementById("activeWeamon").innerHTML;
    let hBar = document.getElementById(active).getElementsByClassName('health-bar')[0];
    let bar = hBar.getElementsByClassName('bar')[0];
    let hit = hBar.getElementsByClassName('hit')[0];
    let textm = document.getElementById("textMenu");
    let atacm = document.getElementById(weamon+"atacMenu");
    let enemy = document.getElementById(active);

    let total = hBar.getAttribute("data-total");
    let value = hBar.getAttribute("data-value");
    let gif = document.getElementById("atacGif");
    gif.setAttribute("src", this.getAttribute("animation"));
    gif.style.display = "inline";
    setTimeout(function () {
        gif.style.display = "none";
    },1000);
    
    var damage = Math.floor(Math.random()*parseInt(this.getAttribute("atac")));
    var newValue = value - damage;
    var barWidth = (newValue / total) * 100;
    var hitWidth = (damage / value) * 100 + "%";
    
    hit.style.width = hitWidth;
    hBar.setAttribute("data-value",newValue);

    atacm.style.display = "none";

        if (value <= 0) {
            textm.innerHTML = "<p>El weamon enemic ha caigut!</p>";
        }else{
            textm.innerHTML = "<p>El teu weamon ha utilitzat "+ this.innerHTML +"</p>";
        }
        textm.style.display = "inline";
    


    setTimeout(function () {
        hit.style.width = 0;
        bar.style.width = barWidth+'%';
    },500);    

    setTimeout(attackEnemic, 1000);
    
    

    setTimeout(function () {
        
        textm.style.display = "none";
        atacm.style.display = "flex";
        if (value<=0) {
            pointCounter("jugador");
            changeActive(active, "activeEnemy");
        }
    },3000);

    
}

function pointCounter(player) {
    let points=0;
    let link = document.getElementById("redirect").innerHTML;
    link = link.slice(0,-1);
    if (player == "jugador") {
        points = document.getElementById("deadEnemies").innerHTML;
        points = parseInt(points)+1;
        document.getElementById("deadEnemies").innerHTML = points;
    }else{
        points = document.getElementById("deadPlayers").innerHTML;
        points = parseInt(points)+1;
        document.getElementById("deadPlayers").innerHTML = points;
    }

    if(points==4){
        document.getElementById("textMenu").style.display="none";
        document.getElementById("battle").style.display="none";
        document.getElementById(document.getElementById("activeWeamon").innerHTML+"atacMenu").style.display="none";
        if (player == "jugador") {
            window.location.replace(link+1);
        }else{
            window.location.replace(link+0);
        }
    }
}

function attackEnemic() {
    let active = document.getElementById("activeWeamon").innerHTML;
    let hBar = document.getElementById(active).getElementsByClassName('health-bar')[0];
    let bar = hBar.getElementsByClassName('bar')[0];
    let hit = hBar.getElementsByClassName('hit')[0];
    let textm = document.getElementById("textMenu");
    let weamon = document.getElementById("activeEnemy").innerHTML;
    

    let total = hBar.getAttribute("data-total");
    let value = hBar.getAttribute("data-value");


    var damage = Math.floor(Math.random()*parseInt(document.getElementById(weamon+"Atac").innerHTML));
    var newValue = value - damage;
    var barWidth = (newValue / total) * 100;
    var hitWidth = (damage / value) * 100 + "%";

    var mov = document.getElementById(weamon).getElementsByClassName("moviments");
    var num = Math.floor(Math.random() * mov.length);
    var gif = document.getElementById("atacGifE");
    gif.setAttribute("src",mov[num].getAttribute("animation"));
    gif.style.display = "inline";
    setTimeout(function () {
        gif.style.display = "none";
    },1000);
    hit.style.width = hitWidth;
    hBar.setAttribute("data-value",newValue);

    if (value <= 0) {
        textm.innerHTML = "<p>El weamon aliat ha caigut!</p>";
    }else{
        textm.innerHTML = "<p>El weamon enemic ha utilitzat "+mov[num].innerHTML+"</p>";
    }
    textm.style.display = "inline";


    setTimeout(function () {
        hit.style.width = 0;
        bar.style.width = barWidth+'%';
    },500); 

    if (value <= 0) {
        pointCounter("enemic");
        changeActive(active, "activeWeamon");
    }
}

function changeActive(id, active) {
    let weamon = document.getElementById(id);
    let newId = weamon.nextElementSibling;
    if (newId!=null) {
    newId = newId.getAttribute("id");
    }

    weamon.style.display = "none";
    if (document.getElementById(id+"atacMenu") != null && document.getElementById(newId+"atacMenu") != null) {
        document.getElementById(id+"atacMenu").style.display = "none"
        document.getElementById(newId+"atacMenu").style.display = "flex"
    }
    
    if (newId!=null) {
        weamon = document.getElementById(newId).style.display = "inline";
        document.getElementById(active).innerHTML=newId;
        
    }
    
    
}