// Cette fonction est conçue pour afficher les détails d'une oeuvre spécifique dans la galerie web.
function afficheOeuvre(oeuvre) {
  // Premièrement, vérifie que l'oeuvre existe et a une date de création définie. Si ce n'est pas le cas, log une erreur.
  if (!oeuvre || oeuvre.date_de_creation === undefined) {
    console.error("Aucune oeuvre à afficher.");
    return;
  }

  // Convertit la date de création, en timestamp, en année.
  const anneeDeCreation = new Date(oeuvre.date_de_creation * 1000).getFullYear();

  // Met à jour le titre de l'oeuvre sur la page, incluant le nom de l'artiste et l'année de création.
  const titreOeuvreElement = document.getElementById("titreOeuvre");
  titreOeuvreElement.innerHTML = `${oeuvre.titre}<br><span>${oeuvre.artiste} ${anneeDeCreation}</span>`;
  titreOeuvreElement.style.display = "block";

  // Met à jour la description de l'oeuvre sur la page.
  const descriptionOeuvreElement = document.getElementById("descriptionOeuvre");
  descriptionOeuvreElement.innerHTML = oeuvre.description;
  descriptionOeuvreElement.style.display = "block";

  // Met à jour le nombre de 'J'aime' de l'oeuvre sur la page.
  const jaimeOeuvreElement = document.getElementById("jaimeOeuvre");
  jaimeOeuvreElement.innerHTML = oeuvre.compteur_jaime;

  // Met à jour le nombre de 'J'aime pas' de l'oeuvre sur la page.
  const jaimePasOeuvreElement = document.getElementById("jaimePasOeuvre");
  jaimePasOeuvreElement.innerHTML = oeuvre.compteur_jaime_pas;

  // Affiche le compteur de 'J'aime' et 'J'aime pas'.
  const compteurJJElement = document.getElementById("compteurJJ");
  compteurJJElement.style.display = "block";
}

// Initialisation d'un tableau pour stocker toutes les oeuvres récupérées depuis l'API.
let toutesLesOeuvres = [];
// Variable pour suivre l'identifiant de l'oeuvre actuellement affichée.
let identifiantOeuvreCourante = 0;

// Fonction pour récupérer les oeuvres depuis l'API et afficher la première oeuvre récupérée.
function recupererEtAfficherOeuvre() {
  fetch("/api/oeuvres")
    .then((response) => response.json())
    .then((data) => {
      toutesLesOeuvres = data;
      console.log(toutesLesOeuvres);
      if (toutesLesOeuvres.length === 0) {
        // Si aucune oeuvre n'est disponible, redirige vers la page galerie down.
        window.location.href = "galerieDown.html";
      } else {
        // Sinon, affiche la première oeuvre du tableau.
        afficheOeuvre(toutesLesOeuvres[identifiantOeuvreCourante]);
      }
    })
    .catch((error) => console.error("Erreur :", error));
}

// Cette fonction est exécutée dès que la page web est complètement chargée.
// Son rôle est de démarrer le processus de récupération des données des œuvres d'art
// depuis le serveur et de les afficher sur la page pour que l'utilisateur puisse les voir.
function initialisation() {
  recupererEtAfficherOeuvre();
}

  // Ajoute des écouteurs d'événements aux boutons pour permettre aux utilisateurs d'interagir avec les oeuvres.
  document.getElementById("btn_jaime").addEventListener("click", function () {
    fetch(
      `/api/oeuvres/${toutesLesOeuvres[identifiantOeuvreCourante].id}/jaime`,
      { method: "POST" }
    )
      .then((response) => response.json())
      .then(() => {
        recupererEtAfficherOeuvre(); // Recharge l'oeuvre actuelle pour mettre à jour les compteurs.
      });
  });

  document
    .getElementById("btn_jaime_pas")
    .addEventListener("click", function () {
      fetch(
        `/api/oeuvres/${toutesLesOeuvres[identifiantOeuvreCourante].id}/jaimePas`,
        { method: "POST" }
      )
        .then((response) => response.json())
        .then(() => {
          recupererEtAfficherOeuvre(); 
        });
    });

  // Permet à l'utilisateur de naviguer à l'oeuvre suivante.
  document.getElementById("btn_suivant").addEventListener("click", function () {
    if (identifiantOeuvreCourante + 1 < toutesLesOeuvres.length) {
      identifiantOeuvreCourante++;
      afficheOeuvre(toutesLesOeuvres[identifiantOeuvreCourante]);
    } else {
      window.location.href = "galerieFin.html"; // S'il n'y a plus d'oeuvre à afficher, redirige vers la page galerie fin.
    }
  });

  // Permet à l'utilisateur de revenir à l'oeuvre précédente.
  document
    .getElementById("btn_precedent")
    .addEventListener("click", function () {
      if (identifiantOeuvreCourante - 1 >= 0) {
        identifiantOeuvreCourante--;
        afficheOeuvre(toutesLesOeuvres[identifiantOeuvreCourante]);
      } else {
        window.location.href = "galerieFin.html"; // Idem qu'au dessus
      }
    });

// Exécute la fonction d'initialisation une fois que le contenu de la page est entièrement chargé.
document.addEventListener("DOMContentLoaded", initialisation);
