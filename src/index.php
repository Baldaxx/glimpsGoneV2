<?php

require __DIR__ . '/../vendor/autoload.php';
$sqlContent = file_get_contents(__DIR__ . '/database/sql/000-install_db.sql');


use GlimpsGoneV2\core\App;

$title = 'Accueil';

require 'core/app.php';
include 'pages/partials/head.php';
include 'pages/partials/menu.php';
include 'pages/user/accueil.php';
include 'pages/partials/footer.php'; 


$app = App::getAppInstance();

$app->run();
