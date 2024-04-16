<?php

namespace GlimpsGoneV2\repository;

use PDO;
use GlimpsGoneV2\core\App;
use GlimpsGoneV2\model\Artiste;

class ArtisteRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getArtisteById(int $id): ?Artiste
    {
        $statement = $this->pdo->prepare("SELECT * FROM " . Artiste::TABLE_NAME . " WHERE id = ?");
        if (!$statement->execute([$id])) {
            App::getAppInstance()->fatalError("Cannot execute SQL request.");
        }

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            App::getAppInstance()->fatalError("Artiste not found.");
        }


        return Artiste::fromPdoResult($result);
    }
}
