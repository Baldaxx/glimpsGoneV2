<?php

namespace GlimpsGoneV2\repository;

use DateTime;
use PDO;
use GlimpsGoneV2\model\Oeuvre;
use GlimpsGoneV2\model\Artiste;

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

    public function addOeuvre(Oeuvre $oeuvre): bool
    {
        $sql = "INSERT INTO oeuvre (artiste_id, titre, description, date_de_creation, compteur_jaime, compteur_jaime_pas) VALUES (?, ?, ?, ?, ?, ?)";
        $statement = $this->pdo->prepare($sql);
        $dateCreation = $oeuvre->getDateCreation()->format('Y-m-d');  // Utilisation correcte de getDateCreation
        return $statement->execute([
            $oeuvre->getArtiste()->getId(),
            $oeuvre->getTitre(),
            $oeuvre->getDescription(),
            $dateCreation,
            $oeuvre->getCompteurJaime(),
            $oeuvre->getCompteurJaimePas()
        ]);
    }
}
