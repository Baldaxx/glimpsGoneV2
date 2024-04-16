// Attache un écouteur d'événements pour exécuter le code une fois que tout le contenu de la page a été chargé.
document.addEventListener("DOMContentLoaded", function () {
  // Sélectionne le formulaire sur la page en utilisant sa classe.
  const form = document.querySelector(".formulaireProposer");

  // Ajoute un écouteur d'événements pour la soumission du formulaire.
  form.addEventListener("submit", function (e) {
    e.preventDefault(); // Empêche le formulaire de se soumettre de manière traditionnelle, permettant un traitement personnalisé.

    // Utilise Parsley.js pour vérifier si le formulaire est valide.
    if ($(form).parsley().isValid()) {
      // Récupère les valeurs des champs du formulaire.
      const fname = document.getElementById("inputPrenom").value;
      const lname = document.getElementById("inputNom").value;
      const email = document.getElementById("inputEmail").value;
      const phone = document.getElementById("inputTelephone").value;
      const message = document.getElementById("inputCommentaire").value;

      // Prépare le corps de l'email avec les informations du formulaire.
      let ebody = `
<b>Name: </b>${fname} ${lname}
<br>
<b>Email: </b>${email}
<br>
<b>Phone: </b>${phone}
<br>
<b>Message: </b>${message}
`;

      // Envoie l'email en utilisant la bibliothèque SMTPJS avec les détails spécifiés.
      Email.send({
        SecureToken: "c8e9eed4-71f6-4903-b98e-194f06173484", // Token de sécurité.
        To: "virginie.baldacchino.menut@gmail.com", // Adresse email destinataire.
        From: "virginie.baldacchino.menut@gmail.com", // Adresse email expéditrice.
        Subject: "Vous avez reçu un email de " + email, // Sujet de l'email.
        Body: ebody, // Corps de l'email formaté avec les informations du formulaire.
      }).then((message) => {
        if (message === "OK") {
          console.log("L'email est parti !"); // Affiche un message de succès dans la console.
          form.reset(); // Réinitialise le formulaire.
          window.location.href = "infoMerci.html"; // Redirige l'utilisateur vers une page de remerciement.
        } else {
          console.error("L'email n'est pas parti !", message); // Affiche un message d'erreur dans la console si l'envoi échoue.
        }
      });
    } else {
      // Si le formulaire n'est pas valide, déclenche la validation de Parsley.js pour afficher les messages d'erreur.
      $(form).parsley().validate();
    }
  });
});
