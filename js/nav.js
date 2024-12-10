document.addEventListener("DOMContentLoaded", function () {
  // gorne menu
  const menuToggle = document.querySelector(".menu-toggle");
  const topNav = document.querySelector(".top-nav");

  menuToggle.addEventListener("click", function () {
    topNav.classList.toggle("menu-active");
  });

  // dolne menu
  const techMenuToggle = document.querySelector(".tech-menu-toggle");
  const bottomNav = document.querySelector(".bottom-nav");

  techMenuToggle.addEventListener("click", function () {
    bottomNav.classList.toggle("menu-active");
  });
});
