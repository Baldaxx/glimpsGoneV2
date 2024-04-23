<?php

namespace GlimpsGoneV2\controller;

use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\core\TemplateEngine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AccueilController extends AbstractController
{

    function execute(): ResponseInterface
    {
        $title = "Accueil";

        // Initialisation du moteur de template
        $templateEngine = new TemplateEngine();
        // Rendu du template d'accueil
        return $templateEngine->render('accueil.pug', ['title' => $title]);
    }
}