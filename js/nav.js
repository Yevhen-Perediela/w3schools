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

function themeChange() {
  // Sprawdzamy, czy body ma już klasę .light-mode
  const body = document.body;
  const isLight = body.classList.contains('light-mode');

  // Jeśli jest w jasnym trybie, to przełączamy na ciemny...
  if (isLight) {
    body.classList.remove('light-mode');
    localStorage.setItem('theme', 'dark'); 
  } 
  else {
    body.classList.add('light-mode');
    localStorage.setItem('theme', 'light');
  }
}
document.addEventListener('DOMContentLoaded', () => {
  const savedTheme = localStorage.getItem('theme');  // 'dark' lub 'light'

  if (savedTheme === 'light') {
    document.body.classList.add('light-mode');
  }

});