<?php

namespace GlimpsGoneV2\controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;

class AccueilController extends AbstractController
{
    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);
    }

    public function execute(): ResponseInterface
    {
        $user = $this->getCurrentUser();

        return $this->phugResponse('accueil', [
            'title' => 'Accueil',
            'isUserLoggedIn' => $user !== null
        ]);
    }
}
