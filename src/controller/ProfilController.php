<?php

namespace GlimpsGoneV2\controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\core\TemplateEngine;
use GlimpsGoneV2\repository\UserRepository;
use GuzzleHttp\Psr7\Response;

class ProfilController extends AbstractController
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
$userId = $_SESSION['user_id'] ?? null;
if ($userId) {
$user = $this->userRepository->getUserById($userId);
$html = $this->templateEngine->render('profil.pug', ['user' => $user]);
return new Response(200, [], $html);
} else {
// Gérer le cas où l'utilisateur n'est pas connecté
// Rediriger vers la page de connexion par exemple
return new Response(302, ['Location' => '/glimpsGoneV2/']);
}
}
}
