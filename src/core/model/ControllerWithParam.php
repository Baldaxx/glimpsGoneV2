<?php

namespace GlimpsGoneV2\core\model;

use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;

class ControllerWithParam
{
    private string $controllerClass;
    private array $pathParams;

    public function __construct(string $controllerClass, array $params)
    {
        $this->controllerClass = $controllerClass;
        $this->pathParams = $params;
    }

    public function instantiate(ServerRequestInterface $request): AbstractController
    {
        return new $this->controllerClass($request, $this->pathParams);
    }
}
