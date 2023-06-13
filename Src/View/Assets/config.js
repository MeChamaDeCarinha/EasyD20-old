var diceDelay;

window.onload = (function () {
    diceDelay = document.getElementById("delayDados");
    if(!localStorage.getItem("diceDelay")){
        localStorage.setItem("diceDelay", 1);
    }

    if(localStorage.getItem("diceDelay") == 1){
        diceDelay.checked = true;
    }
    else {
        diceDelay.checked = false;
    }
});


function changeDelay() {
    console.log("a");
    if(localStorage.getItem("diceDelay") == 0){
        localStorage.setItem("diceDelay", 1);
    }
    else{
        localStorage.setItem("diceDelay", 0);
    }
}