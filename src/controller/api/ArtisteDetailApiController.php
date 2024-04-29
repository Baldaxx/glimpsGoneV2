<?php

namespace GlimpsGoneV2\controller\api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\core\App;
use GlimpsGoneV2\repository\ArtisteRepository;

class ArtisteDetailApiController extends AbstractController
{
    private ArtisteRepository $artisteRepository;

    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);
        $this->artisteRepository = new ArtisteRepository(App::getAppInstance()->getPDO());
    }

    public function execute(): ResponseInterface
    {
        $artisteId = intval($this->pathParams[0]);
        $artiste = $this->artisteRepository->getArtisteById($artisteId);

        if (!$artiste) {
            return $this->jsonResponse(['error' => "Artiste not found"], 404);
        }

        return $this->jsonResponse($artiste->toArray());
    }
}
