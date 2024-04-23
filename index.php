<?php

require __DIR__ . '/vendor/autoload.php';

use GlimpsGoneV2\core\App;

$title = 'Accueil';

// Assurez-vous que ces chemins sont relatifs à l'emplacement du fichier index.php
require __DIR__ . '/src/core/App.php';
include __DIR__ . '/src/view/partials/head.pug';
include __DIR__ . '/src/view/partials/menu.pug';
include __DIR__ . '/src/view/accueil.pug';
include __DIR__ . '/src/view/partials/footer.pug';

$app = App::getAppInstance();
$app->run();
