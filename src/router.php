<?php

use GlimpsGoneV2\controller\EnregistrerController;
use GlimpsGoneV2\controller\ConnexionController;
use GlimpsGoneV2\controller\AccueilController;
use GlimpsGoneV2\controller\ProfilController;
use GlimpsGoneV2\controller\FaqController;
use GlimpsGoneV2\controller\InfosController;
use GlimpsGoneV2\controller\AjouterController;
use GlimpsGoneV2\controller\GalerieController;
use GlimpsGoneV2\controller\GalerieFinController;
use GlimpsGoneV2\controller\GalerieDownController;
use GlimpsGoneV2\controller\AjouterOeuvreController;
use GlimpsGoneV2\controller\ArtisteDetailController;
use GlimpsGoneV2\controller\api\OeuvreDetailApiController;
use GlimpsGoneV2\controller\api\ArtisteDetailApiController;
use GlimpsGoneV2\core\App;

$app = App::getAppInstance();

$app->get("/", AccueilController::class);
$app->get("/artiste/{int}", ArtisteDetailController::class);
$app->get("/ajouter", AjouterController::class);
$app->get("/faq", FaqController::class);
$app->get("/infos", InfosController::class);
$app->get("/galerieDown", GalerieDownController::class);
$app->get("/galerie", GalerieController::class);
$app->get("/galerieFin", GalerieFinController::class);
$app->post("/ajouter", AjouterOeuvreController::class);
$app->get("/enregistrer", EnregistrerController::class);
$app->get("/connexion", ConnexionController::class);
$app->get("/profil", ProfilController::class);
$app->post("/enregistrer", EnregistrerController::class);
$app->get("/api/artiste/{int}", ArtisteDetailApiController::class);
$app->get("/api/oeuvre/{int}", OeuvreDetailApiController::class);
