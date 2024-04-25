<?php


namespace GlimpsGoneV2\controller;

use GlimpsGoneV2\core\App;
use Psr\Http\Message\ResponseInterface;
use GlimpsGoneV2\core\AbstractController;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\repository\OeuvreRepository;

class OeuvreController extends AbstractController
{
    private OeuvreRepository $oeuvreRepository;

    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);
        $this->oeuvreRepository = new OeuvreRepository(App::getAppInstance()->getPDO());
    }

public function execute(): ResponseInterface {
    try {
        $oeuvres = $this->oeuvreRepository->getOeuvres();
        return $this->jsonResponse($oeuvres);
    } catch (\Exception $e) {
        return $this->jsonResponse(['error' => $e->getMessage()], 500);
    }
}
}
