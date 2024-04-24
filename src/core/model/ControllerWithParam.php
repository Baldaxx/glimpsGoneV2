<?php

// Cette classe instancie des contrôleurs avec des paramètres spécifiques extraits des routes. Elle prend le nom d'une classe contrôleur et des paramètres de chemin, puis crée une instance de ce contrôleur en passant la requête HTTP et les paramètres de chemin au constructeur du contrôleur.

namespace GlimpsGoneV2\core\model;

use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;

class ControllerWithParam
{
    private string $controllerClass;
    private array $pathParams;

    public function __construct(string $controllerClass, array $params)
    {
        $this->controllerClass = $controllerClass;
        $this->pathParams = $params;
    }

    public function instantiate(ServerRequestInterface $request): AbstractController
    {
        return new $this->controllerClass($request, $this->pathParams);
    }
}
