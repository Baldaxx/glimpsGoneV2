<?php

namespace GlimpsGoneV2\repository;

use PDO;
use DateTime;
use GlimpsGoneV2\model\Oeuvre;
use GlimpsGoneV2\model\Artiste;

class OeuvreRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function addOeuvre(Oeuvre $oeuvre): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO oeuvre (artiste_id, titre, description, date_de_creation) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$oeuvre->getArtiste()->getId(), $oeuvre->getTitre(), $oeuvre->getDescription(), $oeuvre->getDateCreation()->format('Y-m-d')]);
    }

    public function getAllOeuvres(): array
    {
        $stmt = $this->pdo->query('SELECT o.*, a.* FROM oeuvre o JOIN artiste a ON o.artiste_id = a.id');
        $oeuvres = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            error_log('Row data: ' . print_r($row, true));

            $artiste = new Artiste(
                $row['artiste_id'],
                $row['nom'] ?? '',
                $row['email'] ?? '',
                $row['telephone'] ?? ''
            );

            $dateCreation = isset($row['date_de_creation']) ? $row['date_de_creation'] : '1970-01-01';
            $oeuvres[] = new Oeuvre(
                $row['id'],
                $row['titre'] ?? '',
                $row['description'] ?? '',
                new DateTime($dateCreation),
                $artiste
            );
        }
        return $oeuvres;
    }

    public function getOeuvreById(int $id): Oeuvre|null
    {
        $stmt = $this->pdo->prepare('SELECT * FROM oeuvre o LEFT JOIN artiste a ON o.artiste_id = a.id WHERE o.id = ?');
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result === false) {
            return null;
        }

        $artiste = new Artiste(
            $result['artiste_id'],
            $result['nom'] ?? '',
            $result['email'] ?? '',
            $result['telephone'] ?? ''
        );
        
        $dateCreation = isset($row['date_de_creation']) ? $row['date_de_creation'] : '1970-01-01';
        $oeuvre = new Oeuvre(
            $result['id'],
            $result['titre'] ?? '',
            $result['description'] ?? '',
            new DateTime($dateCreation),
            $result['compteur_jaime'] ?? 0,
            $result['compteur_jaime_pas'] ?? 0,
            $artiste
        );
        return $oeuvre;
    }
}
