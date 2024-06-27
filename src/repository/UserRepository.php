<?php

namespace GlimpsGoneV2\repository;

use PDO;

class UserRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = \GlimpsGoneV2\core\App::getAppInstance()->getPDO();
    }

    public function createUser(string $prenom, string $nom, string $email, string $password, string $telephone, string $bio, string $photoPath): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO users (prenom, nom, email, password, telephone, bio, photo) VALUES (:prenom, :nom, :email, :password, :telephone, :bio, :photo)');
        $stmt->execute([
            ':prenom' => $prenom,
            ':nom' => $nom,
            ':email' => $email,
            ':password' => $password,
            ':telephone' => $telephone,
            ':bio' => $bio,
            ':photo' => $photoPath,
        ]);
    }

    public function getUserByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }
}
