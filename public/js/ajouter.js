// Attend que le DOM (Document Object Model) soit entièrement chargé avant d'exécuter le code JavaScript.
document.addEventListener("DOMContentLoaded", function () {
  // Attache un gestionnaire d'événement à la soumission du formulaire.
  // Cela permet de contrôler le processus de soumission du formulaire.
  document
    .getElementById("formulaireProposerid")
    .addEventListener("submit", function (e) {
      e.preventDefault(); // Empêche le comportement de soumission par défaut du formulaire, permettant une validation et soumission personnalisée.

      // Utilise la bibliothèque Parsley.js pour la validation côté client du formulaire.
      var form = $(this); // Utilise jQuery pour sélectionner le formulaire.
      if (form.parsley().isValid()) {
        // Vérifie si tous les champs du formulaire sont valides selon les critères de Parsley.js.
        submitForm(); // Si le formulaire est valide, appelle la fonction `submitForm` pour le soumettre.
      }
    });
});

// Définit la fonction pour soumettre le formulaire.
function submitForm() {
  // Récupère les valeurs entrées dans les champs du formulaire.
  const inputPrenom = document.getElementById("inputPrenom").value;
  const inputNom = document.getElementById("inputNom").value;
  const inputEmail = document.getElementById("inputEmail").value;
  const inputTelephone = document.getElementById("inputTelephone").value;
  const inputCommentaire = document.getElementById("inputCommentaire").value;

  // Crée un objet JavaScript pour regrouper les données du formulaire.
  const formObject = {
    artiste: inputPrenom, // Prénom de l'artiste.
    titre: inputNom, // Nom de l'œuvre.
    email: inputEmail, // Email de contact.
    telephone: inputTelephone, // Numéro de téléphone.
    description: inputCommentaire, // Commentaire ou description de l'œuvre.
  };

  // Utilise l'API Fetch pour envoyer les données du formulaire au serveur via une requête HTTP POST.
  fetch("/api/oeuvres", {
    method: "POST", // Spécifie la méthode HTTP pour l'envoi des données.
    headers: {
      "Content-Type": "application/json", // Indique que le corps de la requête contient des données JSON.
    },
    body: JSON.stringify(formObject), // Convertit l'objet des données du formulaire en une chaîne JSON.
  })
    .then((response) => {
      if (!response.ok) {
        // Vérifie si la réponse HTTP n'est pas réussie (code 200-299).
        throw new Error("La réponse du réseau n'était pas ok"); // Lance une erreur si la réponse est hors de l'intervalle de succès.
      }
      return response.json(); // Convertit la réponse en JSON si la requête a réussi.
    })
    .then((data) => {
      window.location.href = "ajouterMerci.html"; // Redirige l'utilisateur vers une page de remerciement après la soumission réussie.
    })
    .catch((error) => {
      console.error("Erreur lors de la soumission du formulaire:", error); // Affiche l'erreur dans la console si la requête échoue.
      alert("Erreur lors de la soumission du formulaire. Veuillez réessayer."); // Affiche une alerte à l'utilisateur en cas d'erreur.
    });
}
