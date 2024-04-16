<?php

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
     * The action of the controller.
     *
     * @return ResponseInterface the psr7 response instance
     */
    function execute(): ResponseInterface
    {
        $artisteId = intval($this->pathParams[0]);

        $artiste = $this->artisteRepository->getArtisteById($artisteId);

        return $this->phugResponse("artiste_detail", ["data" => $artiste]);
    }
}
