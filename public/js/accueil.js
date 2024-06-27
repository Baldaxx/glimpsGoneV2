document.addEventListener("DOMContentLoaded", function (event) {
  window.addEventListener("load", function (e) {
    const isDevelopment = false; 
    gsap.registerPlugin(ScrollTrigger);

    let textRevealElements = document.querySelectorAll(".box");
    if (textRevealElements.length === 0) {
      if (isDevelopment)
        console.error("Aucun élément trouvé avec la classe 'box'.");
      return;
    }

    let selection = Splitting({
      target: ".box",
      by: "chars",
      whitespace: true,
    });

    selection.forEach((result) => {
      result.chars.forEach((char) => {
        if (char.textContent === " ") {
          char.style.display = "inline-block";
          char.style.width = "0.5em";
        }
      });
    });

    gsap.fromTo(
      selection[0].chars,
      { opacity: 0 },
      {
        opacity: 1,
        stagger: 0.1,
        scrollTrigger: {
          trigger: ".box",
          start: "top 50%",
          end: "bottom 70%",
          scrub: true,
          markers: isDevelopment,
        },
      }
    );

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
});
