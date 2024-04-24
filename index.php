<?php

require __DIR__ . '/vendor/autoload.php';

use GlimpsGoneV2\core\App;
use GlimpsGoneV2\core\TemplateEngine;

$templateEngine = new TemplateEngine();

$app = App::getAppInstance();
$app->run();
