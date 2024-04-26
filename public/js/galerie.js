document.addEventListener("DOMContentLoaded", async function () {
  const API_BASE_URL = "/glimpsGoneV2/api/oeuvre"; 
  

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
      throw error;
    }
  }

  async function recupererEtAfficherOeuvre(id) {
    try {
      const oeuvre = await fetchAPI(`${API_BASE_URL}/${id}`);
      if (!oeuvre) {
        throw new Error("Aucune oeuvre disponible.");
      }
      afficherOeuvre(oeuvre);
    } catch (error) {
      console.error(error.message);
    }
  }

  function afficherOeuvre(oeuvre) {
    if (!oeuvre) {
      console.error("Aucune oeuvre à afficher.");
      return;
    }
    document.getElementById("titreOeuvre").innerHTML = `${oeuvre.titre} (${
      oeuvre.artiste}, ${new Date(oeuvre.dateCreation).getFullYear()})`;
    document.getElementById("descriptionOeuvre").innerHTML = oeuvre.description;
    document.getElementById("jaimeOeuvre").innerHTML = oeuvre.compteurJaime;
    document.getElementById("jaimePasOeuvre").innerHTML =
      oeuvre.compteurJaimePas;
  }

  recupererEtAfficherOeuvre(1);
});
