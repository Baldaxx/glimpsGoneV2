document.addEventListener("DOMContentLoaded", async function () {
  const API_BASE_URL = "/glimpsgoneV2/src/api";
  let toutesLesOeuvres = [];
  let oeuvreCouranteIndex = 0;

  async function fetchAPI(url, options = {}) {
    try {
      const response = await fetch(url, options);
      if (!response.ok) {
        throw new Error(
          `Erreur réseau: ${response.status} - ${response.statusText}`
        );
      }
      return await response.json(); 
    } catch (error) {
      console.error(
        "Erreur lors de la communication avec l'API:",
        error.message
      );
      // window.location.href = "/glimpsgoneV2/galerieDown";
      throw error; 
    }
  }

  async function recupererOeuvres() {
    try {
      toutesLesOeuvres = await fetchAPI(`${API_BASE_URL}/oeuvre`);
      if (toutesLesOeuvres.length === 0) {
        throw new Error("Aucune oeuvre disponible.");
      }
      recupererEtAfficherOeuvre(toutesLesOeuvres[oeuvreCouranteIndex].id);
    } catch (error) {
      console.error(error.message);
      // window.location.href = "/glimpsgoneV2/galerieDown";
    }
  }

  async function recupererEtAfficherOeuvre(id) {
    try {
      const oeuvre = await fetchAPI(`${API_BASE_URL}/oeuvre/${id}`);
      afficherOeuvre(oeuvre);
    } catch (error) {
      console.error("Erreur lors du chargement de l'oeuvre:", error);
    }
  }

  function afficherOeuvre(oeuvre) {
    if (!oeuvre) {
      console.error("Aucune oeuvre à afficher.");
      return;
    }
    document.getElementById("titreOeuvre").innerHTML = `${oeuvre.titre} (${
      oeuvre.artiste.name
    }, ${new Date(oeuvre.dateCreation).getFullYear()})`;
    document.getElementById("descriptionOeuvre").innerHTML = oeuvre.description;
    document.getElementById("jaimeOeuvre").innerHTML = oeuvre.compteurJaime;
    document.getElementById("jaimePasOeuvre").innerHTML =
      oeuvre.compteurJaimePas;
  }

  async function ajouterAction(action, id) {
    try {
      await fetchAPI(`${API_BASE_URL}/oeuvre/action`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ action, id }),
      });
      recupererEtAfficherOeuvre(id);
    } catch (error) {
      console.error("Erreur lors de l'ajout d'une action:", error);
    }
  }

  function afficherOeuvreSuivante() {
    oeuvreCouranteIndex = (oeuvreCouranteIndex + 1) % toutesLesOeuvres.length;
    recupererEtAfficherOeuvre(toutesLesOeuvres[oeuvreCouranteIndex].id);
  }

  function afficherOeuvrePrecedente() {
    oeuvreCouranteIndex =
      (oeuvreCouranteIndex - 1 + toutesLesOeuvres.length) %
      toutesLesOeuvres.length;
    recupererEtAfficherOeuvre(toutesLesOeuvres[oeuvreCouranteIndex].id);
  }

  document
    .getElementById("btn_jaime")
    .addEventListener("click", () =>
      ajouterAction("like", toutesLesOeuvres[oeuvreCouranteIndex].id)
    );
  document
    .getElementById("btn_jaime_pas")
    .addEventListener("click", () =>
      ajouterAction("dislike", toutesLesOeuvres[oeuvreCouranteIndex].id)
    );
  document
    .getElementById("btn_suivant")
    .addEventListener("click", afficherOeuvreSuivante);
  document
    .getElementById("btn_precedent")
    .addEventListener("click", afficherOeuvrePrecedente);

  await recupererOeuvres(); 
});
