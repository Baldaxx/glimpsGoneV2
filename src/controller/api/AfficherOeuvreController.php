<?php

namespace GlimpsGoneV2\controller\api;

use GlimpsGoneV2\core\App;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\repository\OeuvreRepository;

class AfficherOeuvreController extends AbstractController
{
    protected ServerRequestInterface $request;  
    private OeuvreRepository $oeuvreRepository;

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
        $this->oeuvreRepository = new OeuvreRepository(App::getAppInstance()->getPDO());
    }

    public function execute(): ResponseInterface
    {
        $id = (int)$this->request->getAttribute('id');
        $oeuvre = $this->oeuvreRepository->getOeuvreById($id);
        if (!$oeuvre) {
            return $this->jsonResponse(['error' => 'Oeuvre not found'], 404);
        }
        return $this->jsonResponse($oeuvre->toArray());
    }
}
