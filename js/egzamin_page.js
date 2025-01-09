document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("main-container");
    const svg = document.getElementById("connections");
    const items = container.querySelectorAll("div");

    function drawLines() {
        // Aktualizujemy wymiary SVG
        const containerRect = container.getBoundingClientRect();
        svg.setAttribute("width", containerRect.width);
        svg.setAttribute("height", containerRect.height);

        svg.innerHTML = "";

        items.forEach((item, index) => {
            if (index < items.length - 1) {
                const rect1 = item.getBoundingClientRect();
                const rect2 = items[index + 1].getBoundingClientRect();

                // Obliczamy punkty połączenia względem kontenera
                const x1 = rect1.left - containerRect.left + (rect1.width / 2);
                const y1 = rect1.top - containerRect.top + (rect1.height * 0.4);
                const x2 = rect2.left - containerRect.left + (rect2.width / 2);
                const y2 = rect2.top - containerRect.top + (rect2.height * 0.4);

                const line = document.createElementNS("http://www.w3.org/2000/svg", "line");
                line.setAttribute("x1", x1);
                line.setAttribute("y1", y1);
                line.setAttribute("x2", x2);
                line.setAttribute("y2", y2);
                line.setAttribute("stroke", "#04aa6d");
                line.setAttribute("stroke-width", "3");
                line.setAttribute("stroke-dasharray", "5,5");
                svg.appendChild(line);
            }
        });
    }

    // Aktualizujemy linie przy każdym przewinięciu
    window.addEventListener("scroll", drawLines);
    window.addEventListener("resize", drawLines);
    
    // Inicjalne narysowanie linii
    setTimeout(drawLines, 100); // Małe opóźnienie, aby upewnić się, że wszystko jest załadowane

    // Obsługa aktywnego menu
    const params = new URLSearchParams(window.location.search);
    const type = params.get('course');
    
    const menuItems = document.querySelectorAll('.item-menu');
    menuItems.forEach(item => {
        if (item.id === `${type}-menu`) {
            item.classList.add('active-page');
        }
    });
});
