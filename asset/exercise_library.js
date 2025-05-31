function filterBy(part) {
    window.location.href = '?filter=' + encodeURIComponent(part);
}

function saveFavorite(name) {
    let favs = JSON.parse(localStorage.getItem('favorites') || "[]");
    if (!favs.includes(name)) {
        favs.push(name);
        localStorage.setItem('favorites', JSON.stringify(favs));
        alert(name + " saved to favorites!");
    } else {
        alert(name + " is already in favorites.");
    }
}
