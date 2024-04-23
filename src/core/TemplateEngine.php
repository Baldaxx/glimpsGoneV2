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
            'basedir' => __DIR__ . '/../view',  // Chemin de base où se trouvent les fichiers de template.
            'pretty' => true,                   // Configure Pug pour qu'il génère un HTML "joli" (bien formaté).
            'cache' => __DIR__ . '/../cache'    // Dossier où stocker les fichiers temporaires pour accélérer le rendu.
        ]);
    }

    // Méthode pour générer le HTML à partir d'un template et des variables fournies.
    public function render($template, $variables = [])
    {
        // Construit le chemin complet vers le fichier de template.
        $fullPath = $this->pug->getOption('basedir') . '/' . $template;

        // Vérifie si le fichier de template existe, sinon arrête l'exécution et affiche un message d'erreur.
        if (!file_exists($fullPath)) {
            die("Le fichier template {$fullPath} n'existe pas.");
        }

        // Utilise Pug pour générer et afficher le HTML à partir du fichier de template et des variables fournies.
        echo $this->pug->render($fullPath, $variables);
    }
}
