<?php

namespace GlimpsGoneV2\controller;

use GlimpsGoneV2\core\AbstractController;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\ServerRequest;

class InfosMerciController extends AbstractController
{
    public function execute(): ResponseInterface
    {
        $request = ServerRequest::fromGlobals();

        if ($request->getMethod() === 'POST') {
            return $this->redirectionResponse('/glimpsGoneV2/infosMerci');
        }

        return $this->phugResponse('infosMerci', [
            'title' => 'Merci pour votre message'
        ]);
    }
}
