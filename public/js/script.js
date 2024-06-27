// Menu burger
function toggleMenu() {
  let menu = document.getElementById("menuBurgerDesign");
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

document.addEventListener("DOMContentLoaded", function () {
  const currentLocation = location.href;
  const navLinks = document.querySelectorAll("nav ul li a");
  navLinks.forEach((link) => {
    if (link.href === currentLocation) {
      link.classList.add("active");
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  // Sélectionne uniquement les éléments <a> avec les classes custom-bouton1 et custom-bouton2
  const buttons = document.querySelectorAll(
    "a.custom-bouton1, a.custom-bouton2"
  );

  buttons.forEach((item) => {
    let buttonText = item.innerHTML;
    item.innerHTML = `<span>${buttonText}</span>`;
    let span = item.querySelector("span");

    let tl = gsap.timeline({ paused: true });

    tl.to(span, { duration: 0.2, yPercent: -150, ease: "power2.in" })
      .set(span, { yPercent: 150 })
      .to(span, { duration: 0.2, yPercent: 0, ease: "power2.out" });

    item.addEventListener("mouseenter", () => {
      tl.play(0);
    });
  });
});



// Lenis smooth scrolling
document.addEventListener("DOMContentLoaded", function (event) {
  const lenis = new Lenis({
    duration: 1.2,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
  });

  function raf(time) {
    lenis.raf(time);
    requestAnimationFrame(raf);
  }

  requestAnimationFrame(raf);
});
