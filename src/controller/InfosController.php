<?php

namespace GlimpsGoneV2\controller;

use GlimpsGoneV2\core\AbstractController;
use GlimpsGoneV2\core\TemplateEngine;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;

class InfosController extends AbstractController
{
    public function execute(): ResponseInterface
    {
        $templateEngine = new TemplateEngine();

        $isUserLoggedIn = isset($_SESSION['user_id']) && $_SESSION['user_id'] !== null;
        $userId = $_SESSION['user_id'] ?? null;

        $html = $templateEngine->render('infos.pug', [
            'title' => 'Infos',
            'isUserLoggedIn' => $isUserLoggedIn,
            'userId' => $userId
        ]);

        return new Response(200, [], $html);
    }
}
