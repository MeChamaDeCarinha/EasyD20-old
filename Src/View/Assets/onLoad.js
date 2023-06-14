const userTheme = localStorage.getItem("theme");

if (userTheme === null) {
    document.documentElement.classList.add("dark");
    localStorage.setItem("theme", "dark");
}

if (userTheme === "dark") {
    document.documentElement.classList.add("dark");
    localStorage.setItem("theme", "dark");
}


window.onload = (function () {

    if(document.getElementById("theme")){
        if (userTheme === "dark") {
            document.getElementById("theme").checked = true;
        }
        else {
            document.getElementById("theme").checked = false;
        }
    }


    if(document.getElementById("splash")){
        splash = document.getElementById("splash");
    
        showSplash();
    }


    if(document.getElementById("delayDados")){
        if(!localStorage.getItem("diceDelay")){
            localStorage.setItem("diceDelay", 1);
        }
    
        if(localStorage.getItem("diceDelay") == 1){
            document.getElementById("delayDados").checked = true;
        }
        else {
            document.getElementById("delayDados").checked = false;
        }
    }

    if(document.getElementById('imagem')){
        img = document.querySelector('img').src;
        select = document.getElementById('imagem');
        preview = document.querySelector('img');
    }

    if(document.getElementById("dices")){
        dados.push(document.getElementById("dices"));
        dados.push(document.getElementById("dice1"));
        dados.push(document.getElementById("dice2"));
        dados.push(document.getElementById("dice3"));
        dados.push(document.getElementById("dice4"));
        dados.push(document.getElementById("dice5"));
        dados.push(document.getElementById("dice6"));
    
        lados = document.getElementById("lados");
        lados.value = localStorage.getItem("lastDice");
    
        texto = document.getElementById("placeholder");
        if (localStorage.getItem("lastResult")) {
            texto.innerHTML = localStorage.getItem("lastResult");
        }
    
        button = document.getElementById("botao");
    
        beauty();
    
        if (localStorage.getItem("diceDelay") == 0) {
            animation();
        }
    }   

});