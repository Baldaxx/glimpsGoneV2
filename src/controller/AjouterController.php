<?php

namespace GlimpsGoneV2\controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\Psr7\Response;
use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\core\TemplateEngine;

class AjouterController extends AbstractController
{
    private TemplateEngine $templateEngine;

    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);
        $this->templateEngine = new TemplateEngine();
    }

    public function execute(): ResponseInterface
    {
        $data = [
            'title' => 'Ajouter une nouvelle oeuvre',
        ];

        $html = $this->templateEngine->render('ajouter.pug', $data);

        return new Response(200, [], $html);
    }
}
