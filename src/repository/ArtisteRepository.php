<?php

namespace GlimpsGoneV2\repository;

use GlimpsGoneV2\model\Artiste;
use PDO;
use GlimpsGoneV2\core\App;

class ArtisteRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = App::getAppInstance()->getPDO();
    }

    public function findOrCreateByName(string $nom): Artiste
    {
        $stmt = $this->pdo->prepare("SELECT * FROM artiste WHERE nom = ?");
        $stmt->execute([$nom]);
        $result = $stmt->fetch();

        if ($result) {
            // Assurez-vous que les champs email et téléphone ne sont pas null
            return new Artiste(
                $result['id'],
                $result['nom'],
                $result['email'] ?? '', // Utilisation de l'opérateur null coalescent
                $result['telephone'] ?? ''
            );
        } else {
            // Créer un nouvel artiste si non trouvé avec des valeurs par défaut pour email et téléphone
            $stmt = $this->pdo->prepare("INSERT INTO artiste (nom, email, telephone) VALUES (?, ?, ?)");
            // Des valeurs par défaut sont passées pour email et téléphone
            $stmt->execute([$nom, '', '']);
            $id = $this->pdo->lastInsertId();
            return new Artiste($id, $nom, '', '');
        }
    }
}
