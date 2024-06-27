<?php

namespace GlimpsGoneV2\controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\Psr7\Response;
use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\core\TemplateEngine;

class FaqController extends AbstractController
{
    private TemplateEngine $templateEngine;

    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);
        $this->templateEngine = new TemplateEngine();
    }

    public function execute(): ResponseInterface
    {
        // Rendre le contenu de la page FAQ
        $html = $this->templateEngine->render('faq.pug');

        // Retourner une réponse HTTP avec le contenu HTML
        return new Response(200, [], $html);
    }
}
