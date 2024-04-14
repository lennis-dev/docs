function darkTheme() {
    console.log("dark");
    localStorage.setItem("theme", "dark");
    document.querySelector(".theme-switcher input").checked = false;
}

function lightTheme() {
    console.log("light");
    localStorage.setItem("theme", "light");
    document.querySelector(".theme-switcher input").checked = true;
}

document.querySelector(".theme-switcher input").addEventListener("change", function () {
    if (this.checked) {
        lightTheme();
    } else {
        darkTheme();
    }
});

if (localStorage.getItem("theme") === "dark") {
    darkTheme();
} else if (localStorage.getItem("theme") === "light") {
    lightTheme();
} else {
    darkTheme();
}