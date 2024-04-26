<?php

// Ce code PHP définit un contrôleur API pour récupérer les détails d'un artiste. Il utilise une instance de ArtisteRepository pour interagir avec la base de données et récupérer les données de l'artiste à partir de son ID. Ensuite, il renvoie les détails de l'artiste au format JSON en réponse à la requête.

namespace GlimpsGoneV2\controller\api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\core\App;
use GlimpsGoneV2\repository\ArtisteRepository;

class ArtisteDetailApiController extends AbstractController
{
    private ArtisteRepository $artisteRepository;

    /**
     * The constructor of the class.
     *
     * @param ServerRequestInterface $request the psr7 request instance
     * @param array $pathParams the path parameters
     */
    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);
        $this->artisteRepository = new ArtisteRepository(App::getAppInstance()->getPDO());
    }

    /**
     * Get the artiste repository.
     *
     * @return ArtisteRepository The artiste repository instance
     */
    public function getArtisteRepository(): ArtisteRepository
    {
        return $this->artisteRepository;
    }

    /**
     * Execute the action.
     *
     * @return ResponseInterface The response interface
     */
public function execute(): ResponseInterface
{
    $artisteId = intval($this->pathParams[0]);
    $artiste = $this->artisteRepository->getArtisteById($artisteId);

    return $this->jsonResponse($artiste->toArray()); 
}
}
