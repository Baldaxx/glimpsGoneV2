<?php

namespace GlimpsGoneV2\repository;

use DateTime;
use PDO;
use GlimpsGoneV2\model\Oeuvre;

class OeuvreRepository
{
    private PDO $pdo;

    public function __construct(PDO $inputPdo)
    {
        $this->pdo = $inputPdo;
    }

    public function getOeuvres(): array
    {
        $sql = "SELECT * FROM oeuvre ORDER BY id";
        $statement = $this->pdo->query($sql);
        $results = $statement->fetchAll();

        return array_map(function ($result) {
            return new Oeuvre(
                $result["id"],
                $result["titre"],
                $result["description"],
                new DateTime($result["date_de_creation"]),
                $result["compteur_jaime"],
                $result["compteur_jaime_pas"]
            );
        }, $results);
    }
}
