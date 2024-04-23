<?php

namespace GlimpsGoneV2\core;
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

    public function render($template, $variables = [])
    {
        $fullPath = $this->pug->getOption('basedir') . '/' . $template;
        if (!file_exists($fullPath)) {
            die("Le fichier template {$fullPath} n'existe pas.");
        }

        echo $this->pug->render($fullPath, $variables);
    }
}
