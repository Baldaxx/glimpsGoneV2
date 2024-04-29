document.addEventListener("DOMContentLoaded", function () {
  const formulaire = document.getElementById("formulaireProposerid");
  formulaire.addEventListener("submit", function (e) {
    e.preventDefault();
    if (validateForm()) {
      submitForm();
    }
  });
});

function validateForm() {
  let isValid = true;
  const inputPrenom = document.getElementById("inputPrenom");
  const inputNom = document.getElementById("inputNom");
  const inputEmail = document.getElementById("inputEmail");
  const inputTelephone = document.getElementById("inputTelephone");
  const inputCommentaire = document.getElementById("inputCommentaire");

  document.querySelectorAll(".error").forEach((e) => e.remove());

  if (inputPrenom.value.trim() === "") {
    showError(inputPrenom, "Veuillez entrer votre nom de scène.");
    isValid = false;
  }
  if (inputNom.value.trim() === "") {
    showError(inputNom, "Veuillez entrer le titre de l'œuvre.");
    isValid = false;
  }
  if (!validateEmail(inputEmail.value)) {
    showError(inputEmail, "Veuillez entrer une adresse email valide.");
    isValid = false;
  }
  if (!validatePhone(inputTelephone.value)) {
    showError(inputTelephone, "Veuillez entrer un numéro de téléphone valide.");
    isValid = false;
  }
  if (inputCommentaire.value.trim() === "") {
    showError(inputCommentaire, "Veuillez entrer une description de l'œuvre.");
    isValid = false;
  }
  return isValid;
}

function showError(element, message) {
  const error = document.createElement("div");
  error.className = "error";
  error.style.color = "red";
  error.innerText = message;
  element.parentNode.appendChild(error);
}

function submitForm() {
  const formObject = {
    artiste: document.getElementById("inputPrenom").value,
    titre: document.getElementById("inputNom").value,
    email: document.getElementById("inputEmail").value,
    telephone: document.getElementById("inputTelephone").value,
    description: document.getElementById("inputCommentaire").value,
  };

  fetch("/glimpsGoneV2/ajouter", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(formObject),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Oeuvre ajoutée avec succès !");
        window.location.href = "/glimpsGoneV2/ajouterMerci"; // Redirection après succès
      } else {
        throw new Error(data.error || "Erreur serveur");
      }
    })
    .catch((error) => {
      console.error("Erreur lors de la soumission du formulaire:", error);
      alert("Erreur lors de la soumission du formulaire. Veuillez réessayer.");
    });
}

function validateEmail(email) {
  return /\S+@\S+\.\S+/.test(email);
}

function validatePhone(phone) {
  const pattern = /^(?:0|\(?\+33\)?\s?|0033\s?)[1-79](?:[\.\-\s]?\d\d){4}$/;
  return pattern.test(phone);
}
