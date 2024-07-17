<?php

namespace GlimpsGoneV2\model;

class User
{
    private int $id;
    private string $nom;
    private string $email;
    private string $telephone;
    private string $bio;
    private string $photo;
    private string $password;

    public function __construct(
        int $id,
        string $nom,
        string $email,
        string $telephone,
        string $bio,
        string $photo,
        string $password
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->bio = $bio;
        $this->photo = $photo;
        $this->password = $password;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function getBio()
    {
        return $this->bio;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'nom' => $this->getNom(),
            'email' => $this->getEmail(),
            'photo' => $this->getPhoto(),
            'bio' => $this->getBio(),
            'telephone' => $this->getTelephone()
        ];
    }
}
