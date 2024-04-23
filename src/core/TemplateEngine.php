<?php

namespace GlimpsGoneV2\core;
use Pug\Pug;

class TemplateEngine
{
    private $pug;

    public function __construct()
    {
        echo __DIR__ . '/../view';
        $this->pug = new Pug([
            'basedir' => __DIR__ . '/../view',  
            'pretty' => true,                  
            'cache' => __DIR__ . '/../cache'    
        ]);
    }

    // Méthode pour générer le HTML à partir d'un template et des variables fournies.
    public function render($template, $variables = [])
    {
        $fullPath = $this->pug->getOption('basedir') . '/' . $template;
        if (!file_exists($fullPath)) {
            die("Le fichier template {$fullPath} n'existe pas.");
        }
        echo $this->pug->render($fullPath, $variables);
    }
}
