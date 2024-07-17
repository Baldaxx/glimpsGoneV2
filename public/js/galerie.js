document.addEventListener("DOMContentLoaded", (event) => {
  let oeuvres;
  let currentIndex = 0;

  fetch("api/oeuvre")
    .then((response) => response.json())
    .then((data) => {
      oeuvres = data;
      console.log(oeuvres);
      displayOeuvre(currentIndex);
    });

  const titreOeuvre = document.getElementById("titreOeuvre");
  const descriptionOeuvre = document.getElementById("descriptionOeuvre");
  const imageOeuvre = document.getElementById("imageOeuvre");
  const jaimeOeuvre = document.getElementById("jaimeOeuvre");
  const jaimePasOeuvre = document.getElementById("jaimePasOeuvre");

  function displayOeuvre(index) {
    const oeuvre = oeuvres[index];
    const anneeDeCreation = new Date(oeuvre.dateCreation * 1000).getFullYear();
    const titreOeuvreElement = document.getElementById("titreOeuvre");
    titreOeuvreElement.innerHTML = `${oeuvre.titre}<br><span>${oeuvre.artiste_nom} ${anneeDeCreation}</span>`;
    titreOeuvreElement.style.display = "block";
    descriptionOeuvre.textContent = oeuvre.description;
    jaimePasOeuvre.textContent = oeuvre.compteurJaimePas;
  }

  document.getElementById("btn_suivant").addEventListener("click", () => {
    if (currentIndex < oeuvres.length - 1) {
      currentIndex++;
      displayOeuvre(currentIndex);
    } else {
      window.location.href = "/glimpsGoneV2/galerieFin";
    }
  });

  document.getElementById("btn_precedent").addEventListener("click", () => {
    if (currentIndex > 0) {
      currentIndex--;
      displayOeuvre(currentIndex);
    }
  });
});
