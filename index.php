<?php

require __DIR__ . '/vendor/autoload.php';

use GlimpsGoneV2\core\App;
use GlimpsGoneV2\core\TemplateEngine;

// Initialisation du moteur de template
$templateEngine = new TemplateEngine();


// Initialisation et démarrage de l'application
$app = App::getAppInstance();
$app->run();
