<?php

namespace GlimpsGoneV2\controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;

class ProfilController extends AbstractController
{
    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);
    }

    public function execute(): ResponseInterface
    {
        $user = $this->getCurrentUser();
        if ($user) {
            return $this->phugResponse('profil', ['data' => $user]);
        } else {
            return $this->redirectionResponse('/glimpsGoneV2/connexion');
        }
    }
}
