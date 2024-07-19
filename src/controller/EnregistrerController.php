<?php

namespace GlimpsGoneV2\controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\core\TemplateEngine;
use GlimpsGoneV2\repository\UserRepository;
use GlimpsGoneV2\core\Config;
use GuzzleHttp\Psr7\Response;

class EnregistrerController extends AbstractController
{
    private TemplateEngine $templateEngine;
    private UserRepository $userRepository;

    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);
        $this->templateEngine = new TemplateEngine();
        $this->userRepository = new UserRepository(Config::getPDO()); 
    }

    public function execute(): ResponseInterface
    {
        if ($this->request->getMethod() === 'POST') {
            return $this->handlePost();
        }

        $html = $this->templateEngine->render('enregistrer.pug');
        return new Response(200, [], $html);
    }

    private function handlePost(): ResponseInterface
    {
        $data = $this->request->getParsedBody();
        $file = $this->request->getUploadedFiles()['photo'];

        // Valider et traiter les données
        $prenom = $data['prenom'] ?? '';
        $nom = $data['nom'] ?? '';
        $email = $data['email'] ?? '';
        $password = password_hash($data['password'] ?? '', PASSWORD_DEFAULT);
        $telephone = $data['telephone'] ?? '';
        $bio = $data['bio'] ?? '';
        $photo = 'public/uploads/' . $file->getClientFilename();

        $file->moveTo(__DIR__ . '/../../' . $photo);

        if ($this->userRepository->getUserByEmail($email)) {
            $html = $this->templateEngine->render('enregistrer.pug', [
                'error' => 'Email déjà utilisé'
            ]);
            return new Response(400, [], $html);
        }

        $this->userRepository->createUser($prenom, $nom, $email, $password, $telephone, $bio, $photo);

        return new Response(302, ['Location' => '/glimpsGoneV2/connexion']);
    }
}
