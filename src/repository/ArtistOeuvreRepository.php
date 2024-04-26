<?php

namespace GlimpsGoneV2\repository;

use PDO;
use GlimpsGoneV2\model\Artiste;
use GlimpsGoneV2\model\Oeuvre;
use DateTime;

class ArtistOeuvreRepository
{
    private PDO $pdo;

    public function __construct(PDO $inputPdo)
    {
        $this->pdo = $inputPdo;
    }

    public function getArtistAndOeuvresById(int $artistId)
    {
        $sql = "SELECT artiste.*, oeuvre.* FROM artiste 
                JOIN oeuvre ON artiste.id = oeuvre.artiste_id 
                WHERE artiste.id = ?";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$artistId]);
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (!$results) {
            return null;
        }

        $artiste = null;
        $oeuvres = [];
        foreach ($results as $result) {
            if (!$artiste) {
                $artiste = new Artiste(
                    $result['id'],
                    $result['nom'],
                    $result['email'],
                    $result['telephone']
                );
            }
            $oeuvres[] = new Oeuvre(
                $result['id'],
                $result['titre'],
                $result['description'],
                new DateTime($result['date_de_creation']),
                $result['compteur_jaime'],
                $result['compteur_jaime_pas']
            );
        }

        return ['artiste' => $artiste, 'oeuvres' => $oeuvres];
    }
}
