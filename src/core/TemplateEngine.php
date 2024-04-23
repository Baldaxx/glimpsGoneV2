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

    // Méthode pour générer le HTML à partir d'un template et des variables fournies.
    public function render(string $template, array $variables = []): ResponseInterface
    {
        $response = new Response();
        $fullPath = $this->pug->getOption('basedir') . '/' . $template;
        if (!file_exists($fullPath)) {
            die("Le fichier template {$fullPath} n'existe pas.");
        }
        $output = $this->pug->render($fullPath, $variables);
        $response->getBody()->write($output);

        return $response;
    }
}
