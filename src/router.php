<?php

use GlimpsGoneV2\controller\AccueilController;
use GlimpsGoneV2\controller\AjouterController;
use GlimpsGoneV2\controller\api\ArtisteDetailApiController;
use GlimpsGoneV2\controller\api\OeuvreDetailApiController;
use GlimpsGoneV2\controller\ArtisteDetailController;
use GlimpsGoneV2\controller\FaqController;
use GlimpsGoneV2\controller\GalerieController;
use GlimpsGoneV2\controller\GalerieDownController;
use GlimpsGoneV2\controller\InfosController;
use GlimpsGoneV2\core\App;

$app = App::getAppInstance();

/** Page d'accueil */
$app->get("/", AccueilController::class);

/** Affiche le détail d'un artiste. (ici pour démo) */
$app->get("/artiste/{int}", ArtisteDetailController::class);

/** Affiche le formulaire pour ajouter une oeuvre. */
$app->get("/ajouter", AjouterController::class);

/** Affiche la FAQ. */
$app->get("/faq", FaqController::class);

/** Affiche la page Info/contacte */
$app->get("/infos", InfosController::class);

/** Affiche une page d'erreur. */
$app->get("/galerieDown", GalerieDownController::class);

/** Affiche la gallerie */
$app->get("/galerie", GalerieController::class);

/*
 * Route d'API json
 */

/** Retourne le détail d'un artiste. */
$app->get("/api/artiste/{int}", ArtisteDetailApiController::class);

/** Retourne le détail d'une oeuvre. */
$app->get("/api/oeuvre/{int}", OeuvreDetailApiController::class);
