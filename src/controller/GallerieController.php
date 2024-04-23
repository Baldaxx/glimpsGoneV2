<?php

// Ce code définit une classe en PHP pour un contrôleur de galerie qui gère l'affichage d'œuvres d'art récupérées depuis une base de données, adaptant le contenu montré basé sur l'utilisateur connecté et renvoyant une page web générée.

namespace GlimpsGoneV2\controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\core\App;
use GlimpsGoneV2\repository\OeuvreRepository;

class GallerieController extends AbstractController
{
    private OeuvreRepository $oeuvreRepository;

    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);
        $this->oeuvreRepository = new OeuvreRepository(App::getAppInstance()->getPDO());
    }

    function execute(): ResponseInterface
    {
        $user = $this->getCurrentUser();
        if ($user == null) {
            // Gérer l'absence d'utilisateur si nécessaire
        }

        $oeuvres = $this->oeuvreRepository->getOeuvres();
        $oeuvreAAfficher = $oeuvres[0];

        return $this->phugResponse("oeuvre", ["data" => $oeuvreAAfficher]);
    }
}
