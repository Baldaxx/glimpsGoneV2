<?php

require __DIR__ . '/../vendor/autoload.php';
use GlimpsGoneV2\core\TemplateEngine;

$sqlContent = file_get_contents(__DIR__ . '/../database/sql/000-install_db.sql');

$engine = new TemplateEngine();
$engine->render('view/accueil.pug');

$title = 'Accueil';

$app = \GlimpsGoneV2\core\App::getAppInstance();

$app->run();
