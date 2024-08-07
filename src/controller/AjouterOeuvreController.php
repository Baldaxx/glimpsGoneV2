<?php

namespace GlimpsGoneV2\controller;

use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\core\App;
use GlimpsGoneV2\model\Oeuvre;
use GlimpsGoneV2\model\Artiste;
use GlimpsGoneV2\repository\OeuvreRepository;
use GlimpsGoneV2\repository\ArtisteRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AjouterOeuvreController extends AbstractController
{
    private OeuvreRepository $oeuvreRepository;
    private ArtisteRepository $artisteRepository;

    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);
        $pdo = App::getAppInstance()->getPDO();
        $this->oeuvreRepository = new OeuvreRepository($pdo);
        $this->artisteRepository = new ArtisteRepository($pdo);
    }

    public function execute(): ResponseInterface
    {
        $requestData = $this->request->getParsedBody();
        $requiredFields = ['titre', 'description', 'artiste'];

        foreach ($requiredFields as $field) {
            if (empty($requestData[$field])) {
                return $this->jsonResponse(["error" => "Le champ '$field' est manquant ou vide"], 400);
            }
        }

        $artiste = $this->artisteRepository->findOrCreateByName($requestData['artiste']);
        $dateCreation = new \DateTime();

        $oeuvre = new Oeuvre(
            null,
            $requestData['titre'],
            $requestData['description'],
            $dateCreation,
            $artiste
        );

        if ($this->oeuvreRepository->addOeuvre($oeuvre)) {
            return $this->redirectionResponse('ajouterMerci');
        } else {
            return $this->jsonResponse(["error" => "Erreur lors de l'enregistrement"], 500);
        }
    }
}
