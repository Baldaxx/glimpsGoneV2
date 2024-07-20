<?php

use GlimpsGoneV2\core\App;
use GlimpsGoneV2\controller\FaqController;
use GlimpsGoneV2\controller\InfosController;
use GlimpsGoneV2\controller\ProfilController;
use GlimpsGoneV2\controller\AccueilController;
use GlimpsGoneV2\controller\AjouterController;
use GlimpsGoneV2\controller\GalerieController;
use GlimpsGoneV2\controller\ConnexionController;
use GlimpsGoneV2\controller\GalerieFinController;
use GlimpsGoneV2\controller\InfosMerciController;
use GlimpsGoneV2\controller\DeconnexionController;
use GlimpsGoneV2\controller\EnregistrerController;
use GlimpsGoneV2\controller\AjouterMerciController;
use GlimpsGoneV2\controller\GalerieDownController; 
use GlimpsGoneV2\controller\AjouterOeuvreController;
use GlimpsGoneV2\controller\ArtisteDetailController;
use GlimpsGoneV2\controller\PostConnexionController;
use GlimpsGoneV2\controller\PostEnregistrerController;
use GlimpsGoneV2\controller\api\OeuvreListeApiController;
use GlimpsGoneV2\controller\api\OeuvreDetailApiController;

$app = App::getAppInstance();

$app->get("/", AccueilController::class);
$app->get("/artiste/{int}", ArtisteDetailController::class);
$app->get("/ajouter", AjouterController::class);
$app->get("/faq", FaqController::class);
$app->get("/infos", InfosController::class);
$app->post("/ajouter", AjouterOeuvreController::class);
$app->get("/enregistrer", EnregistrerController::class);
$app->get("/connexion", ConnexionController::class);
$app->post('/enregistrer', PostEnregistrerController::class);
$app->post('/connexion', PostConnexionController::class);
$app->get('/deconnexion', DeconnexionController::class);
$app->get("/profil", ProfilController::class);
$app->get("/api/oeuvre/{int}", OeuvreDetailApiController::class);
$app->get('/galerie', GalerieController::class);
$app->get('/galerieFin', GalerieFinController::class);
$app->get('/galerieDown', GalerieDownController::class); 
$app->get('/api/oeuvre', OeuvreListeApiController::class);
$app->get('/ajouterMerci', AjouterMerciController::class);
$app->get('/infosMerci', InfosMerciController::class);
$app->post('/infos', InfosMerciController::class);
