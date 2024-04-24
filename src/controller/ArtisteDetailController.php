<?php

// Ce code PHP définit un contrôleur pour récupérer les détails d'un artiste. Il utilise une instance de ArtisteRepository pour interagir avec la base de données et récupérer les données de l'artiste à partir de son ID. Ensuite, il renvoie une réponse au format PSR-7 en utilisant la méthode phugResponse, probablement pour générer une vue avec les détails de l'artiste.

namespace GlimpsGoneV2\controller;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\core\App;
use GlimpsGoneV2\repository\ArtisteRepository;

class ArtisteDetailController extends AbstractController
{
    private ArtisteRepository $artisteRepository;

    /**
     * Constructeur de la classe.
     *
     * @param ServerRequestInterface $request L'instance de la requête PSR-7
     * @param array $pathParams Les paramètres de chemin
     */
    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);
        $this->artisteRepository = new ArtisteRepository(App::getAppInstance()->getPDO());
    }

    /**
     * Action du contrôleur.
     *
     * @return ResponseInterface L'instance de réponse PSR-7
     */
    function execute(): ResponseInterface
    {
        $artisteId = intval($this->pathParams[0]);
        $artiste = $this->artisteRepository->getArtisteById($artisteId);

        return $this->phugResponse("artiste_detail", ["data" => $artiste]);
    }
}
