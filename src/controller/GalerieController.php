<?php

namespace GlimpsGoneV2\controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\core\TemplateEngine;

class GalerieController extends AbstractController
{
    private TemplateEngine $templateEngine;

    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);
        $this->templateEngine = new TemplateEngine();
    }

    function execute(): ResponseInterface
    {
        return $this->templateEngine->render('galerie.pug');
    }
}
