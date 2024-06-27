<?php

namespace GlimpsGoneV2\repository;

use GlimpsGoneV2\core\Config;
use PDO;

class UserRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Config::getPDO();
    }

    public function createUser($prenom, $nom, $email, $password, $telephone, $bio, $photo)
    {
        $stmt = $this->pdo->prepare('INSERT INTO users (prenom, nom, email, password, telephone, bio, photo) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$prenom, $nom, $email, $password, $telephone, $bio, $photo]);
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
