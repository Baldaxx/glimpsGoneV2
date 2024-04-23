<?php

// Ce code définit une classe en PHP pour un contrôleur d'informations qui gère l'affichage de contenu statique, tel que des informations sur votre galerie, et renvoyant une page web générée.

namespace GlimpsGoneV2\controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\core\TemplateEngine;

class InfosController extends AbstractController
{
    private TemplateEngine $templateEngine;

    public function __construct(ServerRequestInterface $request, array $pathParams)
    {
        parent::__construct($request, $pathParams);
        $this->templateEngine = new TemplateEngine();
    }

    function execute(): ResponseInterface
    {
        // Ici, comme il s'agit d'une page d'information, nous n'avons pas besoin de récupérer des données de la base de données.
        // Nous allons simplement renvoyer la vue correspondante.

        return $this->templateEngine->render('infos');
    }
}
