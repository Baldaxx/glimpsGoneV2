<?php

namespace GlimpsGoneV2\model;

class Artiste
{
    private int $id;
    private string $nom;
    private ?string $email;
    private ?string $telephone;

    public function __construct(int $id, string $nom, ?string $email, ?string $telephone)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->email = $email;
        $this->telephone = $telephone;
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
}
