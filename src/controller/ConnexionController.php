<?php

namespace GlimpsGoneV2\controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\core\TemplateEngine;
use GuzzleHttp\Psr7\Response;

class ConnexionController extends AbstractController
{
private TemplateEngine $templateEngine;

public function __construct(ServerRequestInterface $request, array $pathParams)
{
parent::__construct($request, $pathParams);
$this->templateEngine = new TemplateEngine();
}

public function execute(): ResponseInterface
{
$html = $this->templateEngine->render('connexion.pug');
return new Response(200, [], $html);
}
}
