<?php

namespace GlimpsGoneV2\repository;

use GlimpsGoneV2\core\App; 
use GlimpsGoneV2\model\Artiste;

class ArtisteRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = App::getAppInstance()->getPDO();
    }

    public function getArtisteById(int $id): Artiste
    {
        $statement = $this->pdo->prepare("SELECT * FROM " . Artiste::TABLE_NAME . " WHERE id = ?");
        if (!$statement->execute([$id])) {
            App::getAppInstance()->fatalError("Cannot execute SQL request");
        }

        $result = $statement->fetch();

        if (!$result) {
            App::getAppInstance()->fatalError("The artiste is not found.");
        }

        return Artiste::fromPdoResult($result);
    }
}
