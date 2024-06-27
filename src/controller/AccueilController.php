<?php

namespace GlimpsGoneV2\controller;

use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\core\TemplateEngine;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;

class AccueilController extends AbstractController
{
    function execute(): ResponseInterface
    {
        $title = "Accueil";
        $templateEngine = new TemplateEngine();
        $html = $templateEngine->render('accueil.pug', ['title' => $title]);
        return new Response(200, [], $html);
    }
}
