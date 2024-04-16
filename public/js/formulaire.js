
// librairie Parsley pour validation de formulaire
$(document).ready(function () {
  $(".formulaireProposer").parsley();
  Parsley.addMessages("fr", {
    defaultMessage: "Ce champ est obligatoire.",
  });
  Parsley.setLocale("fr");
});


