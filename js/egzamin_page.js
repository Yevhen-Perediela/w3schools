document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("main-container");
    const svg = document.getElementById("connections");
    const items = container.querySelectorAll("div");

    // Pobierz pozycje każdego sześciokąta i narysuj linie
    function drawLines() {
        svg.innerHTML = ""; // Wyczyść stare linie

        items.forEach((item, index) => {
            if (index < items.length - 1) {
                const rect1 = item.getBoundingClientRect();
                const rect2 = items[index + 1].getBoundingClientRect();

                const x1 = rect1.left + rect1.width / 3;
                const y1 = rect1.top + rect1.height / 10;
                const x2 = rect2.left + rect2.width / 7;
                const y2 = rect2.top + rect2.height / 15;

                const line = document.createElementNS("http://www.w3.org/2000/svg", "line");
                line.setAttribute("x1", x1);
                line.setAttribute("y1", y1);
                line.setAttribute("x2", x2);
                line.setAttribute("y2", y2);
                line.setAttribute("stroke", "#04aa6d");
                line.setAttribute("stroke-width", "5");
                svg.appendChild(line);
            }
        });
    }

    // Narysuj linie przy załadowaniu i skalowaniu okna
    drawLines();
    window.addEventListener("resize", drawLines);
});


const params = new URLSearchParams(window.location.search);
const type = params.get('course');
console.log(document.getElementById(type+'-menu'));

document.getElementById(type+'-menu').style.backgroundColor='#04aa6d'
document.getElementById('egzamin').style.backgroundColor='black'
