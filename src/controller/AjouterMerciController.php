<?php

namespace GlimpsGoneV2\controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\Psr7\Response;
use GlimpsGoneV2\core\AbstractController;

class AjouterMerciController extends AbstractController
{

    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);
    }

    public function execute(): ResponseInterface
    {
        // Rendre le contenu de la page ajouterMerci
        return $this->phugResponse('ajouterMerci');
    }
}
