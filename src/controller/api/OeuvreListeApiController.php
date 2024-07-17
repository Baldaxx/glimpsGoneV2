<?php

namespace GlimpsGoneV2\controller\api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\core\TemplateEngine;
use GlimpsGoneV2\repository\OeuvreRepository;
use GlimpsGoneV2\core\App;

class OeuvreListeApiController extends AbstractController
{
    private OeuvreRepository $oeuvreRepository;
    private TemplateEngine $templateEngine;

    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);
        $this->oeuvreRepository = new OeuvreRepository(App::getAppInstance()->getPDO());
        $this->templateEngine = new TemplateEngine();
    }

    public function execute(): ResponseInterface
    {
        $oeuvres = $this->oeuvreRepository->getAllOeuvres();

        $oeuvresArray = array_map(fn($oeuvre): array => $oeuvre->toArray(), $oeuvres);

        return $this->jsonResponse($oeuvresArray);
    }
}
