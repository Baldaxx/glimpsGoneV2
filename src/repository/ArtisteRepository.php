<?php

namespace GlimpsGoneV2\repository;

use GlimpsGoneV2\model\Artiste;
use PDO;
use GlimpsGoneV2\core\App;

class ArtisteRepository
{
    private PDO $pdo;

    /**
     * Constructeur de la classe.
     * Initialise la connexion PDO à partir de l'instance de l'application.
     */
    public function __construct()
    {
        $this->pdo = App::getAppInstance()->getPDO();
    }

    /**
     * Recherche un artiste par son nom ou en crée un nouveau s'il n'existe pas.
     * 
     * @param string $nom Le nom de l'artiste à rechercher ou à créer.
     * @return Artiste Renvoie l'objet Artiste trouvé ou créé.
     */
    public function findOrCreateByName(string $nom): Artiste
    {
        $stmt = $this->pdo->prepare("SELECT * FROM artiste WHERE nom = ?");
        $stmt->execute([$nom]);
        $result = $stmt->fetch();

        if ($result) {
            return new Artiste(
                $result['id'],
                $result['nom'],
                $result['email'] ?? '',  
                $result['telephone'] ?? ''
            );
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO artiste (nom, email, telephone) VALUES (?, ?, ?)");
            $stmt->execute([$nom, '', '']);
            $id = $this->pdo->lastInsertId();
            return new Artiste($id, $nom, '', '');
        }
    }

    /**
     * Récupère un artiste par son ID.
     *
     * @param int $id L'identifiant de l'artiste à récupérer.
     * @return Artiste|null Retourne un objet Artiste si trouvé, sinon null.
     */
    public function getArtisteById(int $id): ?Artiste
    {
        $stmt = $this->pdo->prepare("SELECT * FROM artiste WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();

        if (!$result) {
            return null;
        }

        return new Artiste(
            $result['id'],
            $result['nom'],
            $result['email'] ?? '',  
            $result['telephone'] ?? ''  
        );
    }
}
