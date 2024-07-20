<?php

namespace GlimpsGoneV2\controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\repository\UserRepository;
use GlimpsGoneV2\core\Config;

class PostEnregistrerController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);
        $this->userRepository = new UserRepository(Config::getPDO());
    }

    public function execute(): ResponseInterface
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
            return $this->phugResponse("enregistrer", [
                'error' => 'Email déjà utilisé'
            ]);
        }

        $this->userRepository->createUser($prenom, $nom, $email, $password, $telephone, $bio, $photo);

        return $this->redirectionResponse('/glimpsGoneV2/connexion');
    }
}
