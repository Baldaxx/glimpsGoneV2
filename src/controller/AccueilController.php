<?php

namespace GlimpsGoneV2\controller;

use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\core\TemplateEngine;
use Psr\Http\Message\ResponseInterface;

class AccueilController extends AbstractController
{

    function execute(): ResponseInterface
    {
        $title = "Accueil";
        $templateEngine = new TemplateEngine();
        return $templateEngine->render('accueil.pug', ['title' => $title]);
    }
}
