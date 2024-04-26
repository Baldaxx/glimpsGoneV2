<?php

namespace GlimpsGoneV2\controller\api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\core\App;
use GlimpsGoneV2\repository\ArtistOeuvreRepository;

class ArtistOeuvreJointApiController extends AbstractController
{
    private ArtistOeuvreRepository $artistOeuvreRepository;

    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);
        $this->artistOeuvreRepository = new ArtistOeuvreRepository(App::getAppInstance()->getPDO());
    }

    public function execute(): ResponseInterface
    {
        $artistId = intval($this->pathParams['artistId'] ?? null);
        if (!$artistId) {
            return $this->jsonResponse(['error' => 'Artist ID is required'], 400);
        }

        $data = $this->artistOeuvreRepository->getArtistAndOeuvresById($artistId);
        if (!$data) {
            return $this->jsonResponse(['error' => 'Artist not found'], 404);
        }

        if (empty($data['oeuvres'])) {
            return $this->jsonResponse(['error' => 'No artworks found for this artist'], 404);
        }

        $result = [
            'artiste' => $data['artiste']->toArray(),
            'oeuvres' => array_map(function ($oeuvre) {
                return $oeuvre->toArray();
            }, $data['oeuvres'])
        ];

        return $this->jsonResponse($result);
    }
}
