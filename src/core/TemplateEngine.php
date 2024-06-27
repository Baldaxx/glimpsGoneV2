<?php

namespace GlimpsGoneV2\core;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Pug\Pug;

class TemplateEngine
{
    private $pug;

    public function __construct()
    {
        $this->pug = new Pug([
            'basedir' => __DIR__ . '/../view',
            'pretty' => true,
            'cache' => __DIR__ . '/../cache'
        ]);
    }

    public function render(string $template, array $variables = []): string
    {
        $fullPath = $this->pug->getOption('basedir') . '/' . $template;
        if (!file_exists($fullPath)) {
            die("Le fichier template {$fullPath} n'existe pas.");
        }
        return $this->pug->render($fullPath, $variables);
    }
}
