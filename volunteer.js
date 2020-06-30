function showSlideDeck() {
    let dlg = document.getElementById("dlgSlideDeck");
    dlg.style.display = "block";
}

function hideSlideDeck() {
    let dlg = document.getElementById("dlgSlideDeck");
    dlg.style.display = "none";
}

window.onload = function () {
    var a = document.getElementById("showSlideDeck");
    a.onclick = function () {
        showSlideDeck();
        // return false to prevent any redirects and event bubbling
        return false;
    }

    var btn = document.getElementById("closeDeck");
    btn.onclick = function () {
        hideSlideDeck();
        return false;
    }
}
