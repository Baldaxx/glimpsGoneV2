<?php

namespace GlimpsGoneV2\controller;

use GlimpsGoneV2\core\App;
use GuzzleHttp\Psr7\Response;
use GlimpsGoneV2\core\TemplateEngine;
use Psr\Http\Message\ResponseInterface;
use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\repository\UserRepository;

class ConnexionController extends AbstractController
{
    private $templateEngine;
    private $userRepository;

    public function __construct($request, $pathParams)
    {
        parent::__construct($request, $pathParams);
        $this->templateEngine = new TemplateEngine();
        $this->userRepository = new UserRepository(App::getAppInstance()->getPDO());
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

        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if ($email && $password) {
            $user = $this->userRepository->getUserByEmail($email);

            if ($user && password_verify($password, $user->getPassword())) {
                $_SESSION['user_id'] = $user->getId();
                return new Response(302, ['Location' => '/glimpsGoneV2/profil']);
            }
        }

        // Si l'authentification échoue, renvoyez l'utilisateur à la page de connexion avec un message d'erreur
        $html = $this->templateEngine->render('connexion.pug', ['error' => 'Identifiants invalides.']);
        return new Response(200, [], $html);
    }
}
