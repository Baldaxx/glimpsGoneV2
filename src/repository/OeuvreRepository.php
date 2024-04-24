<?php

// Ce code PHP définit une classe appelée OeuvreRepository qui gère l'accès aux données des œuvres dans une base de données. La méthode getOeuvres() récupère toutes les œuvres à partir de la base de données et les retourne sous forme de tableau d'objets Oeuvre.

namespace GlimpsGoneV2\repository;

use DateTime;
use GlimpsGoneV2\core\App; 
use GlimpsGoneV2\model\Oeuvre;

class OeuvreRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = App::getAppInstance()->getPDO();
    }

    public function getOeuvres(): array
    {
        header('Content-Type: application/json');  
        $sql = "SELECT * FROM oeuvre ORDER BY id";
        $statement = $this->pdo->query($sql);
        $results = $statement->fetchAll();

        if (empty($results)) {
            return [];
        }
        $oeuvres = array_map(function ($result) {
            $oeuvre = new Oeuvre(
                $result['id'],
                $result['titre'],
                $result['description'],
                new DateTime($result['date_de_creation']),
                $result['compteur_jaime'],
                $result['compteur_jaime_pas']
            );
            return [
                'id' => $oeuvre->getId(),
                'titre' => $oeuvre->getTitre(),
                'description' => $oeuvre->getDescription(),
                'dateCreation' => $oeuvre->getDateCreation()->format('Y-m-d'),
                'compteurJaime' => $oeuvre->getCompteurJaime(),
                'compteurJaimePas' => $oeuvre->getCompteurJaimePas(),
                'artiste' => ['name' => 'Nom de l\'artiste']
            ];
        }, $results);
        echo json_encode($oeuvres);  
        exit;
    }
}
