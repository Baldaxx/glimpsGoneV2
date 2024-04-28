<?php

namespace GlimpsGoneV2\repository;

use DateTime;
use GlimpsGoneV2\model\Artiste;
use PDO;
use GlimpsGoneV2\model\Oeuvre;

class OeuvreRepository
{
    private PDO $pdo;

    public function __construct(PDO $inputPdo)
    {
        $this->pdo = $inputPdo;
    }

    public function getOeuvreById(int $id): ?Oeuvre
    {
        $sql = <<<SQL
        SELECT * FROM oeuvre 
        LEFT JOIN artiste ON artiste.id = oeuvre.artiste_id
        WHERE oeuvre.id = ?
SQL;
        $statement = $this->pdo->prepare($sql);
        $statement->execute([$id]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        return new Oeuvre(
            $result['id'],
            $result['titre'],
            $result['description'],
            new DateTime($result['date_de_creation']),
            $result['compteur_jaime'],
            $result['compteur_jaime_pas'],
            new Artiste(
                $result["artiste_id"],
                $result["nom"],
                $result["email"],
                $result["telephone"]
            )
        );
    }
}
