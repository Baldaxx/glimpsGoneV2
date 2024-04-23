<?php

require __DIR__ . '/vendor/autoload.php';
$sqlContent = file_get_contents(__DIR__ . '/database/sql/000-install_db.sql');

use GlimpsGoneV2\core\App;

$title = 'Accueil';

require __DIR__ . '/src/core/App.php';
include __DIR__ . '/src/view/partials/head.pug';
include __DIR__ . '/src/view/partials/menu.pug';
include __DIR__ . '/src/view/accueil.pug';
include __DIR__ . '/src/view/partials/footer.pug';

$app = App::getAppInstance();
$app->run();
