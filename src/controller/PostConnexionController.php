<?php

namespace GlimpsGoneV2\controller;

use GlimpsGoneV2\core\App;
use Psr\Http\Message\ResponseInterface;
use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\repository\UserRepository;

class PostConnexionController extends AbstractController
{
    private $userRepository;

    public function __construct($request, $pathParams)
    {
        parent::__construct($request, $pathParams);
        $this->userRepository = new UserRepository(App::getAppInstance()->getPDO());
    }

    public function execute(): ResponseInterface
    {
        $data = $this->request->getParsedBody();

        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if ($email && $password) {
            $user = $this->userRepository->getUserByEmail($email);

            if ($user && password_verify($password, $user->getPassword())) {
                $_SESSION['user_id'] = $user->getId();
                return $this->redirectionResponse('/glimpsGoneV2/profil');
            }
        }

        // Si l'authentification échoue, renvoyez l'utilisateur à la page de connexion avec un message d'erreur
        return $this->phugResponse('connexion', [
            'title' => 'Connexion',
            'error' => 'Identifiants invalides.'
        ]);
    }
}
