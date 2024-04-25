<?php

namespace GlimpsGoneV2\repository;

use DateTime;
use GlimpsGoneV2\core\App;
use GlimpsGoneV2\model\Oeuvre;
use PDO;
use PDOException;
use Exception;

class OeuvreRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = App::getAppInstance()->getPDO();
    }

    public function getOeuvres(): array
    {
        try {
            $sql = "SELECT * FROM oeuvre ORDER BY id";
            $statement = $this->pdo->query($sql);
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            if (empty($results)) {
                return [];
            }
            $oeuvres = array_map(function ($result) {
                return new Oeuvre(
                    $result['id'],
                    $result['titre'],
                    $result['description'],
                    new DateTime($result['date_de_creation']),
                    $result['compteur_jaime'],
                    $result['compteur_jaime_pas']
                );
            }, $results);
            return $oeuvres;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des oeuvres: " . $e->getMessage());
            throw new Exception("Erreur de base de données.");
        }
    }

    public function getOeuvreById(int $id): ?Oeuvre
    {
        $sql = "SELECT * FROM oeuvre WHERE id = ?";
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
            $result['compteur_jaime_pas']
        );
    }
}
