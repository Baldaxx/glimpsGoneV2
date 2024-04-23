<?php

namespace GlimpsGoneV2\model;

/**
 * Representation of an artist.
 */
class Artiste
{
    /**
     * The name of the artist table in the database.
     */
    public const TABLE_NAME = "artiste";

    /**
     * @var int|null Identifier of the artist. Null if the artist is not yet registered in the DB.
     */
    private ?int $id;

    /**
     * @var string The name of the artist.
     */
    private string $name;

    /**
     * @var string The email of the artist.
     */
    private string $email;

    /**
     * @var string The telephone number of the artist.
     */
    private string $telephone;

    /**
     * Constructor for the Artiste class.
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
     * Creates an instance of Artiste from a PDO result.
     *
     * @param array $pdoResult
     * @return Artiste
     */
    public static function fromPdoResult(array $pdoResult): Artiste
    {
        return new Artiste($pdoResult["id"], $pdoResult["nom"], $pdoResult["email"], $pdoResult["telephone"]);
    }

    /**
     * Converts the artist instance to JSON.
     */
    public function toJson(): string
    {
        return json_encode([
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'telephone' => $this->telephone
        ]);
    }

    /**
     * Getters and Setters
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
