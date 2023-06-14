var dados = [];
var lados;
var texto;
var button;

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
        texto.style.color = "#737373";

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