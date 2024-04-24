document.addEventListener("DOMContentLoaded", function () {
  let toutesLesOeuvres = [];
  let identifiantOeuvreCourante = 0;

  async function recupererEtAfficherOeuvre() {
    try {
      const response = await fetch(
        "/glimpsgoneV2/src/repository/OeuvreRepository.php"
      );
      if (!response.ok) {
        throw new Error(
          "Erreur lors de la récupération des œuvres : " + response.status
        );
      }
      const textResponse = await response.text(); 
      console.log("Réponse brute du serveur:", textResponse); 
      if (!textResponse) {
        throw new Error("La réponse du serveur est vide");
      }
      const oeuvres = JSON.parse(textResponse); 
      if (!oeuvres || oeuvres.length === 0) {
        window.location.href = "galerieDown.pug";
        return;
      }
      toutesLesOeuvres = oeuvres;
      afficheOeuvre(toutesLesOeuvres[identifiantOeuvreCourante]);
    } catch (error) {
      console.error(
        "Erreur lors du parsing JSON ou de la récupération des données:",
        error
      );
    }
  }

  function afficheOeuvre(oeuvre) {
    if (!oeuvre) {
      console.error("Aucune oeuvre à afficher.");
      return;
    }
    document.getElementById("titreOeuvre").innerHTML = `${
      oeuvre.titre
    }<br><span>${oeuvre.artiste.name} ${new Date(
      oeuvre.dateCreation
    ).getFullYear()}</span>`;
    document.getElementById("descriptionOeuvre").innerHTML = oeuvre.description;
    document.getElementById("jaimeOeuvre").innerHTML = oeuvre.compteurJaime;
    document.getElementById("jaimePasOeuvre").innerHTML =
      oeuvre.compteurJaimePas;
  }

  async function ajouterAction(action) {
    try {
      const response = await fetch("/galerie", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          action: action,
          id: toutesLesOeuvres[identifiantOeuvreCourante].id,
        }),
      });
      if (!response.ok) {
        throw new Error(
          "Erreur lors de l'ajout de l'action " +
            action +
            ": " +
            response.status
        );
      }
      recupererEtAfficherOeuvre(); 
    } catch (error) {
      console.error(error);
    }
  }

  function afficherOeuvreSuivante() {
    if (identifiantOeuvreCourante + 1 < toutesLesOeuvres.length) {
      identifiantOeuvreCourante++;
      afficheOeuvre(toutesLesOeuvres[identifiantOeuvreCourante]);
    } else {
      window.location.href = "galerieFin.pug";
    }
  }

  function afficherOeuvrePrecedente() {
    if (identifiantOeuvreCourante - 1 >= 0) {
      identifiantOeuvreCourante--;
      afficheOeuvre(toutesLesOeuvres[identifiantOeuvreCourante]);
    } else {
      window.location.href = "galerieFin.pug";
    }
  }

  document
    .getElementById("btn_jaime")
    .addEventListener("click", () => ajouterAction("like"));
  document
    .getElementById("btn_jaime_pas")
    .addEventListener("click", () => ajouterAction("dislike"));
  document
    .getElementById("btn_suivant")
    .addEventListener("click", afficherOeuvreSuivante);
  document
    .getElementById("btn_precedent")
    .addEventListener("click", afficherOeuvrePrecedente);

  recupererEtAfficherOeuvre(); 
});
