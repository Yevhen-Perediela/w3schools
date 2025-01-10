function createStars() {
  const starsContainer = document.getElementById("stars");
  const numberOfStars = 10000;

  for (let i = 0; i < numberOfStars; i++) {
    const star = document.createElement("div");
    star.className = "star";

    star.style.left = Math.random() * 100 + "%";
    star.style.top = Math.random() * 100 + "%";

    const size = Math.random() * 1.2 + 0.3;
    star.style.width = size + "px";
    star.style.height = size + "px";

    star.style.opacity = Math.random() * 0.5 + 0.1;

    
    const animationDuration = Math.random() * 3 + 2;
    const animationDelay = Math.random() * 5;
    star.style.animation = `twinkle ${animationDuration}s infinite ${animationDelay}s`;
    

    starsContainer.appendChild(star);
  }
}
