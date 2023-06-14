var splash;

async function showSplash() {
    await sleep(1000);
    splash.style.width = "300px";
    splash.style.height = "300px";
    await sleep(1000);
    splash.style.opacity = "0";
    splash.style.zIndex = "-100";
}