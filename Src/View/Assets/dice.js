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
});

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function animation() {
    while (true) {
        for (i = 0; i < dados.length; i++) {
            dados[i].style.opacity = "0";
            if (i == 0 || i == (dados.length - 1)) {
                await sleep(500);
            }

            if (i == (dados.length - 1)) {
                texto.style.color = "#BC77D6";
            }
            if (i != (dados.length - 1)) {
                dados[i + 1].style.opacity = "1";
            }
            else {
                dados[0].style.opacity = "1";
            }

            await sleep(500);
        }
    }
}

async function rowDices() {
    if (localStorage.getItem("lastDice") != lados.value || localStorage.getItem("lastDice") == null) {
        localStorage.setItem("lastDice", lados.value);
    }

    lados.disabled = true;
    button.disabled = true;

    if (localStorage.getItem("diceDelay") == 1) {
        texto.style.color = "#A3A3A3";

        for (i = 0; i < dados.length; i++) {
            dados[i].style.opacity = "0";
            if (i == 0 || i == (dados.length - 1)) {
                await sleep(500);
            }

            if (i == (dados.length - 1)) {
                texto.style.color = "#BC77D6";
            }

            result = Math.floor(Math.random() * lados.value) + 1;
            texto.innerHTML = result;
            localStorage.setItem("lastResult", result);

            if (i != (dados.length - 1)) {
                dados[i + 1].style.opacity = "1";
            }
            else {
                dados[0].style.opacity = "1";
            }

            await sleep(500);
        }
    }
    else {
        result = Math.floor(Math.random() * lados.value) + 1;
        texto.innerHTML = result;
        localStorage.setItem("lastResult", result);
    }

    lados.disabled = false;
    button.disabled = false;
}

function beauty() {
    if (lados.validity.valid) {
        button.disabled = false;
    }
    else {
        button.disabled = true;
    }
}