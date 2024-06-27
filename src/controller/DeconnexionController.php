<?php

namespace GlimpsGoneV2\controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;
use GuzzleHttp\Psr7\Response;

class DeconnexionController extends AbstractController
{
    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);
    }

    public function execute(): ResponseInterface
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Détruire toutes les variables de session.
        $_SESSION = [];

        // Si vous voulez détruire complètement la session, effacez également le cookie de session.
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Enfin, détruisez la session.
        session_destroy();

        // Rediriger vers la page de connexion ou d'accueil
        return new Response(302, ['Location' => '/glimpsGoneV2/']);
    }
}
