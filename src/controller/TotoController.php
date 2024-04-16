<?php

namespace GlimpsGoneV2\controller;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;

class TotoController extends AbstractController
{

    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);
    }

    function execute(): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write("Hello world");

        return $response;
    }
}
