<?php

namespace GlimpsGoneV2\controller;
use Psr\Http\Message\ResponseInterface;
use GlimpsGoneV2\core\AbstractController;

class ConnexionController extends AbstractController
{
    public function __construct($request, $pathParams)
    {
        parent::__construct($request, $pathParams);
    }

    public function execute(): ResponseInterface
    {
        return $this->phugResponse('connexion', [
            'title' => 'Connexion',
        ]);
    }
}
