<?php

namespace GlimpsGoneV2\model;

/**
 * Représentation d'un artiste.
 */
class Artiste
{
    /**
     * Le nom de la table artiste dans la base de données.
     */
    public const TABLE_NAME = "artiste";

    /**
     * @var int|null L'identifiant de l'artiste. Null si l'artiste n'est pas encore enregistré dans la DB.
     */
    private int|null $id;

    /**
     * @var string Le nom de l'artiste.
     */
    private string $name;

    /**
     * @var string L'email de l'artiste.
     */
    private string $email;

    /**
     * @var string Le numéro de téléphone de l'artiste.
     */
    private string $telephone;

    /**
     * Constructeur de la classe Artiste.
     *
     * @param int|null $id
     * @param string $name
     * @param string $email
     * @param string $telephone
     */
    public function __construct(?int $id, string $name, string $email, string $telephone)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->telephone = $telephone;
    }

    /**
     * Crée une instance d'Artiste à partir d'un résultat PDO.
     *
     * @param array $pdoResult
     * @return Artiste
     */
    public static function fromPdoResult(array $pdoResult): Artiste
    {
        return new Artiste($pdoResult["id"], $pdoResult["nom"], $pdoResult["email"], $pdoResult["telephone"]);
    }

    /**
     * Convertit l'instance de l'artiste en JSON.
     */
    public function toJson(): string
    {
        return json_encode(get_object_vars($this));
    }

    /**
     * Getters et Setters
     */
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
        $this->email = email;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): void
    {
        $this->telephone = telephone;
    }
}
