<?php

namespace Ttizio\DemoPhp\controller\api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ttizio\DemoPhp\core\AbstractController;
use Ttizio\DemoPhp\core\App;
use Ttizio\DemoPhp\repository\ArtisteRepository;

class ArtisteDetailApiController extends AbstractController
{
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

    function execute(): ResponseInterface
    {
        $artisteId = intval($this->pathParams[0]);

        $artiste = $this->artisteRepository->getArtisteById($artisteId);

        return $this->jsonResponse($artiste->toJson());
    }
}