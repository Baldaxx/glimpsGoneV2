<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/vendor/autoload.php';

use GlimpsGoneV2\core\App;

$app = App::getAppInstance();
$app->run();
