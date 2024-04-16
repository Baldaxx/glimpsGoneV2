
// Gestion des messages flash
document.addEventListener('DOMContentLoaded', () => {
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(flash => {
        setTimeout(() => { 
            flash.style.display = 'none'; 
        }, 5000); // Ferme le message après 5 secondes

        flash.querySelector('.close').addEventListener('click', () => {
            flash.style.display = 'none';
        });
    });
});

// Menu burger
function toggleMenu() {
  var menu = document.getElementById("menuBurgerDesign");
  menu.classList.toggle("menuShown");
}

// Animation sur le titre
function applyGlitchEffect() {
  const title = document.querySelector(".titre");
  title.classList.add("glitch");

  setTimeout(() => {
    title.classList.remove("glitch");
  }, 1000);
}
function randomGlitchEffect() {
  const minTime = 10000;
  const maxTime = 2000;
  const randomTime = Math.random() * (maxTime - minTime) + minTime;

  setTimeout(() => {
    applyGlitchEffect();
    randomGlitchEffect();
  }, randomTime);
}
document.addEventListener("DOMContentLoaded", randomGlitchEffect);

//Focus sur la barre de navigation
document.addEventListener("DOMContentLoaded", function () {
  // Obtention de l'URL actuelle
  const currentLocation = location.href;
  const navLinks = document.querySelectorAll("nav ul li a");
  navLinks.forEach((link) => {
    if (link.href === currentLocation) {
      link.classList.add("active");
    }
  });
});
