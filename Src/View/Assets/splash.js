var splash;
var background;

window.onload = (function () {
    splash = document.getElementById("splash");
    background = document.getElementById("background");
});

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function showSplash() {
    splash.style.width = "2500px";
    splash.style.height = "2500px";
    await sleep(2600);
    background.style.display = "none";
    splash.style.width = "50px";
    splash.style.height = "50px";
    await sleep(200);
    splash.style.opacity = "0";
}