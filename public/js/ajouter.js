document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("formulaireProposerid")
    .addEventListener("submit", function (e) {
      e.preventDefault(); 
      var form = $(this); 
      if (form.parsley().isValid()) {
        submitForm(); 
      }
    });
});

function submitForm() {
  const inputPrenom = document.getElementById("inputPrenom").value;
  const inputNom = document.getElementById("inputNom").value;
  const inputEmail = document.getElementById("inputEmail").value;
  const inputTelephone = document.getElementById("inputTelephone").value;
  const inputCommentaire = document.getElementById("inputCommentaire").value;

 
  const formObject = {
    artiste: inputPrenom, 
    titre: inputNom, 
    email: inputEmail, 
    telephone: inputTelephone,
    description: inputCommentaire, 
  };

  fetch("/api/oeuvres", {
    method: "POST", 
    headers: {
      "Content-Type": "application/json", 
    },
    body: JSON.stringify(formObject), 
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("La réponse du réseau n'était pas ok"); 
      }
      return response.json(); 
    })
    .then((data) => {
      window.location.href = "/glimpsGoneV2/ajouterMerci"; 
    })
    .catch((error) => {
      console.error("Erreur lors de la soumission du formulaire:", error); 
      alert("Erreur lors de la soumission du formulaire. Veuillez réessayer.");
    });
}
