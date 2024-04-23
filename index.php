<?php

require __DIR__ . '/vendor/autoload.php';

use GlimpsGoneV2\core\App;
use GlimpsGoneV2\core\TemplateEngine;

// Initialisation du moteur de template
$templateEngine = new TemplateEngine();

// Chargement du contenu SQL (assurez-vous que le chemin est correct et que le fichier existe)
$sqlContent = file_get_contents(__DIR__ . '/database/sql/000-install_db.sql');
if ($sqlContent === false) {
    die("Erreur lors du chargement du fichier SQL.");
}

// Configuration initiale du titre de la page (si nécessaire pour le template)
$title = 'Accueil';

// Rendu du template d'accueil
$templateEngine->render('accueil.pug', ['title' => $title]);

// Initialisation et démarrage de l'application
$app = App::getAppInstance();
$app->run();
