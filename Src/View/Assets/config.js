function changeDelay() {
    if(localStorage.getItem("diceDelay") == 0){
        localStorage.setItem("diceDelay", 1);
    }
    else{
        localStorage.setItem("diceDelay", 0);
    }
}

function changeTheme() {
    console.log("a");
    if (document.documentElement.classList.contains("dark")) {
        document.documentElement.classList.remove("dark");
        localStorage.setItem("theme", "light");
        return;
    }
    document.documentElement.classList.add("dark");
    localStorage.setItem("theme", "dark");
}