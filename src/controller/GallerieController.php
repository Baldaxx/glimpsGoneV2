<?php

namespace GlimpsGoneV2\controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\core\App;
use GlimpsGoneV2\repository\OeuvreRepository;

class GallerieController extends AbstractController
{

    private OeuvreRepository $oeuvreRepository;

    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);

        $this->oeuvreRepository = new OeuvreRepository(App::getAppInstance()->getPDO());
    }

    function execute(): ResponseInterface
    {
        $user = $this->getCurrentUser();

        if ($user == null) {
        }

        $oeuvres = $this->oeuvreRepository->getOeuvres();

        $oeuvreAAfficher = $oeuvres[0];

        return $this->phugResponse("oeuvre", ["data" => $oeuvreAAfficher]);
    }
}
