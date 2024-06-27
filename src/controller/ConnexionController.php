<?php

namespace GlimpsGoneV2\controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\core\TemplateEngine;
use GlimpsGoneV2\repository\UserRepository;
use GuzzleHttp\Psr7\Response;

class ConnexionController extends AbstractController
{
    private TemplateEngine $templateEngine;
    private UserRepository $userRepository;

    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);
        $this->templateEngine = new TemplateEngine();
        $this->userRepository = new UserRepository();
    }

    public function execute(): ResponseInterface
    {
        if ($this->request->getMethod() === 'POST') {
            return $this->handlePost();
        }

        $html = $this->templateEngine->render('connexion.pug');
        return new Response(200, [], $html);
    }

    private function handlePost(): ResponseInterface
    {
        $data = $this->request->getParsedBody();
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $user = $this->userRepository->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            return new Response(302, ['Location' => '/glimpsGoneV2/profil']);
        }

        // Retourner une réponse avec un message d'erreur en cas de connexion échouée
        $html = $this->templateEngine->render('connexion.pug', ['error' => 'Email ou mot de passe incorrect']);
        return new Response(200, [], $html);
    }
}
