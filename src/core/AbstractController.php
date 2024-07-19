<?php

namespace GlimpsGoneV2\core;

use Throwable;
use Phug\Renderer;
use Phug\RendererException;
use GlimpsGoneV2\model\User;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use GlimpsGoneV2\repository\UserRepository;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractController
{
    private UserRepository $userRepository;

    protected ServerRequestInterface $request;

    protected array $pathParams;

    abstract function execute(): ResponseInterface;

    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        $this->userRepository = new UserRepository(App::getAppInstance()->getPDO());
        $this->request = $request;
        $this->pathParams = $pathParams;
    }

    protected function getCurrentUser(): User|null
    {
        $userId = $_SESSION['user_id'] ?? null;
        if ($userId === null) return null;
        return $this->userRepository->getUserById($userId);
    }

    protected function phugResponse(string $templateName, array $params = []): ResponseInterface
    {
        $templateFile = __DIR__ . "/../view/" . $templateName . ".pug";
        $response = new Response();

        $user = $this->getCurrentUser();
        if ($user === null) {
            $params['userId'] = null;
            $params['isUserLoggedIn'] = false;
        } else {
            $params['userId'] = $user->getId();
            $params['isUserLoggedIn'] = true;
        }


        try {
            $output = $this->getPhugRenderer()->renderFile($templateFile, $params);
            $response->getBody()->write($output);
        } catch (Throwable $e) {
            App::getAppInstance()->fatalError("Failed to render the view (" . $e->getMessage() . ")");
        }

        return $response;
    }

    protected function jsonResponse(array $data, int $statusCode = 200): ResponseInterface
    {
        $response = new Response();
        $jsonBody = json_encode($data);
        $response->getBody()->write($jsonBody);

        return $response
            ->withStatus($statusCode)
            ->withHeader("Content-Type", "application/json");
    }

    protected function redirectionResponse(string $direction): ResponseInterface
    {
        return new Response(302, ['Location' => $direction]);
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
