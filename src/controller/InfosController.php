<?php

namespace GlimpsGoneV2\controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;

class InfosController extends AbstractController
{
    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);
    }

    public function execute(): ResponseInterface
    {
        return $this->phugResponse('infos', [
            'title' => 'Infos',
        ]);
    }
}
