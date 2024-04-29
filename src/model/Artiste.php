<?php

namespace GlimpsGoneV2\model;

class Artiste
{
    public const TABLE_NAME = "artiste";

    private ?int $id;
    private string $name;
    private string $email;
    private string $telephone;

    public function __construct(?int $id, string $name, string $email, string $telephone)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->telephone = $telephone;
    }

    public static function fromPdoResult(array $pdoResult): Artiste
    {
        return new Artiste(
            $pdoResult["id"],
            $pdoResult["nom"],
            $pdoResult["email"],
            $pdoResult["telephone"]
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'telephone' => $this->getTelephone()
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): void
    {
        $this->telephone = $telephone;
    }
}
