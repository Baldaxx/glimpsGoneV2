<?php

namespace GlimpsGoneV2\repository;

use PDO;
use GlimpsGoneV2\model\User;
use GlimpsGoneV2\core\Config;

class UserRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createUser($prenom, $nom, $email, $password, $telephone, $bio, $photo)
    {
        $stmt = $this->pdo->prepare('INSERT INTO users (prenom, nom, email, password, telephone, bio, photo) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$prenom, $nom, $email, $password, $telephone, $bio, $photo]);
    }

    public function getUserByEmail(string $email): User|null
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result === false) {
            return null;
        }
        $user = new User(
            $result['id'],
            $result['nom'] ?? '',
            $result['email'] ?? '',
            $result['telephone'] ?? '',
            $result['bio'] ?? '',
            $result['photo'] ?? '',
            $result['password']
        );
        return $user;
    }

    public function getUserById(int $id): User|null
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result === false) {
            return null;
        }
        $user = new User(
            $result['id'],
            $result['nom'] ?? '',
            $result['email'] ?? '',
            $result['telephone'] ?? '',
            $result['bio'] ?? '',
            $result['photo'] ?? '',
            $result['password']
        );
        return $user;
    }
}
