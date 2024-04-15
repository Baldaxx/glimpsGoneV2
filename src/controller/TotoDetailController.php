<?php

namespace GlimpsGoneV2\controller;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;

class TotoDetailController extends AbstractController
{
    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);
    }

    function execute(): ResponseInterface
    {
        // Assume that the name is the first parameter in the path
        $name = $this->pathParams[0];
        $response = new Response();
        $response->getBody()->write("Hello $name");

        return $response;
    }
}
