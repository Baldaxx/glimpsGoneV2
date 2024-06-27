<?php

namespace GlimpsGoneV2\controller;

use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\core\TemplateEngine;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;

class GalerieController extends AbstractController
{
    public function execute(): ResponseInterface
    {
        $templateEngine = new TemplateEngine();
        $html = $templateEngine->render('galerie.pug', [
            'title' => 'Galerie'
        ]);

        return new Response(200, [], $html);
    }
}
