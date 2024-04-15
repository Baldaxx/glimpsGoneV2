<?php

namespace Ttizio\DemoPhp\repository;

use PDO;
use Ttizio\DemoPhp\core\App;
use Ttizio\DemoPhp\model\Artiste;

class ArtisteRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getArtisteById(int $id): Artiste
    {
        $statement = $this->pdo->prepare("SELECT * FROM " . Artiste::TABLE_NAME . " WHERE id = ?");
        if (!$statement->execute([$id])) {
            App::getAppInstance()->fatalError("Can not execute sql request");
        }

        $result = $statement->fetch();

        if (!$result) {
            App::getAppInstance()->fatalError("The artiste is not found.");
        }

        return Artiste::fromPdoResult($result);
    }
}