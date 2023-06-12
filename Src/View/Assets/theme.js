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
    if(document.getElementById("colorCheckbox")){
        if (userTheme === "dark") {
            document.getElementById("colorCheckbox").checked = true;
        }
        else {
            document.getElementById("colorCheckbox").checked = false;
        }
    }
});

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