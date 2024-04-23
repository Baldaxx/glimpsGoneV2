<?php

namespace GlimpsGoneV2\core;

use GuzzleHttp\Psr7\Response;
use Phug\Renderer;
use Phug\RendererException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use GlimpsGoneV2\model\Artiste;

abstract class AbstractController
{

    protected ServerRequestInterface $request;

    protected array $pathParams;

    abstract function execute(): ResponseInterface;

    protected function __construct(ServerRequestInterface $request, array $pathParams)
    {
        $this->request = $request;
        $this->pathParams = $pathParams;
    }

    protected function getCurrentUser(): Artiste|null
    {
    }

    protected function phugResponse(string $templateName, array $params): ResponseInterface
    {
        $templateFile = __DIR__ . "/../view/" . $templateName . ".pug";
        $response = new Response();

        try {
            $output = $this->getPhugRenderer()->renderFile($templateFile, $params);
            $response->getBody()->write($output);
        } catch (Throwable $e) {
            App::getAppInstance()->fatalError("Failed to render the view (" . $e->getMessage() . ")");
        }

        return $response;
    }

    protected function jsonResponse(string $jsonBody, int $statusCode = 200): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write($jsonBody);

        return $response
            ->withStatus($statusCode)
            ->withHeader("Content-Type", "application/json");
    }

    private function getPhugRenderer(): Renderer
    {
        try {
            return new Renderer([]);
        } catch (RendererException $e) {
            App::getAppInstance()->fatalError("Failed to instantiate Phug renderer (" . $e->getMessage() . ")");
        }
    }
}
