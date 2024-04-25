<?php

namespace GlimpsGoneV2\controller\api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\core\App;
use GlimpsGoneV2\repository\OeuvreRepository;

class OeuvreDetailApiController extends AbstractController
{
    private OeuvreRepository $oeuvreRepository;

    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);
        $this->oeuvreRepository = new OeuvreRepository(App::getAppInstance()->getPDO());
    }

    public function execute(): ResponseInterface
    {
        $oeuvreId = intval($this->pathParams[0]);
        $oeuvre = $this->oeuvreRepository->getOeuvreById($oeuvreId);

        if (!$oeuvre) {
            return $this->jsonResponse(['error' => "Oeuvre not found"], 404);
        }

        return $this->jsonResponse($oeuvre->toArray());
    }

}
