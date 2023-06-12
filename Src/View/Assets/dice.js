var dados = [];
var lados;
var texto;
var button;

window.onload = (function () {
    dados.push(document.getElementById("dices"));
    dados.push(document.getElementById("dice1"));
    dados.push(document.getElementById("dice2"));
    dados.push(document.getElementById("dice3"));
    dados.push(document.getElementById("dice4"));
    dados.push(document.getElementById("dice5"));
    dados.push(document.getElementById("dice6"));

    lados = document.getElementById("lados");

    texto = document.getElementById("placeholder");

    button = document.getElementById("botao");
});

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function rowDices(){
    lados.disabled = true;
    button.disabled = true;

    texto.style.color = "#A3A3A3";

    for(i = 0; i < dados.length; i++){
        dados[i].style.opacity = "0";
        if(i == 0 || i == (dados.length - 1)){
            await sleep(500);
        }

        if(i == (dados.length - 1)){
            texto.style.color = "#BC77D6";
        }

        texto.innerHTML = Math.floor(Math.random() * lados.value) + 1;

        if(i != (dados.length - 1)){
            dados[i + 1].style.opacity = "1";
        }
        else{
            dados[0].style.opacity = "1";
        }

        await sleep(500);
    }

    lados.disabled = false;
    button.disabled = false;
}

function beauty(){
    if(lados.validity.valid){
        button.disabled = false;
    }
    else{
        button.disabled = true;
    }
}